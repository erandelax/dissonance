@php
    /** @var \App\Components\Forms\FormField $field */
@endphp
<label
    class="d-flex h-200 @if (!$field->isReadOnly()) btn @endif "
    for="{{$field->getID()}}"
    @if (!$field->isReadOnly())
    data-iframe-modal="{{scoped_route('uploads.browse', ['locale' => $locale])}}"
    data-iframe-return-id="value{{'@#'.$field->getID()}}"
    data-iframe-return-url="src{{'@#'.$field->getID()}}-preview"
    @endif
>
    @php
        $previewUrl = $field->getValue() ?? scoped_route('svg', ['value' => 'N/A']);
        try {
            \Ramsey\Uuid\Uuid::fromString($previewUrl);
            $previewUrl = \App\Models\Upload::find($previewUrl)?->preview_url;
        } catch (\Ramsey\Uuid\Exception\InvalidUuidStringException) {}
    @endphp
    <img
        src="{{$previewUrl}}"
        class="w-full h-200 rounded"
        id="{{$field->getID()}}-preview"
        style="object-fit: contain"
    >
    <input
        @if (!$field->isReadOnly()) name="{{$field->getName()}}" @endif
    type="hidden"
        id="{{$field->getID()}}"
        @if($field->isRequired()) required="required" @endif
        @if ($field->isReadOnly()) readonly @endif
    >
</label>
