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
    <link href="{{ URL::asset('assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
    <!-- loader-->
    <link href="{{ URL::asset('assets/css/pace.min.css') }}" rel="stylesheet" />
    <script src="{{ URL::asset('assets/js/pace.min.js') }}"></script>
    <!-- Bootstrap CSS -->
    <link href="{{ URL::asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/css/bootstrap-extended.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
    <link href="{{ URL::asset('assets/css/app.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/css/icons.css') }}" rel="stylesheet">
    <title>Rocker - Bootstrap 5 Admin Dashboard Template</title>
</head>

<body class="bg-login">
    <!--wrapper-->
    <div class="wrapper">
        <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
            <div class="container-fluid">
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
                    <div class="col mx-auto">
                        <div class="mb-4 text-center">
                            <img src="assets/images/logo.jpg" width="130" alt="" />
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="border p-2 rounded">
                                    <div class="text-center">
                                        <h5 class="">لوحة تحكم موقع جامعة كردفان</h5>
                                    </div>
                                    <div class="login-separater text-center mb-4">
                                        <hr />
                                    </div>
                                    <div class="form-body">
                                        <form class="g-3 needs-validation" role="form" method="post" action=""
                                            id="login-form" novalidate>
                                            {{ csrf_field() }}
                                            <div class="mb-3">
                                                <label for="email" class="form-label">البريد الالكتروني</label>
                                                <input type="email" class="form-control" name="email"
                                                    id="email" placeholder="البريد الالكتروني" required>
                                                <div class="invalid-feedback"> الرجاء ادخل البريد الالكتروني </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="password" class="form-label">كلمة المرور</label>
                                                <div class="input-group" id="show_hide_password">
                                                    <input type="password" class="form-control"
                                                        name="password" id="password" value="" placeholder="كلمة المرور"
                                                        required>
                                                    <div class="invalid-feedback"> الرجاء ادخل كلمة المرور </div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <div class="d-grid">
                                                    <div class="msg alert alert-danger d-none rounded-pill py-2" role="alert"></div>
                                                    <button class="btn btn-success" type="submit"
                                                        id="submit">
                                                        <span class="spinner-border spinner-border-sm d-none"
                                                            role="status" aria-hidden="true"></span>
                                                        تسجيل الدخول <i class="bx bxs-lock-open"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end row-->
            </div>
        </div>
    </div>
    <!--end wrapper-->
    <!-- Bootstrap JS -->
    <script src="{{ URL::asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <!--plugins-->
    <script src="{{ URL::asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
    <!--Password show & hide js -->
    <script>
        $(document).ready(function() {
            var csrfToken = $('meta[name=csrf_token]').attr("content");

            function refreshToken() {
                $.get('{{ URL::to('/') . '/refresh-csrf' }}').done(function(data) {
                    $('meta[name=csrf_token]').attr("content", data);
                });
            }

            $("#login-form").submit(function(e) {
                e.preventDefault();
                var forms = document.querySelectorAll('.needs-validation');
                var form = $(this);

                if (form[0].checkValidity() === false) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                else {
                    $.ajax({
                        url: "{{ URL::to('admin/login') }}",
                        type: "post",
                        data: new FormData($("#login-form").get(0)),
                        processData: false,
                        contentType: false,
                        async: false,
                        beforeSend: function() {
                            $('.msg').fadeOut();
                        },
                        success: function(data) {
                            setTimeout(function() {
                                if (data != 'wrong') {
                                    $(location).attr('href', data);
                                } else {
                                    $('.msg').removeClass('d-none').fadeIn().html('البيانات غير صحيحة ...');
                                }
                            }, 500);
                        }
                    });
                }
                form.addClass('was-validated');
            });
        });
    </script>
</body>
</html>
