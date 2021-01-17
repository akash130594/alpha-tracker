@foreach($fields as $key => $field)
    @php
        $default = '';
        if( isset($clientSecurityImpl) && !empty($clientSecurityImpl) ){
            $methodData = json_decode($clientSecurityImpl->method_data);
            $default = $methodData->$key;
        }
    @endphp
    @if($field->type == 'select')
        @if(!empty($default))
            {!!Form::select($key, $field->name, (array) $field->options, $default)!!}
        @else
            {!!Form::select($key, $field->name, (array) $field->options)!!}
        @endif
    @elseif( $field->type == 'text' )
        @if(!empty($default))
            {!!Form::text($key, $field->name,  $default)!!}
        @else
            {!!Form::text($key, $field->name,  $field->default)!!}
        @endif
    @endif
@endforeach
