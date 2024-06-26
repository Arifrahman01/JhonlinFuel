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
    @include('components.layouts._meta')
</head>
<style>
    .footer {
        margin-top: auto;
        position: fixed;
        bottom: 0;
        width: 100%;
        z-index: 1000;
        
    }
    .editable {
            border: 1px solid #ddd; /* Tambahkan border untuk menunjukkan bahwa sel bisa diedit */
            padding: 5px; /* Tambahkan padding untuk memperbaiki tampilan */
        }
</style>
@livewireStyles

<body>
    <script src="{{ asset('./dist/js/demo-theme.min.js') }}"></script>
    <div class="page">
        <header class="navbar navbar-expand-md d-print-none">
            @include('components.layouts._header')
        </header>
        <header class="navbar-expand-md">
            @include('components.layouts._navbar')
        </header>
        <div class="page-wrapper">
            {{-- ini isi content --}}
            {{ $slot }}
        </div>
        {{-- <footer class="footer footer-transparent d-print-none">
            @include('components.layouts._footer')
        </footer> --}}
    </div>
    @livewireScripts
    <script src="{{ asset('./dist/js/tabler.min.js') }}"></script>
    <script src="{{ asset('./dist/js/demo.min.js') }}"></script>
    <script src="{{ asset('./dist/js/button.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('./dist/js/livewireCustom.js') }}"></script>
    @stack('scripts')
</body>

</html>
