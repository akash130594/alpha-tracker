@extends('internal.layouts.new-app')

@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )

@section('content')
    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>
                        <i class="fas fa-tachometer-alt"></i> @lang('Edit Survey Vendors')
                    </strong>
                </div><!--card-header-->

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            @include('internal.project.includes.edit_tabs')
                            <div class="card">
                                <div class="card-body">
                                    {!!Form::open()->patch()->autocomplete('off')->id('source-create')!!}

                                    <div class="form-group ">
                                        <label for="source_id">Source</label>
                                        <select multiple="multiple" data-live-search="true" type="select" name="source_id[]" id="source_id" class="form-control">
                                            @foreach($sources as $source)
                                                @php
                                                    $selected_vendor = false;
                                                    if($project_vendors->contains('vendor_id', $source->id)){
                                                        $selected_vendor = $project_vendors->firstWhere('vendor_id', $source->id);
                                                    }
                                                @endphp
                                                <option value="{{$source->id}}" data-all_quota_id="{{$quota_id}}" data-all_quota_name="{{$quota_name}}" data-vendor_id="{{$source->id}}"
                                                        @if($selected_vendor !== false && $selected_vendor->vendor_screener_excl_flag)
                                                        data-global_screener="{{$selected_vendor->global_screener}}" data-predefined_screener="{{$selected_vendor->predefined_screener}}"  data-custom_screener="{{$selected_vendor->custom_screener}}"
                                                        @else
                                                        data-global_screener="{{$source->global_screener}}" data-predefined_screener="{{$source->defined_screener}}"  data-custom_screener="{{$source->custom_screener}}"
                                                        @endif
                                                        @if($source->pre_selected == 1 || $selected_vendor !== false )
                                                        selected="selected"
                                                        @endif
                                                        @if( $selected_vendor )
                                                       data-project_vendor_id="{{$selected_vendor->id}}" data-quota_assign="{{$selected_vendor->spec_quota_ids}}" data-quota_assign_text="{{$selected_vendor->spec_quota_names}}"  data-cpi="{{$selected_vendor->cpi}}" data-quota="{{$selected_vendor->quota}}"
                                                    @endif
                                                >
                                                    {{$source->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="vendor_container2">
                                        <div class="card">
                                            <div class="card-header card_panel_header text-center" style="font-weight:bold; color: red;">
                                                <div class="panel-heading row">
                                                    <div class="col-3 col-sm-3 col-md-3">Source</div>
                                                    <div class="col-2 col-sm-2 col-md-2">CPI</div>
                                                    <div class="col-2 col-sm-2 col-md-2">Quota</div>
                                                    <div class="col-2 col-sm-2 col-md-2">Screener <span class="small text-truncate">(Global, Pre-Defined, Custom) </span></div>
                                                    <div class="col-2 col-sm-2 col-md-2 text-truncate">Quota Selection</div>
                                                    <div class="col-1 col-sm-1 col-md-1"></div>

                                                </div>
                                            </div>
                                            <div class="card-body text-center source-list-result vendor_container">

                                            </div>

                                        </div>
                                    </div>
                                    {!!Form::submit('Save')!!}

                                    {!!Form::close()!!}
                                </div>

                            </div>

                        </div>
                    </div><!-- row -->
                </div> <!-- card-body -->
            </div><!-- card -->
        </div><!-- row -->
    </div><!-- row -->

    {{--Screener Allocation Modal Starts here--}}
    <div class="modal screener_change" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {!! Form::open()->id('screener_popup_form') !!}
                        <div class="margin-top screener_modal">
                            <input type="checkbox" id="global_screener" name="global_screener" value="1"> Global Screener <br>
                            <input type="checkbox" id="predefined_screener" name="predefined_screener" value="1"> Predefined Screener <br>
                            <input type="checkbox" id="custom_screener" name="custom_screener" value="1"> Custom Screener <br>
                            <input type="hidden" name="vendor_id" id="vendor_id_screener" value="">
                        </div>
                    {!! Form::close() !!}
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary screener_pop_save">Save changes</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
    {{--Screener Allocation Model Ends Here--}}
    {{--Quota Assignment Allocation Starts here--}}
    <div class="modal quota_change" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="margin-top quota_modal">
                        {!! Form::open()->id('quota_popup_form') !!}
                        <div class="list-group">
                            <ul class="list-group quota_list_group">
                                @foreach($project_quotas as $key => $value)
                                    <li class="list-group-item quota_item">
                                        <input type="checkbox" data-quota_text="{{$value}}" class="quota-list quota_list_item" value="{{$key}}" name="{{$value}}" data-name="{{$value}}" checked>
                                        <strong>{{$value}}</strong>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <input type="hidden" name="vendor_id" id="vendor_id_quota" value="">
                        {!! Form::close() !!}
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary quota_pop_save">Save changes</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
    {{--Quota Assignment Allocation Ends here--}}
@endsection

@push('after-styles')
    <!-- Latest compiled and minified CSS -->
    <style>
        .card_panel_header{
            background-color: #1c84c6;
            border-color: #1c84c6;
            color: white;
        }

        .source-list-result > .content > .row {
            padding: 8px 0px 0px 0;
        }

        .source-list-result > .content > .row:hover {
            background: #f5f5f5;
            box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
            transition: 0.3s;
        }
    </style>

    {{--Todo: Add in own Asset--}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" />
@endpush

@push('after-scripts')
    <!-- Are You Sure Script -->
    <script src="{{ asset('vendors/jquery-are-you-sure/jquery.are-you-sure.js') }}"></script>
    <!-- Latest compiled and minified JavaScript -->
    {{--Todo: add in own Asset--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

    {!! script(asset('js/internal/source_quota_assignment.js')) !!}

@endpush
