<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Validator;

use App\Membership;
use App\Country;
use App\Organization;

class MembershipsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth::id();
        $data = Membership::where('user_id',$id)->get();

    	return View('memberships.index', ['data'=>$data]);
    }

    public function validation() {

        $data = [
            'title' => 'required',
            'start_date' => 'required|date',
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
        $membership = Membership::find($data['id']);

        $validator = Validator::make($data, $this->validation());
        if ($validator->fails()) {
            Session::flash("flash_notification", [
                "level"=>"danger",
                "message"=>$validator->messages()
            ]);

            return redirect()->action('MembershipsController@index');
        }

        //check if data exists update else create
        if($membership){
            $membership->update($data);
        } else {
            $data['user_id'] = Auth::id();
            Membership::create($data);
        }

        Session::flash("flash_notification", [
            "level"=>"success",
            "message"=>"Membership Information Updated"
        ]);

        return redirect()->action('MembershipsController@index');
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
        $membership = Membership::where('id', $id)->where('user_id',Auth::id())->first();
        $membership->delete();

        Session::flash("flash_notification", [
            "level"=>"danger",
            "message"=>"Membership Information Deleted"
        ]);

        return 'ok';
    }
}
