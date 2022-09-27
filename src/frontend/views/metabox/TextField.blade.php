<div>
    {!! $nonce !!}
    <label for="{{ $labelField->id }}">{{ $labelField->label }}</label>
    <input type="text" id="{{ $labelField->id }}" name="{{ $labelField->id }}" value="{{ $labelField->value }}" class="widefat" >
    <label for="{{ $valueField->id }}">{{ $valueField->label }}</label>
    <input type="text" id="{{ $valueField->id }}" name="{{ $valueField->id }}" value="{{ $valueField->value }}" class="widefat" >
</div>