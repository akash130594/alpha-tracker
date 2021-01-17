@extends('internal.layouts.new-app')

@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )

@section('content')
    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>
                        <i class="fas fa-tachometer-alt"></i> @lang('Edit Client')
                    </strong>
                </div><!--card-header-->

                <div class="card-body">
                    {!!Form::open()->patch()->autocomplete('off')!!}
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-body">

                                    @can('delete clients')
                                    <div class="float-right">
                                        <a href="{{route('internal.client.delete.show', ['id' => $client_details->id])}}" class="text-danger" onclick="return deleteConfirmation();">
                                            <i class="fas fa-trash-alt"></i>Delete Client</a>
                                    </div>
                                    @endcan

                                    {!!Form::text('name', 'Client Name *', $client_details->name)->required()->placeholder('Enter Client Name')!!}

                                    {!!Form::text('code', 'Client Code *', $client_details->code)->required()->placeholder('Enter Client Code')!!}

                                    {!!Form::text('cvars', 'Client Variables *', $client_details->cvars)->required()->placeholder('Enter Client Variables')->help('Variables should be seperated by comma.')!!}

                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="security_type">Validation Type</label>
                                            <div class="input-group">
                                                @if(!empty($client_security))
                                                    <input id="security_type" style="/* top: 22%; */margin-top: 8px;" value="{{$client_security->name}}" readonly="readonly" class="form-control"/>
                                                @else
                                                    <input id="security_type" style="/* top: 22%; */margin-top: 8px;" value="No Validation" readonly="readonly" class="form-control"/>
                                                @endif

                                                @can('access client security')
                                                <span class="input-group-append">
				                                    <a href="{{route('internal.client.edit.security.show', [$client_details->id])}}" class="btn btn-primary">
                                                        <span class="material-icons">
                                                            settings
                                                        </span>
                                                    </a>
                                                </span>
                                                @endcan
                                            </div>
                                        </div>
                                    </div>

                                    {!!Form::text('website', 'Website URL', $client_details->website)->placeholder('Enter Website URL')!!}

                                    {!!Form::text('email', 'Email', $client_details->email)->type('email')->placeholder('Enter Client Email')!!}

                                    {!!Form::text('phone', 'Phone', $client_details->phone)->placeholder('Enter Client Phone')!!}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                        @include('internal.client.includes.edit.redirect_Flag')
                        </div>
                    </div><!-- row -->
                    <div class="card-footer">
                        {!!Form::submit("Update")!!}
                    </div>
                    {!!Form::close()!!}
                </div> <!-- card-body -->
        </div><!-- card -->
    </div><!-- row -->
</div><!-- row -->
@endsection

@push('after-styles')

@endpush

@push('after-scripts')
    <script>
        function deleteConfirmation() {
            if(!confirm("Are You Sure to delete this"))
                event.preventDefault();
        }
    </script>
@endpush
