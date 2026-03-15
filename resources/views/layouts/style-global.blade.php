<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>@yield('title', 'Dashboard')</title>

<link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

<link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />

<!-- SELECT2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>

.select2-container {
    width: 100% !important;
}

.select2-container--default .select2-selection--single {
    height: 45px;
    padding: 8px 12px;
    border: 1px solid #ced4da;
    border-radius: 4px;
}

.select2-container--default .select2-selection__rendered {
    line-height: 28px;
}

.select2-container--default .select2-selection__arrow {
    height: 45px;
}

.select2-dropdown {
    z-index: 9999;
}

</style>