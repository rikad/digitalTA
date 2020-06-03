<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\User;
use App\KpCorp;
use App\Course;
use App\Equivalency;
use App\Curriculum;
use App\Topic;
use App\TopicInterest;
use App\Role;
use Yajra\Datatables\Datatables;
use Yajra\Datatables\Html\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;
use Validator;

class CoursesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dosen() {
        $data = User::join('role_user','role_user.user_id','users.id')
            ->join('roles','role_user.role_id','roles.id')
            ->where('roles.name','dosen')
            ->pluck("users.name","users.id");

        return $data;
    }

    public function validation($id) {

        $data = [
            'title' => 'required|unique:topics,title',
            'title_en' => 'required',
            'waktu' => 'required',
            'dana' => 'required'
        ];

        if($id != false ) {
            $data['title'] = $data['title'].','.$id;
        }

        return $data;
    }

    public function index(Request $request, Builder $htmlBuilder)
    {
        if ($request->ajax()) {
            $data = Course::select('pc_courses.*', 'pc_curricula.title as curriculum')
            ->join('pc_curricula','pc_courses.curriculum_id','pc_curricula.id')->orderBy('rex');

            return Datatables::of($data)
                    ->addColumn('status',function($data){
                        if($data['rex']!="R"&&$data['rex']!="E"&&$data['rex']!="X"){
                            return '<button class="btn btn-danger btn-xs">Data Tidak Lengkap</button>';
                        }
                        return '';
                    })        
                    ->addColumn('action',function($data) { 
                        return '<button class="btn btn-primary btn-xs" onclick="rikad.edit(this,\''.$data->id.'\')"><span class="glyphicon glyphicon-pencil"></span></button>';// <button class="btn btn-danger btn-xs" onclick="rikad.delete(\''.$data->id.'\')"><span class="glyphicon glyphicon-remove"></span></button>';
                    })->make(true);
        }

        $html = $htmlBuilder
          ->addColumn(['data' => 'curriculum', 'name'=>'curriculum', 'title'=>'Kurikulum', 'orderable'=>false, 'searchable'=>false])
          ->addColumn(['data' => 'code', 'name'=>'pc_courses.code', 'title'=>'Kode'])
          ->addColumn(['data' => 'title', 'name'=>'pc_courses.title', 'title'=>'Mata Kuliah'])
          ->addColumn(['data' => 'title_en', 'name'=>'pc_courses.title_en', 'title'=>'Mata Kuliah (EN)'])
          ->addColumn(['data' => 'status', 'name'=>'status', 'title'=>'Status', 'orderable'=>false, 'searchable'=>false])
          ->addColumn(['data' => 'action', 'name'=>'action', 'title'=>'Action', 'orderable'=>false, 'searchable'=>false]);

        return view('administration.course.index')->with(compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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

        $data = $request->except(['_token', 'kurikulum']);
        $kurikulum = $request->input('kurikulum');

        $curriculum = Curriculum::select(['pc_curricula.*'])->where('title', $kurikulum)->first();
        if($curriculum==null){
            Session::flash("flash_notification", [
             "level"=>"danger",
             "message"=>"Informasi Kurikulum tidak sesuai format. Coba lagi dengan memasukkan tahun kurikulum"
         ]);

         return redirect('/tu/courses');//->route('users.index');
        }

        if($data['rex']!="R"&&$data['rex']!="E"&&$data['rex']!="X"){
            Session::flash("flash_notification", [
             "level"=>"danger",
             "message"=>"Informasi REX tidak sesuai. Isi REX dengan R atau E atau X"
         ]);

         return redirect('/tu/courses');//->route('users.index');
        }

        //return $data;
        $course = Course::select('pc_courses.*')->find($data['id']);
        $data['curriculum_id']=$curriculum->id;

         if($course){
             $course->update($data);
         } else {
             //$corp = KpCorp::create($data);
            Session::flash("flash_notification", [
             "level"=>"danger",
             "message"=>"Mata kuliah tidak ditemukan"
         ]);

         return redirect('/tu/courses');//->route('users.index');
         }

        Session::flash("flash_notification", [
             "level"=>"success",
             "message"=>"Informasi Mata kuliah berhasil disimpan"
         ]);

         return redirect('/tu/courses');//->route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        //
        //if ($request->ajax()) {
            $corp = Course::select('pc_courses.*', 'pc_curricula.title as curriculum')
            ->join('pc_curricula','pc_courses.curriculum_id','pc_curricula.id')->find($id);
            return $corp;
        //}
        return $id;
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
        $user = KpCorp::where('id', $id)->first();
        $user->delete();

        Session::flash("flash_notification", [
            "level"=>"danger",
            "message"=>"Data Perusahaan dihapus"
        ]);

        return 'ok';
    }

    public function peminat(Request $request, Builder $htmlBuilder){
        //return $request->route('id');

        if ($request->ajax()) {
            /*$data = Topic::selectRaw('topics.id,topics.title,topics.description,topics.is_taken, topics.dosen1_id, users.name, topics.bobot, topics.waktu, topics.dana,
                (select count(*) from group_topic where group_topic.topic_id = topics.id) as peminat2'
                //DB::raw('')
                )*/

                $data=TopicInterest::selectRaw('title, nim1, student1, nim2, student2, status, id, gtopic_id')
                    ->where('id',$request->route('id'))
                    ->get();

            return Datatables::of($data)
                    ->addColumn('status2',function($data) { 
                        if($data->status==0){return 'Mengajukan';}
                        if($data->status==1){return 'Disetujui';}
                        if($data->status==2){return 'Ditolak';}
                    })
                    ->addColumn('action',function($data) { 
                        if($data->status==0){
                        return '<button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#myModalAccept" onclick="prepare(\''.$data->gtopic_id.'\', \''.$data->id.'\')"><span class="glyphicon glyphicon-pencil"></span> Setujui</button>';
                        /*return '<button class="btn btn-primary btn-xs" onClick="rikad.edit(this,\''.$data->id.'\')"><span class="glyphicon glyphicon-pencil"></span></button>';*/
                    }else{return '';}
                    })->make(true);
        }

        $html = $htmlBuilder
          ->addColumn(['data' => 'title', 'name'=>'topics.title', 'title'=>'Judul'])
          ->addColumn(['data' => 'student1', 'name'=>'student1', 'title'=>'Student 1'])
          ->addColumn(['data' => 'student2', 'name'=>'student2', 'title'=>'Student 2'])
          ->addColumn(['data' => 'status2', 'name'=>'status2', 'title'=>'Status'])
          ->addColumn(['data' => 'action', 'name'=>'action', 'title'=>'Action', 'orderable'=>false, 'searchable'=>false]);

        return view('dosen.topics.interest')->with(compact('html'));
    }

    public function peminatRespond(Request $request){
        //return "huh";
        $data = $request->except(['_token']);
        
        DB::table('group_topic')
            ->where('id', $data['idtopic'])
            ->update(['status' => $data['submitRespond'], 'note' => $data['note']]);

        $currentgroup = DB::table('group_topic')
            ->where('id', $data['idtopic'])
            ->first();

        if($data['submitRespond']==1){$hasil="disetujui";
            DB::table('topics')
            ->where('id', $data['id_topic'])
            ->update(['is_taken' => 1]);

            DB::table('group_topic')
            ->where('group_id', $currentgroup->group_id)
            ->whereNotIn('status', [1])
            ->delete();

            DB::table('group_topic')
            ->where('topic_id', $data['id_topic'])
            ->whereNotIn('status', [1])
            ->delete();
        }
        elseif($data['submitRespond']==2){$hasil="ditolak";}

        Session::flash("flash_notification", [
             "level"=>"success",
             "message"=>"Permintaan topik sudah di ".$hasil
         ]);

         return redirect('/dosen/topics/peminat/'.$data['id_topic']);//->route('users.index');
    }

    public function registerEquivalencies(Request $request){
        $data = $request->except(['_token']);
        $error = "Error for ";

        $curriculum2008 = Curriculum::select(['pc_curricula.*'])->where('title', '2008')->first();
        $curriculum2013 = Curriculum::select(['pc_curricula.*'])->where('title', '2013')->first();

        $list = preg_split('/\r\n|[\r\n]/', $data['data']);
        for ($x = 0; $x < count($list); $x++) {
            $params = preg_split('/\t|[\t]/', $list[$x]);

            if($params[6]!=""&&$params[2]!=""){
                $course2008 = Course::select(['pc_courses.id'])->where('code', $params[6])
                    ->where('curriculum_id', $curriculum2008->id)->first();
                $course2013 = Course::select(['pc_courses.id'])->where('code', $params[2])
                    ->where('curriculum_id', $curriculum2013->id)->first();

                if($course2008!=null&&$course2013!=null){
                    $tmp['course2008_id']=$course2008->id;
                    $tmp['course2013_id']=$course2013->id;
                    Equivalency::create($tmp);
                }else{
                    $error.=$params[2]."-13 and ".$params[6]."-08, ";
                }
            }
            
        }

        if($error=="Error for "){
            Session::flash("flash_notification", [
            "level"=>"danger",
            "message"=>$error
            ]);

            return redirect('tu/courses');        
        }else{
            Session::flash("flash_notification", [
            "level"=>"success",
            "message"=>"Data equivalency berhasil di update"
            ]);

            return redirect('tu/courses');        
        }

        return $data;
    }

    //untuk mengisi course data kurikulum baru dengan kurikulum lama
    public function lookup(Request $request){

        $courses = Course::where('rex','-')->get();

        foreach($courses as $v) {
           $same = Course::where('code',$v->code)->where('rex','!=','-')->first();
           
           if($same) {
               $course = Course::find($v->id);
               $course->title_en = $same->title_en;

               if($course->rex == '-') {
                   $course->rex = $same->rex;
               }

               if($same->mbs != '0') {
                   $course->mbs = $course->sch;
               }
               if($same->et != '0') {
                   $course->et = $course->sch;
               }
               if($same->ge != '0') {
                   $course->ge = $course->sch;
               }

               $course->save();
           }
        }

        Session::flash("flash_notification", [
            "level"=>"success",
            "message"=>"Data berhasil di lookup"
        ]);

        return redirect('tu/courses');
    }

}
