<?php

namespace App\Http\Controllers\student;

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
        $group = Group::select('groups.id')
          ->where('student1_id',Auth::id())
          ->orWhere('student2_id',Auth::id())
          ->first();

        if($group==null){
          Session::flash("flash_notification", [
             "level"=>"danger",
             "message"=>"Menu 'Bimbingan Tugas Akhir' hanya dapat diakses setelah membuat group"
            ]);

            return redirect('/home');//->route('users.index');
        }

        $gt = DB::table('group_topic')->where('group_id',$group->id)->where('status',1)->get();

        if(count($gt)==0){
          Session::flash("flash_notification", [
             "level"=>"danger",
             "message"=>"Menu 'Bimbingan Tugas Akhir' hanya dapat diakses setelah ada topik yang disetujui oleh dosen"
            ]);

            return redirect('/home');//->route('users.index');
        }

        $record = BukuBiru::select('buku_biru.id', 'buku_biru.tanggal_bimbingan')
          ->where('group_id', $group->id)->get();

        $result = [];
        for($i=0;$i<count($record);$i++){
          $tmp=[];
          $tmp['id']=$record[$i]->id;

          $dt = DateTime::createFromFormat('!Y-m-d', $record[$i]->tanggal_bimbingan);
          $tmp['year']=$dt->format('Y');
          $tmp['date']=$dt->format('j M');
          $tmp['status']=$record[$i]->status;

          array_push($result, $tmp);
        }

        //return $result;


        return view('students.bimbinganta.index')->with(compact('result'));
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
          if($data['tanggalbimbingan']==null){
            Session::flash("flash_notification", [
                     "level"=>"danger",
                     "message"=>"Isi form dengan benar"
                 ]);

            return redirect('/student/bimbinganTA');//->route('users.index');
          }
          $bimbingan = [];
          $bimbingan['tanggal_bimbingan']=$data['tanggalbimbingan'];
          $bimbingan['kegiatan']=$data['description'];
          $bimbingan['note']="";

          $group = Group::select('groups.id')
                ->where('student1_id',Auth::id())
                ->orWhere('student2_id',Auth::id())
                ->first();

          $bimbingan['group_id']=$group->id;

          if ($request->hasFile('file')) {
            $uploaded_file = $request->file('file');
            $extension = $uploaded_file->getClientOriginalExtension();
            $filename = md5(time()) . '.' . $extension;
            $destinationPath = storage_path(). DIRECTORY_SEPARATOR . 'bimbingan';
            $uploaded_file->move($destinationPath, $filename);
            $bimbingan['attachment'] = $filename;
          }

          //return $bimbingan;

          BukuBiru::create($bimbingan);
        }else{
          if($data['submit']=='destroy'){
            $buku_biru = BukuBiru::where('id', $data['id'])->first();

            if($buku_biru->attachment!=null&&$buku_biru->attachment!=""){
                $file = storage_path().DIRECTORY_SEPARATOR."bimbingan".DIRECTORY_SEPARATOR.$buku_biru->attachment;
                if(file_exists($file)){  unlink($file); }
              }

            $buku_biru->delete();


            Session::flash("flash_notification", [
             "level"=>"success",
             "message"=>"Laporan Bimbingan berhasil dihapus"
            ]);

            return redirect('/student/bimbinganTA');//->route('users.index');
          }else{
            $buku_biru = BukuBiru::where('id', $data['id'])->first();
            $buku_biru->kegiatan = $data['viewBimbingan'];


            if ($request->hasFile('file')) {
              $uploaded_file = $request->file('file');
              $extension = $uploaded_file->getClientOriginalExtension();
              $filename = md5(time()) . '.' . $extension;
              $destinationPath = storage_path(). DIRECTORY_SEPARATOR . 'bimbingan';
              $uploaded_file->move($destinationPath, $filename);

              if($buku_biru->attachment!=null&&$buku_biru->attachment!=""){
                $file = storage_path().DIRECTORY_SEPARATOR."bimbingan".DIRECTORY_SEPARATOR.$buku_biru->attachment;
                if(file_exists($file)){  unlink($file); }
              }
              
              $buku_biru->attachment = $filename;
            }

            $buku_biru->save();

            Session::flash("flash_notification", [
             "level"=>"success",
             "message"=>"Laporan Bimbingan berhasil diupdate"
            ]);

            return redirect('/student/bimbinganTA');//->route('users.index');
          }
          
        }

        Session::flash("flash_notification", [
             "level"=>"success",
             "message"=>"Laporan Bimbingan berhasil Di Tambahkan"
         ]);

         return redirect('/student/bimbinganTA');//->route('users.index');
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

      $file = $bimbingan->attachment;

      $extension = explode('.',$file);
      $extension = $extension[1];

      $fileName = 'bimbingan-'.$bimbingan->tanggal_bimbingan .'-'. Auth::user()->name . '.' . $extension;

      return $this->downloadBimbingan($file,$fileName);
    }

    public function downloadBimbingan($file,$fileName) {
        $file = storage_path().DIRECTORY_SEPARATOR."bimbingan".DIRECTORY_SEPARATOR.$file;

        return Response()->download($file, $fileName);
    }
}
