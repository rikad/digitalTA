<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Validator;

use App\Experience;
use App\Country;
use App\Organization;

class ExperiencesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function validation() {

        $data = [
            'position' => 'required',
            'organization_id' => 'required|numeric',
            'type' => 'required|boolean',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date'
        ];

        return $data;
    }

    public function organizations() {
        $data = Organization::pluck("organization","id")->all();
        return $data;
    }

    public function index()
    {
        $id = Auth::id();
        $data = Experience::join('organizations','organizations.id','=','experiences.organization_id')
                ->select('experiences.*','organizations.organization as organization')
                ->where('user_id',$id)
                ->where('type',true)
                ->get();
        $data2 = Experience::join('organizations','organizations.id','=','experiences.organization_id')
                ->select('experiences.*','organizations.organization as organization')
                ->where('user_id',$id)
                ->where('type',false)
                ->get();

        return View('experiences.index', ['data'=>$data,'data2'=>$data2]);
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
        $experiences = Experience::find($data['id']);

        if ($data['type'] == '1') {
            $data['type']=true;
        }

        if ($data['type'] == '0') {
            $data['type']=false;
        }

        if(!is_numeric($data['organization_id']) && !is_null($data['organization_id'])) {
            $new = Organization::firstOrCreate(['organization'=>$data['organization_id']],['form_id'=>0]);
            $data['organization_id'] = $new->id;
        }

        $validator = Validator::make($data, $this->validation());
        if ($validator->fails()) {
            Session::flash("flash_notification", [
                "level"=>"danger",
                "message"=>$validator->messages()
            ]);

            return redirect()->action('ExperiencesController@index');
        }

        //check if data exists update else create
        if($experiences){
            $experiences->update($data);
        } else {
            $data['user_id'] = Auth::id();
            Experience::create($data);
        }

        Session::flash("flash_notification", [
            "level"=>"success",
            "message"=>"Experience Information Updated"
        ]);

        return redirect()->action('ExperiencesController@index');

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
        $experiences = Experience::where('id', $id)->where('user_id',Auth::id())->first();
        $experiences->delete();

        Session::flash("flash_notification", [
            "level"=>"danger",
            "message"=>"Experience Information Deleted"
        ]);

        return 'ok';
    }
}
