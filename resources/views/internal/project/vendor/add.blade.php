
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
                </div><!--card-header-->
                <div class="card-body">
                    <div class="card border-primary mb-3" >
                        <div class="card-header bg-primary text-white">
                            <blockquote class="blockquote">
                                <p><strong>Add Vendor</strong></p>
                            </blockquote>
                        </div>
                        {{html()->form('POST',route('internal.project.add.vendor',$project_id))->open()}}
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                            <label class="font-weight-bold">Select Vendors:</label>&nbsp&nbsp
                            <span>

                                <select class="form-control col-sm-11 select_vendor" name="vendor_id">
                                    <option value="0" selected>----select vendor-----</option>
                                    @if(!$vendor_remains->isEmpty())
                                     @foreach($vendor_remains as $vendor)
                                        <option value="{{$vendor['id']}}">{{$vendor['name']}}</option>
                                    @endforeach
                                    @else
                                    @foreach($total_vendors as $vendor)
                                        <option value="{{$vendor->id}}">{{$vendor->name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </span>
                            </div>
                            <div class="col-sm-6 vendor_details" style="display:none">
                                <label class="font-weight-bold">Source Code:</label>&nbsp&nbsp
                                <span>
                               <input type="text" class="form-control source_code" disabled name="source_code" value="">
                                    <input type="hidden" class="code" name="vendor_code" value="">
                                    <input type="hidden" class="vendor_id" value="">
                                </span>
                            </div>
                            </div>
                            <hr>
                            <div class="row col-12">
                                <label class="font-weight-bold">CPI:</label>&nbsp&nbsp
                                <span>
                               <input type="text" class="form-control"  name="cpi" value=""><br>
                                </span>
                            </div>
                            <hr>
                            <div class="row col-12">
                                <label class="font-weight-bold">Project Code:</label>&nbsp&nbsp
                                <span>
                                    {{$project->code}}
                                     </span>
                               <input type="hidden" class="form-control"  name="project_code" value="{{$project->code}}"><br>
                            </div>
                            <hr>
                            <div class="row col-12">
                                <label class="font-weight-bold">Quota:</label>&nbsp&nbsp
                                <span>
                               <input type="text" class="form-control"  name="quota" value="{{$quota}}"><br>
                                </span>
                            </div>
                            <hr>
                            <div class="row col-12">
                                <label class="font-weight-bold">Screener:</label>&nbsp&nbsp
                                <span>
                                <input type="checkbox" name="global_screener" value="1">Global<br>
                                <input type="checkbox" name="predefined_screener" value="1">Predefined<br>
                                <input type="checkbox" name="custom_screener" value="1">Custom<br>
                                </span>
                            </div>
                            <hr>
                            <div class="row col-12">
                                <label class="font-weight-bold">Quota Selection:</label>&nbsp&nbsp
                                <span>
                                    @foreach($get_project_quota as $project_quota)
                                <input type="checkbox" checked name="spec_quota_ids[]" value="{{$project_quota->id}}">{{$project_quota->name}}<br>
                                        @endforeach
                                </span>
                            </div>
                            <hr>
                            <div class="row col-12">
                                <label class="font-weight-bold">Set End Pages:</label>&nbsp&nbsp
                                <select name="sy_excl_link_flag" class="link_url_flag">
                                    <option  selected  value="0">No</option>
                                    <option  value="1">Yes</option>
                                </select>
                            </div>
                            <div class="row col-12">
                                <div class="excl_link" style="display:none" disabled="disabled">
                                    <label>Complete</label>&nbsp&nbsp<input class="form-control excl_link_detail" disabled="disabled" size="50" type="text" name="syv_complete" value=""><br>
                                    <label>Terminate</label>&nbsp&nbsp<input class="form-control excl_link_detail" disabled="disabled" type="text" name="syv_terminate" value=""><br>
                                    <label>QuotaFull</label>&nbsp&nbsp<input class="form-control excl_link_detail" disabled="disabled" type="text" name="syv_quotafull" value=""><br>
                                    <label>Quality Term</label>&nbsp&nbsp<input class="form-control excl_link_detail" disabled="disabled" type="text" name="syv_qualityterm" value=""><br>
                                </div>
                            </div>
                            <hr>
                            <div class="row col-12">
                                <label class="font-weight-bold">Active:</label>&nbsp&nbsp
                                <span>
                                    <input type="radio" name="is_active" value="0">No<br>
                                    <input type="radio" name="is_active" value="1">Yes<br>
                                    </span>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            <button class="btn btn-lg btn-primary pull-right"  style="width:15em" type="submit"><i class="far fa-check-circle"></i>Save Changes</button>
                        </div>
                        {{html()->form()->close()}}
                    </div>
                </div>
            </div><!-- card -->
        </div><!-- row -->
    </div><!-- row -->
@endsection

@push('after-scripts')

    <!-- Toastr style -->
    <script src="{{ asset('vendors/jquery-are-you-sure/jquery.are-you-sure.js') }}"></script>
    <script>

            $('.select_vendor').on('change',function (e) {
                var vendor_id = $(this).val();
                   if(vendor_id > 0){
                       getVendorDetails(vendor_id);
                       var source_code = $('input.source_code').val();
                       console.log(source_code);
                        $('.vendor_details').show();
                   }
            }); $("form")[0].reset();

            $('select.link_url_flag').on('change', function (e) {
                /*$('.excl_link').toggle('');
                $(this).removeAttr("disabled");*/
                /*$('.excl_link_detail').removeAttr("disabled");*/
                $excLink = $('.excl_link');

                if($(this).val() === "0"){
                    $($excLink).hide();
                    $('.excl_link_detail').attr("disabled","disabled");
                }else{
                    $($excLink).show();
                    $(this).removeAttr("disabled");
                    $('.excl_link_detail').removeAttr("disabled");
                }
            }).change();
            $("form")[0].reset();

        function getVendorDetails($vendor_id)
        {
            var vendor_id = $vendor_id;
            var headers = {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            }
            if(!vendor_id || vendor_id===0){
                return false;
            }
            axios.get("{{ route('internal.project.get.vendor.details')}}", {
                params: {
                    vendor_id: vendor_id,
                }
            }).then(function (response) {
            if(response.status === 200){
                $div = $('.vendor_details');
                var statuses = response.data;

                $('input.source_code').val(statuses.code);
                $('input.code').val(statuses.code);
                $('input.vendor_id').val(vendor_id);
            }
        }).catch(function (error) {
                // handle error
                //$('#dynamic_response_div').html('Some Error Occurred');
                console.log('Error Occured');
            }).then(function () {
                // always executed
                console.log('always executed');
            });
        }
    </script>
@endpush
@push('after-styles')

    <link rel="stylesheet" href="{{ mix('css/datatable.css') }}" >
@endpush

