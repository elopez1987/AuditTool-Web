<script src="{{asset('/theme/assets/libs/jquery/dist/jquery.min.js')}}"></script>
<script src="{{asset('/theme/assets/libs/popper.js/dist/umd/popper.min.js')}}"></script>
<script src="{{asset('/theme/assets/libs/bootstrap/dist/js/bootstrap.min.js')}}"></script>

<script src="{{asset('/theme/dist/js/app.min.js')}}"></script>
<script src="{{asset('/theme/dist/js/app.init.horizontal-fullwidth.js')}}"></script>
<script src="{{asset('/theme/dist/js/app-style-switcher.horizontal.js')}}"></script>

<script src="{{asset('/theme/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js')}}"></script>
<script src="{{asset('/theme/assets/extra-libs/sparkline/sparkline.js')}}"></script>

<script src="{{asset('/theme/dist/js/waves.js')}}"></script>
<script src="{{asset('/theme/dist/js/sidebarmenu.js')}}"></script>
<script src="{{asset('/theme/dist/js/custom.min.js')}}"></script>

<script src="{{asset('theme/assets/extra-libs/toastr/dist/build/toastr.min.js')}}"></script>
<script src="{{asset('/theme/assets/libs/select2/dist/js/select2.full.min.js')}}"></script>
<script src="{{asset('/theme/assets/libs/select2/dist/js/select2.min.js')}}"></script>

<script>
    $(".select2").select2();
</script>



<script  src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.11.4/b-2.2.2/b-colvis-2.2.2/b-html5-2.2.2/b-print-2.2.2/cr-1.5.5/fc-4.0.2/fh-3.2.2/r-2.2.9/rg-1.1.4/rr-1.2.8/sc-2.0.5/sr-1.1.0/datatables.min.js"></script>


<script src="{{asset('/theme/assets/libs/moment/moment.js')}}"></script>
<script src="{{asset('/theme/assets/libs/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker-custom.js')}}"></script>


<script>
    $('.workday').bootstrapMaterialDatePicker({ weekStart: 0, time: false });
    $('.datefilter').bootstrapMaterialDatePicker({ format: 'YYYY-MM-DD HH:mm', lang: 'es', weekStart: 1, cancelText: 'Cancelar' });
    $('.date').bootstrapMaterialDatePicker({ format: 'YYYY-MM-DD HH:mm', lang: 'es', weekStart: 1, cancelText: 'Cancelar' });

</script>


