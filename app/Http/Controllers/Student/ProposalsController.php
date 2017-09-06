<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Proposal;
use App\User;
use Illuminate\Support\Facades\Auth;

use Session;
use Validator;

class ProposalsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function validation() {

        $data = [
            'group_id' => 'required|exists:groups,id',
            'tanggal' => 'required|date',
            'note_student' => 'nullable',
            'note_dosen' => 'nullable',
            'file' => 'string|required',
            'status' => 'integer|required'
        ];

        return $data;
    }

    public function index(Request $request)
    {
        $group = User::select('groups.id','group2.id AS id2')
                            ->leftjoin('groups','groups.student1_id','users.id')
                            ->leftjoin('groups AS group2','group2.student2_id','users.id')
                            ->where('users.id',auth::id())
                            ->first();
        $group_id = isset($group->id) ? $group->id : $group->id2;

        $data = Proposal::where('group_id',$group_id)->get();

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

        $data = $request->except(['_token','file']);
        
        $validator = Validator::make($data, $this->validation());

        if ($validator->fails()) {
            Session::flash("flash_notification", [
                "level"=>"danger",
                "message"=>$validator->messages()
            ]);
            return back();
        }
             

        $topic = Topic::create($data);
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Proposal::find($id)->delete();

        Session::flash("flash_notification", [
            "level"=>"danger",
            "message"=>"Berhasil Di Hapus"
        ]);

        return back();
    }
}
