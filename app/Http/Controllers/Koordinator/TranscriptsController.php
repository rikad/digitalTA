<?php

namespace App\Http\Controllers\Koordinator;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\User;
use App\Course;
use App\Transcript;
use Yajra\Datatables\Datatables;
use Yajra\Datatables\Html\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;
use Validator;

class TranscriptsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function period() {
        $data = Period::pluck("display_name","id")->all();
        return "$data;";
    }

    public function validation($id) {

        $data = [
            'no_induk' => 'required:unique:users,no_induk',
            'name' => 'required:unique:users,name',
            'email' => 'required:unique:users,email',
        ];

        if($id != false ) {
            $data['no_induk'] = $data['no_induk'].','.$id;
            $data['name'] = $data['name'].','.$id;
            $data['email'] = $data['email'].','.$id;
        }

        return $data;
    }

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
                        return '<button class="btn btn-primary btn-xs" onclick="rikad.upload(this,\''.$data->id.'\')"><span class="glyphicon glyphicon-upload"></span> Upload Transkrip</button>';
                        }else{
                            return '<a href="/koordinator/transcripts/detail?id='.$data->id.'" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-eye-open"></span> Lihat Transkrip</button>';
                        }
                    })->make(true);
        }

        $html = $htmlBuilder
          ->addColumn(['data' => 'no_induk', 'name'=>'users.no_induk', 'title'=>'No Induk'])
          ->addColumn(['data' => 'name', 'name'=>'users.name', 'title'=>'Mahasiswa'])
          ->addColumn(['data' => 'action', 'name'=>'action', 'title'=>'Transkrip', 'orderable'=>false, 'searchable'=>false]);

        return view('koordinator.transcripts.index')->with(compact('html'));
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

        for ($i = 0; $i < 8; $i++) {
            $result[$i] = Transcript::select(['pc_transcripts.grade_uni','pc_courses.code', 'pc_courses.title_en', 'pc_courses.sch', 'pc_courses.rex', 'pc_courses.mbs', 'pc_courses.et', 'pc_courses.ge', 'pc_curricula.title', 'pc_courses.semester'])
                    ->join('pc_courses','pc_transcripts.course_id','pc_courses.id')
                    ->join('pc_curricula','pc_courses.curriculum_id','pc_curricula.id')
                    ->where('pc_transcripts.student_id',$data['id'])
                    ->where('pc_transcripts.is_transcripted',1)
                    ->where('pc_courses.semester',$i+1)
                    ->get();
        }

        //debugging view
        $tmp = "";
        for ($j = 0; $j < count($result); $j++) {
        $tmp.="Semester ".($j+1)."<br><table border=1><tr><th>Code<th>Course<th>SCH<th>Grade<th>REX<th>MBS<th>ET<th>GE";
        for ($i = 0; $i < count($result[$j]); $i++) {
            $tmp.="<tr><td>".$result[$j][$i]->code."-".substr($result[$j][$i]->title, -2);
            $tmp.="<td>".$result[$j][$i]->title_en;
            $tmp.="<td>".$result[$j][$i]->sch;
            $tmp.="<td>".$result[$j][$i]->grade_uni;
            $tmp.="<td>".$result[$j][$i]->rex;
            $tmp.="<td>".$result[$j][$i]->mbs;
            $tmp.="<td>".$result[$j][$i]->et;
            $tmp.="<td>".$result[$j][$i]->ge;
        }
        $tmp.="</table><br>";
        }

        return $result;
    }

    public function create()
    {
        //
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
            "message"=>"Data transkrip berhasil diupload. Lihat hasil dengan menekan tombol disamping <a href='/koordinator/transcripts/detail?id=".$data['id']."' class='btn btn-primary btn-xs'><span class='glyphicon glyphicon-eye-open'></span></a>"
        ]);

        return redirect('koordinator/transcripts');
        
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::where('id', $id)->first();
        $user->delete();

        Session::flash("flash_notification", [
            "level"=>"danger",
            "message"=>"User Deleted"
        ]);

        return 'ok';
    }

    public function bulk(Request $request)
    {
        $data = $request->except(['_token']);
        return "$data";
    }
}
