<?php

namespace App\Http\Controllers\Koordinator;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Group;
use App\Period;
use Yajra\Datatables\Datatables;
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

    public function index(Request $request)
    {

        $period_id = $request->input('id');
        if (!$period_id) {
            $last_period = Period::orderBy('id','desc')->first();
            $period_id = $last_period->id;
        }


        if ($request->ajax()) {
            $data = Group::select('groups.*','users.username as s1_username','users.name as s1_name','users2.username as s2_username','users2.name as s2_name', 'topics.title', 'topics.is_taken', 'group_topic.topic_id as topicid')
                ->leftJoin('group_topic','group_topic.group_id','groups.id')
                ->leftJoin('topics','topics.id','group_topic.topic_id')
                ->join('users','users.id','groups.student1_id')
                ->join('student_period','users.id','student_period.student_id')
                ->leftJoin('users as users2','users2.id','groups.student2_id')
                ->where('student_period.period_id', $period_id);

            return Datatables::of($data)->make(true);
        }

        $period = Period::orderBy('id')->get();
	    $last_period = $period[count($period) - 1]->id;

        return view('koordinator.groups.index')->with(compact('period'))->with(compact('last_period'));
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,Request $request, Builder $htmlBuilder)
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
        $user = Group::where('id', $id)->first();
        $user->delete();

        Session::flash("flash_notification", [
            "level"=>"danger",
            "message"=>"Group Deleted"
        ]);

        return 'ok';
    }
}
