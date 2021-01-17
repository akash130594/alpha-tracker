@extends('internal.layouts.new-app')

@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )

@section('content')
    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>
                        <i class="fas fa-tachometer-alt"></i> @lang('Survey Management')
                    </strong>
                </div><!--card-header-->
                {{html()->form('post',route('internal.project.surveys.post.status',[$project_id,$project_vendor_id]))->open()}}
                <div class="card-body">
                    <div class="card border-primary mb-3">
                        <div class="card-header  bg-primary text-white">
                            <blockquote class="blockquote">
                                <p><strong>Survey Details For {{$vendor_name}} :</strong></p>
                            </blockquote>
                        </div>
                        <div class="card-body">
                            <div class="container">
                                <label>Change Status To:</label>
                            <select class="form-control col-sm-4" name="status">
                            @foreach($project_statuses as $project_status)
                                    <option value="{{$project_status['id']}}">{{$project_status['name']}}</option>
                                @endforeach
                            </select>
                            </div>
                            <hr>
                        <table class="table col-12">
                            <thead>
                            <tr>
                                <th><input type="checkbox" name="select_all[]" id="select_all_surveys"></th>
                                <th scope="col">Index</th>
                                <th scope="col">Survey Code</th>
                                <th scope="col">Vendor Project No.</th>
                                <th scope="col">Vendor Code</th>
                                <th scope="col">Status</th>
                                <th scope="col">Status Label</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                        @php $i=1; @endphp
                        @foreach($project_surveys as $project_survey)
                                 <tbody>
                                    <tr id="tr_{{$project_survey['id']}}" class="survey_item" data-survey_id="{{$project_survey['id']}}">
                                        <input type="hidden" value="{{$project_survey['status_id']}}" name="current_status[]">
                                        <td class="select-checkbox" data-project_id="{{$project_status['id']}}" ><input type="checkbox" name="selected_id[]" value="{{$project_survey['id']}}"></td>
                                        <td class="card-title"> @php echo($i); @endphp</td>
                                        <td>{{($project_survey['project_code']).'-'.($project_survey['code'])}}</td>
                                        <td>{{$project_survey['vendor_survey_code']}}</td>
                                        <td>{{$project_survey['vendor_code']}}</td>
                                        <td>{{$project_survey['status_id']}}</td>
                                        <td>{{$project_survey['status_label']}}</td>
                                        <td><a href="javascript:void(0);" class="change_status" data-id="{{$i}}" data-project_survey_id="{{$project_survey['id']}}" data-toggle="modal" data-vendor_id="{{$project_survey['vendor_id']}}" data-project_status_label="{{$project_survey['status_label']}}" data-survey_current_status="{{$project_survey['status_id']}}">Change Status</a></td>
                                    </tr>
                                 </tbody>
                            @php $i++; @endphp
                        @endforeach()
                        </table>
                        </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-lg btn-primary pull-right"  style="width:15em" type="submit"><i class="far fa-check-circle"></i>Save</button>
                    </div>
                {{html()->form()->close()}}



                <div class="modal change_status_modal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Status Change</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            {{html()->form('POST',route('internal.project.change.survey.status'))->open()}}
                            <div class="modal-body">

                                <span>Your Current Status is : <span id="current_status_span"></span></span>

                                {!! Form::select('survey_status', 'Change to', []) !!}
                                <input type="hidden" name="vendor_id" id="change_status_vendor_id" value="">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>

                            </div>
                            {{html()->form()->close()}}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div><!-- card -->
    @push('after-scripts')
        <!-- Toastr style -->
        <script src="{{ asset('vendors/jquery-are-you-sure/jquery.are-you-sure.js') }}"></script>
        <script>

                $('#select_all_surveys').on('click',function (e) {
                    var table = $(e.target).closest('table');
                    $('td input:checkbox',table).prop('checked',this.checked);
                });
                $(document).on('click','.change_status',function(e){
                    var i = $(this).attr("data-id");
                   /* var select = $('select.select_status').attr("data-current_id");*/
                    $(this).closest('.row').find('.select_status_change').find('select.select_status[data-current_id="'+i+'"]').show().removeAttr("disabled");
                    var $vendor_id = $(this).attr("data-vendor_id");
                    var $current_status = $(this).attr("data-survey_current_status");
                    var $project_vendor_id = $(this).attr("data-project_survey_id");
                    var $status_label = $(this).attr('data-project_status_label');
                    $('#change_status_vendor_id').val($project_vendor_id);
                    $select = $('#survey_status');
                    $select.find('option').remove();
                    getChangableStatus($vendor_id,$current_status);
                    $('#current_status_span').html($status_label);
                    $('.change_status_modal').modal('toggle');
                });
            function  getChangableStatus($vendor_id,$current_status)
            {
                var vendor_id = $vendor_id;
                var current_status_id = $current_status;
                var headers = {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                }
                if(!vendor_id || vendor_id === 0){
                    return false;
                }
                axios.get("{{ route('internal.project.vendor.fetchflow') }}", {
                    params: {
                        vendor_id: vendor_id,
                        status_id: current_status_id,
                    }
                }).then(function(response){
                    if(response.status === 200) {
                        $select = $('.select_status');
                        var statuses = response.data;
                        $select = $('#survey_status');
                        $.each(statuses, function (value, name) {
                            $select.append($('<option />', {value: value, text: name}));
                        });
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

@endsection
