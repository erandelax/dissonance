@php
    /** @var \App\Forms\FormField $field */
@endphp
<select
    @if (!$field->isReadOnly()) name="{{$field->getName()}}" @endif
class="form-control"
    @if ($field->isReadOnly()) readonly @endif
>
    @foreach($field->getOptions() as $key => $label)
        <option
            @if($key === $field->getValue()) selected @endif
        value="{{$key}}"
        >{{$label}}</option>
    @endforeach
</select>
