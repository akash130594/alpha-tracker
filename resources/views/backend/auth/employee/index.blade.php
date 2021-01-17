@extends('backend.layouts.app')

@section('title', app_name() . ' | ' . 'Employee List')

@section('breadcrumb-links')
    @include('backend.auth.employee.includes.breadcrumb-links')
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                        {{ __('Employee Management') }} <small class="text-muted">{{ __('All Employees') }}</small>
                    </h4>
                </div><!--col-->

                <div class="col-sm-7">
                    @include('backend.auth.employee.includes.header-buttons')
                </div><!--col-->
            </div><!--row-->

            <div class="row mt-4">
                <div class="col">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>@lang('Name')</th>
                                <th>@lang('Salary')</th>
                                <th>@lang('Date of Birth')</th>
                                <th>@lang('Date of joining')</th>
                                <th>@lang('Position')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($employees as $employee)
                                    <tr>
                                        <td>{{ $employee->name }}</td>
                                        <td>{{ $employee->salary }}</td>
                                        <td>{{ $employee->dob }}</td>
                                        <td>{!! $employee->doj !!}</td>
                                        <td>{!! $employee->position !!}</td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="User Actions">
                                                <a href="{{route('admin.auth.employee.edit',$employee->id)}}" data-toggle="tooltip" data-placement="top" title="Edit" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div><!--col-->
            </div><!--row-->
            <div class="row">
                <div class="col-7">
                    <div class="float-left">
                        {!! $employees->total() !!} {{ trans_choice('Total Employees', $employees->total()) }}
                    </div>
                </div><!--col-->

                <div class="col-5">
                    <div class="float-right">
                        {!! $employees->render() !!}
                    </div>
                </div><!--col-->
            </div><!--row-->
        </div><!--card-body-->
    </div><!--card-->
@endsection
