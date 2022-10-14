@extends('admin.layout')
@section('content')
				<div class="row">
					<div class="col-xl-11 mx-auto">
						<div class="card border-top border-0 border-4 border-success">
							<div class="card-body">
								<div class="border p-4 rounded">
								<div class="card-title d-flex align-items-center">
									<div><i class="bx bxs-photo-album me-1 font-22"></i>
									</div>
									<h5 class="mb-0">معارض الصور</h5>
								</div>
								<hr />
								<form class="g-3 needs-validation" role="form" name="form" id="form" enctype="multipart/form-data" method="post" novalidate>
                                            @csrf
											@method('') 
											<div class="mb-3 row">
												<div class="col-md-6">
													<label for="title" class="form-label">عنوان المعرض باللغة العربية</label>
													<input type="text" class="form-control" name="title"
														id="title" placeholder="عنوان المعرض باللغة العربية" value="@if(isset($edit_data->title)){{$edit_data->title}}@endif" required>
													<div class="invalid-feedback"> الرجاء ادخل عنوان المعرض باللغة العربية</div>
												</div>
												<div class="col-md-6">
													<label for="title_en" class="form-label">عنوان المعرض باللغة الانجليزية</label>
													<input type="text" class="form-control" name="title_en"
														id="title_en" placeholder="عنوان المعرض باللغة الانجليزية" value="@if(isset($edit_data->title_en)){{$edit_data->title_en}}@endif" required>
														<div class="invalid-feedback"> الرجاء ادخل عنوان المعرض باللغة الانجليزية</div>
														<input class="form-control" name="id" id="id" type="hidden" value="@if(isset($edit_data->id)) {{$edit_data->id}} @endif">
												</div>
											</div>
											<div class="mb-3 row">
												<div class="col-md-6">
													<label for="college_id" class="form-label">الكليات</label>
													<select class="form-select" required name="college_id" id="college_id">
														<option value="">اختر الكلية</option>
														@if(isset($colleges))
															@foreach($colleges as $college)
															{!! "<option value='$college->id' " !!}
																@if(isset($edit_data->college->id) && $edit_data->college->id == $college->id){{ 'selected="selected"' }} @endif
															{!! ">".$college->name."</option>" !!} 
															@endforeach
														@endif
													</select>
													<div class="invalid-feedback">اختر الكلية</div>
												</div>
												<div class="col-md-6">
													<label for="keywords" class="form-label">الكلمات المفتاحية</label>
													<input type="text" name="keywords" class="form-control" data-role="tagsinput" value="@if(isset($edit_data->keywords)){{ $edit_data->keywords }} @else {{ 'جامعة السودان للعلوم والتكنولوجيا' }} @endif">
													<div class="invalid-feedback"> الرجاء ادخل الكلمات المفتاحية</div>
												</div>
											</div>
											<div class="mb-3 row">
												<div class="col-md-12">
													<div class="form-check form-switch">
														<input class="form-check-input" type="checkbox" name="active" id="active" @if(!isset($edit_data) || isset($edit_data->active) && $edit_data->active == 1) {{ 'checked' }} @endif value="1" />
														<label class="form-check-label" for="active">تنشيط المعرض</label>
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
<div class="card radius-10">
	<div class="card-body">
	   <div class="d-flex align-items-center">
		   <div class="col-12">
			   <h6 class="mb-3">معارض الصور</h6>
			   <hr>
		   </div>
	   </div>
	<div class="table-responsive">
		<table id="tabl" class="table table-striped table-bordered">
			<thead>
				<tr>
					<th>اسم الكلية</th>
					<th>عنوان المعرض بالعربية</th>
					<th>عنوان المعرض بالانجليزية</th>
					<th>الكلمات المفتاحية</th>
					<th>تنشيط</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@php $url = URL::to('/').'/'.Request::segment(1).'/'.Request::segment(2) @endphp
				@if(isset($data))
				@foreach( $data as $key )
				@php $keywords = explode(',', $key->keywords) @endphp
				<tr>
					<td>{{ $key->college->name }}</td>
					<td>{{ $key->title }}</td>
					<td>{{ $key->title_en }}</td>
					@php $tokens = ''; @endphp
					@foreach ($keywords as $keyword)
						@php
							$tokens .= '<div class="chip m-1 bg-success text-white">'.$keyword.'</div>';
						@endphp
					@endforeach
					<td>{!! $tokens !!}</td>
					<td>
						@if($key->active == 1)
						<span class="badge bg-gradient-quepal text-white shadow-sm w-100">منشط</span>
						@else
						<span class="badge bg-gradient-bloody text-white shadow-sm w-100">غير منشط</span>
						@endif
					</td>
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