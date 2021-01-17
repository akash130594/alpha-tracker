@extends('internal.layouts.new-app')

@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )

@section('content')
    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>
                        <i class="fas fa-tachometer-alt"></i> @lang('Clients')
                    </strong>
                    <div class="float-right">
                        <a href="{{route('internal.client.create.show')}}" class="btn btn-primary">Create CLient</a>
                    </div>
                </div><!--card-header-->

                <div class="card-body">
                    <div class="row">
                        <table id="clients-show" class="table table-striped table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Client Variables</th>
                                    <th>Status</th>
                                    <th>Security Validation</th>
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
                                </tr>
                            </tbody>
                        </table>


                    </div><!-- row -->
                </div> <!-- card-body -->
            </div><!-- card -->
        </div><!-- row -->
    </div><!-- row -->
@endsection

@push('after-styles')
    <link rel="stylesheet" href="{{ mix('css/datatable.css') }}" >
@endpush

@push('after-scripts')
    {{-- For DataTables --}}
    <script type="text/javascript" src="{{ mix('js/dataTable.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#clients-show').DataTable({
                serverSide: true,
                ajax: "{{ route('internal.client.datatable') }}",
                columns: [
                    { name: 'id' },
                    { name: 'name' },
                    { name: 'code' },
                    { name: 'cvars' },
                    { name: 'status', orderable: false, searchable: false },
                    { name: 'security_flag', orderable: false, searchable: false },
                    { name: 'action', orderable: false, searchable: false }
                ]
            });
            $(".dataTables_wrapper").css("width","100%");
        } );
    </script>
@endpush
