@extends('internal.layouts.new-app')

@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )

@section('content')
    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>
                        <i class="fas fa-tachometer-alt"></i> @lang('Source')
                    </strong>
                    <div class="float-right">
                        <a href="{{route('internal.source.create.show')}}" class="btn btn-primary">Create Sources</a>
                    </div>
                </div><!--card-header-->

                <div class="card-body">
                    <div class="row">
                        <table id="source-show" class="table table-striped table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Source Variables</th>
                                    <th>Source Type</th>
                                    <th>Screener<br>
                                        <span>(Global, Defined, Custom)</span>
                                    </th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>


                    </div><!-- row -->
                </div> <!-- card-body -->
            </div><!-- card -->
        </div><!-- row -->
    </div><!-- row -->

    <div class="modal fade in" tabindex="-1" role="dialog" id="linkModal" aria-labelledby="myLargeModalLabel-1" aria-hidden="true">
        <div class="modal-dialog modal-lg link_modal">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button> <h3 class="modal-title"></h3>
                </div>
                <div class="question_loader" style="display:none;">
                    <div class="sk-spinner sk-spinner-wave">
                        <div class="sk-rect1"></div>
                        <div class="sk-rect2"></div>
                        <div class="sk-rect3"></div>
                        <div class="sk-rect4"></div>
                        <div class="sk-rect5"></div>
                    </div>
                </div>
                <div class="modal-body view_link_vendor">

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal-->




@endsection

@push('after-styles')
    <link rel="stylesheet" href="{{ mix('css/datatable.css') }}" >
    <style>
        .sk-spinner-wave.sk-spinner {
            margin: 0 auto;
            width: 50px;
            height: 30px;
            text-align: center;
            font-size: 10px;
        }
        .sk-spinner-wave div {
            background-color: #1ab394;
            height: 100%;
            width: 6px;
            display: inline-block;
            -webkit-animation: sk-waveStretchDelay 1.2s infinite ease-in-out;
            animation: sk-waveStretchDelay 1.2s infinite ease-in-out;
        }
        .sk-spinner-wave .sk-rect2 {
            -webkit-animation-delay: -1.1s;
            animation-delay: -1.1s;
        }
        .sk-spinner-wave .sk-rect3 {
            -webkit-animation-delay: -1s;
            animation-delay: -1s;
        }
        .sk-spinner-wave .sk-rect4 {
            -webkit-animation-delay: -0.9s;
            animation-delay: -0.9s;
        }
        .sk-spinner-wave .sk-rect5 {
            -webkit-animation-delay: -0.8s;
            animation-delay: -0.8s;
        }
        @-webkit-keyframes sk-waveStretchDelay {
            0%,
            40%,
            100% {
                -webkit-transform: scaleY(0.4);
                transform: scaleY(0.4);
            }
            20% {
                -webkit-transform: scaleY(1);
                transform: scaleY(1);
            }
        }
        @keyframes  sk-waveStretchDelay {
            0%,
            40%,
            100% {
                -webkit-transform: scaleY(0.4);
                transform: scaleY(0.4);
            }
            20% {
                -webkit-transform: scaleY(1);
                transform: scaleY(1);
            }
        }
    </style>
@endpush

@push('after-scripts')
    {{-- For DataTables --}}
    <script type="text/javascript" src="{{ mix('js/dataTable.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#source-show').DataTable({
                serverSide: true,
                ajax: "{{ route('internal.source.datatable') }}",
                columns: [
                    { name: 'id' },
                    { name: 'name' },
                    { name: 'code' },
                    { name: 'vvars' },
                    { name: 'sourceType.name' , orderable: false, searchable: true },
                    { name: 'screener', orderable: false, searchable: false },
                    { name: 'status', orderable: false, searchable: false },
                    { name: 'action', orderable: false, searchable: false }
                ]
            });
            $(".dataTables_wrapper").css("width","100%");
        } );


        $(document).on('click','#links',function (e) {
            var source_id = $(this).attr('data-source_id');
            var headers = {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            }
            if(!source_id || source_id === 0){
                return false;
            }
            jQuery('.question_loader').show();
            $('.view_link_vendor').html('');
            axios.get("{{ route('internal.source.link.show') }}", {
                params:{
                    source_id: source_id
                }
            }).then(function (response) {
                if(response.status === 200){
                    var $html = response.data;
                    console.log($html);
                    $('.view_link_vendor').html($html);
                }
            }).catch(function (error) {
                alert('error occured');
                console.log(error);
            }).then(function () {
                jQuery('.question_loader').hide();
            });
            $('#linkModal').modal('toggle');
        });
    </script>
@endpush
