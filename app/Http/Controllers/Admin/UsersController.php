<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use Yajra\Datatables\Datatables;
use Yajra\Datatables\Html\Builder;
use Illuminate\Support\Facades\Auth;
use Session;
use Validator;

class UsersController extends Controller
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
            'name' => 'required:unique:users,name',
            'email' => 'required:unique:users,email',
            'password' => 'nullable',
            'role' => 'required|exists:roles,id',
        ];

        if($id != false ) {
            $data['name'] = $data['name'].','.$id;
            $data['email'] = $data['email'].','.$id;
        }

        return $data;
    }

    public function index(Request $request, Builder $htmlBuilder)
    {
        if ($request->ajax()) {
            $data = User::select(['users.id','users.name', 'users.email','roles.display_name'])
                    ->join('role_user','role_user.user_id','users.id')
                    ->join('roles','role_user.role_id','roles.id');
            return Datatables::of($data)
                    ->addColumn('action',function($data) { 
                        return '<button class="btn btn-primary btn-xs" onclick="rikad.edit(this,\''.$data->id.'\')"><span class="glyphicon glyphicon-pencil"></span></button> <button class="btn btn-danger btn-xs" onclick="rikad.delete(\''.$data->id.'\')"><span class="glyphicon glyphicon-remove"></span></button>';
                    })->make(true);
        }

        $html = $htmlBuilder
          ->addColumn(['data' => 'name', 'name'=>'users.name', 'title'=>'Username'])
          ->addColumn(['data' => 'email', 'name'=>'users.email', 'title'=>'Email'])
          ->addColumn(['data' => 'display_name', 'name'=>'roles.display_name', 'title'=>'Role'])
          ->addColumn(['data' => 'action', 'name'=>'action', 'title'=>'Action', 'orderable'=>false, 'searchable'=>false]);

        return view('users.index')->with(compact('html'));
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

        if ($request->input('password') != null) {
            $data['password'] = bcrypt($request->input('password'));
        }

        //check if data exists update else create
        if($user){
            $validator = Validator::make($data, $this->validation($data['id']));
            if ($validator->fails()) {
                Session::flash("flash_notification", [
                    "level"=>"danger",
                    "message"=>$validator->messages()
                ]);

                return redirect()->route('users.index');
            }

            $user->update($data);

            $user->detachRole($user->role_id);
            $user->attachRole($data['role']);
        } else {
            $validator = Validator::make($data, $this->validation(false));
            if ($validator->fails()) {
                Session::flash("flash_notification", [
                    "level"=>"danger",
                    "message"=>$validator->messages()
                ]);

                return redirect()->route('users.index');
            }

            $data['user_id'] = Auth::id();
            $user = User::create($data);
            $user->attachRole($data['role']);
        }

        Session::flash("flash_notification", [
            "level"=>"success",
            "message"=>"Users Information Updated"
        ]);

        return redirect()->route('users.index');

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
