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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.3/dist/sweetalert2.all.min.js"></script>

{{--<script src="{{ URL::asset('build/js/datatables-global.js') }}"></script>--}}
<script src="{{asset('js/datatables-global.js')}}"></script>
<style>
    /* Ensure Select2 dropdowns appear above Bootstrap modals */
    .select2-container {
        width: 100% !important;
    }
    
    /* Select2 dropdown that appears in the body should be above modal backdrop */
    .select2-container--open {
        z-index: 1056 !important;
    }
    
    /* Select2 dropdown rendered in body above modal backdrop (1055) and modal (1050) */
    .select2-dropdown {
        z-index: 1056 !important;
    }
    
    /* Ensure SweetAlert2 appears above Select2 and everything else */
    .swal2-container {
        z-index: 9999 !important;
    }
    
    .swal2-popup {
        z-index: 10000 !important;
    }
</style>
<script>
    (function($){
        function initSelect2InContext($ctx){
            $ctx.find('.select2').each(function(){
                var $el = $(this);
                var $modal = $el.closest('.modal');
                if ($modal.length) {
                    // If already initialized without proper parent, destroy and re-init scoped to modal
                    if ($el.data('select2')) { $el.select2('destroy'); }
                    $el.select2({ dropdownParent: $modal, width: '100%' });
                } else {
                    if (!$el.data('select2')) {
                        $el.select2({ width: '100%' });
                    }
                }
            });
        }

        $(function(){
            // Initialize on page load
            initSelect2InContext($(document));
            // Initialize when any Bootstrap modal is shown
            $(document).on('shown.bs.modal', '.modal', function(){
                initSelect2InContext($(this));
            });
        });
    })(jQuery);
</script>


<script src="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.11.4/b-2.2.2/b-colvis-2.2.2/b-html5-2.2.2/b-print-2.2.2/cr-1.5.5/fc-4.0.2/fh-3.2.2/r-2.2.9/rg-1.1.4/rr-1.2.8/sc-2.0.5/sr-1.1.0/datatables.min.js"></script>



<script src="{{asset('/theme/assets/libs/moment/moment.js')}}"></script>
<script src="{{asset('/theme/assets/libs/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker-custom.js')}}"></script>


<script>
    $('.workday').bootstrapMaterialDatePicker({ weekStart: 0, time: false });
    $('.datefilter').bootstrapMaterialDatePicker({ format: 'YYYY-MM-DD HH:mm', lang: 'es', weekStart: 1, cancelText: 'Cancelar' });
    $('.date').bootstrapMaterialDatePicker({ format: 'YYYY-MM-DD HH:mm', lang: 'es', weekStart: 1, cancelText: 'Cancelar' });

</script>


<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery(".numeric").on('input', function () {
            jQuery(this).val(jQuery(this).val().replace(/[^0-9]/g, ''));
        });
    });
</script>

<script type="text/javascript">
    // Custom sidebar toggle for tablets
    jQuery(document).ready(function () {
        // Function to check if device is tablet
        function isTablet() {
            var width = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
            return width >= 768 && width <= 1024;
        }

        // Handle sidebar toggle click
        jQuery('.sidebartoggler').on('click', function(e) {
            if (isTablet()) {
                e.preventDefault();
                jQuery('#main-wrapper').toggleClass('show-sidebar');
            }
        });

        // Close sidebar when clicking outside on tablets
        jQuery(document).on('click', function(e) {
            if (isTablet() && jQuery('#main-wrapper').hasClass('show-sidebar')) {
                var $sidebar = jQuery('.left-sidebar');
                var $toggler = jQuery('.sidebartoggler');
                
                if (!$sidebar.is(e.target) && $sidebar.has(e.target).length === 0 && 
                    !$toggler.is(e.target) && $toggler.has(e.target).length === 0) {
                    jQuery('#main-wrapper').removeClass('show-sidebar');
                }
            }
        });

        // Handle window resize
        jQuery(window).on('resize', function() {
            if (!isTablet() && jQuery('#main-wrapper').hasClass('show-sidebar')) {
                jQuery('#main-wrapper').removeClass('show-sidebar');
            }
        });
    });
</script>
