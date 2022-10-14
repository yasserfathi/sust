<!doctype html>
<html lang="en" dir="rtl">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" id="token" content="{{ csrf_token() }}" />
    <!--favicon-->
    <link rel="icon" href="{{ URL::asset('assets/images/favicon-32x32.png') }}" type="image/png" />
    <!-- loader-->
    <link href="{{ URL::asset('assets/css/pace.min.css') }}" rel="stylesheet" />
    <script src="{{ URL::asset('assets/js/pace.min.js') }}"></script>
    <!-- Bootstrap CSS -->
    <link href="{{ URL::asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/css/bootstrap-extended.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/css/app.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/css/icons.css') }}" rel="stylesheet">
    <title>جامعة السودان للعلوم و التكنولوجيا - لوحة التحكم</title>
</head>

<body class="bg-login">
    <!--wrapper-->
    <div class="wrapper">
        <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
            <div class="container-fluid">
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
                    <div class="col mx-auto">
                        <div class="card shadow-none">
                            <div class="card-body">
                                <div class="mb-4 text-center">
                                    <img src="{{ URL::asset('assets/images/sust-logo.png') }}" alt="" />
                                </div>
                                <div class="login-separater text-center mb-4">
                                    <hr />
                                    <p class="h6 text-start">سيتم ارسال رابط إعادة تعيين كلمة المرور الى البريد الكتروني
                                        الخاص بك</p>
                                    <hr />
                                </div>
                                <div class="border p-4 rounded">
                                    <div class="form-body">
                                        <form class="g-3 needs-validation" role="form" method="post"
                                            action="{{ URL::to('admin/forgot-password') }}" id="login-form" novalidate>
                                            @csrf
                                            <div class="mb-3">
                                                <label for="email" class="form-label">البريد الالكتروني</label>
                                                <div class="input-group" id="show_hide_password">
                                                    <input type="email" class="form-control" name="email"
                                                        id="email" placeholder="البريد الالكتروني" required>
                                                    <div class="invalid-feedback"> الرجاء ادخل البريد الالكتروني </div>
                                                </div>
                                            </div>
                                            <div class="mb-0">
                                                <div class="d-grid">
                                                    <button class="btn btn-secondary" type="submit" id="submit">
                                                        <span style="font-size:smaller;">ارسل الرابط</span>
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
                <div class="container">
                    <div class="error-container">
                        <div class="alert alert-secondary alert-dismissible fade show pe-2 d-none" role="alert"></div>
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
        <!--Password show & hide js -->
        <script>
            $(document).ready(function() {
                function refreshToken() {
                    $.get('{{ URL::to('/') . '/refresh-csrf' }}').done(function(data) {
                        $('#token').attr("content", data);
                        $('input[name=_token]').attr("value", data);
                    });
                    return;
                }
                //setInterval(refreshToken, 50);
                // $("#login-form").submit(function(e) {
                //     e.preventDefault();
                //     setInterval(refreshToken, 2000);
                //     var forms = document.querySelectorAll('.needs-validation');
                //     var form = $(this);

                //     if (form[0].checkValidity() === false) {
                //         e.preventDefault();
                //         e.stopPropagation();
                //     } else {
                //         $("#submit").prop('disabled', true).addClass('disabled');
                //         $('.alert').removeClass("alert-danger").fadeIn().html(
                //             '<span class="float-sm-right">الرجاء الانتظار</span><div class="spinner-border spinner-border-sm mx-2" role="status"><span class="visually-hidden">الرجاء الانتظار</span></div>'
                //             ).delay(2000).removeClass("d-none");
                //         $.ajaxSetup({
                //             headers: {
                //                 'X-CSRF-TOKEN': $('#token').attr('content')
                //             }
                //         });
                //         $.ajax({
                //             url: "{{ URL::to('admin/forgot-password') }}",
                //             type: "post",
                //             beforeSend: function(request) {
                //                 request.setRequestHeader("X-CSRF-TOKEN", $('#token').attr(
                //                     'content'));
                //             },
                //             data: new FormData($("#login-form").get(0)),
                //             processData: false,
                //             contentType: false,
                //             async: false,
                //             success: function(data) {
                //                 setTimeout(function() {
                //                     if (data != 'wrong') {
                //                         $(location).attr('href', data);
                //                     } else {
                //                         $('.alert').addClass('alert-danger').fadeIn().html(
                //                             '<span class="float-sm-right">فشل عملية الدخول</span><i class="bx bx-info-circle mr-1 mx-2"></i>'
                //                             ).delay(2000).fadeOut();
                //                         setTimeout(function() {
                //                             $("#submit").removeClass('disabled')
                //                                 .prop('disabled', false);
                //                         }, 2000);
                //                     }
                //                 }, 2000);
                //             },
                //             error: function(data) {
                //                 console.log(data)
                //             }
                //         });
                //     }
                //     form.addClass('was-validated');
                // });
            });
        </script>
        <!--app JS-->
</body>

</html>
