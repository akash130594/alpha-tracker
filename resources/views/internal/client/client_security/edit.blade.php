@extends('internal.layouts.new-app')

@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )

@section('content')
    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>
                        @lang('Edit Client Security Types')
                    </strong>
                </div><!--card-header-->
                <div class="card-body">
                    {{html()->form('post',route('internal.client.security.edit.post',$client_security->id))->open()}}
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    {!!Form::text('code', 'Security Code *', $client_security->code)->required()->placeholder('Enter Client Security Code')!!}
                                    {!!Form::text('name', 'Security Name *', $client_security->name)->required()->placeholder('Enter Client Security Name')!!}
                                    {!!Form::text('field_data', 'Security Data*', $client_security->field_data)->required()->placeholder('Enter Client Security Data')!!}
                                </div>
                            </div>
                        </div>
                    </div><!-- row -->
                    <div class="card-footer">
                        {!!Form::submit("Update")!!}
                    </div>
                    {{html()->form()->close()}}
                </div> <!-- card-body -->
            </div><!-- card -->
        </div><!-- row -->
    </div><!-- row -->
@endsection

@push('after-styles')

@endpush

@push('after-scripts')
@endpush
