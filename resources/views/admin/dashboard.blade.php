@extends('layouts.app')

@section('content')
<div class="header-title text-center">
	<div class="container">	
		<h1>
			Dashboard Page
		</h1>
	</div>	
</div>

<section class="devices">
	<div class="container">		
		<div class="row">
			<div class="col-md-10 col-centered">
				<div class="device-filter-holder">			
					<form name="_token" action=""  name="device_filter" method="get" >
						<div class="form-row">					
							<div class="form-group col-md-4">
						      	<label for="inputState">Sort Devices</label>      
							    <select id="filter-form" class="form-control" onchange="filter()">						    		         
							     	<option selected="selected" value="imei">Sort by imei</option>	         
							      	<option value="name">Sort by name</option>	        
							      	<option value="users">Sort by users</option>	        
							   </select>
						   	</div>	
						</div>  
				    </form>			
				</div>				
				<h1>Total Devices {{ $numberDevices }}</h1>		
				
				@for( $i = 0; $i < count($devices); $i++ )				
				<article class="device{{ $i % 3 === 0 ? ' first-in-line' : (($i + 1) % 3 === 0 ? ' last-in-line' : '') }}">				
					<div class="row">
						<div class="col-md-4">
							<p><span>Device Name: </span><strong>{{ $devices[$i]->name }}</strong></p> 
						</div>
						<div class="col-md-4">
							<p><span>Device Imei: </span><strong>{{ $devices[$i]->imei }}</strong></p>
						</div>
						<div class="col-md-4">
							<p><span>Device Used By: </span><strong>{{ $devices[$i]->users()->count() }}</strong></p>
						</div>
					</div>									
				</article>
				@endfor	
				{{ $devices }}				
			</div>
		</div>
	</div>
</section>
@endsection
@section('scripts')
<script>
   	function filter(){
    	var name = document.getElementById("filter-form").value;    	
        window.location.href = "{{ URL::action('FilterController@filter') }}/"+name;        
	}
</script>
@endsection
