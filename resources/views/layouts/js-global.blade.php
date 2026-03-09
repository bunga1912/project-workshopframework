{{-- resources/views/layouts/js-global.blade.php --}}

{{-- CORE VENDOR JS (sudah include jQuery & Popper bawaan template) --}}
<script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>

{{-- BOOTSTRAP JS (bundle = sudah include Popper.js, wajib untuk modal) --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

{{-- DATATABLES JS (harus setelah jQuery) --}}
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

{{-- CORE TEMPLATE JS --}}
<script src="{{ asset('assets/js/off-canvas.js') }}"></script>
<script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script>
<script src="{{ asset('assets/js/misc.js') }}"></script>
<script src="{{ asset('assets/js/settings.js') }}"></script>
<script src="{{ asset('assets/js/dashboard.js') }}"></script>