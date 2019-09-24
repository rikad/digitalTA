<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Helpers\exportExcelTranskrip;

use Illuminate\Http\Request;
use App\User;
use App\Course;
use App\Equivalency;
use App\Transcript;
use App\TranscriptInfo;
use App\Curriculum;
use Yajra\Datatables\Datatables;
use Yajra\Datatables\Html\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;

class TranscriptsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request, Builder $htmlBuilder)
    {
        if ($request->ajax()) {
            $data = User::select(['users.id','users.name','users.no_induk', 'users.email','roles.display_name'])
                    ->join('role_user','role_user.user_id','users.id')
                    ->join('roles','role_user.role_id','roles.id')
                    ->where('roles.name','student');

            return Datatables::of($data)
                    ->addColumn('action',function($data) { 
                        $is_filled = Transcript::select(['id'])->where('student_id', $data->id)->get();
                        $button = '';
                        if(count($is_filled)==0){
                            $button = '<button class="btn btn-primary btn-xs" onclick="rikad.showModal(\''.$data->id.'\')"><span class="glyphicon glyphicon-upload"></span> Upload Transkrip</button>';
                        }else{
                            $button = '<a href="/tu/transcripts/detail?id='.$data->id.'" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-eye-open"></span> Graduation Checklist</a> ';

                            $button .= '<a href="/tu/transcripts/detailHistoris?id='.$data->id.'" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-eye-open"></span> Transcript History</a>';

                            $button .= ' <button class="btn btn-danger btn-xs" onclick="rikad.delete(\''.$data->id.'\')"><span class="glyphicon glyphicon-remove"></span> Reset</button>';
                        }

                        return $button;

                    })->make(true);
        }

        $html = $htmlBuilder
          ->addColumn(['data' => 'no_induk', 'name'=>'users.no_induk', 'title'=>'No Induk'])
          ->addColumn(['data' => 'name', 'name'=>'users.name', 'title'=>'Mahasiswa'])
          ->addColumn(['data' => 'action', 'name'=>'action', 'title'=>'Transkrip', 'orderable'=>false, 'searchable'=>false]);

        $dosenWali = User::select(['users.id','users.name','users.no_induk', 'users.email','roles.display_name'])
                    ->join('role_user','role_user.user_id','users.id')
                    ->join('roles','role_user.role_id','roles.id')
                    ->where('roles.name','dosen');

        $kaprodi = $this->readConfig();

        return view('administration.transcripts.index')->with(compact('html'))
                                                    ->with(compact('dosenWali'))
                                                    ->with(compact('kaprodi'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function detail(Request $request)
    {

        //
        $data = $request->except(['_token','password']);
    $user = Transcript::select('users.no_induk','users.name')->join('users','users.id','pc_transcripts.student_id')
                            ->where('pc_transcripts.student_id',$data['id'])
                            ->first();

        for ($i = 0; $i < 8; $i++) {
            $result[$i] = Transcript::select(['pc_transcripts.grade_uni','pc_courses.code', 'pc_courses.title_en', 'pc_courses.sch', 'pc_courses.rex', 'pc_courses.mbs', 'pc_courses.et', 'pc_courses.ge', 'pc_curricula.title', 'pc_courses.semester'])
                    ->join('pc_courses','pc_transcripts.course_id','pc_courses.id')
                    ->join('pc_curricula','pc_courses.curriculum_id','pc_curricula.id')
                    ->where('pc_transcripts.student_id',$data['id'])
                    ->where('pc_transcripts.is_transcripted',1)
                    ->where('pc_courses.rex',"R")
                    ->where('pc_courses.semester',$i+1)
                    ->orderBy('pc_courses.curriculum_id')
                    ->orderBy('pc_courses.code')
                    ->get();
        }

    
        $finalData = [];
        for ($j = 0; $j < count($result); $j++) {

            $semester = [];

            for ($i = 0; $i < count($result[$j]); $i++) {
                $rows = [];
    
                $rows[] = $result[$j][$i]->code."-".substr($result[$j][$i]->title, -2);
                $rows[] = $result[$j][$i]->title_en;
                $rows[] = $result[$j][$i]->sch;
                $rows[] = $result[$j][$i]->grade_uni;
                $rows[] = $result[$j][$i]->rex;
                $rows[] = $result[$j][$i]->mbs;
                $rows[] = $result[$j][$i]->et;
                $rows[] = $result[$j][$i]->ge;
        
                $semester[] = $rows;
            }

            $finalData['semester'.($j+1)] = $semester;
        }
    $finalData['name'] = trim($user->name);
    $finalData['nim'] = trim($user->no_induk);

    //set kaprodi
    $kaprodi = $this->readConfig();
    $finalData['programChairNip'] = 'NIP: '.$kaprodi->nip;
    $finalData['programChairName'] = $kaprodi->name;
    
    $pilihan = Transcript::select(['pc_transcripts.grade_uni','pc_courses.code', 'pc_courses.title_en', 'pc_courses.sch', 'pc_courses.rex', 'pc_courses.mbs', 'pc_courses.et', 'pc_courses.ge', 'pc_curricula.title', 'pc_courses.semester', 'pc_transcripts.smt_taken', 'pc_transcripts.year_taken'])
                    ->join('pc_courses','pc_transcripts.course_id','pc_courses.id')
                    ->join('pc_curricula','pc_courses.curriculum_id','pc_curricula.id')
                    ->where('pc_transcripts.student_id',$data['id'])
                    ->where('pc_transcripts.is_transcripted',1)
                    ->where('pc_courses.rex',"!=","R")
                    ->orderBy('pc_courses.code')
                    ->orderBy('pc_transcripts.year_taken', 'desc')
                    ->orderBy('pc_transcripts.is_transcripted', 'desc')
                    ->get();


    //untuk matkul pilihan, olah dulu penempatan semesternya (sesuai semester pengambilan oleh mhs, bukan struktur kurikulum)
        for ($j = 0; $j < count($pilihan); $j++) {
        $pilihan[$j]->semester = 6+($pilihan[$j]->smt_taken%2);
        
                $rows = [];
    
                $rows[] = $pilihan[$j]->code."-".substr($pilihan[$j]->title, -2);
                $rows[] = $pilihan[$j]->title_en;
                $rows[] = $pilihan[$j]->sch;
                $rows[] = $pilihan[$j]->grade_uni;
                $rows[] = $pilihan[$j]->rex;
                $rows[] = $pilihan[$j]->mbs;
                $rows[] = $pilihan[$j]->et;
                $rows[] = $pilihan[$j]->ge;


        if(count($finalData['semester7'])>count($finalData['semester8'])){
            array_push($finalData['semester8'], $rows);
        }else{array_push($finalData['semester7'], $rows);}
        
    }

        for ($j = 0; $j < 8; $j++) {
        $grade = 0;
        $sks = 0;

        $current = $finalData['semester'.($j+1)];
        for($i = 0; $i < count($current); $i++){
            $singleGrade = $this->gradeConvert($current[$i][3], substr($current[$i][0],2,1)=="1");
            $grade+=$current[$i][2]*$singleGrade;
            $sks+=$current[$i][2];
        }
        if ($sks == 0) {
            $finalData['semester'.($j+1).'ip']=0;
        } else {
            $finalData['semester'.($j+1).'ip']=$grade/$sks;
        }
    }
    $endResult = array_merge($finalData, $this->countIPK($data['id']));
    //[WARNING] Kemana semester diatas 8?
    

    //Dapatkan info transkrip
    $transcriptInfo = TranscriptInfo::select(['tr_infos.*'])->where('student_id', $data['id'])->first();
    if($transcriptInfo!=null){
        $advisor = User::select(['users.*'])->where('id', $transcriptInfo->advisor_id)->first();

        $endResult['yudisiumDate'] = $transcriptInfo->yudisium_date;
        $endResult['graduationDate'] = $transcriptInfo->graduation_date;
        $endResult['academicAdviserName'] = $advisor->name;
        $endResult['academicAdviserNip'] = "NIP: ".$advisor->no_induk;
        //$endResult['final_exam'] = $transcriptInfo->final_exam;
        $endResult['finalExamination'] = [];

        $final_exam=explode(";", $transcriptInfo->final_exam);
        $tmp_counter = 0;
        for ($i=0; $i<count($final_exam); $i++) {
            if(($i % 2) != 0) {
                $endResult['finalExaminationCheck'][] = [ $final_exam[$i] ];
            } else {
                $tmp_counter++;
                $endResult['finalExamination'][] = [ $tmp_counter, $final_exam[$i] ];
            }

            if($final_exam[$i] == '1') break;
        }
    }

    //return $endResult;

        $name = 'Transkrip-'.$user->no_induk.'-'.$user->name.'.xlsx';
        $template = storage_path(). DIRECTORY_SEPARATOR . 'templates'. DIRECTORY_SEPARATOR . 'finalGradTemplate.xlsx';
        $file = $this->exportExcel($endResult,$template);
        return Response()->download($file, $name)->deleteFileAfterSend(true);
    }

    public function countIPK($id){
        for ($i = 0; $i < 8; $i++) {
            $result[$i] = Transcript::select(['pc_transcripts.grade_uni','pc_courses.code', 'pc_courses.sch', 'pc_courses.rex',  'pc_courses.semester'])
                    ->join('pc_courses','pc_transcripts.course_id','pc_courses.id')
                    ->join('pc_curricula','pc_courses.curriculum_id','pc_curricula.id')
                    ->where('pc_transcripts.student_id',$id)
                    ->where('pc_courses.rex',"R")
                    ->where('pc_courses.semester',$i+1)
                    ->get();
        }

        $last_pilihan="";
        $last_semester=0;

        $total7=0;
        $total8=0;
    
        $finalData = [];
        for ($j = 0; $j < count($result); $j++) {

            $semester = [];

            for ($i = 0; $i < count($result[$j]); $i++) {
                $rows = [];
    
                $rows[] = $result[$j][$i]->code."-".substr($result[$j][$i]->title, -2);
                $rows[] = $result[$j][$i]->sch;
                $rows[] = $result[$j][$i]->grade_uni;
        
                $semester[] = $rows;

                if($last_pilihan!=$result[$j][$i]->code){
                    $last_pilihan=$result[$j][$i]->code;
                    if($j==6){$total7++;}
                    if($j==7){$total8++;}
                }
            }

            $finalData['semester'.($j+1)] = $semester;
        }
        $last_pilihan="";
    
    $pilihan = Transcript::select(['pc_transcripts.grade_uni','pc_courses.code', 'pc_courses.sch', 'pc_courses.rex',  'pc_courses.semester', 'pc_transcripts.smt_taken', 'pc_transcripts.year_taken', 'pc_courses.curriculum_id', 'pc_transcripts.is_transcripted'])
                    ->join('pc_courses','pc_transcripts.course_id','pc_courses.id')
                    ->join('pc_curricula','pc_courses.curriculum_id','pc_curricula.id')
                    ->where('pc_transcripts.student_id',$id)
                    ->where('pc_courses.rex',"!=","R")
                    ->orderBy('pc_courses.code')
                    ->orderBy('pc_transcripts.year_taken', 'desc')
                    ->orderBy('pc_transcripts.is_transcripted', 'desc')
                    ->get();

    
                    //return $pilihan;
    //untuk matkul pilihan, olah dulu penempatan semesternya (sesuai semester pengambilan oleh mhs, bukan struktur kurikulum)
        
        $untranscripted = [];

        for ($j = 0; $j < count($pilihan); $j++) {
        $pilihan[$j]->semester = 6+($pilihan[$j]->smt_taken%2);
        
                $rows = [];
    
                $rows[] = $pilihan[$j]->code."-".substr($pilihan[$j]->title, -2);
                $rows[] = $pilihan[$j]->sch;
                $rows[] = $pilihan[$j]->grade_uni;

        if($pilihan[$j]->code==$last_pilihan){
            array_push($finalData['semester'.$last_semester], $rows);
        }else{
            $last_pilihan=$pilihan[$j]->code;
            
            //pembeda matkul pilihan drop/tidak. ==0 utk drop
            if($pilihan[$j]->is_transcripted!=0){
                if($total7>$total8){
                    array_push($finalData['semester8'], $rows);
                    $last_semester=8;
                    $total8++;
                }else{array_push($finalData['semester7'], $rows);
                    $last_semester=7;
                    $total7++;
                }    
            }else{
                array_push($untranscripted, $rows);
            }
        }
    }

    //If untranscripted counted.........................
    for ($j = 0; $j < count($untranscripted); $j++) {
        if($total7>$total8){
            array_push($finalData['semester8'], $untranscripted[$j]);
            $total8++;
        }else{
            array_push($finalData['semester7'], $untranscripted[$j]);
            $total7++;
        }
    }


        for ($j = 0; $j < 8; $j++) {
        $grade = 0;
        $sks = 0;

        $current = $finalData['semester'.($j+1)];
        for($i = 0; $i < count($current); $i++){
            $singleGrade = $this->gradeConvert($current[$i][2], substr($current[$i][0],2,1)=="1");

            $grade+=$current[$i][1]*$singleGrade;
            $sks+=$current[$i][1];
        }
        $f['semester'.($j+1).'ipk']=$grade/$sks;
    }

    return $f;

    }

    
    public function exportExcel($data,$template)
    {

        $outputFile = storage_path(). DIRECTORY_SEPARATOR . 'download_tmp'. DIRECTORY_SEPARATOR . md5(time()) .'.xlsx';

        $transkrip = new exportExcelTranskrip($data,$template,$outputFile);
        $transkrip->generate();

        return $outputFile;
    }

    public function gradeConvert($alphabet,$isTPB) {
    $grade = ["T"=>0, "E"=>0, "D"=>0, "C"=>2, "BC"=>2.5, "B"=>3, "AB"=>3.5, "A"=>4];
    if ($isTPB) $grade['D'] = 1;
    return $grade[$alphabet];
    }

    public function create(Request $request)
    {
        $data = $request->except(['_token']);

        try {
            $this->writeConfig(json_encode($data));

            Session::flash("flash_notification", [
                 "level"=>"success",
                 "message"=>"Pengaturan Berhasil Di ubah"
            ]);
        }

        catch(Exception $e) {
            Session::flash("flash_notification", [
                 "level"=>"success",
                 "message"=>"Pengaturan Berhasil Di ubah"
            ]);
        }

        return back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except(['_token','password']);

        $list = preg_split('/\r\n|[\r\n]/', $data['grade']);

        for ($x = 0; $x < count($list); $x++) {
        	$params = preg_split('/;|[;]/', $list[$x]);
        	if(count($params)!=10){
                Session::flash("flash_notification", [
                "level"=>"danger",
                "message"=>"Data file bukan CSV. Pastikan pemisah kolom menggunakan tanda <b>;</b> dan terdapat 10 kolom data. <br><b>Contoh:</b>&nbsp;&nbsp;&nbsp;1;2011;1;1;2;FI1101;Elementary Physics IA;4;B;X"
                ]);

                return redirect('tu/transcripts');        
            }
        }

        for ($x = 0; $x < count($list); $x++) {
            //$params = preg_split('/\t|[\t]/', $list[$x]);
            $params = preg_split('/;|[;]/', $list[$x]);


            $courseID = 0;
            $curriculumID = 0;

            $kurikulum_title=2008+(floor(($params[1]-2008)/5))*5;

            $curriculum = Curriculum::select(['pc_curricula.*'])->where('title', $kurikulum_title)->first();
                if ($kurikulum_title<2000||$kurikulum_title>2100) {
                        Session::flash("flash_notification", [
                            "level"=>"danger",
                            "message"=>"Gagal : Tahun Masukan Tidak Valid: '".$kurikulum_title."'".count($kurikulum_title)
                        ]);
                    return back();
                }
                elseif($curriculum==null) {
                    $newCurriculum['title']=$params[4];
                    $curriculumNew = Curriculum::create($newCurriculum); 

                    $curriculumID=$curriculumNew->id;
                }else{
                    $curriculumID=$curriculum->id;
                }

            $course = Course::select(['pc_courses.id'])
                    ->join('pc_curricula','pc_courses.curriculum_id','pc_curricula.id')
                    ->where('pc_courses.code',$params[5])
                    ->where('pc_courses.curriculum_id',$curriculumID)
                    ->first();

            if($course==null){
                $newCourse['code']=$params[5];
                $newCourse['title']=$params[6];
                $newCourse['title_en']='-';
                $newCourse['semester']=$params[3];
                $newCourse['sch']=$params[7];
                $newCourse['curriculum_id']=$curriculumID;
                $newCourse['rex']="-";
                $newCourse['mbs']=0;
                $newCourse['et']=0;
                $newCourse['ge']=0;
                $newCourse['no']=$params[4];

                $courseNew = Course::create($newCourse); 
                $courseID=$courseNew->id;
            
            }else{
                if($course->no==null||$course->no==0){
                    $course->no=$params[4];
                    if($course->semester!=$params[3]&&$params[3]<=8){$course->semester=$params[3];}
                    $course->save();
                }
                $courseID=$course->id;
            }

            $tmp[$x]['student_id']=$data['id'];
            $tmp[$x]['course_id']=$courseID;
            $tmp[$x]['year_taken']=$params[1];
            $tmp[$x]['smt_taken']=$params[2];
            $tmp[$x]['grade_uni']=$params[8];
            $tmp[$x]['grade_ps']=$params[8];
            if(!isset($params[9])||$params[9]==""){
            $tmp[$x]['is_transcripted']=0;    
            }else{$tmp[$x]['is_transcripted']=1;}
            
            $transcript = Transcript::create($tmp[$x]);
        } 

        Session::flash("flash_notification", [
            "level"=>"success",
            "message"=>"Data transkrip berhasil diupload. Lihat hasil dengan menekan tombol disamping <a href='/tu/transcripts/detail?id=".$data['id']."' class='btn btn-primary btn-xs'><span class='glyphicon glyphicon-eye-open'></span></a>"
        ]);

        return redirect('tu/transcripts');        
    }

    public function register(Request $request)
    {
        $data = $request->except(['_token','password']);


		foreach ($data['data'] as $params) {
		    
            if($params[0]==null) continue;

            $userID = 0;
            $user_check = User::select(['users.*'])->where('no_induk', $params[0])->first();
            if($user_check==null) {
                //Buatkan entitiy student
                $user['no_induk'] = $params[0];
                $user['username'] = $params[0];
                $user['name'] = $params[1];
                $user['email'] = $params[0]."@gmail.com";
                $user['password'] = bcrypt($params[0]);

                $userNew = User::create($user);
                $userNew->attachRole(4);

                $userID = $userNew['id'];
            } else {
            	$userID = $user_check->id;
            }

            //Cari ID advisor dari inisial (username)
            $advisor_id = User::select(['users.*'])->where('username', strtolower($params[2]))->first();
            //Update info nip dan nama (plus gelar) kalau kolom nip masih kosong
            if($advisor_id->no_induk=="" || $advisor_id->no_induk==null){
                DB::table('users')
                ->where('id', $advisor_id->id)
                ->update(['no_induk' => $params[3], 'name' => $params[4]]);
            }

            //Buat record info transcript
            $transcriptInfo = TranscriptInfo::where('student_id',$userID)->first();

            $tmp_final_exam = array_slice($params, 8);


            if ($transcriptInfo) {
            	$transcriptInfo->advisor_id = $advisor_id->id;
            	$transcriptInfo->yudisium_date = $params[6];
            	$transcriptInfo->graduation_date = $params[7];
            	$transcriptInfo->final_exam = join(";", $tmp_final_exam);
            	$transcriptInfo->save();
            } else {
	            $info['student_id']=$userID;
	            $info['advisor_id']=$advisor_id->id;
	            $info['yudisium_date']=$params[6];
	            $info['graduation_date']=$params[7];
	            $info['final_exam']= join(";", $tmp_final_exam);

	            $transcriptInfo = TranscriptInfo::create($info);
            }
            
        } 

        Session::flash("flash_notification", [
            "level"=>"success",
            "message"=>"Data transkrip berhasil diupload."
        ]);

        return redirect('tu/transcripts');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('administration.transcripts.form');
    }


    public function writeConfig($config) {
        $file = storage_path() . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'configTemplates.json';

        $FileOpen = fopen($file, "w") or die("Unable to open file!");

        fwrite($FileOpen, $config);

        fclose($FileOpen);
    }

    public function readConfig() {
        $file = storage_path() . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'configTemplates.json';

        $config = file_get_contents($file);

        return json_decode($config);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $user = Transcript::where('student_id', $id)->delete();

            Session::flash("flash_notification", [
                "level"=>"success",
                "message"=>"Nilai Berhasil Di Reset"
            ]);
        }
        catch (Exception $e) {
            Session::flash("flash_notification", [
                "level"=>"danger",
                "message"=>"Nilai Gagal Di reset"
            ]);
        }

        return 'ok';
    }

    public function bulk(Request $request)
    {
        $data = $request->except(['_token']);
        return "$data";
    }

    public function detailHistoris(Request $request)
    {

        $data = $request->except(['_token','password']);
        $user = Transcript::select('users.no_induk','users.name')->join('users','users.id','pc_transcripts.student_id')
                            ->where('pc_transcripts.student_id',$data['id'])
                            ->first();
        if($user==null){
            Session::flash("flash_notification", [
                "level"=>"danger",
                "message"=>"Data transkrip untuk mahasiswa tersebut tidak ditemukan"
            ]);
            return redirect('tu/transcripts');
        }

        for ($i = 0; $i < 8; $i++) {
            $result[$i] = Transcript::select(['pc_transcripts.grade_uni','pc_courses.code', 'pc_courses.title_en', 'pc_courses.sch', 'pc_courses.rex', 'pc_courses.mbs', 'pc_courses.et', 'pc_courses.ge', 'pc_curricula.title', 'pc_courses.semester', 'pc_transcripts.year_taken',
                'pc_transcripts.course_id'])
                    ->join('pc_courses','pc_transcripts.course_id','pc_courses.id')
                    ->join('pc_curricula','pc_courses.curriculum_id','pc_curricula.id')
                    ->where('pc_transcripts.student_id',$data['id'])
                    ->where('pc_transcripts.is_transcripted',1)
                    ->where('pc_courses.rex',"R")
                    ->where('pc_courses.semester',$i+1)
                    ->orderBy('pc_courses.no')
                    ->get();
        }


    
        $finalData = [];
        for ($j = 0; $j < count($result); $j++) {

            $semester = [];

            for ($i = 0; $i < count($result[$j]); $i++) {
                $rows = [];
    
                $rows[] = $result[$j][$i]->code;
                $rows[] = $result[$j][$i]->title_en;
                $rows[] = $result[$j][$i]->sch;

                $rows[] = $result[$j][$i]->year_taken."/".($result[$j][$i]->year_taken+1);
                $rows[] = $result[$j][$i]->grade_uni;

                $tmp = Transcript::select(['pc_transcripts.grade_uni', 'pc_transcripts.year_taken'])
                        ->where('pc_transcripts.student_id',$data['id'])
                        ->where('pc_transcripts.course_id',$result[$j][$i]->course_id)
                        ->where('pc_transcripts.is_transcripted', 0)
                        ->orderBy('pc_transcripts.year_taken', 'desc')
                        ->get();

                
                for($k=0;$k<count($tmp);$k++){
                    $rows[] = $tmp[$k]->year_taken."/".($tmp[$k]->year_taken+1);
                    $rows[] = $tmp[$k]->grade_uni;
                }

                //cek equivalencies dari 2008 ke 2013
                $checkEqui = Equivalency::select(['pc_equivalencies.*'])
                    ->where('course2008_id', $result[$j][$i]->course_id)->first();
                if($checkEqui){
                    $tmp = Transcript::select(['pc_transcripts.grade_uni', 'pc_transcripts.year_taken'])
                        ->where('pc_transcripts.student_id',$data['id'])
                        ->where('pc_transcripts.course_id',$checkEqui->course2013_id)
                        ->where('pc_transcripts.is_transcripted', 0)
                        ->orderBy('pc_transcripts.year_taken', 'desc')
                        ->get();

                
                    for($k=0;$k<count($tmp);$k++){
                        $rows[] = $tmp[$k]->year_taken."/".($tmp[$k]->year_taken+1);
                        $rows[] = $tmp[$k]->grade_uni;
                    }                    
                }

                //cek equivalencies dari 2013 ke 2008
                $checkEqui = Equivalency::select(['pc_equivalencies.*'])
                    ->where('course2013_id', $result[$j][$i]->course_id)->first();
                if($checkEqui){
                    $tmp = Transcript::select(['pc_transcripts.grade_uni', 'pc_transcripts.year_taken'])
                        ->where('pc_transcripts.student_id',$data['id'])
                        ->where('pc_transcripts.course_id',$checkEqui->course2008_id)
                        ->where('pc_transcripts.is_transcripted', 0)
                        ->orderBy('pc_transcripts.year_taken', 'desc')
                        ->get();

                
                    for($k=0;$k<count($tmp);$k++){
                        $rows[] = $tmp[$k]->year_taken."/".($tmp[$k]->year_taken+1);
                        $rows[] = $tmp[$k]->grade_uni;
                    }                    
                }
                
                
                $semester[] = $rows;
            }

            $finalData['Hsemester'.($j+1)] = $semester;
        }

        $finalData['Hname'] = ': '.trim($user->name);
        $finalData['Hnim'] = ': '.trim($user->no_induk);

        //set kaprodi
        $kaprodi = $this->readConfig();
        $finalData['HprogramChairNip'] = 'NIP: '.$kaprodi->nip;
        $finalData['HprogramChairName'] = $kaprodi->name;

        
        $pilihan = Transcript::select(['pc_transcripts.grade_uni','pc_courses.code', 'pc_courses.title_en', 'pc_courses.sch', 'pc_courses.rex', 'pc_courses.mbs', 'pc_courses.et', 'pc_courses.ge', 'pc_curricula.title', 'pc_courses.semester', 'pc_transcripts.smt_taken', 'pc_transcripts.year_taken', 'pc_transcripts.course_id'])
                    ->join('pc_courses','pc_transcripts.course_id','pc_courses.id')
                    ->join('pc_curricula','pc_courses.curriculum_id','pc_curricula.id')
                    ->where('pc_transcripts.student_id',$data['id'])
                    ->where('pc_transcripts.is_transcripted',1)
                    ->where('pc_courses.rex',"!=","R")
                    ->get();
        

        //untuk matkul pilihan, olah dulu penempatan semesternya (sesuai semester pengambilan oleh mhs, bukan struktur kurikulum)
        for ($j = 0; $j < count($pilihan); $j++) {
            //$pilihan[$j]->semester = 6+($pilihan[$j]->smt_taken%2);
        
                $rows = [];
    
                $rows[] = $pilihan[$j]->code."-".substr($pilihan[$j]->title, -2);
                if($pilihan[$j]->title_en=="-"){
                    $rows[] = $pilihan[$j]->title;
                }else{
                    $rows[] = $pilihan[$j]->title_en;
                }
                
                $rows[] = $pilihan[$j]->sch;
                
                $rows[] = $pilihan[$j]->year_taken."/".($pilihan[$j]->year_taken+1);
                $rows[] = $pilihan[$j]->grade_uni;

                //kalau ngulang cari lagi
                $tmp = Transcript::select(['pc_transcripts.grade_uni', 'pc_transcripts.year_taken'])
                        ->where('pc_transcripts.student_id',$data['id'])
                        ->where('pc_transcripts.course_id',$pilihan[$j]->course_id)
                        ->where('pc_transcripts.is_transcripted', 0)
                        ->orderBy('pc_transcripts.year_taken', 'desc')
                        ->get();

                for($k=0;$k<count($tmp);$k++){
                    $rows[] = $tmp[$k]->year_taken."/".($tmp[$k]->year_taken+1);
                    $rows[] = $tmp[$k]->grade_uni;
                }
                //$rows[] = $pilihan[$j]->year_taken."/".($pilihan[$j]->year_taken+1);;
                //$rows[] = $pilihan[$j]->grade_uni;
                
                array_push($finalData['Hsemester'.$pilihan[$j]->semester], $rows);
                //if(count($finalData['Hsemester7'])>count($finalData['Hsemester8'])){
                    //array_push($finalData['Hsemester8'], $rows);
                //}else{array_push($finalData['Hsemester7'], $rows);}
        }

        
        //itung ip&ipk disini
        for ($j = 0; $j < 8; $j++) {
            $grade = 0;
            $sks = 0;

            $grade_ipk =0;
            $sks_ipk =0;

            $current = $finalData['Hsemester'.($j+1)];
            for($i = 0; $i < count($current); $i++){
                $singleGrade = $this->gradeConvert($current[$i][4], substr($current[$i][0],2,1)=="1");
                $grade+=$current[$i][2]*$singleGrade;
                $sks+=$current[$i][2];

                //untuk ipk
                $grade_ipk+=$current[$i][2]*$singleGrade;
                $sks_ipk+=$current[$i][2];

                for($k=6;$k<count($current[$i]);$k+=2){
                    $singleGrade = $this->gradeConvert($current[$i][$k], substr($current[$i][0],2,1)=="1");
                    $grade_ipk+=$current[$i][2]*$singleGrade;
                    $sks_ipk+=$current[$i][2];
                }
            }
            $finalData['Hsemester'.($j+1).'ip']=$grade/$sks;
            $finalData['Hsemester'.($j+1).'ipk']=$grade_ipk/$sks_ipk;
            $finalData['Hsemester'.($j+1).'sks_lulus']=$sks;
            $finalData['Hsemester'.($j+1).'sks_total']=$sks_ipk;
        }

        //matkul pilihan tidak lulus smt7
        $ghostList = [];
        $pilihanGhost = Transcript::select(['pc_transcripts.grade_uni','pc_courses.code', 'pc_courses.title_en', 'pc_courses.sch', 'pc_courses.rex', 'pc_courses.mbs', 'pc_courses.et', 'pc_courses.ge', 'pc_curricula.title', 'pc_courses.semester', 'pc_transcripts.smt_taken', 'pc_transcripts.year_taken', 'pc_transcripts.course_id'])
                    ->join('pc_courses','pc_transcripts.course_id','pc_courses.id')
                    ->join('pc_curricula','pc_courses.curriculum_id','pc_curricula.id')
                    ->where('pc_transcripts.student_id',$data['id'])
                    ->where('pc_transcripts.is_transcripted',0)
                    ->where('pc_courses.rex',"!=","R")
                    ->where('pc_courses.semester',7)
                    ->get();

        
        for ($j = 0; $j < count($pilihanGhost); $j++) {
            $checking = Transcript::select(['pc_transcripts.*'])
                        ->where('pc_transcripts.student_id',$data['id'])
                        ->where('pc_transcripts.course_id', $pilihanGhost[$j]->course_id)
                        ->where('pc_transcripts.is_transcripted',1)
                        ->first();
            
            if($checking==null&&!in_array($pilihanGhost[$j]->code, $ghostList)){
                array_push($ghostList, $pilihanGhost[$j]->code);

                $rows = [];
    
                $rows[] = $pilihanGhost[$j]->code."-".substr($pilihanGhost[$j]->title, -2);
                $rows[] = $pilihanGhost[$j]->title_en;
                $rows[] = $pilihanGhost[$j]->sch;
                
                $rows[] = $pilihanGhost[$j]->year_taken."/".($pilihanGhost[$j]->year_taken+1);
                $rows[] = $pilihanGhost[$j]->grade_uni;

                $tmp = Transcript::select(['pc_transcripts.grade_uni', 'pc_transcripts.year_taken'])
                        ->join('pc_courses','pc_transcripts.course_id','pc_courses.id')
                        ->where('pc_transcripts.student_id',$data['id'])
                        ->where('pc_transcripts.course_id',$pilihanGhost[$j]->course_id)
                        ->where('pc_transcripts.is_transcripted', 0)
                        ->where('pc_transcripts.smt_taken',1)
                        ->orderBy('pc_transcripts.year_taken', 'desc')
                        ->get();

                for($k=0;$k<count($tmp);$k++){
                    $rows[] = $tmp[$k]->year_taken."/".($tmp[$k]->year_taken+1);
                    $rows[] = $tmp[$k]->grade_uni;
                }

                array_push($finalData['Hsemester7'], $rows);
            }
        }

        //matkul pilihan tidak lulus smt8
        $ghostList = [];
        $pilihanGhost = Transcript::select(['pc_transcripts.grade_uni','pc_courses.code', 'pc_courses.title_en', 'pc_courses.sch', 'pc_courses.rex', 'pc_courses.mbs', 'pc_courses.et', 'pc_courses.ge', 'pc_curricula.title', 'pc_courses.semester', 'pc_transcripts.smt_taken', 'pc_transcripts.year_taken', 'pc_transcripts.course_id'])
                    ->join('pc_courses','pc_transcripts.course_id','pc_courses.id')
                    ->join('pc_curricula','pc_courses.curriculum_id','pc_curricula.id')
                    ->where('pc_transcripts.student_id',$data['id'])
                    ->where('pc_transcripts.is_transcripted',0)
                    ->where('pc_courses.rex',"!=","R")
                    ->where('pc_courses.semester',8)
                    ->get();

        
        for ($j = 0; $j < count($pilihanGhost); $j++) {
            $checking = Transcript::select(['pc_transcripts.*'])
                        ->where('pc_transcripts.student_id',$data['id'])
                        ->where('pc_transcripts.course_id', $pilihanGhost[$j]->course_id)
                        ->where('pc_transcripts.is_transcripted',1)
                        ->first();
            
            if($checking==null&&!in_array($pilihanGhost[$j]->code, $ghostList)){
                array_push($ghostList, $pilihanGhost[$j]->code);

                $rows = [];
    
                $rows[] = $pilihanGhost[$j]->code."-".substr($pilihanGhost[$j]->title, -2);
                $rows[] = $pilihanGhost[$j]->title_en;
                $rows[] = $pilihanGhost[$j]->sch;
                
                $rows[] = $pilihanGhost[$j]->year_taken."/".($pilihanGhost[$j]->year_taken+1);
                $rows[] = $pilihanGhost[$j]->grade_uni;

                $tmp = Transcript::select(['pc_transcripts.grade_uni', 'pc_transcripts.year_taken'])
                        ->join('pc_courses','pc_transcripts.course_id','pc_courses.id')
                        ->where('pc_transcripts.student_id',$data['id'])
                        ->where('pc_transcripts.course_id',$pilihanGhost[$j]->course_id)
                        ->where('pc_transcripts.is_transcripted', 0)
                        ->where('pc_transcripts.smt_taken',2)
                        ->orderBy('pc_transcripts.year_taken', 'desc')
                        ->get();

                for($k=0;$k<count($tmp);$k++){
                    $rows[] = $tmp[$k]->year_taken."/".($tmp[$k]->year_taken+1);
                    $rows[] = $tmp[$k]->grade_uni;
                }

                array_push($finalData['Hsemester8'], $rows);
            }
        }

        //itung lagi ip&ipk dengan matkul pilihan ghost
        //itung ip&ipk disini
        for ($j = 0; $j < 8; $j++) {
            $grade = 0;
            $sks = 0;

            $grade_ipk =0;
            $sks_ipk =0;

            $current = $finalData['Hsemester'.($j+1)];
            for($i = 0; $i < count($current); $i++){
                $singleGrade = $this->gradeConvert($current[$i][4], substr($current[$i][0],2,1)=="1");
                
                $grade+=$current[$i][2]*$singleGrade;
                if($singleGrade!=0){ $sks+=$current[$i][2]; }

                //untuk ipk
                $grade_ipk+=$current[$i][2]*$singleGrade;
                $sks_ipk+=$current[$i][2];

                for($k=6;$k<count($current[$i]);$k+=2){
                    $singleGrade = $this->gradeConvert($current[$i][$k], substr($current[$i][0],2,1)=="1");
                    $grade_ipk+=$current[$i][2]*$singleGrade;
                    $sks_ipk+=$current[$i][2];
                }
            }
            $finalData['Hsemester'.($j+1).'ip_v2']=$grade/$sks;
            $finalData['Hsemester'.($j+1).'ipk_v2']=$grade_ipk/$sks_ipk;
            $finalData['Hsemester'.($j+1).'sks_lulus_v2']=$sks;
            $finalData['Hsemester'.($j+1).'sks_total_v2']=$sks_ipk;
        }


        //hitung ip&ipk final
        $grade_total=0;
        $grade_total_ipk=0;
        $sks_total=0;
        $sks_total_ipk=0;

        for ($j = 0; $j < 8; $j++) {
            $grade_total+=($finalData['Hsemester'.($j+1).'ip']*$finalData['Hsemester'.($j+1).'sks_lulus']);
            $sks_total+=$finalData['Hsemester'.($j+1).'sks_lulus'];
            $grade_total_ipk+=($finalData['Hsemester'.($j+1).'ipk']*$finalData['Hsemester'.($j+1).'sks_lulus']);
            $sks_total_ipk+=$finalData['Hsemester'.($j+1).'sks_total'];
        }
        $finalData['HFinal_sks_lulus']=$sks_total;
        $finalData['HFinal_sks_total']=$sks_total_ipk;
        $finalData['HFinal_ip']=$grade_total/$sks_total;
        $finalData['HFinal_ipk']=$grade_total_ipk/$sks_total_ipk;
        
        $grade_total=0;
        $grade_total_ipk=0;
        $sks_total=0;
        $sks_total_ipk=0;

        for ($j = 0; $j < 8; $j++) {
            $grade_total+=($finalData['Hsemester'.($j+1).'ip_v2']*$finalData['Hsemester'.($j+1).'sks_lulus_v2']);
            $sks_total+=$finalData['Hsemester'.($j+1).'sks_lulus_v2'];
            $grade_total_ipk+=($finalData['Hsemester'.($j+1).'ipk_v2']*$finalData['Hsemester'.($j+1).'sks_lulus_v2']);
            $sks_total_ipk+=$finalData['Hsemester'.($j+1).'sks_total_v2'];
        }
        //$finalData['HFinal_sks_lulus_v2']=$sks_total;
        //$finalData['HFinal_sks_total_v2']=$sks_total_ipk;
        //$finalData['HFinal_ip_v2']=$grade_total/$sks_total;

        //ipkv1
        $tmp = Transcript::select(['pc_transcripts.grade_uni', 'pc_transcripts.year_taken', 'pc_courses.sch', 'pc_courses.code'])
                        ->join('pc_courses','pc_transcripts.course_id','pc_courses.id')
                        ->where('pc_transcripts.student_id',$data['id'])
                        ->get();

        $total_grade=0;$total_sks=0;
        for ($j = 0; $j < count($tmp); $j++) {
            $singleGrade = $this->gradeConvert($tmp[$j]->grade_uni, substr($tmp[$j]->code,2,1)=="1");
            $total_grade+=$singleGrade*$tmp[$j]->sch;
            $total_sks+=$tmp[$j]->sch;
        }

        //$finalData['HFinal_ipk_v2']=$grade_total_ipk/$sks_total_ipk;
        $finalData['HFinal_ipk']=$total_grade/$total_sks;
        $finalData['HFinal_sks_total']=$total_sks;

        //ipkv2
        $tmp = Transcript::select(['pc_transcripts.grade_uni', 'pc_transcripts.year_taken', 'pc_courses.sch', 'pc_courses.code'])
                        ->join('pc_courses','pc_transcripts.course_id','pc_courses.id')
                        ->where('pc_transcripts.student_id',$data['id'])
                        ->get();

        $total_grade=0;$total_sks=0;
        for ($j = 0; $j < count($tmp); $j++) {
            $singleGrade = $this->gradeConvert($tmp[$j]->grade_uni, true);
            $total_grade+=$singleGrade*$tmp[$j]->sch;
            $total_sks+=$tmp[$j]->sch;
        }

        //$finalData['HFinal_ipk_v2']=$grade_total_ipk/$sks_total_ipk;
        $finalData['HFinal_ipk_v2']=$total_grade/$total_sks;
        $finalData['HFinal_sks_total_v2']=$total_sks;

        //crosscheck sks sarjana v1
        $tmp = Transcript::select(['pc_transcripts.grade_uni', 'pc_transcripts.year_taken', 'pc_courses.sch', 'pc_courses.code'])
                        ->join('pc_courses','pc_transcripts.course_id','pc_courses.id')
                        ->where('pc_transcripts.student_id',$data['id'])
                        ->where('pc_courses.semester','>',2)
                        ->get();

        $total_grade=0;$total_sks=0;
        for ($j = 0; $j < count($tmp); $j++) {
            $singleGrade = $this->gradeConvert($tmp[$j]->grade_uni,  substr($tmp[$j]->code,2,1)=="1");
            $total_grade+=$singleGrade*$tmp[$j]->sch;
            $total_sks+=$tmp[$j]->sch;
        }

        $finalData['HSarjana_ipk']=$total_grade/$total_sks;
        $finalData['HSarjana_sks']=$total_sks;

        //crosscheck sks sarjana v1
        $tmp = Transcript::select(['pc_transcripts.grade_uni', 'pc_transcripts.year_taken', 'pc_courses.sch', 'pc_courses.code'])
                        ->join('pc_courses','pc_transcripts.course_id','pc_courses.id')
                        ->where('pc_transcripts.student_id',$data['id'])
                        ->where('pc_courses.semester','>',2)
                        ->get();

        $total_grade=0;$total_sks=0;
        for ($j = 0; $j < count($tmp); $j++) {
            $singleGrade = $this->gradeConvert($tmp[$j]->grade_uni, true);
            $total_grade+=$singleGrade*$tmp[$j]->sch;
            $total_sks+=$tmp[$j]->sch;
        }

        $finalData['HSarjana_ipk_v2']=$total_grade/$total_sks;
        $finalData['HSarjana_sks_v2']=$total_sks;
        

        //hitung final grade R
        $finalData['HfinalGrade'] = [
            [
                $finalData['Hsemester1sks_lulus']+$finalData['Hsemester2sks_lulus'],
                ($finalData['Hsemester1ip']*$finalData['Hsemester1sks_lulus']+$finalData['Hsemester2ip']*$finalData['Hsemester2sks_lulus'])/($finalData['Hsemester1sks_lulus']+$finalData['Hsemester2sks_lulus']),
                $finalData['Hsemester1sks_total']+$finalData['Hsemester2sks_total'],
                ($finalData['Hsemester1ipk']*$finalData['Hsemester1sks_total']+$finalData['Hsemester2ipk']*$finalData['Hsemester2sks_total'])/($finalData['Hsemester1sks_total']+$finalData['Hsemester2sks_total']),
                $finalData['Hsemester1sks_total_v2']+$finalData['Hsemester2sks_total_v2'],
                ($finalData['Hsemester1ipk_v2']*$finalData['Hsemester1sks_total_v2']+$finalData['Hsemester2ipk_v2']*$finalData['Hsemester2sks_total_v2'])/($finalData['Hsemester1sks_total_v2']+$finalData['Hsemester2sks_total_v2'])
            ],
            [
            $finalData['Hsemester3sks_lulus']+$finalData['Hsemester4sks_lulus']+$finalData['Hsemester5sks_lulus']+$finalData['Hsemester6sks_lulus']+$finalData['Hsemester7sks_lulus']+$finalData['Hsemester8sks_lulus'],
            ($finalData['Hsemester3ip']*$finalData['Hsemester3sks_lulus']+$finalData['Hsemester4ip']*$finalData['Hsemester4sks_lulus']+$finalData['Hsemester5ip']*$finalData['Hsemester5sks_lulus']+$finalData['Hsemester6ip']*$finalData['Hsemester6sks_lulus']+$finalData['Hsemester7ip']*$finalData['Hsemester7sks_lulus']+$finalData['Hsemester8ip']*$finalData['Hsemester8sks_lulus'])/($finalData['Hsemester3sks_lulus']+$finalData['Hsemester4sks_lulus']+$finalData['Hsemester5sks_lulus']+$finalData['Hsemester6sks_lulus']+$finalData['Hsemester7sks_lulus']+$finalData['Hsemester8sks_lulus']),
            $finalData['HSarjana_sks'],
            $finalData['HSarjana_ipk'],
            $finalData['HSarjana_sks_v2'],
            $finalData['HSarjana_ipk_v2'],
            ],
            [
                $finalData['HFinal_sks_lulus'],
                $finalData['HFinal_ip'],
                $finalData['HFinal_sks_total'],
                $finalData['HFinal_ipk'],
                $finalData['HFinal_sks_total_v2'],
                $finalData['HFinal_ipk_v2'],
            ],
        ];

         //return $finalData;


    //$endResult = array_merge($finalData, $this->countIPK($data['id']));

    //[WARNING] Kemana semester diatas 8?
    

    //Dapatkan info transkrip
    $transcriptInfo = TranscriptInfo::select(['tr_infos.*'])->where('student_id', $data['id'])->first();

    if($transcriptInfo!=null){
        $advisor = User::select(['users.*'])->where('id', $transcriptInfo->advisor_id)->first();

        // $endResult['HyudisiumDate'] = $transcriptInfo->yudisium_date;
        // $endResult['HgraduationDate'] = $transcriptInfo->graduation_date;
        $finalData['HacademicAdviserName'] = $advisor->name;
        $finalData['HacademicAdviserNip'] = "NIP: ".$advisor->no_induk;
        //$endResult['Hfinal_exam'] = $transcriptInfo->final_exam;
        // $endResult['HfinalExamination'] = [];

        // $final_exam=explode(";", $transcriptInfo->final_exam);
        // for ($i=0; $i<count($final_exam); $i+=2) {
        //     $tmp_[0]=$i/2+1;
        //     $tmp_[1]=$final_exam[$i];

        //     $tmp2[0]=$final_exam[$i+1];
        //     $endResult['HfinalExaminationCheck'][] = $tmp2;
        //     $endResult['HfinalExamination'][] = $tmp_;
        // }
    }

        
        $name = 'Transkrip-'.$user->no_induk.'-'.$user->name.'.xlsx';
        $template = storage_path(). DIRECTORY_SEPARATOR . 'templates'. DIRECTORY_SEPARATOR . 'finalHistoryTemplate.xlsx';
        $file = $this->exportExcel($finalData,$template);
        return Response()->download($file, $name)->deleteFileAfterSend(true);
    }
}
