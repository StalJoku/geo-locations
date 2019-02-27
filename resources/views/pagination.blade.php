@foreach( $devicesPerUser as $device )
<div class="row">
	 	<div class="col-md-1">
			<input type="checkbox" class="geo-mark" data-geoid="{{ $device->id }}" name="device_status[{{ $device->id }}]" 
                   value='1' {{ ($device->pivot->active) === 1 ? 'checked' : ''}}>
		</div>
		<div class="col-md-11">
           <span>Device Name: </span> <strong>{{ $device->name }}</strong><br> 
           <span>Device Imei: </span> <strong>{{ $device->imei }}</strong>
		</div>

</div>
<div class="clearfix">&nbsp;</div>
@endforeach	
{!! $devicesPerUser->links() !!}