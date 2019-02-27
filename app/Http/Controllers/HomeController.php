<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Device;
use App\User;
use Validator;

use Geocoder;

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
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function getDevices(Request $request){

        $user_id = $request->user()->id;
        $devices = User::find($user_id)->devices;
        $totalDevices = count($devices);
        $pagedDevices = User::find($user_id)->devices()->orderBy('imei' , 'desc')->paginate(6);               

        if($request->ajax()){
            $activeDevices = User::find($user_id)->devices()->wherePivot('active', '=', 1)->get();

            $mapObj = array();        

            foreach ($activeDevices as $device) {
                $findAddress = Geocoder::getAddressForCoordinates($device['attributes']['latitude'], $device['attributes']['longitude']);
                          
                $mapObj[] = array( 
                    'coords' => 
                        array( 
                            'lat' => $device['attributes']['latitude'], 
                            'lng' => $device['attributes']['longitude'] 
                        ), 
                    'title' => $device['attributes']['name'], 
                    'address' => $findAddress['formatted_address'],
                    'imei' => $device['attributes']['imei'],
                    'id' => $device['attributes']['id']
                ); 
               
            }
            return response()->json(['mapObj' => $mapObj]);
        }
       
        return view('home', ['devicesPerUser' => $pagedDevices, 'totalDevices' => $totalDevices]);
    }

    public function getPages(Request $request){

        $user_id = $request->user()->id;        
        $pagedDevices = User::find($user_id)->devices()->orderBy('imei' , 'desc')->paginate(6); 
        if($request->ajax()){  
            //return response()->json(['pagedDevices' => $pagedDevices]);
            return response()->json([
                'html' => view('pagination', ['devicesPerUser' => $pagedDevices])->render()
            ]);
                 
        }
        
        return view('pagination', ['devicesPerUser' => $pagedDevices]);               
          
    }

    public function updateDeviceStatus(Request $request)
    {

        if($request->ajax()){
            $geoId = $request['geoId'];
            
            $status = $request['state'];

            $user_id = $request->user()->id;
            
            $newDevice = Device::find($geoId);
            
            $newAddress = Geocoder::getAddressForCoordinates($newDevice['latitude'], $newDevice['longitude']);
            

            $updateStatus = User::find($user_id)->devices()->updateExistingPivot($geoId, ['active' => $status]);        
            
            return response()->json([
                'msg'=>'Updated Successfully', 
                'success'=>true, 
                'newDevice' => $newDevice, 'address' => $newAddress
            ]);
            
               
        }     
           
    }

    public function addUpdateDevice(Request $request){
        
        $validator = Validator::make($request->all(), [
           'deviceName' => 'required',           
           'deviceImei' => 'required',           
           'deviceLatitude' => 'required',           
           'deviceLongitude' => 'required'           
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->getMessages()]);
        }
        
        $deviceName = $request['deviceName'];
        $deviceImei = $request['deviceImei'];
        $deviceLatitude = $request['deviceLatitude'];
        $deviceLongitude = $request['deviceLongitude'];
        $status = $request['state'];

        $user_id = $request->user()->id;
        //$device = User::find($user_id)->devices()->where('imei', '=', $request['device_imei'])->first();
        $device = User::find($user_id)->devices->where('imei', '=', $request['deviceImei'])->first();
        if(!$device){            
            $device = new Device();
            $device->name = $deviceName;
            $device->imei = $deviceImei;
            $device->longitude = $deviceLatitude;
            $device->latitude = $deviceLongitude;
            $device->save();
            $device->users()->attach($user_id);            
        }
        
        $device->name = $deviceName;
        $device->longitude = $deviceLatitude;
        $device->latitude = $deviceLongitude;
        $device->update();
        
        return response()->json(['msg' => 'Updated Successfully', 'newDevice' => $device]);
    }
}
