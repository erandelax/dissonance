@php
    /** @var \App\Components\FormField $field */
@endphp
<textarea
    type="text"
    @if (!$field->isReadOnly()) name="{{$field->getName()}}" @endif
    class="form-control" id="{{$field->getID()}}"
    placeholder="{{$field->getTitle()}}"
    @if($field->isRequired()) required="required" @endif
    @if ($field->isReadOnly()) readonly @endif
>{{$field->getValue()}}</textarea>
