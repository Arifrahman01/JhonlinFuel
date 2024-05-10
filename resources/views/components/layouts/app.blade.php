<!doctype html>
<!--
* Tabler - Premium and Open Source dashboard template with responsive and high quality UI.
* @version 1.0.0-beta19
* Copyright 2018-2023 The Tabler Authors
* Copyright 2018-2023 codecalm.net Paweł Kuna
* Licensed under MIT (https://github.com/tabler/tabler/blob/master/LICENSE)
-->
<html lang="en">
<head>
    @include('layouts._meta')
</head>
<body>
    <script src="./dist/js/demo-theme.min.js"></script>
    <div class="page">
        <header class="navbar navbar-expand-md d-print-none">
            @include('layouts._header')
        </header>
        <header class="navbar-expand-md">
            @include('layouts._navbar')
        </header>
        <div class="page-wrapper">
            {{-- ini isi content --}}
            {{ $slot }}
        </div>
        <footer class="footer footer-transparent d-print-none">
            @include('layouts._footer')
        </footer>
    </div>
    
    <script src="./dist/js/tabler.min.js"></script>
    <script src="./dist/js/demo.min.js"></script>
    @yield('js_page')
</body>

</html>
