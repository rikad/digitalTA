<?php

namespace App\Http\Controllers\Koordinator;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\Period;
use Yajra\Datatables\Datatables;
use Yajra\Datatables\Html\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;
use Validator;

class StudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function period() {
        $data = Period::pluck("display_name","id")->all();
        return "$data;";
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

        $period_id = $request->input('id');
        if (!$period_id) {
            $last_period = Period::orderBy('id','desc')->first();
            $period_id = $last_period->id;
        }

        if ($request->ajax()) {
            $data = User::select(['users.id','users.name','users.no_induk', 'users.email','roles.display_name'])
                    ->join('role_user','role_user.user_id','users.id')
                    ->join('roles','role_user.role_id','roles.id')
                    ->join('student_period','student_period.student_id','users.id')
                    ->where('roles.name','student')
                    ->where('student_period.period_id',$period_id);

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

        $period = Period::orderBy('id')->get();
        $last_period = $period[count($period) - 1]->id;

        return view('koordinator.students.index')->with(compact('html'))->with(compact('period'))->with(compact('last_period'));
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
        
        
        if($data['students']){
            //return $data['students'];
            $list = preg_split('/\r\n|[\r\n]/', $data['students']);
            for ($x = 0; $x < count($list); $x++) {
              $tmp = explode(' ', $list[$x], 2);

              $student['id'] = Auth::id();
              $student['no_induk'] = $tmp[0];
              $student['username'] = $tmp[0];
              $student['name'] = $tmp[1];
              $student['email'] = $tmp[0]."@gmail.com";
              $student['password']=bcrypt($tmp[0]);
                $user = User::create($student);
                $user->attachRole(4);

                
                DB::table('student_period')->insert(
                    ['student_id' => $user['id'], 'period_id' => $data['period'] ]
                );
            } 

            Session::flash("flash_notification", [
            "level"=>"success",
            "message"=>"New Users Information Added"
            ]);

            return redirect('koordinator/students');
        }else{

        $user = User::select('users.*','role_user.role_id')
                    ->join('role_user','role_user.user_id','users.id')
                    ->find($data['id']);

        if($user){
            $validator = Validator::make($data, $this->validation($data['id']));

            if ($validator->fails()) {
                Session::flash("flash_notification", [
                    "level"=>"danger",
                    "message"=>$validator->messages()
                ]);

                return redirect('koordinator/students');
            }

            $user->update($data);
        }/*else{
            $validator = Validator::make($data, $this->validation($data['id']));

            if ($validator->fails()) {
                Session::flash("flash_notification", [
                    "level"=>"danger",
                    "message"=>$validator->messages()
                ]);

                return redirect('koordinator/students');
            }

            $data['id'] = Auth::id();
            $data['password']=bcrypt($data['no_induk']);
            $user = User::create($data);
            $user->attachRole(4);
        }*/
        
        Session::flash("flash_notification", [
            "level"=>"success",
            "message"=>"Users Information Updated"
        ]);

        return redirect('koordinator/students');
        }
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

    public function bulk(Request $request)
    {
        $data = $request->except(['_token']);
        return "$data";
    }
}
