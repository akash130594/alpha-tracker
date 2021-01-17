@extends('internal.layouts.new-app')

@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )

@section('content')
    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>
                        <i class="fas fa-tachometer-alt"></i> Languages
                    </strong>
                    <div class="float-right">
                        <a href="{{route('internal.general.language.create')}}" class="btn btn-primary">Create Language</a>
                    </div>
                </div><!--card-header-->



                <div class="card-body">
                    <div class="row">
                        <table id="source-show" class="table table-striped table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Language Code</th>
                                <th>Name</th>
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
            $('#source-show').DataTable({
                serverSide: true,
                ajax: "{{ route('internal.general.language.datatable') }}",
                columns: [
                    { name: 'id' },
                    { name: 'code' },
                    { name: 'name' },
                    { name: 'status', orderable: false, searchable: false },
                    { name: 'action', orderable: false, searchable: false }
                ]
            });
            $(".dataTables_wrapper").css("width","100%");
        } );
    </script>
@endpush
