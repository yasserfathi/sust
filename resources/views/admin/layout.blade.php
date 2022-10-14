<!doctype html>
<html lang="en" dir="rtl">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf_token" content="{{ csrf_token() }}" />
	<!--favicon-->
	<link rel="icon" href="{{ URL::asset('assets/images/favicon-32x32.png') }}" type="image/png" />
	<!--plugins-->
	<link href="{{ URL::asset('assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
	<link href="{{ URL::asset('assets/plugins/input-tags/css/tagsinput.css') }}" rel="stylesheet" />
	<link href="{{ URL::asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
	<link href="{{ URL::asset('assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
	<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
=	<!-- loader-->
	<!-- Bootstrap CSS -->
	<link href="{{ URL::asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ URL::asset('assets/css/bootstrap-extended.css') }}" rel="stylesheet">
	<link href="{{ URL::asset('assets/css/app.css') }}" rel="stylesheet">
	<link href="{{ URL::asset('assets/css/icons.css') }}" rel="stylesheet">
	<script src="{{ URL::asset('assets/js/jquery.min.js') }}"></script>
	<title>لوحة تحكم موقع جامعة السودان للعلوم والتكنولوجيا</title>
</head>

<body>
	<!--wrapper-->
	<div class="wrapper">
		@include('admin.sidebar')
		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
				@yield('content')
				@yield('table')
			</div>
			<div>
			<div class="error-container">
			   <div class="alert alert-success bg-success alert-dismissible fade show pe-2 d-none" role="alert">
				<span class="float-sm-right txt"></span>
				<div class="spinner-border spinner-border-sm mx-2" role="status"> <span class="visually-hidden">الرجاء الانتظار</span></div>
			 </div>
		   </div>
		</div>
		<!-- Modal -->
		<div class="modal fade" id="DeleteModal" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title text-black">تأكيد الحذف</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body text-black">
						<p>تأكيد عملية الحذف</p>
					</div>
					<div class="modal-footer flex-row-reverse">
						<form id="modal-form" action="" method="POST">
							@method('DELETE')
							@csrf
							<button type="submit" class="btn btn-sm btn-danger btn-ok">موافق</button>
							<button type="button" class="btn btn-sm btn-dark" data-bs-dismiss="modal">الغاء</button>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="LogoutModal" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title text-black">تسجيل الخروج</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body text-black">
						<p>تسجيل الخروج</p>
					</div>
					<div class="modal-footer flex-row-reverse">
						<form id="logout-form" action="{{ URL :: to('admin/logout')}}" method="POST">
							@method('POST')
							@csrf
							<button type="submit" class="btn btn-sm btn-danger btn-ok">موافق</button>
							<button type="button" class="btn btn-sm btn-dark" data-bs-dismiss="modal">الغاء</button>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="enlargeImageModal" tabindex="-1" role="dialog" aria-labelledby="enlargeImageModal" aria-hidden="true">
			<div class="modal-dialog modal-md" role="document">
			  <div class="modal-content">
				<div class="modal-body">
				  <img src="" class="enlargeImageModalSource" style="width: 100%;height:500px">
				</div>
			  </div>
			</div>
		</div>
		<!--end Modal-->
		</div>
		
		<!--end page wrapper -->
		<!--start overlay-->
		<div class="overlay toggle-icon"></div>
		<!--end overlay-->
		<!--Start Back To Top Button-->
		  <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
		<!--End Back To Top Button-->
		<footer class="page-footer">
			<p class="mb-0">جميع الحقوق محفوظة لجامعة السودان للعلوم والتكنولوجيا © {{ now()->year }}</p>
		</footer>
	</div>
	<!--end wrapper-->
	<!-- Bootstrap JS -->
	<script src="{{ URL::asset('assets/js/bootstrap.bundle.min.js') }}"></script>
	<!--plugins-->
	<script src="{{ URL::asset('assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
	<script src="{{ URL::asset('assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
	<script src="{{ URL::asset('assets/plugins/input-tags/js/tagsinput.js') }}"></script>
	<script src="{{ URL::asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
	<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
=	<script src="{{ URL::asset('assets/plugins/datetimepicker/js/picker.js') }}"></script>
	<script src="{{ URL::asset('assets/plugins/datetimepicker/js/picker.date.js') }}"></script>
	<script src="{{ URL::asset('assets/plugins/datetimepicker/js/translations/ar.js') }}"></script>
	<link href="{{ URL::asset('assets/plugins/datetimepicker/css/classic.css') }}" rel="stylesheet" />
	<link href="{{ URL::asset('assets/plugins/datetimepicker/css/classic.date.css') }}" rel="stylesheet" />
	<!--app JS-->
	<script src="{{ URL::asset('assets/js/app.js') }}"></script>
	<script type="text/javascript">
		$(document).ready(function(){
		$('.datepicker').pickadate({selectMonths: true, selectYears: true,format: 'yyyy-mm-dd',formatSubmit: 'yyyy-mm-dd'});
		var table = $('#tabl').DataTable({<?php if(Request::segment(2) == 'category_page_user') echo '"scrollX": true,'; ?> "language": { "url": "{{ URL::asset('assets/plugins/datatable/Arabic.json') }}" },"lengthMenu": [ 5,10, 25, 50, 100 ]});
		table.on( 'draw', function () {
			$('#tabl').css('width', '100%');
		});
		   $('#DeleteModal,#LogoutModal').on('show.bs.modal', function(e) {
			   $(this).find('form').attr('action',  $(e.relatedTarget).data('href'));
		   });
		   $(".magic").click(function () {
			   $('#page_id').val($(this).attr('id'));
			   $('#page_title').val($(this).attr('title'));
			   $('#page_keywords').val($(this).attr('data-href'));
			   $('#page_desc').val($(this).attr('alt'));
			   $('#tabl_name').val($(this).attr('rel'));
			   $('#meta').modal('toggle');
		   });
		   ///////////////////////////////////////////////////
			$('.enbl').on('click', function() {
			  id = $(this).attr('id');
			  if($(this).is(':checked') == true)
			   {
				   $("#enblval").val('1');
				   $("select").prop( "disabled", false);
			   }
			   else
			   {
				   $("#enblval").val('0');
				   $("select").prop( "disabled", true);
			   }
		});
		///////////////////////////////////////////////////
			$('.enbluser').on('click', function() {
			  id = $(this).attr('id');
			  if($(this).is(':checked') == true)
			   {
				   $("#enblval").val('1');
				   $("select").prop( "disabled", true);
			   }
			   else
			   {
				   $("#enblval").val('0');
				   $("select").prop( "disabled", false);
			   }
		   });
		   ///////////////////////////////////////////////////
		   $('img').on('click', function() {
			   $('.enlargeImageModalSource').attr('src', $(this).attr('src'));
			   $('#caption').text($(this).attr('id'));
			   $('#enlargeImageModal').modal('show');
		   });
		   ///////////////////////////////////////////////////
		   <?php
			   if(Request::segment(2) != 'welcome')
			   {
		   ?>
		   $("#form").submit(function(e) {
			e.preventDefault();
                var forms = document.querySelectorAll('.needs-validation');
                var form = $(this);
                if (form[0].checkValidity() === false) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                else {
					var txt = '';
			   		<?php $url = $base_url = URL::to('/').'/'.Request::segment(1).'/'.Request::segment(2);
				   		if(Request::segment(2) == 'page' || Request::segment(2) == 'staff') { $url = $url.'/'.Request::segment(3); }
				   		if((Request::segment(2) == 'page' || Request::segment(2) == 'staffpage') && Request::segment(3) != '') {
							$url = $url.'/'.Request::segment(3);
							if(Request::segment(5) == '') { $url = $url.'/store'; } else { $url = $url.'/update'; }
						}
				   		if(Request::segment(2) != 'page' && Request::segment(2) != 'staff' && Request::segment(2) != 'Password' && Request::segment(3) == '')
						{ $url = $url; }  
						else if(Request::segment(2) != 'page' && Request::segment(2) != 'staff' && Request::segment(3) != ''){ $url = $url.'/'.Request::segment(3);
					} ?>
				//    $("#submit").addClass('disabled');
				   $(".txt").text('الرجاء الانتظار');
				   $('.alert').removeClass("alert-danger bg-danger d-none").addClass('alert-success bg-success');
				   var formData = new FormData($("#form").get(0));
				   var url =$(location).attr('href').split("/");
				   if(url[5] != undefined || (url[5] != 'staff' && url[6] != undefined)) { formData.append('_method', 'PUT'); }
				   $.ajax({
				   url:"{{ $url }}",
				   type:"post",
				   data: formData,
				   processData:false,
				   contentType:false,
				   cache:false,
				   async:false,
				   success: function(data){
					   setTimeout(function(){
						   if(data == 'success'){txt ='تمت العملية بنجاح ...';} else if(data == 'error'){txt ='البيانات موجودة بالفعل !!!';}
							   $(".txt").text(txt);
							   if(data == 'error'){
									$('.alert').removeClass('alert-success bg-success').addClass('alert-danger bg-danger');
									setTimeout(function() { $("#submit").removeClass('disabled'); $('.alert').addClass("d-none"); }, 2000);
							   }
							   else
							   if(data == 'success')
							   {
								   $(location).attr('href',"{{ URL :: to( $base_url)}}");
							   }
					   }, 1000);
				   }
			   });
                }
                form.addClass('was-validated');
		});
			   <?php } ?>
		});
	 </script>
</body>

</html>