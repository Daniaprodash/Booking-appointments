<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>

    <!-- ✅ رابط Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- font awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- إذا كنتِ تستخدمين Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- css link -->
    <link rel="stylesheet" href="{{asset('assets/css/navbarStyle.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/heroStyle.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/serviceStyle.css')}}">
</head>
<body>
    @include('partial.navbar')
    @yield('content')
</body>
</html>