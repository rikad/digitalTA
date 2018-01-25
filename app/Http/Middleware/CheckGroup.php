<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Session;
use Illuminate\Support\Facades\Auth;

class CheckGroup
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $group = User::select('users.*','teman.name as teman_name','teman.id AS teman_id','groups.id AS group_id','groups.status')
                  ->join('groups','groups.student1_id','users.id')
                  ->leftjoin('users AS teman','groups.student2_id','teman.id')
                  ->where('users.id',Auth::id())
                  ->where('status',1)
                  ->first();

        if (!$group) { //jika tidak ada di student1
          $group = User::select('users.*','teman.name as teman_name','teman.id AS teman_id','groups.id AS group_id','groups.status')
                    ->join('groups','groups.student2_id','users.id')
                    ->leftjoin('users AS teman','groups.student1_id','teman.id')
                    ->where('users.id',Auth::id())
                    ->where('status',1)
                    ->first();
        }

        if (!$group) {
            Session::flash("flash_notification", [
                        "level"=>"danger",
                        "message"=>"Anda Belum Mempunyai Kelompok, Silahkan Buat terlebih Dahulu"
                    ]);

            return back();
        }

        return $next($request);
    }
}
