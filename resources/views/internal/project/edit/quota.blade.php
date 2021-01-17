@extends('internal.layouts.new-app')

@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )

@section('content')
    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>
                        <i class="fas fa-tachometer-alt"></i> @lang('Edit Survey Respondents')
                    </strong>
                </div><!--card-header-->

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            @include('internal.project.includes.edit_tabs')
                            <div class="card">
                                <div class="card-body apace_quota_section">
                                    {{--{!!Form::open()->patch()->autocomplete('off')->id('project-respondents-create')!!}--}}

                                    @include('internal.project.edit.quota.respondent')

                                    {!!Form::open()->patch()->autocomplete('off')->id('project-quota-create')!!}
                                    {{--Todo: On Save Button Click: Serialize and Store "quota_target_form" form in here so that it can be sent to request--}}

                                    {!!Form::button("Save")->id('quota_step_save')->color("primary")!!}

                                    {!!Form::close()!!}
                                </div>
                            </div>

                        </div>
                    </div><!-- row -->
                </div> <!-- card-body -->
            </div><!-- card -->
        </div><!-- row -->
    </div><!-- row -->
@endsection

@push('after-styles')

@endpush

@push('after-scripts')
    <!-- Toastr style -->
    <script src="{{ asset('vendors/jquery-are-you-sure/jquery.are-you-sure.js') }}"></script>
    <script src="{{asset('vendors/deserialize/json2.min.js')}}"></script>
    <script src="{{asset('vendors/deserialize/jquery.deserialize.js')}}"></script>

    <script>
        function copyForms( $form1 , $form2 ) {
            $form_inputs = $form1.find('input[type="hidden"]');
            $form_inputs.each(function( index ) {
                $(this).clone().appendTo($form2);
            });
        }
        $(document).ready(function(e){
            // With a custom message
            $('#project-quota-create').areYouSure( {'message':'Your profile details are not saved!'} );

            $('#quota_step_save').on('click', function (e) {
                var $final_form = $('#project-quota-create');
                var $source_quota_form = $('.quota_target_form');
                copyForms($source_quota_form, $final_form);

                $final_form.submit();
            });

        });

    </script>
@endpush
