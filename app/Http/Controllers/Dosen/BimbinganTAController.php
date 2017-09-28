<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\User;
use App\Topic;
use App\BukuBiru;
use App\Group;
use App\Role;
use Yajra\Datatables\Datatables;
use Yajra\Datatables\Html\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

use DateTime;
use Session;
use Validator;

class BimbinganTAController extends Controller
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
            $data = Topic::select('topics.id', 'topics.title', 'groups.id as group_id', 'users.name', 'users2.name as name2')
            ->join('group_topic', 'group_topic.topic_id', 'topics.id')
            ->join('groups', 'group_topic.group_id', 'groups.id')
            ->join('users','users.id','groups.student1_id')
            ->join('users as users2','users2.id','groups.student2_id')
            ->where(function($query){$query->where('dosen1_id', Auth::id())->orWhere('dosen2_id', Auth::id());})
            ->where('is_taken', 1);
            
            return Datatables::of($data)
                    ->addColumn('sum',function($data) { 
                        $bimbingan = BukuBiru::select('buku_biru.*')->where('group_id', $data->group_id)->get();
                        return count($bimbingan);                        
                    })
                    ->addColumn('action',function($data) { 
                        return '<a class="btn btn-primary btn-xs" href="/dosen/bimbinganTA/regu/'.$data->group_id.'"><span class="glyphicon glyphicon-eye-open"></span> Lihat Bimbingan</button>';
                    })->make(true);
        }

        $html = $htmlBuilder
          ->addColumn(['data' => 'title', 'name'=>'topics.title', 'title'=>'Judul'])
          ->addColumn(['data' => 'name', 'name'=>'users.name', 'title'=>'Nama 1'])
          ->addColumn(['data' => 'name2', 'name'=>'users2.name', 'title'=>'Nama 2'])
          ->addColumn(['data' => 'sum', 'name'=>'sum', 'title'=>'Jumlah Bimbingan', 'searchable'=>false])
          ->addColumn(['data' => 'action', 'name'=>'action', 'title'=>'Action', 'orderable'=>false, 'searchable'=>false]);

        return view('dosen.bimbinganta.index')->with(compact('html'));

        //$topic = 
            //return $topic;
    }

    public function regu(Request $request, Builder $htmlBuilder){
        $group_id = $request->route('id');

        $info = Topic::select('topics.id', 'topics.title', 'groups.id as group_id', 'users.name', 'users2.name as name2')
            ->join('group_topic', 'group_topic.topic_id', 'topics.id')
            ->join('groups', 'group_topic.group_id', 'groups.id')
            ->join('users','users.id','groups.student1_id')
            ->join('users as users2','users2.id','groups.student2_id')
            ->where('group_id', $group_id)
            ->where('is_taken', 1)->first();


        if($group_id==null){
          Session::flash("flash_notification", [
             "level"=>"danger",
             "message"=>"Link tidak dapat diakses"
            ]);

            return redirect('/home');//->route('users.index');
        }

        $record = BukuBiru::select('buku_biru.id', 'buku_biru.tanggal_bimbingan')
          ->where('group_id', $group_id)->get();

        $result = [];
        for($i=0;$i<count($record);$i++){
          $tmp=[];
          $tmp['id']=$record[$i]->id;

          $dt = DateTime::createFromFormat('!Y-m-d', $record[$i]->tanggal_bimbingan);
          $tmp['year']=$dt->format('Y');
          $tmp['date']=$dt->format('j M');

          array_push($result, $tmp);
        }

        //return $result;


        return view('dosen.bimbinganta.regu')->with(compact('result'))->with(compact('info'));
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

        if(!isset($data['id'])){
          
        }else{
          
            $buku_biru = BukuBiru::where('id', $data['id'])->first();
            $buku_biru->note = $data['viewCatatan'];
            $buku_biru->save();

            Session::flash("flash_notification", [
             "level"=>"success",
             "message"=>"Laporan Bimbingan berhasil diupdate"
            ]);

            return redirect('/dosen/bimbinganTA/regu/'.$buku_biru->group_id);//->route('users.index');
        }

        Session::flash("flash_notification", [
             "level"=>"success",
             "message"=>"Laporan Bimbingan berhasil Di Tambahkan"
         ]);

         return redirect('/dosen/bimbinganTA/regu/'.$buku_biru->group_id);//->route('users.index');
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
        $result = BukuBiru::select('buku_biru.*')->where('id', $id)->first();
        return $result;
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
        $group = Group::select('groups.id')
                ->leftjoin('users','users.id','groups.student1_id')
                ->leftjoin('users as users2','users2.id','groups.student2_id')
                ->where('users.id',Auth::id())
                ->orWhere('users2.id',Auth::id())
                ->first();

        $data['group_id'] = $group->id;
        $data['topic_id'] = $id;
        $data['status'] = 0;

        DB::table('group_topic')->insert($data);

        Session::flash("flash_notification", [
            "level"=>"success",
            "message"=>"Topik Berhasil Di Ajukan"
        ]);

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
        DB::table('group_topic')->where('id',$id)->delete();

        Session::flash("flash_notification", [
            "level"=>"danger",
            "message"=>"Berhasil Di Batalkan"
        ]);

        return 'ok';
    }

    public function download(Request $request){
      $data = $request->except(['_token']);
      
      $bimbingan = BukuBiru::where('id',$data['id'])->first();

      $topic = Topic::select('topics.title')->join('group_topic', 'group_topic.topic_id', 'topics.id')
      ->where('group_topic.group_id', $bimbingan->group_id)->first();

      $file = $bimbingan->attachment;

      $extension = explode('.',$file);
      $extension = $extension[1];

      $fileName = 'bimbingan-'.$topic->title.'-'.$bimbingan->tanggal_bimbingan .'.' . $extension;

      return $this->downloadBimbingan($file,$fileName);
    }

    public function downloadBimbingan($file,$fileName) {
        $file = storage_path().DIRECTORY_SEPARATOR."bimbingan".DIRECTORY_SEPARATOR.$file;

        return Response()->download($file, $fileName);
    }
}
