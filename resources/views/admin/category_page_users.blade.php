@extends('admin.layout')
@section('content')
				<div class="row">
					<div class="col-xl-11 mx-auto">
						<div class="card border-top border-0 border-4 border-success">
							<div class="card-body">
								<div class="border p-4 rounded">
								<div class="card-title d-flex align-items-center">
									<div><i class="bx bxs-buildings me-1 font-22"></i>
									</div>
									<h5 class="mb-0"> تنسيب الصفحات للمستخدمين </h5>
								</div>
								<hr />
								<form class="g-3 needs-validation" role="form" name="form" id="form" enctype="multipart/form-data" method="post" novalidate>
                                            @csrf
											@method('')
											<div class="mb-3 row">
												<div class="col-md-4">
													<label for="college_id" class="form-label">الكليات</label>
													<select class="form-select" required name="college_id" id="college_id">
														<option value="">اختر الكلية</option>
														@if(isset($colleges))
															@foreach($colleges as $college)
															{!! "<option value='$college->id' " !!}
																@if(isset($edit_data[0]->staff->department->college->id) && $edit_data[0]->staff->department->college->id == $college->id){{ 'selected="selected"' }} @endif
															{!! ">".$college->name."</option>" !!} 
															@endforeach
														@endif
													</select>
													<div class="invalid-feedback">اختر الكلية</div>
												</div>
												<div class="col-md-4">
													<label for="department_id" class="form-label">الاقسام</label>
													<select class="form-select" required name="department_id" id="department_id">
														<option value="">اختر القسم</option>
														@if(isset($edit_data[0]->staff->department->id)){!! "<option value='".$edit_data[0]->staff->department->id."' selected>".$edit_data[0]->staff->department->name."</option>" !!} @endif
													</select>
													<div class="invalid-feedback">اختر القسم</div>
												</div>
												<div class="col-md-4">
													<label for="user_id" class="form-label">اعضاء القسم</label>
													<select class="form-select" required name="user_id" id="user_id">
														<option value="">اختر القسم</option>
														@if(isset($edit_data[0]->staff->department->id)){!! "<option value='".$edit_data[0]->staff->department->id."' selected>".$edit_data[0]->staff->department->name."</option>" !!} @endif
													</select>
													<div class="invalid-feedback">اختر القسم</div>
												</div>
											</div>
                                            <div class="mb-3 row">
												<div class="col-md-6">
													<label for="category_id" class="form-label">فئات لوحة التحكم</label>
													<select class="form-select" required name="category_id" id="category_id">
														<option value="">اختر الفئة</option>
														@if(isset($categories))
															@foreach($categories as $category)
															{!! "<option value='$category->id' " !!}
																@if(isset($edit_data->category->id) && $edit_data->category->id == $category->id){{ 'selected="selected"' }} @endif
															{!! ">".$category->title."</option>" !!} 
															@endforeach
														@endif
													</select>
													<div class="invalid-feedback">اختر الفئة</div>
												</div>
												<div class="col-md-6">
													<label for="category_page_id" class="form-label">صفحات لوحة التحكم</label>
													<select class="form-select" required name="category_page_id" id="category_page_id">
														<option value="">اختر عنوان الصفحة</option>
													</select>
													<div class="invalid-feedback">اختر عنوان الصفحة</div>
												</div>
												<input class="form-control" name="id" id="id" type="hidden" value="@if(isset($edit_data->id)) {{$edit_data->id}} @endif">
											</div>
                                            <div class="mb-3">
                                                <div class="d-grid">
                                                    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-2">
														<div class="col-12">
															<input type="submit" id="submit" value="موافق" class="btn btn-sm btn-success px-5" />
														</div>
													</div>
                                                </div>
                                            </div>
                                        </form>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--end row-->
@endsection
@section('table')
<div class="row">
	<div class="col-xl-11 mx-auto">
<div class="card radius-10">
	<div class="card-body">
	   <div class="d-flex align-items-center">
		   <div class="col-12">
			   <h6 class="mb-3">تنسيب الصفحات للمستخدمين</h6>
			   <hr>
		   </div>
	   </div>
	<div class="table-responsive">
		<table id="tabl" class="table table-striped table-bordered responsive">
			<thead>
				<tr>
					<th>اسم المستخدم</th>
					<th>الفئة</th>
					<th>عناوين الصفحات الخاصه به</th>
				</tr>
			</thead>
			<tbody>
				@php $url = URL::to('/').'/'.Request::segment(1).'/'.Request::segment(2) @endphp
				@if(isset($data))
					@foreach( $data as $key => $value)
						@foreach ($value as $category => $arr )
							<tr>
							<td>{{ $key }}</td>
							<td>{{ $category }}</td>
							@php $pages = ''; @endphp
							@foreach ($arr as $page)
								@php
									$pages .= '<div class="chip m-1 bg-success">'.$page.'<span class="closebtn" onclick="#">×</span></div>';
								@endphp
							@endforeach
							<td>{!! $pages !!}</td>
							</tr>
						@endforeach
					@endforeach
				@endif
			</tbody>
		</table>
	 </div>
	</div>
</div>
	</div>
</div>
@endsection
<script src="{{ URL::asset('assets/js/jquery.min.js') }}"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#college_id").on("change", function(){
			$.post("{{ URL :: to('admin/department/list') }}",{ "_token": "{{ csrf_token() }}",id:$(this).val(),rand:Math.random() } ,function(data){
				$("#department_id").prop("disabled", false).html(data);
			});
		});
		$("#department_id").on("change", function(){
			$.post("{{ URL :: to('admin/user/list') }}",{ "_token": "{{ csrf_token() }}",id:$(this).val(),rand:Math.random() } ,function(data){
				$("#user_id").prop("disabled", false).html(data);
			});
		});
		$("#category_id").on("change", function(){
			$.post("{{ URL :: to('admin/category_page/list') }}",{ "_token": "{{ csrf_token() }}",id:$(this).val(),rand:Math.random() } ,function(data){
				$("#category_page_id").prop("disabled", false).html(data);
			});
		});
	});
</script>