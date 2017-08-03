<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Profile;

class VitaeController extends Controller
{
	public function index()
	{
        $id = Auth::id();
        $data = Profile::where('user_id', $id)->first();

        return view('vitae.index')->with(compact('data'));

	}
}
