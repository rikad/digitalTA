<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Helpers\exportExcelTranskrip;

use Illuminate\Http\Request;
use App\User;
use App\Course;
use App\Transcript;
use App\TranscriptInfo;
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
                        if(count($is_filled)==0){
                        return '<button class="btn btn-primary btn-xs" onclick="rikad.showModal(\''.$data->id.'\')"><span class="glyphicon glyphicon-upload"></span> Upload Transkrip</button>';
                        }else{
                            return '<a href="/tu/transcripts/detail?id='.$data->id.'" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-eye-open"></span> Lihat Transkrip</button>';
                        }
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
	$finalData['name'] = $user->name;
    $finalData['nim'] = $user->no_induk;

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
		$finalData['semester'.($j+1).'ip']=$grade/$sks;
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
        for ($i=0; $i<count($final_exam); $i+=2) {
            $tmp[0]=$i/2+1;
            $tmp[1]=$final_exam[$i];

            $tmp2[0]=$final_exam[$i+1];
            $endResult['finalExaminationCheck'][] = $tmp2;
            $endResult['finalExamination'][] = $tmp;
        }
    }
    //return $endResult;

	$name = 'Transkrip-'.$user->no_induk.'-'.$user->name.'.xlsx';
        $file = $this->exportExcel($endResult);
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

	
        $finalData = [];
        for ($j = 0; $j < count($result); $j++) {

            $semester = [];

            for ($i = 0; $i < count($result[$j]); $i++) {
                $rows = [];
    
                $rows[] = $result[$j][$i]->code."-".substr($result[$j][$i]->title, -2);
                $rows[] = $result[$j][$i]->sch;
                $rows[] = $result[$j][$i]->grade_uni;
        
                $semester[] = $rows;
            }

            $finalData['semester'.($j+1)] = $semester;
        }
	
	$pilihan = Transcript::select(['pc_transcripts.grade_uni','pc_courses.code', 'pc_courses.sch', 'pc_courses.rex',  'pc_courses.semester', 'pc_transcripts.smt_taken', 'pc_transcripts.year_taken'])
                    ->join('pc_courses','pc_transcripts.course_id','pc_courses.id')
                    ->join('pc_curricula','pc_courses.curriculum_id','pc_curricula.id')
                    ->where('pc_transcripts.student_id',$id)
                    ->where('pc_courses.rex',"!=","R")
                    ->get();


	//untuk matkul pilihan, olah dulu penempatan semesternya (sesuai semester pengambilan oleh mhs, bukan struktur kurikulum)
        for ($j = 0; $j < count($pilihan); $j++) {
		$pilihan[$j]->semester = 6+($pilihan[$j]->smt_taken%2);
		
                $rows = [];
    
                $rows[] = $pilihan[$j]->code."-".substr($pilihan[$j]->title, -2);
                $rows[] = $pilihan[$j]->sch;
                $rows[] = $pilihan[$j]->grade_uni;


		if(count($finalData['semester7'])>count($finalData['semester8'])){
			array_push($finalData['semester8'], $rows);
		}else{array_push($finalData['semester7'], $rows);}
		
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
    public function exportExcel($data)
    {

        $template = storage_path(). DIRECTORY_SEPARATOR . 'templates'. DIRECTORY_SEPARATOR . 'finalGradTemplate.xlsx';
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
        
	/*$user['no_induk'] = $data['nim'];
	$user['username'] = $data['nim'];
	$user['name'] = $data['nama'];
	$user['email'] = $data['nim']."@gmail.com";
	$user['password'] = bcrypt($data['nim']);

            $userNew = User::create($user);
            $userNew->attachRole(4);*/

        $list = preg_split('/\r\n|[\r\n]/', $data['grade']);

        for ($x = 0; $x < count($list); $x++) {
            $params = preg_split('/\t|[\t]/', $list[$x]);

            $course = Course::select(['pc_courses.id'])
                    ->join('pc_curricula','pc_courses.curriculum_id','pc_curricula.id')
                    ->where('pc_courses.code',$params[1])
                    ->where('pc_curricula.title',$params[4])
                    ->first();

            $tmp[$x]['student_id']=$data['id'];
            $tmp[$x]['course_id']=$course->id;
            $tmp[$x]['year_taken']=$params[6];
            $tmp[$x]['smt_taken']=$params[5];
            $tmp[$x]['grade_uni']=$params[8];
            $tmp[$x]['grade_ps']=$params[9];
            if($params[10]==""){
            $tmp[$x]['is_transcripted']=0;    
            }else{$tmp[$x]['is_transcripted']=1;}
            
            $transcript = Transcript::create($tmp[$x]);
        } 

        //return $tmp;

        Session::flash("flash_notification", [
            "level"=>"success",
            "message"=>"Data transkrip berhasil diupload. Lihat hasil dengan menekan tombol disamping <a href='/tu/transcripts/detail?id=".$data['id']."' class='btn btn-primary btn-xs'><span class='glyphicon glyphicon-eye-open'></span></a>"
        ]);

        return redirect('tu/transcripts');
        
    }

    public function register(Request $request)
    {
        $data = $request->except(['_token','password']);
        
    
        $list = preg_split('/\r\n|[\r\n]/', $data['data']);

        for ($x = 0; $x < count($list); $x++) {
            $params = preg_split('/\t|[\t]/', $list[$x]);

            if($params[0]=='')break;

            $userID = 0;
            $user_check = User::select(['users.*'])->where('no_induk', $params[0])->first();
            if($user_check==null){
                //Buatkan entitiy student
                $user['no_induk'] = $params[0];
                $user['username'] = $params[0];
                $user['name'] = $params[1];
                $user['email'] = $params[0]."@gmail.com";
                $user['password'] = bcrypt($params[0]);

                $userNew = User::create($user);
                $userNew->attachRole(4);

                $userID = $userNew['id'];
            }else{
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
            $info['student_id']=$userID;
            $info['advisor_id']=$advisor_id->id;
            $info['yudisium_date']=$params[6];
            $info['graduation_date']=$params[7];

            $tmp_final_exam = array_slice($params, 8);

            $info['final_exam']= join(";", $tmp_final_exam);

            
            $transcriptInfo = TranscriptInfo::create($info);
        } 

        //return $tmp;

        Session::flash("flash_notification", [
            "level"=>"success",
            "message"=>"Data transkrip berhasil diupload. Lihat hasil dengan menekan tombol disamping <a href='/tu/transcripts/detail?id=".$userID."' class='btn btn-primary btn-xs'><span class='glyphicon glyphicon-eye-open'></span></a>"
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
        //
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
    }

    public function bulk(Request $request)
    {
        $data = $request->except(['_token']);
        return "$data";
    }
}
