<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\User;
use App\KpCorp;
use App\Topic;
use App\TopicInterest;
use App\Role;
use Yajra\Datatables\Datatables;
use Yajra\Datatables\Html\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;
use Validator;

class KPCorpsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dosen() {
        $data = User::join('role_user','role_user.user_id','users.id')
            ->join('roles','role_user.role_id','roles.id')
            ->where('roles.name','dosen')
            ->pluck("users.name","users.id");

        return $data;
    }

    public function validation($id) {

        $data = [
            'title' => 'required|unique:topics,title',
            'bobot' => 'required',
            'waktu' => 'required',
            'dana' => 'required'
        ];

        if($id != false ) {
            $data['title'] = $data['title'].','.$id;
        }

        return $data;
    }

    public function index(Request $request, Builder $htmlBuilder)
    {
        if ($request->ajax()) {
            $data = KpCorp::select('kp_corps.id', 'kp_corps.name', 'kp_corps.bidang', 'kp_corps.address', 'kp_corps.mail1', 'kp_corps.mail2', 'kp_corps.phone1', 'kp_corps.phone2', 'kp_corps.site', 'kp_corps.description');

            return Datatables::of($data)
                    ->addColumn('peminat', function($data){
                        return '<a href=/dosen/topics/peminat/'.$data->id.'>'.$data->peminat2.'</a>';
                    })
                    ->addColumn('action',function($data) { 
                        return '<button class="btn btn-primary btn-xs" onclick="rikad.edit(this,\''.$data->id.'\')"><span class="glyphicon glyphicon-pencil"></span></button> <button class="btn btn-danger btn-xs" onclick="rikad.delete(\''.$data->id.'\')"><span class="glyphicon glyphicon-remove"></span></button>';
                    })->make(true);
        }

        $html = $htmlBuilder
          ->addColumn(['data' => 'name', 'name'=>'kp_corps.name', 'title'=>'Perusahaan'])
          ->addColumn(['data' => 'bidang', 'name'=>'kp_corps.bidang', 'title'=>'Bidang'])
          ->addColumn(['data' => 'address', 'name'=>'kp_corps.address', 'title'=>'Alamat'])
          ->addColumn(['data' => 'site', 'name'=>'kp_corps.site', 'title'=>'Situs'])
          ->addColumn(['data' => 'mail1', 'name'=>'kp_corps.mail1', 'title'=>'Email'])
          ->addColumn(['data' => 'phone1', 'name'=>'kp_corps.phone1', 'title'=>'No. Telp'])
          ->addColumn(['data' => 'action', 'name'=>'action', 'title'=>'Action', 'orderable'=>false, 'searchable'=>false]);

        return view('administration.corps.index')->with(compact('html'));
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
        //return $data;
        $corp = KpCorp::select('kp_corps.*')->find($data['id']);
        
        //return json_encode($data);

        // //check if data exists update else create
         if($corp){
             $corp->update($data);
         } else {
             $corp = KpCorp::create($data);
        }

        Session::flash("flash_notification", [
             "level"=>"success",
             "message"=>"Informasi Perusahaan berhasil disimpan"
         ]);

         return redirect('/tu/kpcorps');//->route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        //
        //if ($request->ajax()) {
            $corp = KpCorp::select('kp_corps.*')->find($id);
            return $corp;
        //}
        return $id;
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
        $user = KpCorp::where('id', $id)->first();
        $user->delete();

        Session::flash("flash_notification", [
            "level"=>"danger",
            "message"=>"Data Perusahaan dihapus"
        ]);

        return 'ok';
    }

    public function peminat(Request $request, Builder $htmlBuilder){
        //return $request->route('id');

        if ($request->ajax()) {
            /*$data = Topic::selectRaw('topics.id,topics.title,topics.description,topics.is_taken, topics.dosen1_id, users.name, topics.bobot, topics.waktu, topics.dana,
                (select count(*) from group_topic where group_topic.topic_id = topics.id) as peminat2'
                //DB::raw('')
                )*/

                $data=TopicInterest::selectRaw('title, nim1, student1, nim2, student2, status, id, gtopic_id')
                    ->where('id',$request->route('id'))
                    ->get();

            return Datatables::of($data)
                    ->addColumn('status2',function($data) { 
                        if($data->status==0){return 'Mengajukan';}
                        if($data->status==1){return 'Disetujui';}
                        if($data->status==2){return 'Ditolak';}
                    })
                    ->addColumn('action',function($data) { 
                        if($data->status==0){
                        return '<button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#myModalAccept" onclick="prepare(\''.$data->gtopic_id.'\', \''.$data->id.'\')"><span class="glyphicon glyphicon-pencil"></span> Setujui</button>';
                        /*return '<button class="btn btn-primary btn-xs" onClick="rikad.edit(this,\''.$data->id.'\')"><span class="glyphicon glyphicon-pencil"></span></button>';*/
                    }else{return '';}
                    })->make(true);
        }

        $html = $htmlBuilder
          ->addColumn(['data' => 'title', 'name'=>'topics.title', 'title'=>'Judul'])
          ->addColumn(['data' => 'student1', 'name'=>'student1', 'title'=>'Student 1'])
          ->addColumn(['data' => 'student2', 'name'=>'student2', 'title'=>'Student 2'])
          ->addColumn(['data' => 'status2', 'name'=>'status2', 'title'=>'Status'])
          ->addColumn(['data' => 'action', 'name'=>'action', 'title'=>'Action', 'orderable'=>false, 'searchable'=>false]);

        return view('dosen.topics.interest')->with(compact('html'));
    }

    public function peminatRespond(Request $request){
        //return "huh";
        $data = $request->except(['_token']);
        
        DB::table('group_topic')
            ->where('id', $data['idtopic'])
            ->update(['status' => $data['submitRespond'], 'note' => $data['note']]);

        $currentgroup = DB::table('group_topic')
            ->where('id', $data['idtopic'])
            ->first();

        if($data['submitRespond']==1){$hasil="disetujui";
            DB::table('topics')
            ->where('id', $data['id_topic'])
            ->update(['is_taken' => 1]);

            DB::table('group_topic')
            ->where('group_id', $currentgroup->group_id)
            ->whereNotIn('status', [1])
            ->delete();

            DB::table('group_topic')
            ->where('topic_id', $data['id_topic'])
            ->whereNotIn('status', [1])
            ->delete();
        }
        elseif($data['submitRespond']==2){$hasil="ditolak";}

        Session::flash("flash_notification", [
             "level"=>"success",
             "message"=>"Permintaan topik sudah di ".$hasil
         ]);

         return redirect('/dosen/topics/peminat/'.$data['id_topic']);//->route('users.index');
    }
}
