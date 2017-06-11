<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Validator;

use App\Award;

class AwardsController extends Controller
{    /**

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth::id();
        $data = Award::where('user_id',$id)->get();

    	return View('awards.index', ['data'=>$data]);
    }

    public function validation() {

        $data = [
            'title' => 'required',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date'
        ];

        return $data;
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
        $membership = Award::find($data['id']);

        $validator = Validator::make($data, $this->validation());
        if ($validator->fails()) {
            Session::flash("flash_notification", [
                "level"=>"danger",
                "message"=>$validator->messages()
            ]);

            return redirect()->action('AwardsController@index');
        }

        //check if data exists update else create
        if($membership){
            $membership->update($data);
        } else {
            $data['user_id'] = Auth::id();
            Award::create($data);
        }

        Session::flash("flash_notification", [
            "level"=>"success",
            "message"=>"Award Information Updated"
        ]);

        return redirect()->action('AwardsController@index');
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
        $membership = Award::where('id', $id)->where('user_id',Auth::id())->first();
        $membership->delete();

        Session::flash("flash_notification", [
            "level"=>"danger",
            "message"=>"Award Information Deleted"
        ]);

        return 'ok';
    }
}
