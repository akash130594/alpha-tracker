

@push('after-styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css" />
@endpush
@push('after-scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.23.0/moment.js"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>

    <script>
       /* $('input.de_dupe_selection').on('change', function(e){
            $dedupe_section = $('.dedupe_filters');
            $dedupe_section.find('.dedupe_action').hide();

            $(this).closest('div').find('.dedupe_action').show();
        });

        $(function () {
            var date = new Date();
            var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
            let config = {
                // https://momentjs.com/docs/#/displaying/
                format: 'YYYY-MM-DD hh:mm:ss',
                useCurrent: false,
                showClear: true,
                showClose: true,
                keepOpen: false
            }

            let control = $('#from_date').datetimepicker(config);
            $('#to_date').datetimepicker(config);

            console.log(control.options())
        });*/
    </script>
@endpush














