@php
    /** @var \App\Forms\FormField $field */
@endphp
<input
    type="hidden"
    id="{{$field->getID()}}"
    @if (!$field->isReadOnly()) name="{{$field->getName()}}" @endif
    value="{{$field->getValue()}}"
    @if($field->isRequired()) required="required" @endif
    @if ($field->isReadOnly()) readonly @endif
    placeholder="{{$field->getTitle()}}"
    data-markdown-editor="{{$field->getID()}}-editor"
>
<div id="{{$field->getID()}}-editor">{{$field->getValue()}}</div>
