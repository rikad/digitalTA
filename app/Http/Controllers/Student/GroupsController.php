<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Group;
use App\User;
use App\Topic;
use App\Role;
use Yajra\Datatables\Datatables;
use Yajra\Datatables\Html\Builder;
use Illuminate\Support\Facades\Auth;
use Session;
use Validator;
use DB;

class GroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function validation() {

        $data = [
            'student1_id' => 'required|exists:users,id',
            'student2_id' => 'required|exists:users,id',
            'period_id' => 'required|exists:periods,id',
        ];

        return $data;
    }

    public function index(Request $request, Builder $htmlBuilder) {

        $period = DB::table('student_period')->where('student_id',Auth::id())->first();


        $group = User::select('users.*','teman.name as teman_name','teman.id AS teman_id','groups.id AS group_id','groups.status')
                  ->join('groups','groups.student1_id','users.id')
                  ->leftjoin('users AS teman','groups.student2_id','teman.id')
                  ->where('users.id',Auth::id())->orderBy('status','desc')->get();
        $konfirmasi_mode = false;

        if (count($group) <= 0) { //jika tidak ada di student1
          $group = User::select('users.*','teman.name as teman_name','teman.id AS teman_id','groups.id AS group_id','groups.status')
                    ->join('groups','groups.student2_id','users.id')
                    ->leftjoin('users AS teman','groups.student1_id','teman.id')
                    ->where('users.id',Auth::id())->orderBy('status','desc')->get();
          $konfirmasi_mode = true;

        }

        if (count($group) > 0) {
          if($group[0]->status == 1) {
                Session::flash("flash_notification", [
                    "level"=>"warning",
                    "message"=>"Anda Telah Mempunyai Grup, Silahkan Melanjutkan Dengan Memilih Topik"
                ]);

              return redirect('/dashboard');
          }
          $html['group'] = $group;
          $html['konfirmasi'] = $konfirmasi_mode;
        }
        else { // tahap awal pemilihan teman TA
          if ($request->ajax()) {
              $data = User::select(['users.id','users.name','users.no_induk', 'users.email','groups.id AS group','groups2.id AS group2','groups.status AS group_status','groups2.status AS group2_status'])
                      ->join('role_user','role_user.user_id','users.id')
                      ->join('roles','role_user.role_id','roles.id')
                      ->leftJoin('groups','groups.student1_id','users.id')
                      ->leftJoin('groups AS groups2','groups2.student2_id','users.id')
                      ->join('student_period','student_period.student_id','users.id')
                      ->where('roles.name','student')
                      ->where('users.id','!=',Auth::id())
                      ->where('student_period.period_id',$period->period_id);

              return Datatables::of($data)
                      ->addColumn('action',function($data) {
                          $button = '<button class="btn btn-primary btn-xs" onclick="showModal(\''.$data->id.'\',\'POST\')" >Pilih</button>';
                          if ($data->group_status == 1 || $data->group2_status == 1) {
                            $button = '<button class="btn btn-info btn-xs disabled">Sudah Punya Group</button>';
                          }

                          return $button;
                      })->make(true);
          }

          $html = $htmlBuilder
            ->addColumn(['data' => 'no_induk', 'name'=>'users.no_induk', 'title'=>'No Induk'])
            ->addColumn(['data' => 'name', 'name'=>'users.name', 'title'=>'Nama'])
            ->addColumn(['data' => 'email', 'name'=>'users.email', 'title'=>'Email'])
            ->addColumn(['data' => 'action', 'name'=>'action', 'title'=>'Action', 'orderable'=>false, 'searchable'=>false]);
        }

        return view('students.groups.index')->with(compact('html'));
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
        //status 0 = belum di konfirmasi / default
        //status 1 = active dan di konfirmasi
        //status 3 = di tolak akan di hapus saat users login

        $data = $request->except(['_token']);

        $data['student1_id'] = Auth::id();
        $data['student2_id'] = $data['id'];
        $data['period_id'] = 1;

        if ($data['id'] == '') {
          $data['status'] = 1;
          Group::Create($data);
          Session::flash("flash_notification", [
              "level"=>"warning",
              "message"=>"Group Telah Di buat"
          ]);

        } else {
          //validasi
          $validator = Validator::make($data, $this->validation());
          if ($validator->fails()) {
              Session::flash("flash_notification", [
                  "level"=>"danger",
                  "message"=>$validator->messages()
              ]);

              return back();
          }
          //validasi
          $group1 = Group::where('student1_id',$data['student1_id'])->where('status',1)->first();
          $group2 = Group::where('student2_id',$data['student2_id'])->where('status',1)->first();

          if ($group1) {
              Session::flash("flash_notification", [
                  "level"=>"danger",
                  "message"=>"Anda Telah Mempunyai Grup"
              ]);
          }
          elseif ($group2) {
              Session::flash("flash_notification", [
                  "level"=>"danger",
                  "message"=>"Teman Anda Telah Mempunyai Grup"
              ]);
          }
          else {
              Group::Create($data);
              Session::flash("flash_notification", [
                  "level"=>"warning",
                  "message"=>"Group Telah Di buat, Menunggu Konfirmasi"
              ]);
          }
        }



        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

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
    public function update(Request $request, $id) //untuk konfirmasi pertemanan
    {
        $group = Group::find($id);
        $user = Auth::id();

        if ($group->student1_id == $user || $group->student2_id == $user) { //jika user sah
            $group->update(['status'=>1]);

            //delete groups pending yang lain
            Group::where('student1_id',$user)->where('status',0)->delete();
            Group::where('student2_id',$user)->where('status',0)->delete();

            Session::flash("flash_notification", [
                "level"=>"success",
                "message"=>"Berhasil Di Konfirmasi"
            ]);
        }
        else {
            Session::flash("flash_notification", [
                "level"=>"danger",
                "message"=>"Gagal Di Konfirmasi"
            ]);
        }

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
        $group = Group::where('id', $id)->first();
        $user = Auth::id();

        if ($group->student1_id == $user || $group->student2_id == $user) { //jika user sah

            $relasi = DB::table('group_topic')->where('group_id',$group->id)->first();

            if ($relasi) {
              $topic = Topic::find($relasi->topic_id);
              $topic->update(['is_taken' => 0]);
            }

            $group->delete();
            Session::flash("flash_notification", [
                "level"=>"success",
                "message"=>"Grup Berhasil Di hapus"
            ]);
        }
        else {
            Session::flash("flash_notification", [
                "level"=>"danger",
                "message"=>"Grup Gagal Di hapus"
            ]);
        }

        return 'ok';
    }
}
