@php
    /** @var \App\Components\FormField $field */
@endphp
<input
    type="text"
    @if (!$field->isReadOnly()) name="{{$field->getName()}}" @endif
    class="form-control" id="{{$field->getID()}}"
    placeholder="{{$field->getTitle()}}"
    @if($field->isRequired()) required="required" @endif
    value="{{$field->getValue()}}"
    @if ($field->isReadOnly()) readonly @endif
>
