<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\User;
use App\Topic;
use App\Group;
use App\Role;
use Yajra\Datatables\Datatables;
use Yajra\Datatables\Html\Builder;
use Illuminate\Support\Facades\Auth;

use Session;
use Validator;

class TopicsController extends Controller
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
            'bobot' => 'required',
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

        $period = DB::table('student_period')->where('student_id',Auth::id())->first();

        if ($request->ajax()) {
            $data = Topic::selectRaw('topics.id,topics.title,topics.description,topics.is_taken, topics.dosen1_id, topics.bobot, topics.waktu, topics.dana,
                (select count(*) from group_topic where group_topic.topic_id = topics.id) as peminat')
                ->where('period_id',$period->period_id);

            return Datatables::of($data)->make(true);
        }

        $topic = Group::select('topics.*','users.name AS siswa1','users.no_induk AS siswa1no','users2.no_induk AS siswa2no','users2.name AS siswa2','dosen1.name AS dosen1','dosen1.no_induk AS dosen1no','dosen2.no_induk AS dosen2no','dosen2.name AS dosen2','group_topic.note','group_topic.status','group_topic.id AS relasi')
                ->leftjoin('users','users.id','groups.student1_id')
                ->leftjoin('users as users2','users2.id','groups.student2_id')
                ->join('group_topic','group_topic.group_id','groups.id')
                ->join('topics','topics.id','group_topic.topic_id')
                ->leftjoin('users as dosen1','dosen1.id','topics.dosen1_id')
                ->leftjoin('users as dosen2','dosen2.id','topics.dosen2_id')
                ->where('users.id',Auth::id())
                ->orWhere('users2.id',Auth::id())
                ->get();

        $partner = Group::select('users.name AS siswa1','users.no_induk AS siswa1no','users2.no_induk AS siswa2no','users2.name AS siswa2')
                ->leftjoin('users','users.id','groups.student1_id')
                ->leftjoin('users as users2','users2.id','groups.student2_id')
                ->where('users.id',Auth::id())
                ->orWhere('users2.id',Auth::id())
                ->first();


        return view('students.topics.index')->with(compact('topic'))->with(compact('partner'));
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

        $data = $request->except(['_token']);
        $topic = Topic::select('topics.*')->find($data['id']);
        
        //return json_encode($data);

        // //check if data exists update else create
        if($topic){

            $valid = [
                'dosen2_id' => 'exists:users,id',
            ];

            $validator = Validator::make($data, $valid);
            if ($validator->fails()) {
                 Session::flash("flash_notification", [
                     "level"=>"danger",
                     "message"=>$validator->messages()
                 ]);

                 return redirect('/student/topics');//->route('users.index');
            }

            $topic->update($data);

        } else {
             $validator = Validator::make($data, $this->validation(false));
             if ($validator->fails()) {
                 Session::flash("flash_notification", [
                     "level"=>"danger",
                   "message"=>$validator->messages()
                 ]);

                 

                 
                 return redirect('/student/topics');//->route('users.index');
             }
    
            $period = DB::table('student_period')->where('student_id',Auth::id())->first();
            $data['period_id']=$period->period_id;
            $data['is_taken']=0;
             

             $topic = Topic::create($data);
        }

        Session::flash("flash_notification", [
             "level"=>"success",
             "message"=>"Berhasil Di Tambahkan"
         ]);

         return redirect('/student/topics');//->route('users.index');
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
        $group = Group::select('groups.id')
                ->leftJoin('users','users.id','groups.student1_id')
                ->leftJoin('users as users2','users2.id','groups.student2_id')
                ->where('users.id',Auth::id())
                ->orWhere('users2.id',Auth::id())
                ->first();


        $search = DB::table('group_topic')->where('group_id',$group->id)->where('topic_id',$id)->get();

        //if not exist
        if (count($search) > 0) {
           Session::flash("flash_notification", [
                "level"=>"danger",
                "message"=>"Topik Sudah Anda Pilih"
            ]);           
        }
        else {
            $data['group_id'] = $group->id;
            $data['topic_id'] = $id;
            $data['status'] = 0;
            DB::table('group_topic')->insert($data);

            Session::flash("flash_notification", [
                "level"=>"success",
                "message"=>"Topik Berhasil Di Ajukan"
            ]);
        }


        return 'ok';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('group_topic')->where('id',$id)->delete();

        Session::flash("flash_notification", [
            "level"=>"danger",
            "message"=>"Berhasil Di Batalkan"
        ]);

        return 'ok';
    }
}
