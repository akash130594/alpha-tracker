@extends('backend.layouts.app')

@section('title', __('labels.backend.access.users.management') . ' | ' . __('labels.backend.access.users.create'))

@section('breadcrumb-links')
    @include('backend.auth.employee.includes.breadcrumb-links')
@endsection

@section('content')
    {{ html()->form('POST', route('admin.auth.employee.post'))->class('form-horizontal')->open() }}
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-5">
                        <h4 class="card-title mb-0">
                            @lang('Employee Management')
                            <small class="text-muted">@lang('Add Employee')</small>
                        </h4>
                    </div><!--col-->
                </div><!--row-->

                <hr>

                <div class="row mt-4 mb-4">
                    <div class="col">
                        <div class="form-group row">
                            {{ html()->label(__('User'))->class('col-md-2 form-control-label')->for('user_id') }}
                            <div class="col-md-10">
                                <select class="form-control" id="user_id" name="user_id">
                                    @foreach($users as $user)
                                <option value={{$user->id}}>{{$user->first_name}} {{$user->last_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            {{ html()->label(__('Employee Name'))->class('col-md-2 form-control-label')->for('name') }}
                            <div class="col-md-10">
                                {{ html()->text('name')
                                    ->class('form-control')
                                    ->placeholder(__('Enter Employee Name'))
                                    ->attribute('maxlength', 191)
                                    ->required()
                                    ->autofocus() }}
                            </div><!--col-->
                        </div><!--form-group-->

                        <div class="form-group row">
                            {{ html()->label(__('Employee Email Address'))->class('col-md-2 form-control-label')->for('email') }}

                            <div class="col-md-10">
                                {{ html()->email('email')
                                    ->class('form-control')
                                    ->placeholder(__('Enter Email Address'))
                                    ->attribute('maxlength', 191)
                                    ->required() }}
                            </div><!--col-->
                        </div><!--form-group-->

                        <div class="form-group row">
                            {{ html()->label(__('Employee Position'))->class('col-md-2 form-control-label')->for('position') }}

                            <div class="col-md-10">
                                {{ html()->text('position')
                                    ->class('form-control')
                                    ->placeholder(__('Enter Employee Position'))
                                    ->attribute('maxlength', 191)
                                    ->required() }}
                            </div><!--col-->
                        </div><!--form-group-->

                        <div class="form-group row">
                            {{ html()->label(__('Employee Salary'))->class('col-md-2 form-control-label')->for('salary') }}

                            <div class="col-md-10">
                                {{ html()->number('salary')
                                    ->class('form-control')
                                    ->placeholder(__('Enter Salary'))
                                    ->required() }}
                            </div><!--col-->
                        </div><!--form-group-->

                        <div class="form-group row">
                            {{ html()->label(__('Employee Employee Id'))->class('col-md-2 form-control-label')->for('emp_id') }}

                            <div class="col-md-10">
                                {{ html()->text('empid')
                                    ->class('form-control')
                                    ->placeholder(__('Enter Employee Id'))
                                    ->required() }}
                            </div><!--col-->
                        </div><!--form-group-->

                        <div class="form-group row">
                            {{ html()->label(__('Employee Mobile Number'))->class('col-md-2 form-control-label')->for('mobile_no') }}

                            <div class="col-md-10">
                                {{ html()->text('mobile_no')
                                    ->class('form-control')
                                    ->placeholder(__('Enter Mobile Number'))
                                    ->required() }}
                            </div><!--col-->
                        </div><!--form-group-->

                        <div class="form-group row">
                            <label class="col-md-2 form-control-label" for="doj">Date of Joining</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" name="doj" id="doj" placeholder="Enter Date of Joining" required="" autofocus="">
                            </div><!--col-->
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 form-control-label" for="doj">Date of Birth</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" name="dob" id="dob" placeholder="Enter Date of Birth" required="" autofocus="">
                            </div><!--col-->
                        </div>

                        <div class="form-group">
                            <div class="radio">
                              <label>
                                <input type="radio" name="status" id="inactive" checked value="0">
                                Inactive
                              </label>
                            </div>
                            <div class="radio">
                              <label>
                                <input type="radio" name="status" id="active" value="1">
                                Active
                              </label>
                            </div>
                          </div>

                    </div><!--col-->
                </div><!--row-->
            </div><!--card-body-->

            <div class="card-footer clearfix">
                <div class="row">
                    <div class="col">
                        {{ form_cancel(route('admin.auth.user.index'), __('buttons.general.cancel')) }}
                    </div><!--col-->

                    <div class="col text-right">
                        {{ form_submit(__('buttons.general.crud.create')) }}
                    </div><!--col-->
                </div><!--row-->
            </div><!--card-footer-->
        </div><!--card-->
    {{ html()->form()->close() }}
@endsection
@push('after-scripts')
    <script>
        $(document).ready(function(e){
            jQuery('#doj').datetimepicker({
                // formatDate:'YYYY-MM-DD',
                // timepicker:false,
                // lang:'ru'
                timepicker:false,
                format:'Y-m-d'
            });
            jQuery('#dob').datetimepicker({
                // formatDate:'YYYY-MM-DD',
                // timepicker:false,
                // lang:'ru'
                timepicker:false,
                format:'Y-m-d'
            });
        })
    </script>
@endpush
