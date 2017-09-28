<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\Group;
use App\Topic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if (Auth::user()->hasRole('admin')) {
            return view('admin.index');
        }
        else if (Auth::user()->hasRole('koordinator')) {
            return view('koordinator.index');
        }
        else if (Auth::user()->hasRole('dosen')) {
            return view('dosen.index');
        }
        elseif (Auth::user()->hasRole('student')) {
            $teman = null;
            $topic = null;
            $group = Group::where('student1_id',Auth::id())->where('status',1)->first();

            if ($group) {
              $teman = User::find($group->student2_id);
            } else {
              $group = Group::where('student2_id',Auth::id())->where('status',1)->first();
              if($group) $teman = User::find($group->student1_id);
            }

            if ($group) {
              $topic = Topic::select('topics.*','group_topic.group_id','user1.name AS dosen1','user2.name AS dosen2')
                        ->join('group_topic','group_topic.topic_id','topics.id')
                        ->leftJoin('users AS user1','user1.id','topics.dosen1_id')
                        ->leftJoin('users AS user2','user2.id','topics.dosen2_id')
                        ->where('group_topic.group_id',$group->id)
                        ->first();
            }

            return view('students.index',[
              'teman' => $teman,
              'topic' => $topic,
            ]);
        }
        elseif (Auth::user()->hasRole('administration')) {
          return view('administration.index');
        }

        return view('welcome');
    }

    public function changePassword(Request $request)
    {
      $data = $request->except(['_token']);
      if($data['passwordNew']!=$data['passwordNew2']){
        Session::flash("flash_notification", [
                    "level"=>"danger",
                    "message"=>"Password baru tidak cocok"
                ]);

        return redirect('dashboard');
      }

      $user = User::select('users.*')
        ->where('users.id',Auth::id())
        ->first();//

      $passwordIsOk = password_verify( $data['passwordCurrent'], $user['password'] );

      if($passwordIsOk){
        DB::table('users')
            ->where('id', Auth::id())
            ->update(['password' => bcrypt($data['passwordNew'])]); 
        
        Session::flash("flash_notification", [
                    "level"=>"success",
                    "message"=>"Password telah dirubah"
                ]);

        return redirect('dashboard');
      }else{
        Session::flash("flash_notification", [
                    "level"=>"danger",
                    "message"=>"Password salah. Silahkan coba lagi"
                ]);

        return redirect('dashboard');
      }
    }

    public function getUserInfo(){
      $user = User::select('users.*')
        ->where('users.id',Auth::id())
        ->first();

      $filtered['no_induk']=$user['no_induk'];
      $filtered['name']=$user['name'];
      $filtered['email']=$user['email'];

      return $filtered;     
    }

    public function updateUserInfo(Request $request){
      $data = $request->except(['_token']);
      
          DB::table('users')
            ->where('id', Auth::id())
            ->update(['no_induk' => $data['ni'], 'name' => $data['name'], 'email' => $data['email']]); 

              Session::flash("flash_notification", [
                    "level"=>"success",
                    "message"=>"Informasi user berhasil di update"
                ]);

        return back();//redirect('dashboard');
    }
}
