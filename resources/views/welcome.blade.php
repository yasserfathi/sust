<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <title>لوحة تحكم موقع جامعة السودان للعلوم والتكنولوجيا</title>
    <link rel="icon" type="image/png" href="{{ versioned_asset('favicon.png') }}">
    <link rel="stylesheet" href="{{ versioned_asset('css/materialdesignicons.min.css') }}">
    <!-- Styles / Scripts -->
    @vite(['resources/js/app.js'])
    @routes
</head>

<body class="font-sans antialiased dark:bg-black dark:text-white/50">
    <div id="app">
    </div>
</body>

</html>