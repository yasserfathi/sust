<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>لوحة تحكم موقع جامعة السودان للعلوم والتكنولوجيا</title>
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
        <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css" rel="stylesheet">
        <!-- Styles / Scripts -->
        @vite(['resources/js/app.js'])
        @routes
    </head>
    <body class="font-sans antialiased dark:bg-black dark:text-white/50">
        <div id="app">
        </div>
    </body>
</html>
