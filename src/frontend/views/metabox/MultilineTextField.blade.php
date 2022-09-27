<div class="metabox multiline-text-field">
    {!! $nonce !!}
    <label for="{{ $groupLabelField->id }}" >
    {{ $groupLabelField->label }}
    </label>
    <input
        type="text"
        id="{{ $groupLabelField->id }}"
        name="{{ $groupLabelField->id }}"
        value="{{ $groupLabelField->value }}"
        class="widefat"
    />
    <div class="meta-field-group">
        @foreach ($valueFields as $field)
            <div class="field">
                <label for="{{ $field->id }}">
                    {{ $field->label }}
                </label>
                <input
                    type="text"
                    id="{{ $field->id }}"
                    name="{{ $field->id }}"
                    value="{{ $field->value }}"
                    class="widefat"
                />
            </div>
        @endforeach
    </div>
</div>