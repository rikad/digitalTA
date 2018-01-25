<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Proposal;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Session;
use Validator;

class ProposalsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    protected $group_id;

    function __construct() {
        $this->middleware(function ($request, $next) {

            $group = User::select('groups.id','group2.id AS id2')
                                ->leftjoin('groups','groups.student1_id','users.id')
                                ->leftjoin('groups AS group2','group2.student2_id','users.id')
                                ->where('users.id',auth::id())
                                ->first();

            $this->group_id = isset($group->id) ? $group->id : $group->id2;

            $gt = DB::table('group_topic')->where('group_id',$this->group_id)->where('status',1)->first();

            if(!$gt){
              Session::flash("flash_notification", [
                 "level"=>"danger",
                 "message"=>"Menu Proposal hanya dapat diakses setelah ada topik yang disetujui oleh dosen"
                ]);

                return back();//->route('users.index');
            }

            return $next($request);
        });
    }

    public function validation() {

        $data = [
            'group_id' => 'required|exists:groups,id',
            'note_student' => 'nullable',
            'note_dosen' => 'nullable',
            'file' => 'required|max:20480|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx',
            'status' => 'integer|required'
        ];

        return $data;
    }

    public function index(Request $request)
    {
        $data = Proposal::where('group_id',$this->group_id)->get();

        return view('students.proposals.index',[ 'data' => $data]);
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
        $data['group_id'] = $this->group_id;
        $data['status'] = 0;
        

        $validator = Validator::make($data, $this->validation());
        if ($validator->fails()) {
            Session::flash("flash_notification", [
                "level"=>"danger",
                "message"=>$validator->messages()
            ]);
            return back();
        }

        if ($request->hasFile('file')) {
            // Mengambil file yang diupload
            $uploaded_file = $request->file('file');

            // mengambil extension file
            $extension = $uploaded_file->getClientOriginalExtension();

            // membuat nama file random berikut extension
            $filename = md5(time()) . '.' . $extension;

            // menyimpan ke folder public/proposals
            $destinationPath = storage_path(). DIRECTORY_SEPARATOR . 'proposals';
            $uploaded_file->move($destinationPath, $filename);

            // mengisi field cover di book dengan filename yang baru dibuat
            $data['file'] = $filename;
        }

        Proposal::create($data);

        Session::flash("flash_notification", [
             "level"=>"success",
             "message"=>"Berhasil Di Tambahkan"
         ]);

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
        $proposal = Proposal::where('group_id',$this->group_id)->find($id);

        $file = $proposal->file;

        $extension = explode('.',$file);
        $extension = $extension[1];

        $fileName = 'proposal-'.$proposal->created_at .'-'. Auth::user()->name . '.' . $extension;

        return $this->downloadProposal($file,$fileName);
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

    public function downloadProposal($file,$fileName) {
        $file = storage_path().DIRECTORY_SEPARATOR."proposals".DIRECTORY_SEPARATOR.$file;

        return Response()->download($file, $fileName);
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $proposal = Proposal::where('group_id',$this->group_id)->find($id);

            if ($proposal->file) {

                $filepath = storage_path().DIRECTORY_SEPARATOR.'proposals'. DIRECTORY_SEPARATOR.$proposal->file;
                File::delete($filepath);
            } 

            $proposal->delete();

            Session::flash("flash_notification", [
                "level"=>"danger",
                "message"=>"Berhasil Di Hapus"
            ]);

        }

        catch (FileNotFoundException $e) {
            Session::flash("flash_notification", [
                "level"=>"danger",
                "message"=>"Opps !, Something Wrong"
            ]);
        }

        return 'ok';
    }
}
