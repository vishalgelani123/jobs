<script src="{{asset('assets/js/helpers.js')}}"></script>
<script src="{{asset('assets/js/template-customizer.js')}}"></script>
<script src="{{asset('assets/js/config.js')}}"></script>
<script src="{{asset('assets/js/libs/jquery/jquery.js')}}"></script>
<script src="{{asset('assets/js/libs/popper/popper.js')}}"></script>
<script src="{{asset('assets/js/bootstrap.js')}}"></script>
<script src="{{asset('assets/js/libs/node-waves/node-waves.js')}}"></script>
<script src="{{asset('assets/js/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
<script src="{{asset('assets/js/libs/hammer/hammer.js')}}"></script>
<script src="{{asset('assets/js/libs/i18n/i18n.js')}}"></script>
<script src="{{asset('assets/js/libs/typeahead-js/typeahead.js')}}"></script>
<script src="{{asset('assets/js/menu.js')}}"></script>
<script src="{{asset('assets/js/libs/apex-charts/apexcharts.js')}}"></script>
<script src="{{asset('assets/js/chart-js.js')}}"></script>
<script src="{{asset('assets/js/libs/swiper/swiper.js')}}"></script>
<script src="{{asset('assets/js/main.js')}}"></script>
<script src="{{asset('assets/js/dashboards-analytics.js')}}"></script>

<script src="{{asset('assets/js/datatables-bootstrap5.js')}}"></script>
<script src="{{asset('assets/js/tables-datatables-advanced.js')}}"></script>

<script src="{{asset('assets/js/jquery.cookie.js')}}"></script>

<script src="{{asset('assets/js/sweetalert2.min.js')}}"></script>
<script src="{{asset('assets/js/select2.min.js')}}"></script>
<script src="{{asset('assets/js/forms-selects.js')}}"></script>

<script src="{{asset('assets/js/repeater.min.js')}}"></script>

<script src="{{asset('assets/js/libs/fullcalendar/fullcalendar.js')}}"></script>
<script src="{{asset('assets/js/libs/form-validation/popular.min.js')}}"></script>
<script src="{{asset('assets/js/libs/form-validation/bootstrap5.js')}}"></script>
<script src="{{asset('assets/js/libs/form-validation/auto-focus.js')}}"></script>
<script src="{{asset('assets/js/libs/flatpickr/flatpickr.js')}}"></script>
<script src="{{asset('assets/js/libs/moment/moment.js')}}"></script>

<script src="{{asset('assets/js/libs/jkanban/jkanban.js')}}"></script>
<script src="{{asset('assets/js/libs/quill/katex.js')}}"></script>
<script src="{{asset('assets/js/libs/quill/quill.js')}}"></script>
<script src="{{asset('assets/js/app-kanban.js')}}"></script>
<script src="{{asset('assets/js/ckeditor.js')}}"></script>
<script src="{{asset('assets/js/ckeditor5.js')}}"></script>
<script src="{{asset('assets/js/moment.min.js')}}"></script>
<script src="{{asset('assets/js/daterangepicker.min.js')}}"></script>
<script src="{{asset('assets/js/filepond.js')}}"></script>
<script src="{{asset('assets/js/filepond-plugin-file-validate-type.js')}}"></script>
<script src="{{asset('assets/js/filepond-plugin-file-validate-size.js')}}"></script>
<script src="{{asset('assets/js/intlTelInput.min.js')}}"></script>
<script src="{{asset('assets/js/custom.js')}}"></script>
<script>
    @php $role = 'admin'; @endphp
    @if(Auth::user() && Auth::user()->hasRole('user'))
    @php $role ='user'; @endphp
    @endif
    let userRole = "{{$role}}";
</script>
