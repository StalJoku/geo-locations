<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Device;
use App\User;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getLogout(){

		Auth::logout();
		return redirect()->route('home');
	}

    public function admin()
    {
        $devices = Device::orderBy('imei','desc')->withCount('users')->paginate(6);
        
        $allDevices = Device::count();   
        
        return view('admin.dashboard', ['devices' => $devices, 'numberDevices' => $allDevices]);
    }
}
