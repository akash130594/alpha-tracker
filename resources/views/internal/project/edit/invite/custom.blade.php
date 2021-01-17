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
                                {{html()->form('post',route('internal.project.edit.custom.post',[$project->id,$custom_details->id]))->open()}}
                                <div class="card-body">
                                    <div class="row">
                                            <textarea name="custom_invite_body" id="custom_invite_body" class="form-control disabled_modal_input custom_invite_body")>{{$custom_details->body}}</textarea>
                                    </div><br>
                                    <div class="row">
                                        <button class="btn btn-primary" type="submit"> <i class="far fa-check-circle"></i>Save</button>
                                    </div>
                                </div>
                                {{html()->form()->close()}}
                                <div class="card-footer">
                                    <button data-toggle="collapse" data-target="#invite_template_placeholder_reference" class="text-muted">You can use these special attributes in invite body</button>
                                    <div id="invite_template_placeholder_reference" class="collapse">
                                        <div class="specialPlaceholderReference">
                                            <table class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>Placeholder</th>
                                                    <th>Description</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>{%S_POINTS%}</td>
                                                    <td>Points for the invite</td>
                                                </tr>
                                                <tr>
                                                    <td>{%S_CODE%}</td>
                                                    <td>Survey Code</td>
                                                </tr>
                                                <tr>
                                                    <td>{%S_NAME%}</td>
                                                    <td>Survey Name</td>
                                                </tr>
                                                <tr>
                                                    <td>{%S_LOI%}</td>
                                                    <td>Survey LOI</td>
                                                </tr>
                                                <tr>
                                                    <td>{%S_EDATE%}</td>
                                                    <td>Survey Enddate</td>
                                                </tr>
                                                <tr>
                                                    <td>{%S_LINK%}</td>
                                                    <td>Survey Live link</td>
                                                </tr><tr>
                                                    <td>{%S_TEST_LINK%}</td>
                                                    <td>Survey Test Link</td>
                                                </tr>
                                                <tr>
                                                    <td>{%U_NAME%}</td>
                                                    <td>User Name - Firstname + Lastname</td>
                                                </tr>
                                                <tr>
                                                    <td>{%U_FNAME%}</td>
                                                    <td>User Name - Firstname</td>
                                                </tr>
                                                <tr>
                                                    <td>{%U_LNAME%}</td>
                                                    <td>User Name - Lastname</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('after-styles')

@endpush

@push('after-scripts')
    <!-- Toastr style -->
    <script src="{{ asset('vendors/jquery-are-you-sure/jquery.are-you-sure.js') }}"></script>

    {!! script(asset('vendors/tinymce/js/tinymce/tinymce.min.js')) !!}
    {!! script(asset('vendors/tinymce/js/tinymce/jquery.tinymce.min.js')) !!}

    <script>
        $(document).ready(function() {
            $('#custom_invite_body').tinymce({
                theme: "modern",
            });

        });
        /*tinymce.init({
            selector: '#custom_invite_body'
        });*/
    </script>

@endpush
