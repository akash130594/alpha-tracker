@extends('internal.layouts.new-app')

@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )

@section('content')
    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>
                        <i class="fas fa-tachometer-alt"></i> @lang('Create Source')
                    </strong>
                </div><!--card-header-->

                <div class="card-body">
                    {!!Form::open()->post()->autocomplete('off')!!}
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-body">

                                    {!! Form::select('source_type_id', 'Source Type', $source_type )->required(false)!!}
                                    {!!Form::text('name', 'Source Name *')->placeholder('Enter Source Name')->required(false)!!}


                                    {!!Form::text('code', 'Source Code *')->placeholder('Enter Source Code')->required(false)!!}

                                    {!!Form::text('vvars', 'Source Variables *')->placeholder('Enter Source Variables')->help('Variables should be seperated by comma.')->required()!!}

                                    {!!Form::text('email', 'Email')->type('email')->placeholder('Enter Source Email')->required(false)!!}

                                    {!!Form::text('phone', 'Phone')->placeholder('Enter Source Phone')->required(false)!!}

                                    {{-- {!!Form::hidden('global_screener', 0)->id('gbl_hid')->required(false)!!}
                                    {!!Form::checkbox('global_screener', 'Global Screener', 1)->required(false)!!}

                                    {!!Form::hidden('defined_screener', 0)->id('def_hid')->required(false)!!}
                                    {!!Form::checkbox('defined_screener', 'Defined Screener', 1)->required(false)!!}

                                    {!!Form::hidden('custom_screener', 0)->id('cus_hid')->required(false)!!}
                                    {!!Form::checkbox('custom_screener', 'Custom Screener', 1)->required(false)!!} --}}

                                    <div class="form-group">
                                        <div class="checkbox">
                                          <label>
                                            <input type="checkbox" name="custom_screener" value="1">
                                            Custom Screener
                                          </label>
                                        </div>

                                        <div class="checkbox">
                                          <label>
                                            <input type="checkbox" name="global_screener" value="1">
                                            Global Screener
                                          </label>
                                        </div>

                                        <div class="checkbox">
                                          <label>
                                            <input type="checkbox" name="defined_screener" value="1">
                                            Defined Screener
                                          </label>
                                        </div>
                                      </div>

                                    {!!Form::select('status', 'Status', [1 => 'Active', 0 => 'Inactive'])->required()   !!}

                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            {!!Form::text('complete_url', 'Complete URL' )->required()->placeholder('Enter Complete URL')!!}
                            {!!Form::text('terminate_url', 'Terminate URL' )->required()->placeholder('Enter Terminate URL')!!}
                            {!!Form::text( 'quotafull_url', 'QuotaFull URL' )->required()->placeholder('Enter Quotafull URL')!!}
                            {!!Form::text( 'quality_term_url', 'Quality Term URL' )->required()->placeholder('Enter Quality Term URL')!!}
                            {!!Form::submit("Create")!!}
                        </div>
                    </div><!-- row -->
                    {!!Form::close()!!}
                </div> <!-- card-body -->
            </div><!-- card -->
        </div><!-- row -->
    </div><!-- row -->
@endsection

@push('after-styles')

@endpush
