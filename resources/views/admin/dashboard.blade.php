@extends('admin.layout')
@section('content')
<!--end row-->
<div class="row row-cols-1 row-cols-md-3 row-cols-xl-5">
	<div class="col">
		<div class="card radius-10">
			<div class="card-body">
				<div class="text-center">
					<div class="widgets-icons rounded-circle mx-auto bg-light-primary text-primary mb-3"><i class='bx bxl-facebook-square'></i>
					</div>
					<h4 class="my-1">84K</h4>
					<p class="mb-0 text-secondary">Facebook Users</p>
				</div>
			</div>
		</div>
	</div>
	<div class="col">
		<div class="card radius-10">
			<div class="card-body">
				<div class="text-center">
					<div class="widgets-icons rounded-circle mx-auto bg-light-danger text-danger mb-3"><i class='bx bxl-twitter'></i>
					</div>
					<h4 class="my-1">34M</h4>
					<p class="mb-0 text-secondary">Twitter Followers</p>
				</div>
			</div>
		</div>
	</div>
	<div class="col">
		<div class="card radius-10">
			<div class="card-body">
				<div class="text-center">
					<div class="widgets-icons rounded-circle mx-auto bg-light-info text-info mb-3"><i class='bx bxl-linkedin-square'></i>
					</div>
					<h4 class="my-1">56K</h4>
					<p class="mb-0 text-secondary">Linkedin Followers</p>
				</div>
			</div>
		</div>
	</div>
	<div class="col">
		<div class="card radius-10">
			<div class="card-body">
				<div class="text-center">
					<div class="widgets-icons rounded-circle mx-auto bg-light-success text-success mb-3"><i class='bx bxl-youtube'></i>
					</div>
					<h4 class="my-1">38M</h4>
					<p class="mb-0 text-secondary">YouTube Subscribers</p>
				</div>
			</div>
		</div>
	</div>
	<div class="col">
		<div class="card radius-10">
			<div class="card-body">
				<div class="text-center">
					<div class="widgets-icons rounded-circle mx-auto bg-light-warning text-warning mb-3"><i class='bx bxl-dropbox'></i>
					</div>
					<h4 class="my-1">28K</h4>
					<p class="mb-0 text-secondary">Dropbox Users</p>
				</div>
			</div>
		</div>
	</div>
</div>
<!--end row-->
<div class="row row-cols-1 row-cols-md-2 row-cols-xl-2">
	<div class="col">
	  <div class="card radius-10 border-start border-0 border-3 border-info">
		 <div class="card-body">
			 <div class="d-flex align-items-center">
				 <div>
					 <p class="mb-0 text-secondary">Total Orders</p>
					 <h4 class="my-1 text-info">4805</h4>
					 <p class="mb-0 font-13">+2.5% from last week</p>
				 </div>
				 <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto"><i class='bx bxs-cart'></i>
				 </div>
			 </div>
		 </div>
	  </div>
	</div>
	<div class="col">
	 <div class="card radius-10 border-start border-0 border-3 border-danger">
		<div class="card-body">
			<div class="d-flex align-items-center">
				<div>
					<p class="mb-0 text-secondary">Total Revenue</p>
					<h4 class="my-1 text-danger">$84,245</h4>
					<p class="mb-0 font-13">+5.4% from last week</p>
				</div>
				<div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto"><i class='bx bxs-wallet'></i>
				</div>
			</div>
		</div>
	 </div>
   </div>
   <div class="col">
	 <div class="card radius-10 border-start border-0 border-3 border-success">
		<div class="card-body">
			<div class="d-flex align-items-center">
				<div>
					<p class="mb-0 text-secondary">Bounce Rate</p>
					<h4 class="my-1 text-success">34.6%</h4>
					<p class="mb-0 font-13">-4.5% from last week</p>
				</div>
				<div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i class='bx bxs-bar-chart-alt-2' ></i>
				</div>
			</div>
		</div>
	 </div>
   </div>
   <div class="col">
	 <div class="card radius-10 border-start border-0 border-3 border-warning">
		<div class="card-body">
			<div class="d-flex align-items-center">
				<div>
					<p class="mb-0 text-secondary">Total Customers</p>
					<h4 class="my-1 text-warning">8.4K</h4>
					<p class="mb-0 font-13">+8.4% from last week</p>
				</div>
				<div class="widgets-icons-2 rounded-circle bg-gradient-blooker text-white ms-auto"><i class='bx bxs-group'></i>
				</div>
			</div>
		</div>
	 </div>
   </div> 
 </div><!--end row-->
@endsection