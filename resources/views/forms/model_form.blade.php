@php
/** @var \App\Forms\ModelForm $form */
    $fields = $form->getFields();
    $model = $form->getModel();
@endphp
@if ($form->hasSubmit())
<form action="{{$form->getAction()}}" method="{{$form->getMethod()}}" class="container-fluid" enctype="multipart/form-data">
    @csrf
@else
<form class="container-fluid">
@endif
    @foreach ($fields as $field)
        <div class="row form-group @if($field->hasErrors()) is-invalid @endif">
            <div class="column col-2 text-right p-5 pr-20">
                <label for="{{$field->getID()}}"
                       class="@if($field->isRequired()) required @endif"
                >{{$field->getTitle()}}</label>
            </div>
            <div class="column col-10">
                @switch($field->getStyle())
                    @case(\App\Forms\ModelField::STYLE_TEXT)
                    <input
                        type="text"
                        @if (!$field->isReadOnly()) name="{{$form->getID()}}[{{$field->getID()}}]" @endif
                        class="form-control" id="{{$field->getID()}}"
                        placeholder="{{$field->getTitle()}}"
                        @if($field->isRequired()) required="required" @endif
                        value="{{$field->getValue($model)}}"
                        @if ($field->isReadOnly()) readonly @endif
                    >
                    @break
                    @case(\App\Forms\ModelField::STYLE_TEXTAREA)
                    <textarea
                        type="text"
                        @if (!$field->isReadOnly()) name="{{$form->getID()}}[{{$field->getID()}}]" @endif
                        class="form-control" id="{{$field->getID()}}"
                        placeholder="{{$field->getTitle()}}"
                        @if($field->isRequired()) required="required" @endif
                        @if ($field->isReadOnly()) readonly @endif
                    >{{$field->getValue($model)}}</textarea>
                    @break
                    @case(\App\Forms\ModelField::STYLE_UPLOAD)
                    <label
                        class="d-flex h-200 @if (!$field->isReadOnly()) btn @endif "
                        for="{{$field->getID()}}"
                        @if (!$field->isReadOnly())
                        data-iframe-modal="{{scoped_route('uploads.browse', ['locale' => $locale])}}"
                        data-iframe-return-id="value{{'@#'.$field->getID()}}"
                        data-iframe-return-url="src{{'@#'.$field->getID()}}-preview"
                        @endif
                    >
                        <img
                            src="{{$field->getValue($model)}}"
                            class="w-full h-200 rounded"
                            id="{{$field->getID()}}-preview"
                            style="object-fit: contain"
                        >
                        <input
                            @if (!$field->isReadOnly()) name="{{$form->getID()}}[{{$field->getID()}}]" @endif
                        type="hidden"
                            id="{{$field->getID()}}"
                            @if($field->isRequired()) required="required" @endif
                            @if ($field->isReadOnly()) readonly @endif
                        >
                    </label>
                    @break
                    @case(\App\Forms\ModelField::STYLE_SELECT)
                    <select
                        @if (!$field->isReadOnly()) name="{{$form->getID()}}[{{$field->getID()}}]" @endif
                    class="form-control"
                        @if ($field->isReadOnly()) readonly @endif
                    >
                        @foreach($field->getOptions() as $key => $label)
                            <option
                                @if($key === $field->getValue($model)) selected @endif
                            value="{{$key}}"
                            >{{$label}}</option>
                        @endforeach
                    </select>
                    @break
                @endswitch
                @if($field->hasErrors())
                    <ul class="invalid-feedback mb-0">
                        @foreach ($field->getErrors() as $error)
                            <li class="mb-0">{{$error}}</li>@endforeach
                    </ul>
                @endif
                @if ($field->hasDescription())
                    <div class="form-text">
                        {!! $field->getDescription() !!}
                    </div>
                @endif
            </div>
        </div>
    @endforeach
    @if ($form->hasSubmit())
    <input class="btn btn-primary btn-block" type="submit" value="Save">
    @endif
    {{--<div class="form-group">
        <label for="password" class="required">Password</label>
        <input type="password" class="form-control" id="password" placeholder="Password" required="required" value="my-password">
        <div class="form-text">
            Must be at least 8 characters long, and contain at least one special character.
        </div>
    </div>
    <div class="form-group is-invalid">
        <label for="confirm-password" class="required">Confirm password</label>
        <div class="invalid-feedback">
            Does not match with the password above.
        </div>
        <input type="password" class="form-control" id="confirm-password" placeholder="Confirm password" required="required" value="mmyy-ppasswordd">
        <div class="form-text">
            Must match the above password exactly.
        </div>
    </div>--}}
@if ($form->hasSubmit()) </form> @else </div> @endif
