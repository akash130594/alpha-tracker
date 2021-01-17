@extends('internal.layouts.new-app')

@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )
@section('content')
    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>
                        <i class="fas fa-tachometer-alt"></i> @lang('Vendor Management')
                    </strong>
                    <div class="float-right">
                    <a class="btn btn-primary" href="{{route('internal.project.add.vendor',$project_id)}}">Add Vendor</a>
                    </div>
                </div><!--card-header-->
                <div class="card-body">
                        <div class="vendor_container2">
                            <div class="card">
                                <div class="card-header bg-primary text-white card_panel_header text-center">
                                    <div class="panel-heading row">
                                        <div class="col-2 col-sm-2 col-md-2">Source</div>
                                        <div class="col-1 col-sm-1 col-md-1">CPI</div>
                                        <div class="col-1 col-sm-1 col-md-1">Quota</div>
                                        <div class="col-2 col-sm-2 col-md-2">Screener <span class="small text-truncate">(Global, Pre-Defined, Custom) </span></div>
                                        <div class="col-2 col-sm-2 col-md-2 text-truncate">Quota Selection</div>
                                        <div class="col-1 col-sm-1 col-md-1 text-truncate">Surveys</div>
                                        <div class="col-3 col-sm-3 col-md-3">Action</div>

                                    </div>
                                </div>
                                @foreach($project_vendors as $vendor)
                                <div class="card-body text-center source-list-result vendor_container">
                                    <div class="row">
                                        <div class="col-2 col-sm-2 col-md-2">
                                            <h5 class="mb-1  @if( !empty($vendor->is_active) ) text-primary @endif ">{{$vendor->source->name}}</h5>
                                        </div>
                                        <div class="col-1 col-sm-1 col-md-1 text-truncate">
                                            <input type="text" class="form-control form-control-sm"  name="cpi" value="{{$vendor->cpi}}">
                                        </div>
                                        <div class="col-1 col-sm-1 col-md-1">
                                            <input type="text" class="form-control form-control-sm" name="quota" value="{{$vendor->quota}}">
                                        </div>
                                        <div class="col-2 col-sm-2 col-md-2">
                                            @if($vendor->vendor_screener_excl_flag)
                                                {!! (!empty($vendor->global_screener))? '<span class="material-icons">done</span>':'<span class="material-icons">cancel</span>' !!}{!! '&nbsp;&nbsp;' !!}
                                                {!! (!empty($vendor->predefined_screener))? '<span class="material-icons">done</span>':'<span class="material-icons">cancel</span>' !!}{!! '&nbsp;&nbsp;' !!}
                                                {!! (!empty($vendor->custom_screener))? '<span class="material-icons">done</span>':'<span class="material-icons">cancel</span>' !!}{!! '&nbsp;&nbsp;' !!}
                                            @else
                                                {!! (!empty($vendor->source->global_screener))? '<span class="material-icons">done</span>':'<span class="material-icons">cancel</span>' !!}{!! '&nbsp;&nbsp;' !!}
                                                {!! (!empty($vendor->source->defined_screener))? '<span class="material-icons">done</span>':'<span class="material-icons">cancel</span>' !!}{!! '&nbsp;&nbsp;' !!}
                                                {!! (!empty($vendor->source->custom_screener))? '<span class="material-icons">done</span>':'<span class="material-icons">cancel</span>' !!}{!! '&nbsp;&nbsp;' !!}
                                            @endif
                                        </div>
                                        <div class="col-2 col-sm-2 col-md-2 text-truncate">
                                            @if( !empty($vendor->spec_quota_names) )
                                                {{$vendor->spec_quota_names}}
                                            @else
                                                All
                                            @endif
                                        </div>
                                        <div class="col-1 col-sm-1 col-md-1">
                                            {{$vendor->surveys->count()}}
                                        </div>
                                        <div class="col-3 col-sm-3 col-md-3">
                                            <div class="float-right">
                                                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                                    {{--<button type="button" class="btn btn-secondary">1</button>--}}
                                                    <a class="btn btn-primary" href="{{route('internal.project.create.surveys',[$project_id,$vendor->id])}}">
                                                        <i class="icon-plus icons"></i> Create Survey
                                                    </a>

                                                    <div class="btn-group" role="group">
                                                        <button id="btnGroupDrop1" type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            Actions
                                                        </button>
                                                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                            <a class="dropdown-item " href="{{route('internal.project.vendor.edit',[$project_id,$vendor->id])}}">Edit Source</a>
                                                            <a class="dropdown-item" href="{{route('internal.project.surveys',[$project_id,$vendor->id])}}">Manage Surveys</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div><!-- card -->
        </div><!-- row -->
    </div><!-- row -->
@endsection

@push('after-scripts')

    <script>
        $(document).ready(function(){
            $('[data-tooltip="tooltip"]').tooltip();
        });
        </script>
    <!-- Toastr style -->
    <script src="{{ asset('vendors/jquery-are-you-sure/jquery.are-you-sure.js') }}"></script>
@endpush
@push('after-styles')

@endpush


