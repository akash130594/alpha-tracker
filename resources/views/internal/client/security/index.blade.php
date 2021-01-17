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
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card">

                                <div class="card-body">
                                    {!!Form::open()->patch()->route('internal.client.edit.security.update', [$client_id])->autocomplete('off')!!}
                                    @php
                                        $default = (!empty($clientSecurityImpl))?$clientSecurityImpl->security_type_id:0;
                                    @endphp

                                    @can('delete.client')
                                    @if( !empty($clientSecurityImpl) )
                                        <div class="float-right">
                                            <a href="{{route('internal.client.edit.security.delete', ['id' => $client_id, 'impl_id' => $clientSecurityImpl->id])}}" class="text-danger" onclick="return deleteConfirmation()"><i class="fas fa-trash-alt"></i>Delete Security</a>
                                        </div>
                                    @endif
                                    @endcan
                                    {{--Todo:change Select to Select2--}}
                                    {!!Form::select('security_type_id', 'Choose Security Type', $types, $default)->id('security_type_id')!!}

                                    <div id="dynamic_response_div">
                                        @if( !empty($clientSecurityImpl) )
                                            @include('internal.client.security.typeform', ['fields' => json_decode($clientSecurityImpl->field_data)])
                                        @endif
                                    </div>

                                    {!!Form::submit("Update")!!}

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
    <script>
        $('#security_type_id').on('change', function(e) {
            //alert( this.value );
            var security_id = this.value;
            var headers = {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            }

            if(!security_id || security_id == 0){
                return false;
            }
            $('#dynamic_response_div').html('Loading....');
            // Make a request for a user with a given ID
            axios.get("{{ route('internal.client.edit.security.data.show', [$client_id]) }}", {
                params: {
                    type_id: security_id,
                }
            }).then(function (response) {
                // handle success
                if(response.status === 200){
                    var result = $.parseHTML(response.data);

                    $('#dynamic_response_div').html(result);
                }

            }).catch(function (error) {
                // handle error
                $('#dynamic_response_div').html('Some Error Occurred');
            }).then(function () {
                // always executed
                console.log('always executed');
            });
        });

        function deleteConfirmation() {
            if(!confirm("Are You Sure to delete this"))
                event.preventDefault();
        }
    </script>
@endpush
