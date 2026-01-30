<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>

    <!-- خطوط -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- ✅ رابط Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- font awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- إذا كنت تستخدم Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- css link -->
    <link rel="stylesheet" href="{{asset('assets/css/indexStyle.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/navStyle.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/footerStyle.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/loginStyle.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/registerStyle.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/userDashboardStyle.css')}}">
    <!-- عم يصير تضارب بأسماء الكلاسات -->
    <link rel="stylesheet" href="{{asset('assets/css/doctorDashboardStyle.css')}}">
</head> 
<body>
    @include('partial.navbar')
    @yield('content')
    @include('partial.footer')

    <script>
        // Navbar Toggle
        const navToggle = document.getElementById('navToggle');
        const navMenu = document.getElementById('navMenu');

        if (navToggle) {
            navToggle.addEventListener('click', () => {
                navToggle.classList.toggle('active');
                navMenu.classList.toggle('active');
            });
        }

        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (navMenu && navToggle && !navMenu.contains(e.target) && !navToggle.contains(e.target)) {
                navMenu.classList.remove('active');
                navToggle.classList.remove('active');
            }
        });

        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Active link on scroll
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.nav-link');

        function activateNavLink() {
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (window.scrollY >= sectionTop - 200) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === `#${current}`) {
                    link.classList.add('active');
                }
            });
        }

        window.addEventListener('scroll', activateNavLink);
    </script>
</body>
</html>