<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\User;
use App\Topic;
use App\TopicInterest;
use App\Role;
use App\Period;
use Yajra\Datatables\Datatables;
use Yajra\Datatables\Html\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;
use Validator;

class TopicssController extends Controller
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

        $period_id = $request->input('id');

        if (!$period_id) {
            $last_period = Period::orderBy('id','desc')->first();
            $period_id = $last_period->id;
        }

        if ($request->ajax()) {
            $data = Topic::selectRaw('topics.id,topics.title,topics.description,topics.is_taken, topics.dosen1_id, users.name, topics.bobot, topics.waktu, topics.dana,
                (select count(*) from group_topic where group_topic.topic_id = topics.id) as peminat')
                    ->join('users','users.id','topics.dosen1_id')
                    ->where('topics.period_id', $period_id)
                    ->where('topics.dosen1_id',Auth::id());

            return Datatables::of($data)->make(true);
        }

        $period = Period::orderBy('id')->get();
        $last_period = $period[count($period) - 1]->id;

        return view('dosen.topics.index')->with(compact('period'))->with(compact('last_period'));
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
            $validator = Validator::make($data, $this->validation($data['id']));
                if ($validator->fails()) {
                 Session::flash("flash_notification", [
                     "level"=>"danger",
                     "message"=>$validator->messages()
                 ]);

                 return redirect('/dosen/topics');//->route('users.index');
             }

             $topic->update($data);
         } else {
             $validator = Validator::make($data, $this->validation(false));
             if ($validator->fails()) {
                 Session::flash("flash_notification", [
                     "level"=>"danger",
                   "message"=>$validator->messages()
                 ]);

                 

                 
                 return redirect('/dosen/topics');//->route('users.index');
             }

            $data['period_id']=1;
            $data['is_taken']=0;
            $data['dosen1_id']=Auth::id();
             

             $topic = Topic::create($data);
        }

        Session::flash("flash_notification", [
             "level"=>"success",
             "message"=>"Users Information Updated"
         ]);

         return redirect('/dosen/topics');//->route('users.index');
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
        $user = Topic::where('id', $id)->first();
        $user->delete();

        Session::flash("flash_notification", [
            "level"=>"danger",
            "message"=>"Topic Deleted"
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
}
