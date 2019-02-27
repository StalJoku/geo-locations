<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Device;
use App\User;

class FilterController extends Controller
{
    public function filter($name = 'imei'){

		switch ($name) {
		    case "name":
		        $devices = Device::orderBy('name','desc')->withCount('users')->paginate(6);
		        break;
		    case "imei":
		        $devices = Device::orderBy('imei','desc')->withCount('users')->paginate(6);
		        break;
	        case "users":
		        $devices = Device::withCount('users')->orderByDesc('users_count')->paginate(6);
		        break;	 	   
		    default:
		       $devices = Device::orderBy($name,'desc')->withCount('users')->paginate(6);
		}	  	
        
    	$allDevices = Device::count();   
        
        return view('admin.dashboard', ['devices' => $devices, 'numberDevices' => $allDevices]);
   	}
}
