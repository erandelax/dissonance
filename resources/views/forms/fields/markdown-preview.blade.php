@php
/** @var \App\Forms\FormFieldMarkdownPreview $field */
@endphp
<div
    id="{{$field->getID()}}"
    data-markdown-preview-api="{{$field->getMarkdownPreviewApiRoute()}}"
    data-markdown-preview-target="{{$field->getSource()->getID()}}"
>{!! $field->getValue() !!}</div>
