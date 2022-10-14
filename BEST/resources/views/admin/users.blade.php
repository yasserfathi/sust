@extends('admin.layout')
@section('content')
				<div class="row">
					<div class="col-xl-11 mx-auto">
						<div class="card border-top border-0 border-4 border-success radius-10">
							<div class="card-body">
								<div class="border p-4 rounded">
								<div class="card-title d-flex align-items-center">
									<div><i class="bx bxs-user me-1 font-22"></i>
									</div>
									<h5 class="mb-0">مستخدمو النظام</h5>
								</div>
								<hr />
								<form class="g-3 needs-validation" role="form" name="form" id="form" enctype="multipart/form-data" method="post" novalidate>
                                            @csrf
											@method('') 
                                            <div class="mb-3 row">
												<div class="col-md-6">
													<label for="name" class="form-label">الاسم</label>
													<input type="text" class="form-control" name="name"
														id="name" placeholder="الاسم" value="@if(isset($edit_data[0]->name)){{$edit_data[0]->name}}@endif" required>
													<div class="invalid-feedback">الرجاء ادخل الاسم</div>
												</div>
                                                <div class="col-md-6">
													<label for="name_en" class="form-label">الاسم باللغة الانجليزية</label>
													<input type="text" class="form-control" name="name_en"
														id="name_en" placeholder="الاسم باللغة الانجليزية " value="@if(isset($edit_data[0]->name_en)){{$edit_data[0]->name_en}}@endif" required>
													<div class="invalid-feedback"> الرجاء ادخل الاسم باللغة الانجليزية </div>
												</div>
											</div>
                                            <div class="mb-3 row">
												<div class="col-md-4">
													<label for="phone" class="form-label">رقم الهاتف </label>
													<input type="text" class="form-control" name="phone"
														id="phone" placeholder="رقم الهاتف " value="@if(isset($edit_data[0]->phone)){{$edit_data[0]->phone}}@endif" required>
													<div class="invalid-feedback"> الرجاء ادخل رقم الهاتف </div>
												</div>
                                                <div class="col-md-4">
													<label for="email" class="form-label">البريد الالكتروني</label>
													<input type="email" class="form-control" name="email"
														id="email" placeholder="البريد الالكتروني" value="@if(isset($edit_data[0]->email)){{$edit_data[0]->email}}@endif" required>
													<div class="invalid-feedback"> الرجاء ادخل البريد الالكتروني </div>
												</div>
                                                <div class="@if(isset($edit_data[0]->img) && $edit_data[0]->img != '' ){{ 'col-md-3' }}@else {{ 'col-md-4' }} @endif">
													<label for="img" class="form-label">صورة المستخدم</label>
													<input type="file" class="form-control" name="img"
														id="img" placeholder="صورة المستخدم" value="" @if(!isset($edit_data)){{ 'required' }}@endif>
													<div class="invalid-feedback"> الرجاء ارفق صورة المستخدم </div>
												</div>
												@if(isset($edit_data[0]->img) && $edit_data[0]->img != '' )
													<div class="col-md-1">
														<img src="{{ URL::asset($edit_data[0]->img) }}" class="rounded-circle border" style="height:64px;width:64px;margin-top:15px" />
													</div>
												@endif
											</div>
                                            <div class="mb-3 row">
                                                <hr>
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
                                                    <label for="rank" class="form-label">الدرجات العلمية</label>
													<select class="form-select" required name="rank" id="rank">
														<option value="">اختر الدرجة</option>
														<option value="مساعد تدريس" @if(isset($edit_data[0]->staff->rank) && $edit_data[0]->staff->rank == "مساعد تدريس"){{ 'selected="selected"' }} @endif>مساعد تدريس</option>
														<option value="محاضر" @if(isset($edit_data[0]->staff->rank) && $edit_data[0]->staff->rank == "محاضر"){{ 'selected="selected"' }} @endif>محاضر</option>
														<option value="استاذ مساعد" @if(isset($edit_data[0]->staff->rank) && $edit_data[0]->staff->rank == "استاذ مساعد"){{ 'selected="selected"' }} @endif>استاذ مساعد</option>
														<option value="استاذ مشارك" @if(isset($edit_data[0]->staff->rank) && $edit_data[0]->staff->rank == "استاذ مشارك"){{ 'selected="selected"' }} @endif>استاذ مشارك</option>
                                                        <option value="استاذ" @if(isset($edit_data[0]->staff->rank) && $edit_data[0]->staff->rank == "استاذ"){{ 'selected="selected"' }} @endif>استاذ</option>
													</select>
													<div class="invalid-feedback">اختر الدرجة</div>
												</div>
                                            </div>
                                            <div class="mb-3 row">
                                                <div class="col-md-4">
													<label for="hire_date" class="form-label">تاريخ التعيين</label>
													<input class="form-control datepicker" name="hire_date"
														id="hire_date" placeholder="تاريخ التعيين " value="@if(isset($edit_data[0]->staff->hire_date)){{$edit_data[0]->staff->hire_date}}@endif" required>
													<div class="invalid-feedback"> الرجاء ادخل تاريخ التعيين </div>
												</div>
												<div class="col-md-4">
													<label for="job_title" class="form-label">مسمى الوظيفة</label>
													<input type="text" class="form-control" name="job_title"
														id="job_title" placeholder="مسمى الوظيفة" value="@if(isset($edit_data[0]->staff->job_title)){{$edit_data[0]->staff->job_title}}@endif" required>
													<div class="invalid-feedback"> الرجاء ادخل مسمى الوظيفة </div>
												</div>
                                                <div class="col-md-4">
													<label for="job_title_en" class="form-label">مسمى الوظيفة بالانجليزية</label>
													<input type="text" class="form-control" name="job_title_en"
														id="job_title_en" placeholder="مسمى الوظيفة بالانجليزية" value="@if(isset($edit_data[0]->staff->job_title_en)){{$edit_data[0]->staff->job_title_en}}@endif" required>
													<div class="invalid-feedback"> الرجاء ادخل مسمى الوظيفة بالانجليزية </div>
												</div>
											</div>
                                            <div class="mb-3 row">
                                                <div class="col-md-3">
													<label for="specialty" class="form-label">التخصص العام</label>
													<input class="form-control" name="specialty"
														id="specialty" placeholder="التخصص العام" value="@if(isset($edit_data[0]->staff->specialty)){{$edit_data[0]->staff->specialty}}@endif" required>
													<div class="invalid-feedback"> الرجاء ادخل التخصص العام </div>
												</div>
												<div class="col-md-3">
													<label for="subspecialty" class="form-label">التخصص الدقيق</label>
													<input type="text" class="form-control" name="subspecialty"
														id="subspecialty" placeholder="التخصص الدقيق" value="@if(isset($edit_data[0]->staff->subspecialty)){{$edit_data[0]->staff->subspecialty}}@endif" required>
													<div class="invalid-feedback"> الرجاء ادخل التخصص الدقيق </div>
												</div>
                                                <div class="col-md-3">
													<label for="specialty_en" class="form-label">التخصص العام بالانجليزية</label>
													<input type="text" class="form-control" name="specialty_en"
														id="specialty_en" placeholder="التخصص العام بالانجليزية" value="@if(isset($edit_data[0]->staff->specialty_en)){{$edit_data[0]->staff->specialty_en}}@endif" required>
													<div class="invalid-feedback"> الرجاء ادخل التخصص العام بالانجليزية </div>
												</div>
                                                <div class="col-md-3">
													<label for="subspecialty_en" class="form-label">التخصص الدقيق بالانجليزية</label>
													<input type="text" class="form-control" name="subspecialty_en"
														id="subspecialty_en" placeholder="التخصص الدقيق بالانجليزية" value="@if(isset($edit_data[0]->staff->subspecialty_en)){{$edit_data[0]->staff->subspecialty_en}}@endif" required>
													<div class="invalid-feedback"> الرجاء ادخل التخصص الدقيق بالانجليزية </div>
												</div>
												<input class="form-control" name="id" id="id" type="hidden" value="@if(isset($edit_data[0]->id)) {{$edit_data[0]->id}} @endif">
											</div>
                                            <div class="mb-3 row">
												<div class="col-md-12">
													<div class="form-check form-switch">
														<input class="form-check-input" type="checkbox" name="active" id="active" @if(!isset($edit_data) || isset($edit_data[0]->active) && $edit_data[0]->active == 1) {{ 'checked' }} @endif value="1" />
														<label class="form-check-label" for="active">تنشيط اسم المستخدم</label>
													</div>
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
					</div>
				</div>
				<!--end row-->
@endsection
@section('table')
<div class="row">
	<div class="col-xl-11 mx-auto">
<div class="card border-top border-0 border-4 border-success radius-10">
	<div class="card-body">
	   <div class="d-flex align-items-center">
		   <div class="col-12">
			   <h6 class="mb-3">اسماء الاقسام</h6>
			   <hr>
		   </div>
	   </div>
	<div class="table-responsive">
		<table id="tabl" class="table table-striped table-bordered">
			<thead>
				<tr>
					<th>الاسم</th>
					<th>الكلية</th>
					<th>البريد الالكتروني</th>
					<th>رقم الهاتف</th>
                    <th></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@php $url = URL::to('/').'/'.Request::segment(1).'/'.Request::segment(2) @endphp
				@if(isset($data))
				@foreach( $data as $key )
				<tr>
					<td>{{ $key->name }}</td>
					<td>{{ $key->staff->department->college->name }}</td>
					<td>{{ $key->email }}</td>
                    <td>{{ $key->phone }}</td>
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
@endsection
<script src="{{ URL::asset('assets/js/jquery.min.js') }}"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#college_id").on("change", function(){
			$.post("{{ URL :: to('admin/department/list') }}",{ "_token": "{{ csrf_token() }}",id:$(this).val(),rand:Math.random() } ,function(data){
				$("#department_id").prop("disabled", false).html(data);
			});
		});
	});
</script>