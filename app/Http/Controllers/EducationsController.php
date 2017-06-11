<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Validator;

use App\Education;
use App\Country;
use App\Organization;

class EducationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth::id();
        $data = Education::join('countries','countries.id','=','educations.country_id')
                ->join('organizations as program','program.id','=','educations.program_id')
                ->join('organizations as institution','institution.id','=','educations.institution_id')
                ->select('educations.*','countries.country','institution.organization as institution','program.organization as program')
                ->where('user_id',$id)
                ->get();

    	return View('educations.index', ['data'=>$data]);
    }

    public function validation() {

        $data = [
            'program_id' => 'required|numeric',
            'institution_id' => 'required|numeric',
            'country_id' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date'
        ];

        return $data;
    }

    public function options() {
        $countries = Country::pluck("country","id")->all();
        $program = Organization::where('form_id',3)->pluck("organization","id")->all();
        $institution = Organization::where('form_id',1)->pluck("organization","id")->all();

        $data=['country_id'=>$countries,'program_id'=>$program,'institution_id'=>$institution];
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
        $education = Education::find($data['id']);

        if(!is_numeric($data['country_id']) && !is_null($data['country_id'])) {
            $new = Country::firstOrCreate(['country'=>$data['country_id']]);
            $data['country_id'] = $new->id;
        }

        if(!is_numeric($data['program_id']) && !is_null($data['program_id'])) {
            $new = Organization::firstOrCreate(['organization'=>$data['program_id']],['form_id'=>3]);
            $data['program_id'] = $new->id;
        }
        if(!is_numeric($data['institution_id']) && !is_null($data['institution_id'])) {
            $new = Organization::firstOrCreate(['organization'=>$data['institution_id']],['form_id'=>1]);
            $data['institution_id'] = $new->id;
        }

        $validator = Validator::make($data, $this->validation());
        if ($validator->fails()) {
            Session::flash("flash_notification", [
                "level"=>"danger",
                "message"=>$validator->messages()
            ]);

            return redirect()->action('EducationsController@index');
        }

        //check if data exists update else create
        if($education){
            $education->update($data);
        } else {
            $data['user_id'] = Auth::id();
            Education::create($data);
        }

        Session::flash("flash_notification", [
            "level"=>"success",
            "message"=>"Education Information Updated"
        ]);

        return redirect()->action('EducationsController@index');
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
        $education = Education::where('id', $id)->where('user_id',Auth::id())->first();
        $education->delete();

        Session::flash("flash_notification", [
            "level"=>"danger",
            "message"=>"Education Information Deleted"
        ]);

        return 'ok';
    }
}
