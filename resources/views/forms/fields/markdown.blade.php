@php
    /** @var \App\Components\Forms\FormField $field */
@endphp
<div class="btn-toolbar rounded" role="toolbar" aria-label="Editor toolbar">
    <div class="btn-group btn-group-sm" role="group" aria-label="Embeds">
        <button data-ace-action="redo" class="btn" type="button"><i class="fa fa-redo"></i></button>
        <button data-ace-action="undo" class="btn" type="button"><i class="fa fa-undo"></i></button>
    </div>
    <div class="btn-group btn-group-sm" role="group" aria-label="Formatting">
        <button data-ace-action="formatBold" class="btn" type="button"><i class="fa fa-bold"></i></button>
        <button data-ace-action="formatItalic" class="btn" type="button"><i class="fa fa-italic"></i></button>
        <button data-ace-action="formatUnderline" class="btn" type="button"><i class="fa fa-underline"></i></button>
        <button data-ace-action="formatStrikethrough" class="btn" type="button"><i class="fa fa-strikethrough"></i></button>
    </div>
    <div class="btn-group btn-group-sm" role="group" aria-label="Styles">
        <div class="btn-group dropdown with-arrow" role="group">
            <button class="btn btn-sm" data-toggle="dropdown" type="button" id="dropdown-toggle-btn-1" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-heading" aria-hidden="true"></i>
                <span class="sr-only">Open dropdown</span>
            </button>
            <div class="dropdown-menu dropdown-menu-center" aria-labelledby="dropdown-toggle-btn-1">
                <h6 class="dropdown-header">Heading levels</h6>
                <div class="dropdown-divider"></div>
                <a data-ace-action="formatH1" href="#" class="dropdown-item">H1</a>
                <a data-ace-action="formatH2" href="#" class="dropdown-item">H2</a>
                {{--<div class="dropdown-divider"></div>
                <div class="dropdown-content">
                    <button class="btn btn-block" type="button">Button</button>
                </div>--}}
            </div>
        </div>
    </div>
    <div class="btn-group btn-group-sm" role="group" aria-label="Embeds">
        <button data-ace-upload="{{scoped_route('uploads.browse', ['locale' => $locale])}}" class="btn" type="button"><i class="fa fa-photo"></i></button>
        <button class="btn" type="button"><i class="fa fa-link"></i></button>
    </div>
</div>
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
