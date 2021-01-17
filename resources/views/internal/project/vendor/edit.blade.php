
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
                                <p><strong>Edit</strong></p>
                            </blockquote>
                        </div>
                        {{html()->form('POST',route('internal.project.vendor.edit.post',[$project_id,$vendor_id]))->open()}}
                        <div class="card-body">
                              <div class="row">
                                  <div class="col-sm-6">
                                  <label class="font-weight-bold">Vendor Name: </label>&nbsp{{$project_vendors->source->name}}
                                  </div>
                                  <div class="col-sm-6">
                                      <label class="font-weight-bold">Quota: </label><input class="form-control" type="text" name="quota" value="{{$project_vendors->quota}}">
                                  </div>
                                  </div>
                            <hr>
                                  <div class="row">
                                      <div class="col-sm-6">
                                          <label class="font-weight-bold">CPI: </label>&nbsp&nbsp
                                          <input class="form-control-sm cpi-unable" type="text" size="5" name="quota" disabled value="{{$project_vendors->cpi}}">&nbsp&nbsp
                                          <span>
                                              <a class="btn btn-danger btn-sm cpi_unable" aria-pressed="true" href="javascript:void(0);" type="button">Update Cpi</a>
                                          </span>
                                      </div>
                                      <div class="col-sm-6">
                                          <label class="font-weight-bold">Screener(Global,Defined,Custom):</label>&nbsp&nbsp
                                          <span>
                                          {{-- {!!Form::hidden('global_screener', 0)->id('gbl_hid')->required(false)!!}
                                          {!!Form::checkbox('global_screener', 'Global Screener', 1)->required(false)->checked($project_vendors->global_screener)!!}
                                          {!!Form::hidden('defined_screener', 0)->id('def_hid')->required(false)!!}
                                          {!!Form::checkbox('defined_screener', 'Defined Screener', 1)->required(false)->checked($project_vendors->predefined_screener)!!}
                                          {!!Form::hidden('custom_screener', 0)->id('cus_hid')->required(false)!!}
                                          {!!Form::checkbox('custom_screener', 'Custom Screener', 1)->required(false)->checked($project_vendors->custom_screener)!!} --}}
                                          <div class="form-group">
                                            <div class="checkbox">
                                              <label>
                                                <input type="checkbox" @if($project_vendors->custom_screener==1) checked @endif name="custom_screener" value="1">
                                                Custom Screener
                                              </label>
                                            </div>

                                            <div class="checkbox">
                                              <label>
                                                <input type="checkbox" @if($project_vendors->global_screener==1) checked @endif name="global_screener" value="1">
                                                Global Screener
                                              </label>
                                            </div>

                                            <div class="checkbox">
                                              <label>
                                                <input type="checkbox" @if($project_vendors->predefined_screener==1) checked @endif name="predefined_screener" value="1">
                                                Defined Screener
                                              </label>
                                            </div>
                                          </div>
                                        </span>
                                      </div>
                                  </div>
                            <hr>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label class="font-weight-bold">
                                           Exclusive End Pages:
                                        </label>&nbsp&nbsp
                                        <span>
                                             <select name="link" class="link_flag">
                                                 <option id="no_link_flag" @if($project_vendors->sy_excl_link_flag==0) selected @endif value="0">No</option>
                                                 <option id="link_flag" @if($project_vendors->sy_excl_link_flag==1) selected @endif value="1">Yes</option>
                                            </select>
                                            <div class="excl_link" style="display:none" disabled="disabled">
                                            <label>Complete</label>&nbsp&nbsp<input class="form-control excl_link_detail" disabled="disabled" size="50" type="text" name="complete" value="{{$project_vendors->syv_complete}}"><br>
                                            <label>Terminate</label>&nbsp&nbsp<input class="form-control excl_link_detail" disabled="disabled" type="text" name="terminate" value="{{$project_vendors->syv_terminate}}"><br>
                                            <label>QuotaFull</label>&nbsp&nbsp<input class="form-control excl_link_detail" disabled="disabled" type="text" name="quota_full" value="{{$project_vendors->syv_quotafull}}"><br>
                                            <label>Quality Term</label>&nbsp&nbsp<input class="form-control excl_link_detail" disabled="disabled" type="text" name="quality_term" value="{{$project_vendors->syv_qualityterm}}"><br>
                                            </div>
                                        </span>
                                    </div>
                                    <div class="col-sm-6">
                                          <label class="font-weight-bold">Quota Selection:</label>&nbsp&nbsp<br>

                                          @foreach($quota_details as $quota_name)
                                              <span>
                                                  @if($project_vendors->spec_quota_ids==null)
                                                      <label>&nbsp{{$quota_name->name}}&nbsp</label><input type="checkbox" name="quota_selection[]" checked value="{{$quota_name->id}}"><br>
                                                  @else
                                                  <label>&nbsp{{$quota_name->name}}&nbsp</label><input type="checkbox" name="quota_selection[]" @if($project_vendors->spec_quota_ids && (in_array($quota_name->id,explode(",",$project_vendors->spec_quota_ids)))) checked @endif value="{{$quota_name->id}}"><br>
                                                  @endif
                                              </span>
                                          @endforeach
                                     </div>
                                </div>
                                  <hr>
                              <div class="row">
                                  <div class="col-sm-6">
                                      <label class="font-weight-bold">Status: </label>&nbsp&nbsp
                                        <span>
                                           <select name="status" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action
                                                <div class="dropdown-menu" x-placement="top-start">
                                                      <option  @if($project_vendors->is_active==1) selected @endif value="1">Yes</option>
                                                      <option  @if($project_vendors->is_active==0) selected @endif value="0">No</option>
                                                </div>
                                            </select>
                                         </span>
                                    </div>
                              </div>
                         </div>
                        <div class="card-footer text-center">
                                <button class="btn btn-lg btn-primary pull-right"  style="width:15em" type="submit"><span class="material-icons">check_box</span>Save Changes</button>
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
        $(document).ready(function() {
            $('select.link_flag').on('change', function (e) {
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
            $('.cpi_unable').on('click',function(e){
                $('input.cpi-unable').removeAttr("disabled");
                });
            });
        $("form")[0].reset();
        </script>
@endpush
@push('after-styles')

    <link rel="stylesheet" href="{{ mix('css/datatable.css') }}" >
@endpush

