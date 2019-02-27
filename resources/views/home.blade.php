@extends('layouts.app')

@section('content')
<div id="map-holder"> 
    <div id="map"></div>   
    <div class="main-panel">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <div class="devices-list">
                            <h1>All Devices {{ $totalDevices }}</h1>
                            <br>
                            <div class="devices-holder">                         
                                @include('pagination')      
                            </div>   
                            
                            <!-- Trigger the modal with a button -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                Add device
                            </button> 
                        </div>          
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 

<!-- The Modal -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Add/Update your device</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            @if(count($errors) > 0)
                <div class="info-box fail">
                    <ul>
                    @foreach( $errors->all() as $error )
                        <li>{{ $error }}</li>               
                    @endforeach
                    </ul>
                </div>
            @endif
            <form id="add-device-form" method="POST" action="{{ route('update.add.device') }}">                
                <div class="form-group">
                    <label for="device-name">Device Name</label>
                    <input type="text" class="form-control" name="deviceName" id="device-name" placeholder="Sony Phone" value="">
                </div>
                <div class="form-group">
                    <label for="device-imei">Device Imei</label>
                    <input type="text" class="form-control" name="deviceImei" id="device-imei" placeholder="e.g. 990000862471854" value="">
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                      <label for="latitude">Latitude</label>
                      <input type="text" class="form-control" name="deviceLatitude" id="latitude" placeholder="Latitude" value="">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="longitude">Longitude</label>
                        <input type="text" class="form-control" name="deviceLongitude" id="longitude" placeholder="Longitude" value="">
                    </div>
                </div>                
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="device-set" value="">
                        <label class="form-check-label device-state" for="device-set">
                            Activate
                        </label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Add Device</button>
                
            </form>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAxvd0XSChs81t4Z_wmk8QkWB_SbuiAvi0"></script> 
   <!--  <script src="{{ asset('js/production.min.js') }}"></script>  -->
     <script src="{{ asset('js/mapajax.js') }}"></script>  
@endsection
