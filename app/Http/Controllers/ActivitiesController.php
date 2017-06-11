<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Validator;

use App\Activity;

class ActivitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function validation() {

        $data = [
            'title' => 'required',
            'type' => 'required|boolean',
        ];

        return $data;
    }

    public function index()
    {
        $id = Auth::id();
        $data = Activity::where('user_id',$id)->where('type',true)->get();
        $data2 = Activity::where('user_id',$id)->where('type',false)->get();

        return View('activities.index', ['data'=>$data,'data2'=>$data2]);
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
        $activities = Activity::find($data['id']);

        if ($data['type'] == '1') {
            $data['type']=true;
        }

        if ($data['type'] == '0') {
            $data['type']=false;
        }

        $validator = Validator::make($data, $this->validation());
        if ($validator->fails()) {
            Session::flash("flash_notification", [
                "level"=>"danger",
                "message"=>$validator->messages()
            ]);

            return redirect()->action('ActivitiesController@index');
        }

        //check if data exists update else create
        if($activities){
            $activities->update($data);
        } else {
            $data['user_id'] = Auth::id();
            Activity::create($data);
        }

        Session::flash("flash_notification", [
            "level"=>"success",
            "message"=>"Activity Information Updated"
        ]);

        return redirect()->action('ActivitiesController@index');

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
        $activities = Activity::where('id', $id)->where('user_id',Auth::id())->first();
        $activities->delete();

        Session::flash("flash_notification", [
            "level"=>"danger",
            "message"=>"Activity Information Deleted"
        ]);

        return 'ok';
    }
}
