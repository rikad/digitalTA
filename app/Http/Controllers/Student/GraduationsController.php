<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use Yajra\Datatables\Datatables;
use Yajra\Datatables\Html\Builder;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

use Session;
use Validator;

class GraduationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request, Builder $htmlBuilder)
    {
        Excel::create('New.xls', function($excel) {

            $excel->sheet('New sheet', function($sheet) {

                $sheet->loadView('students.index');

            });

        })->export('xls');



//        return view('students.graduations.index');
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
