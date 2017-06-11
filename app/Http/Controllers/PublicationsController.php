<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;
use Validator;

use App\Publication;
use App\User;

//get users yang telah di pilij
//cek crud
//bikin crud users

class PublicationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth::id();
        $data = Publication::select('publications.*')
                    ->join('publication_user','publication_user.publication_id','=','publications.id')
                    ->where('publication_user.user_id',$id)->get();

        return View('publications.index', ['data'=>$data]);
    }

    public function selectedUsers($id) {
        $relation = DB::table('publication_user')->where('publication_id',$id)->pluck("user_id","id")->all();
        return $relation;
    }

    public function users() {
        $data = User::where('id','<>',Auth::id())->pluck("name","id")->all();
        return $data;
    }

    public function validation() {

        $data = [
            'title' => 'required',
            'description' => 'required',
            'published' => 'required|date',
            'file' => 'nullable',
        ];

        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
        $publication = Publication::find($data['id']);

        $validator = Validator::make($data, $this->validation());
        if ($validator->fails()) {
            Session::flash("flash_notification", [
                "level"=>"danger",
                "message"=>$validator->messages()
            ]);

            return redirect()->action('PublicationsController@index');
        }

        //check if data exists update else create
        if($publication){
            $publication->update($data);

            //aksi edit ke relasi
            DB::table('publication_user')->where('publication_id',$publication->id)->delete();          
            $relation = [ ['publication_id'=>$publication->id,'user_id'=>Auth::id()] ];
            foreach($data['authors'] as $value) {
                $relation[] = ['publication_id'=>$publication->id,'user_id'=>$value ];
            }
            DB::table('publication_user')->insert($relation);
        } else {
            $publication = Publication::create($data);
            $relation = [ ['publication_id'=>$publication->id,'user_id'=>Auth::id()] ];
            foreach($data->authors as $value) {
                $relation[] = ['publication_id'=>$publication->id,'user_id'=>$value ];
            }
            DB::table('publication_user')->insert($relation);
        }

        Session::flash("flash_notification", [
            "level"=>"success",
            "message"=>"Publication Information Updated"
        ]);

        return redirect()->action('PublicationsController@index');

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
        $publication = Publication::where('id', $id)->where('user_id',Auth::id())->first();
        $relation = DB::table('publication_user')->where('publication_id',$publication->id)->delete();

        Session::flash("flash_notification", [
            "level"=>"danger",
            "message"=>"Publication Information Deleted"
        ]);

        return 'ok';
    }
}
