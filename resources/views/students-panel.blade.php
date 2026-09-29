<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>بوابة الطالب - جامعة السودان</title>
    <link rel="stylesheet" href="{{ versioned_asset('css/materialdesignicons.min.css') }}">
    @vite(['resources/js/students/app.js'])
</head>

<body class="antialiased">
    <div id="app"></div>
</body>

</html>