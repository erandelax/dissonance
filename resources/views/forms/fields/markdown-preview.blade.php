@php
/** @var \App\Components\FormFieldMarkdownPreview $field */
@endphp
<div
    id="{{$field->getID()}}"
    data-markdown-preview
    data-markdown-preview-api="{{$field->getMarkdownPreviewApiRoute()}}"
    data-markdown-preview-target="{{$field->getSource()->getID()}}"
    class="form-control h-full w-full"
    style="max-height: 85vh;overflow:auto"
>{!! $field->getValue() !!}</div>
