<?php

namespace App\Http\Controllers\Koordinator;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\User;
use App\Topic;
use App\Role;
use App\Period;
use Yajra\Datatables\Datatables;
use Yajra\Datatables\Html\Builder;
use Illuminate\Support\Facades\Auth;
use Session;
use Validator;
use DB;

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

        $period_id = $request->input('id');
        if (!$period_id) {
            $last_period = Period::orderBy('id','desc')->first();
            $period_id = $last_period->id;
        }

        if ($request->ajax()) {
            $data = Topic::selectRaw('topics.id,topics.title,topics.description,topics.is_taken, topics.dosen1_id, users.name, topics.bobot, topics.waktu, topics.dana,
                (select count(*) from group_topic where group_topic.topic_id = topics.id) as peminat')
                    ->join('users','users.id','topics.dosen1_id')
                    ->where('topics.period_id',$period_id);
                    //->join('users dosen2','dosen2.id','roles.id')

            return Datatables::of($data)->make(true);
        }

        $period = Period::orderBy('id')->get();
	    $last_period = $period[count($period) - 1]->id;

        return view('koordinator.topics.index')->with(compact('html'))->with(compact('period'))->with(compact('last_period'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $period_id = $request->input('id');
        if (!$period_id) {
            $last_period = Period::orderBy('id','desc')->first();
            $period_id = $last_period->id;
        }

        $data = Topic::select(DB::raw('count(topics.id) as totalTopics, count(groups.id) as totalGroups'),'dosen1.name AS dosen', DB::raw('SUM(case when group_topic.status = 1 then 1 else 0 end) as totalGroupsAgree') )
                ->leftJoin('group_topic','group_topic.topic_id','topics.id')
                ->leftJoin('groups','group_topic.group_id','groups.id')
                ->join('users AS dosen1','dosen1.id','topics.dosen1_id')
                ->groupBy('dosen1.id')
                ->where('topics.period_id',$period_id)
                ->get();

        $period = Period::orderBy('id')->get();

        return view('koordinator.topics.statistics')->with(compact('data'))->with(compact('period'))->with(compact('period_id'));
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

        // //check if data exists update else create
         if($topic){
            $validator = Validator::make($data, $this->validation($data['id']));
                if ($validator->fails()) {
                 Session::flash("flash_notification", [
                     "level"=>"danger",
                     "message"=>$validator->messages()
                 ]);

                 return redirect('/koordinator/topics');//->route('users.index');
             }

             $topic->update($data);
         } else {
             $validator = Validator::make($data, $this->validation(false));
             if ($validator->fails()) {
                 Session::flash("flash_notification", [
                     "level"=>"danger",
                   "message"=>$validator->messages()
                 ]);

                 

                 
                 return redirect('/koordinator/topics');//->route('users.index');
             }

            $data['period_id']=$data['period_id'];
            $data['is_taken']=0;
             

             $topic = Topic::create($data);
        }

        Session::flash("flash_notification", [
             "level"=>"success",
             "message"=>"Topics Information Updated"
         ]);

         return redirect('/koordinator/topics');//->route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,Request $request, Builder $htmlBuilder)
    {

        if ($request->ajax()) {
            $data = Topic::select('topics.*','dosen1.name AS dosen1Name','dosen2.name AS dosen2Name','student1.name AS student1Name','student2.name AS student2Name','group_topic.id as gtopicid')
                    ->leftJoin('group_topic','group_topic.topic_id','topics.id')
                    ->leftJoin('groups','group_topic.group_id','groups.id')
                    ->leftJoin('users AS dosen1','dosen1.id','topics.dosen1_id')
                    ->leftJoin('users AS dosen2','dosen2.id','topics.dosen2_id')
                    ->leftJoin('users AS student1','groups.student1_id','student1.id')
                    ->leftJoin('users AS student2','groups.student2_id','student2.id')
                    ->where('topics.period_id',$id);


            return Datatables::of($data)->make(true);

        }

        $period = Period::all();

        return view('koordinator.topics.status')->with(compact('period'))->with(compact('id'));
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

    public function peminatRespond(Request $request){

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
        elseif($data['submitRespond']==2){
        	$hasil="ditolak";
    	}else {    		
        	$hasil="dibatalkan";
            DB::table('topics')
            ->where('id', $data['id_topic'])
            ->update(['is_taken' => 0]);

    	}

        Session::flash("flash_notification", [
             "level"=>"success",
             "message"=>"Permintaan topik sudah ".$hasil
         ]);

         return redirect('/koordinator/topics/'. $data['period']); //->route('users.index');
    }

}
