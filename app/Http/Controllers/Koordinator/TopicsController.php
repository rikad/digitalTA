<?php

namespace App\Http\Controllers\Koordinator;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\User;
use App\Topic;
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
        if ($request->ajax()) {
            $data = Topic::selectRaw('topics.id,topics.title,topics.description,topics.is_taken, topics.dosen1_id, users.name, topics.bobot, topics.waktu, topics.dana,
                (select count(*) from group_topic where group_topic.topic_id = topics.id) as peminat')
                    ->join('users','users.id','topics.dosen1_id');
                    //->join('users dosen2','dosen2.id','roles.id')
                    //->where('roles.name','student');

            return Datatables::of($data)
                    ->addColumn('action',function($data) { 
                        return '<button class="btn btn-primary btn-xs" onclick="rikad.edit(this,\''.$data->id.'\')"><span class="glyphicon glyphicon-pencil"></span></button> <button class="btn btn-danger btn-xs" onclick="rikad.delete(\''.$data->id.'\')"><span class="glyphicon glyphicon-remove"></span></button>';
                    })->make(true);
        }

        $html = $htmlBuilder
          ->addColumn(['data' => 'name', 'name'=>'users.name', 'title'=>'Dosen'])
          ->addColumn(['data' => 'title', 'name'=>'topics.title', 'title'=>'Judul'])
          ->addColumn(['data' => 'bobot', 'name'=>'topics.bobot', 'title'=>'Bobot', 'searchable'=>false])
          ->addColumn(['data' => 'waktu', 'name'=>'topics.waktu', 'title'=>'Waktu', 'searchable'=>false])
          ->addColumn(['data' => 'dana', 'name'=>'topics.dana', 'title'=>'Dana', 'searchable'=>false])
          ->addColumn(['data' => 'peminat', 'name'=>'peminat', 'title'=>'Peminat', 'searchable'=>false])
          ->addColumn(['data' => 'action', 'name'=>'action', 'title'=>'Action', 'orderable'=>false, 'searchable'=>false]);

        return view('koordinator.topics.index')->with(compact('html'));
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

            $data['period_id']=1;
            $data['is_taken']=0;
             

             $topic = Topic::create($data);
        }

        Session::flash("flash_notification", [
             "level"=>"success",
             "message"=>"Users Information Updated"
         ]);

         return redirect('/koordinator/topics');//->route('users.index');
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
}
