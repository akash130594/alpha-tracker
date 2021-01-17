@extends('internal.layouts.new-app')

@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )

@section('content')
    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>
                        <i class="fas fa-tachometer-alt"></i> @lang('Edit Survey Security & Screener')
                    </strong>
                </div><!--card-header-->

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            @include('internal.project.includes.edit_tabs')
                            <div class="card">
                                <div class="card-body">
                                    {!!Form::open()->patch()->autocomplete('off')->id('security_screener-create')!!}

                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            {!!Form::select('project[loi_validation]', 'LOI Validation', [0 => 'No', 1 => 'Yes' ], $project->loi_validation)->id('loi_validation')!!}
                                        </div>
                                        <div class="form-group col-sm-6">
                                            {!! Form::text('project[loi_validation_time]', 'LOI Validation Time', $project->loi_validation_time)->id('loi_validation_time')->disabled(empty($project->loi_validation))->help('In Minutes') !!}
                                        </div>
                                    </div>


                                    @if( !empty($projectClient) && $projectClient->security_flag === 1)
                                        <hr/>
                                        {{--Todo: There should be a Generator Method to Parse the Json Value--}}
                                        {!! Form::text('unique_var', 'Unique Parameter') !!}
                                    @endif
                                    <hr/>

                                    <strong>Redirect Flag</strong>
                                    @php
                                    $redirect_status = (!empty($project->client_screener_redirect_flag))?true:false;
                                    $redirectData = ['age', 'education', 'gender', 'income', 'ethinicity'];
                                    $redirectDataValues =(array)json_decode($project->client_screener_redirect_data, true);
                                        $redirectData = array_merge(array_fill_keys ($redirectData, ''), $redirectDataValues);
                                    @endphp
                                    <input type="checkbox" name="client[redirect_flag]" @if($redirect_status) checked="checked" @endif value="1"><br>

                                    <a data-toggle="collapse" aria-expanded="false" aria-controls="client_parameter" href="#client_parameter"> Parameters To Change</a>
                                    <div class="collapse" id="client_parameter">
                                        <div class="row my-2">
                                            <div class="form-group col-sm-1">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input checkable_parameter age" @if(!empty($redirectData['age'])) checked="checked" @endif>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-3">
                                                {!! Form::text('age', '')->placeholder('Age')->disabled()!!}
                                            </div>
                                            <div class="form-group col-sm-4 parameter_change">
                                                {!! Form::text('client[parameter][age]', '', $redirectData['age'])->placeholder('Enter Parameter for Age')->id('age_url')->disabled()!!}
                                            </div>
                                        </div>
                                        <div class="row my-2">
                                            <div class="form-group col-sm-1">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input checkable_parameter education" @if(!empty($redirectData['education'])) checked="checked" @endif>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-3">
                                                {!! Form::text('education', '')->placeholder('Education')->disabled()!!}
                                            </div>
                                            <div class="form-group col-sm-4 parameter_change">
                                                {!! Form::text('client[parameter][education]', '', $redirectData['education'])->placeholder('Enter Parameter for Education')->id('education_url')->disabled()!!}
                                            </div>
                                        </div>

                                        <div class="row my-2">
                                            <div class="form-group col-sm-1">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input checkable_parameter gender" @if(!empty($redirectData['gender'])) checked="checked" @endif>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-3">
                                                {!! Form::text('gender', '')->placeholder('Gender')->disabled()!!}
                                            </div>
                                            <div class="form-group col-sm-4 parameter_change">
                                                {!! Form::text('client[parameter][gender]', '', $redirectData['gender'])->placeholder('Enter Parameter for Gender')->id('gender_url')->disabled()!!}
                                            </div>
                                        </div>
                                        <div class="row my-2">
                                            <div class="form-group col-sm-1">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input checkable_parameter income" @if(!empty($redirectData['income'])) checked="checked" @endif>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-3">
                                                {!! Form::text('income', '')->placeholder('Income')->disabled()!!}
                                            </div>
                                            <div class="form-group col-sm-4 parameter_change">
                                                {!! Form::text('client[parameter][income]', '', $redirectData['income'])->placeholder('Enter Parameter for Income')->id('income_url')->disabled()!!}
                                            </div>
                                        </div>
                                        <div class="row my-2">
                                            <div class="form-group col-sm-1">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input checkable_parameter ethincity" @if(!empty($redirectData['ethinicity'])) checked="checked" @endif>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-3">
                                                {!! Form::text('ethinicity', '')->placeholder('Ethinicity')->disabled()!!}
                                            </div>
                                            <div class="form-group col-sm-4 parameter_change">
                                                {!! Form::text('client[parameter][ethinicity]', '', $redirectData['ethinicity'])->placeholder('Enter Parameter for Ethinicity')->id('ethinicity_url')->disabled()!!}
                                            </div>
                                        </div>
                                    </div>

                                    <hr/>
                                    @include('internal.project.includes.security_screener.custom_screener')

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

    {{--Screener Preview Modal--}}

    <div class="modal fade" id="customScreenerPreviewModal" tabindex="-1" role="dialog" aria-labelledby="customScreenerPreviewModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog  modal-primary modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Custom Screener Preview</h4>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="sk-spinner sk-spinner-wave">
                        <div class="sk-rect1"></div>
                        <div class="sk-rect2"></div>
                        <div class="sk-rect3"></div>
                        <div class="sk-rect4"></div>
                        <div class="sk-rect5"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--Screener Preview Modal Ends Here--}}
@endsection

@push('after-styles')

@endpush

@push('after-scripts')
    <!-- Are You Sure Script -->
    <script src="{{ asset('vendors/jquery-are-you-sure/jquery.are-you-sure.js') }}"></script>


    <script>
        $(document).ready(function(e) {
            // With a custom message
            $('#source-create').areYouSure({'message': 'Your survey details are not saved!'})
        });

        $('select#loi_validation').on('change', function(e){
            $loi_time = $('#loi_validation_time');
            if ($(this).val() === "0") {
                $loi_time.attr('disabled', 'disabled');
            }else if($(this).val() === "1") {
                $loi_time.attr('disabled', false);
            }
        });

        $('.checkable_parameter').on('click', function (e) {
            $closestParameterInput = $(this).closest('.row').find('.parameter_change').find('input');
            if($(this).prop("checked")) {
                $closestParameterInput.removeAttr("disabled");
            }else{
                $closestParameterInput.attr("disabled","disabled");
            }
        });

    </script>
@endpush
