@extends('internal.layouts.new-app')

@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )

@section('content')
    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>
                        <i class="fas fa-tachometer-alt"></i> @lang('Edit Source')
                    </strong>
                </div><!--card-header-->

                <div class="card-body">
                    {!!Form::open()->patch()->autocomplete('off')!!}
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-body">

                                    @can('delete.sources')
                                    <div class="float-right">
                                        <a href="{{route('internal.source.delete.show', ['id' => $source->id])}}" class="text-danger" onclick="return deleteConfirmation();">
                                            <i class="fas fa-trash-alt"></i> Delete Source</a>
                                    </div>
                                    @endcan
                                    {!! Form::select('source_type_id', 'Source Type', $sourceTypes, $source->source_type_id) !!}

                                    {!!Form::text('name', 'Source Name *', $source->name)->required()->placeholder('Enter Source Name')!!}

                                    {!!Form::text('code', 'Source Code *', $source->code)->required()->placeholder('Enter Source Code')!!}
                                    {!!Form::text('vvars', 'Source Variables *', $source->vvars)->required()->placeholder('Enter Source Variables')->help('Variables should be seperated by comma.')!!}
                                    {!!Form::text('email', 'Email', $source->email)->type('email')->placeholder('Enter Client Email')!!}
                                    {!!Form::text('phone', 'Phone', $source->phone)->placeholder('Enter Client Phone')!!}

                                    {{-- {!!Form::hidden('global_screener', 0)->id('gbl_hid')->required(false)!!}
                                    {!!Form::checkbox('global_screener', 'Global Screener', 1)->required(false)->checked($source->global_screener)!!}

                                    {!!Form::hidden('defined_screener', 0)->id('def_hid')->required(false)!!}
                                    {!!Form::checkbox('defined_screener', 'Defined Screener', 1)->required(false)->checked($source->defined_screener)!!}

                                    {!!Form::hidden('custom_screener', 0)->id('cus_hid')->required(false)!!}
                                    {!!Form::checkbox('custom_screener', 'Custom Screener', 1)->required(false)->checked($source->custom_screener)!!} --}}

                                    <div class="form-group">
                                        <div class="checkbox">
                                          <label>
                                            <input type="checkbox" @if($source->custom_screener==1) checked @endif name="custom_screener" value="1">
                                            Custom Screener
                                          </label>
                                        </div>

                                        <div class="checkbox">
                                          <label>
                                            <input type="checkbox" @if($source->global_screener==1) checked @endif name="global_screener" value="1">
                                            Global Screener
                                          </label>
                                        </div>

                                        <div class="checkbox">
                                          <label>
                                            <input type="checkbox" @if($source->defined_screener==1) checked @endif name="defined_screener" value="1">
                                            Defined Screener
                                          </label>
                                        </div>
                                      </div>

                                    {!!Form::select('status', 'Status', [1 => 'Active', 0 => 'Inactive'],$source->status)!!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            {!!Form::text('complete_url', 'Complete URL', $source->complete_url)->required()->placeholder('Enter Complete URL')!!}
                            {!!Form::text('terminate_url', 'Terminate URL', $source->terminate_url)->required()->placeholder('Enter Terminate URL')!!}
                            {!!Form::text('quotafull_url', 'QuotaFull URL', $source->quotafull_url)->required()->placeholder('Enter Quotafull URL')!!}
                            {!!Form::text('quality_term_url', 'Quality Term URL', $source->quality_term_url)->required()->placeholder('Enter Quality Term URL')!!}
                            {!!Form::submit("Update")!!}
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

<script>
    function deleteConfirmation() {
        if(!confirm("Are You Sure to delete this"))
            event.preventDefault();
    }
</script>
