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
									<h5 class="mb-0"> صفحات فئات لوحة التحكم </h5>
								</div>
								<hr />
								<form class="g-3 needs-validation" role="form" name="form" id="form" enctype="multipart/form-data" method="post" novalidate>
                                            @csrf
											@method('') 
                                            <div class="mb-3 row">
												<div class="col-md-12">
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
											</div>
											<div class="mb-3 row">
												<div class="col-md-6">
													<label for="title" class="form-label">اسم الصفحة</label>
													<input type="text" class="form-control" name="title"
														id="title" placeholder="اسم الصفحة" value="@if(isset($edit_data->title)){{$edit_data->title}}@endif" required>
													<div class="invalid-feedback"> الرجاء ادخل اسم الصفحة</div>
												</div>
												<div class="col-md-6">
													<label for="url" class="form-label">عنوان الصفحة (URL):</label>
													<input type="text" class="form-control" name="url"
														id="url" placeholder="عنوان الصفحة" value="@if(isset($edit_data->url)){{$edit_data->url}}@endif" required>
														<div class="invalid-feedback"> الرجاء ادخل عنوان الصفحة</div>
														<input class="form-control" name="id" id="id" type="hidden" value="@if(isset($edit_data->id)) {{$edit_data->id}} @endif">
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
			   <h6 class="mb-3">اسماء الاقسام</h6>
			   <hr>
		   </div>
	   </div>
	<div class="table-responsive">
		<table id="tabl" class="table table-striped table-bordered">
			<thead>
				<tr>
					<th>الفئة</th>
					<th>اسم الصفحة</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@php $url = URL::to('/').'/'.Request::segment(1).'/'.Request::segment(2) @endphp
				@if(isset($data))
				@foreach( $data as $key )
				<tr>
					<td>{{ $key->category->title }}</td>
					<td>{{ $key->title }}</td>
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