@extends('admin.layout')
@section('content')
				<div class="row">
					<div class="col-12 col-lg-3 mx-10">
						<div class="card border-top border-0 border-4 border-success">
							<div class="card-body">
								<div class="card-title d-flex align-items-center">
									<div><i class="bx bxs-buildings me-1 font-22"></i>
									</div>
									<h5 class="mb-0"> أعضاء هيئة التدريس </h5>
								</div>
								<div class="fm-menu">
									<div class="list-group list-group-flush"> <a href="javascript:;" class="list-group-item py-1"><i class="bx bx-folder me-2"></i><span>الأوراق العلمية</span></a>
										<a href="javascript:;" class="list-group-item py-1"><i class="bx bx-trash-alt me-2"></i><span>المناصب الادارية</span></a>
										<a href="javascript:;" class="list-group-item py-1"><i class="bx bx-trash-alt me-2"></i><span>اللجان والجمعيات</span></a>
										<a href="javascript:;" class="list-group-item py-1"><i class="bx bx-trash-alt me-2"></i><span>الدورات التدريبية</span></a>
										<a href="javascript:;" class="list-group-item py-1"><i class="bx bx-trash-alt me-2"></i><span>الجوائز و الشهادات التقديرية</span></a>
										<a href="javascript:;" class="list-group-item py-1"><i class="bx bx-devices me-2"></i><span>المشاريع البحثية الجارية</span></a>
										<a href="javascript:;" class="list-group-item py-1"><i class="bx bx-analyse me-2"></i><span>الكتب , فصول من كتاب</span></a>
										<a href="javascript:;" class="list-group-item py-1"><i class="bx bx-plug me-2"></i><span>المقررات الدراسية</span></a>
										<a href="javascript:;" class="list-group-item py-1"><i class="bx bx-trash-alt me-2"></i><span>خدمة المجتمع</span></a>
										<a href="javascript:;" class="list-group-item py-1"><i class="bx bx-trash-alt me-2"></i><span>الورش والمؤتمرات والسمنارات</span></a>
										<a href="javascript:;" class="list-group-item py-1"><i class="bx bx-trash-alt me-2"></i><span>الاشراف على مشاريع بحثية</span></a>
										<a href="javascript:;" class="list-group-item py-1"><i class="bx bx-trash-alt me-2"></i><span>مشاريع بحثية</span></a>
										<a href="javascript:;" class="list-group-item py-1"><i class="bx bx-trash-alt me-2"></i><span>روابط مهمة</span></a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-lg-9">
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
																@if(isset($edit_data[0]->users->department->college->id) && $edit_data[0]->users->department->college->id == $college->id){{ 'selected="selected"' }} @endif
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
												<div class="col-md-12">
													<label for="lang" class="form-label">لغة المحتوى</label>
													<select class="form-select" required name="lang" id="lang">
														<option value="">اختر لغة المحتوى</option>
														<option value="1" @if(isset($edit_data->lang) && $edit_data->lang == 1){{ 'selected="selected"' }}@endif >اللغة العربية</option>
														<option value="2" @if(isset($edit_data->lang) && $edit_data->lang == 2){{ 'selected="selected"' }}@endif >اللغة الانجليزية</option>
													</select>
													<div class="invalid-feedback">اختر لغة المحتوى</div>
												</div>
											</div>
											<div class="mb-3 row">
												<div class="col-md-12">
													<label for="keywords" class="form-label">الكلمات المفتاحية</label>
													<input type="text" name="keywords" class="form-control" data-role="tagsinput" value="جامعة السودان للعلوم والتكنولوجيا">
													<div class="invalid-feedback"> الرجاء ادخل الكلمات المفتاحية</div>
												</div>
												<input class="form-control" name="id" id="id" type="hidden" value="@if(isset($edit_data->id)) {{$edit_data->id}} @endif">
											</div>
											<div class="mb-3 row">
												<div class="col-md-6">
													<label for="item_val" class="form-label">عنوان الكتاب</label>
													<input type="text" class="form-control" name="item_val"
														id="item_val" placeholder="عنوان الكتاب" value="@if(isset($edit_data->item_val)){{$edit_data->item_val}}@endif" required>
													<div class="invalid-feedback"> الرجاء ادخل عنوان الكتاب</div>
												</div>
												<div class="@if(isset($edit_data[0]->img) && $edit_data[0]->img != '' ){{ 'col-md-3' }}@else {{ 'col-md-6' }} @endif">
													<label for="img" class="form-label">صورة غلاف الكتاب</label>
													<input type="file" class="form-control" name="img"
														id="img" placeholder="صورة غلاف الكتاب" value="" @if(!isset($edit_data)){{ 'required' }}@endif>
													<div class="invalid-feedback"> الرجاء ارفق صورة غلاف الكتاب </div>
												</div>
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
										<th>عنوان الكتاب</th>
										<th>صورة الكتاب</th>
									</tr>
								</thead>
								<tbody>
									@php $url = URL::to('/').'/'.Request::segment(1).'/'.Request::segment(2).'/'.Request::segment(3) @endphp
									@if(isset($data))
										@foreach( $data as $key )
										<tr>
											<td>{{ $key->name }}</td>
											<td>{{ $key->keywords }}</td>
											<td>{{ $key->item_val }}</td>
											<td><img src="{{ URL::asset($key->img) }}" class="rounded-circle p-1 border" style="height: 60px;width: 60px" /></td>
											<td>
												<div class="dropdown ms-auto">
													<a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
													</a>
													<ul class="dropdown-menu">
														<li><a class="dropdown-item" href="{{ URL :: to( $url.'/'.$key->id)}}" style="color:green"><i class="bx bx-pencil"></i> تعديل</a></li>
														<li><a class="dropdown-item" href="#" data-href="{{ URL :: to( $url.'/'.$key->id)}}" data-bs-toggle="modal" data-bs-target="#DeleteModal" style="color:red"><i class="bx bx-trash"></i> حذف</a>
														</li>
													</ul>
												</div>
											</td>
										</tr>
										@endforeach
									@endif
								</tbody>
							</table>
						 </div>
						</div>
					</div>
					</div>
				</div>
		<!--end row-->
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