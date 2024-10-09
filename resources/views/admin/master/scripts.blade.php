{{-- Leitor Qr Code --}}
<script src="{{ asset('adminlte/bower_components/html5-qrcode/html5-qrcode.min.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>


{{-- Camera --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
<!-- jQuery 3 -->
<script src="{{ asset('adminlte/bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('adminlte/bower_components/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- SlimScroll -->
<script src="{{ asset('adminlte/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>

<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('adminlte/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>

<!-- date-range-picker -->
<script src="{{ asset('adminlte/bower_components/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('adminlte/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

{{-- Mascaras de input --}}
<script src="{{ asset('adminlte/bower_components/inputmask/dist/inputmask/inputmask.js') }}"></script>
<script src="{{ asset('adminlte/bower_components/inputmask/dist/inputmask/inputmask.extensions.js') }}"></script>
<script src="{{ asset('adminlte/bower_components/inputmask/dist/inputmask/jquery.inputmask.js') }}"></script>

{{-- Toastr --}}
<script src="{{ asset('adminlte/bower_components/toastr/toastr.min.js') }}"></script>

{{-- icheck --}}
<script src="{{ asset('adminlte/plugins/iCheck/icheck.min.js') }}"></script>

<!-- Bootstrap WYSIHTML5 -->
<script src="{{ asset('adminlte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
<!--Ck Editor
<script src="{{ asset('adminlte/bower_components/ckeditor/ckeditor.js') }}"></script>
-->
<script src="https://cdn.jsdelivr.net/npm/chart.js" charset="utf-8"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>


<script src="{{ asset('adminlte/bower_components/select2/dist/js/select2.min.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.1.2/handlebars.min.js"></script>

<script src="{{ asset('adminlte/bower_components/html2canva/js/html2canvas.min.js?v=2') }}"></script>

<script src="{{ asset('adminlte/bower_components/powerbi/powerbi.min.js') }}"></script>

<script>
    @if (Session::has('message'))
        toastr.options = {
            "closeButton": true,
            "progressBar": true
        }
        toastr.success("{{ session('message') }}");
    @endif

    @if (Session::has('error'))
        toastr.options = {
            "closeButton": true,
            "progressBar": true
        }
        toastr.error("{{ session('error') }}");
    @endif

    @if (Session::has('info'))
        toastr.options = {
            "closeButton": true,
            "progressBar": true
        }
        toastr.info("{{ session('info') }}");
    @endif

    @if (Session::has('warning'))
        toastr.options = {
            "closeButton": true,
            "progressBar": true
        }
        toastr.warning("{{ session('warning') }}");
    @endif
</script>
