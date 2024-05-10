<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
<meta http-equiv="X-UA-Compatible" content="ie=edge"/>
<meta name="csrf-token" content="{{ csrf_token() }}" />
<title>{{ config('app.name', 'JG-Fuel') }} | {{ $title ?? 'Dashboard' }} </title>
<!-- CSS files -->
<link href="./dist/css/tabler.min.css" rel="stylesheet"/>
<link href="./dist/css/tabler-flags.min.css" rel="stylesheet"/>
<link href="./dist/css/tabler-payments.min.css" rel="stylesheet"/>
<link href="./dist/css/tabler-vendors.min.css" rel="stylesheet"/>
<link href="./dist/css/demo.min.css" rel="stylesheet"/>
<link href="./dist/css/costum.css" rel="stylesheet"/>
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
  @import url('https://rsms.me/inter/inter.css');
  :root {
      --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
  }
  body {
      font-feature-settings: "cv03", "cv04", "cv11";
  }
</style>