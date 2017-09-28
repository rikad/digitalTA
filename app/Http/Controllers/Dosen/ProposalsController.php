<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\User;
use App\Topic;
use App\Proposal;
use App\Group;
use App\Role;
use Yajra\Datatables\Datatables;
use Yajra\Datatables\Html\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

use DateTime;
use Session;
use Validator;

class ProposalsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

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
                    ->addColumn('status',function($data) {
                        $proposal = Proposal::where('group_id', $data->group_id)->where('status',0)->get();
                        if(count($proposal) > 0) {
                            return '<div class="label label-warning">Menunggu Review</div>';
                        } else {
                            return '<div class="label label-success">Sudah di Review</div>';
                        }
                    })
                    ->addColumn('action',function($data) {
                        return '<a class="btn btn-primary btn-xs" href="/dosen/proposals/'.$data->group_id.'"><span class="glyphicon glyphicon-eye-open"></span> Lihat Proposal</button>';
                    })->make(true);
        }

        $html = $htmlBuilder
          ->addColumn(['data' => 'title', 'name'=>'topics.title', 'title'=>'Judul'])
          ->addColumn(['data' => 'name', 'name'=>'users.name', 'title'=>'Nama 1'])
          ->addColumn(['data' => 'name2', 'name'=>'users2.name', 'title'=>'Nama 2'])
          ->addColumn(['data' => 'status', 'name'=>'status', 'title'=>'Status', 'searchable'=>false])
          ->addColumn(['data' => 'action', 'name'=>'action', 'title'=>'Action', 'orderable'=>false, 'searchable'=>false]);

        return view('dosen.proposals.index')->with(compact('html'));
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
        $data = $request->except(['_token','proposal_id']);
        $id = $request->input('proposal_id');

        $proposal = Proposal::find($id)->update($data);

        Session::flash("flash_notification", [
         "level"=>"success",
         "message"=>"Berhasil Di perbaharui"
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
        $data = Proposal::where('group_id', $id)->get();

        return view('dosen.proposals.proposal')->with(compact('data'));
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
    }

    public function download($id) {
        $proposal = Proposal::find($id);
        $group = Group::select('users.name','users2.name AS name2')
                ->join('users','users.id','groups.student1_id')
                ->join('users as users2','users2.id','groups.student2_id')
                ->where('groups.id',$proposal->group_id)
                ->first();

        $group = $group->name.'-'.$group->name2;


        $file = $proposal->file;

        $extension = explode('.',$file);
        $extension = $extension[1];

        $fileName = 'proposal-'.$proposal->created_at .'-'. $group . '.' . $extension;

        return $this->downloadProposal($file,$fileName);

    }

    public function downloadProposal($file,$fileName) {
        $file = storage_path().DIRECTORY_SEPARATOR."proposals".DIRECTORY_SEPARATOR.$file;

        return Response()->download($file, $fileName);
    }

}
