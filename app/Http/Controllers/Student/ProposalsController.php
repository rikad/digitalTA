<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\User;
use App\Topic;
use App\Group;
use App\Role;
use Illuminate\Support\Facades\Auth;

use Session;
use Validator;

class ProposalsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

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

    public function index(Request $request)
    {

        return view('students.proposals.index');
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

            $data['period_id']=1;
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
                ->leftjoin('users','users.id','groups.student1_id')
                ->leftjoin('users as users2','users2.id','groups.student2_id')
                ->where('users.id',Auth::id())
                ->orWhere('users2.id',Auth::id())
                ->first();

        $data['group_id'] = $group->id;
        $data['topic_id'] = $id;
        $data['status'] = 0;

        DB::table('group_topic')->insert($data);

        Session::flash("flash_notification", [
            "level"=>"success",
            "message"=>"Topik Berhasil Di Ajukan"
        ]);

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
