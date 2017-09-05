<?php

namespace App\Http\Controllers\Koordinator;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use Yajra\Datatables\Datatables;
use Yajra\Datatables\Html\Builder;
use Illuminate\Support\Facades\Auth;
use Session;
use Validator;

class StudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function roles() {
        $data = Role::pluck("display_name","id")->all();
        return $data;
    }

    public function validation($id) {

        $data = [
            'no_induk' => 'required:unique:users,no_induk',
            'name' => 'required:unique:users,name',
            'email' => 'required:unique:users,email',
        ];

        if($id != false ) {
            $data['no_induk'] = $data['no_induk'].','.$id;
            $data['name'] = $data['name'].','.$id;
            $data['email'] = $data['email'].','.$id;
        }

        return $data;
    }

    public function index(Request $request, Builder $htmlBuilder)
    {
        if ($request->ajax()) {
            $data = User::select(['users.id','users.name','users.no_induk', 'users.email','roles.display_name'])
                    ->join('role_user','role_user.user_id','users.id')
                    ->join('roles','role_user.role_id','roles.id')
                    ->where('roles.name','student');

            return Datatables::of($data)
                    ->addColumn('action',function($data) { 
                        return '<button class="btn btn-primary btn-xs" onclick="rikad.edit(this,\''.$data->id.'\')"><span class="glyphicon glyphicon-pencil"></span></button> <button class="btn btn-danger btn-xs" onclick="rikad.delete(\''.$data->id.'\')"><span class="glyphicon glyphicon-remove"></span></button>';
                    })->make(true);
        }

        $html = $htmlBuilder
          ->addColumn(['data' => 'no_induk', 'name'=>'users.no_induk', 'title'=>'No Induk'])
          ->addColumn(['data' => 'name', 'name'=>'users.name', 'title'=>'Username'])
          ->addColumn(['data' => 'email', 'name'=>'users.email', 'title'=>'Email'])
          ->addColumn(['data' => 'action', 'name'=>'action', 'title'=>'Action', 'orderable'=>false, 'searchable'=>false]);

        return view('koordinator.students.index')->with(compact('html'));
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
        $data = $request->except(['_token','password']);
        $user = User::select('users.*','role_user.role_id')
                    ->join('role_user','role_user.user_id','users.id')
                    ->find($data['id']);

        $validator = Validator::make($data, $this->validation($data['id']));

        if ($validator->fails()) {
            Session::flash("flash_notification", [
                "level"=>"danger",
                "message"=>$validator->messages()
            ]);

            return redirect('koordinator/students');
        }

        $user->update($data);

        Session::flash("flash_notification", [
            "level"=>"success",
            "message"=>"Users Information Updated"
        ]);

        return redirect('koordinator/students');

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
        $user = User::where('id', $id)->first();
        $user->delete();

        Session::flash("flash_notification", [
            "level"=>"danger",
            "message"=>"User Deleted"
        ]);

        return 'ok';
    }
}
