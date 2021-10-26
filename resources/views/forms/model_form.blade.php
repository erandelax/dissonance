@php
$fields = $form->getFields();
$model = $form->getModel();
@endphp
<form action="..." method="..." class="mw-full"> <!-- w-400 = width: 40rem (400px), mw-full = max-width: 100% -->
    @foreach ($fields as $field)
    <div class="form-group @if($field->hasErrors()) is-invalid @endif">
        <label for="username" class="@if($field->isRequired()) required @endif">{{$field->getTitle()}}</label>
        @if($field->hasErrors())
        <div class="invalid-feedback">
            <ul>
                @foreach ($field->getErrors() as $error)
                <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
        @endif
        @switch($field->getStyle())
        @case(\App\Forms\ModelField::STYLE_TEXT)
        <input type="text" class="form-control" id="{{$field->getID()}}" placeholder="{{$field->getTitle()}}" @if($field->isRequired()) required="required" @endif value="{{$field->getValue($model)}}">
        @break
        @case(\App\Forms\ModelField::STYLE_IMAGE)
        <div class="d-flex">
            <img src="{{$field->getValue($model)}}" class="w-300 rounded" id="{{$field->getID()}}-preview" style="object-fit: contain">
            <div class="custom-file p-10">
                <input type="file" data-img-preview="{{$field->getID()}}-preview" class="form-control" id="{{$field->getID()}}" @if($field->isRequired()) required="required" @endif value="{{$field->getValue($model)}}">
                <label for="{{$field->getID()}}">Choose picture</label>
            </div>
        </div>
        @break
        @case(\App\Forms\ModelField::STYLE_SELECT)
        <select class="form-control">
            @foreach($field->getOptions() as $key => $label)
                <option @if($key === $field->getValue($model)) selected @endif value="{{$key}}">{{$label}}</option>
            @endforeach
        </select>
        @break
        @endswitch
        @if ($field->hasDescription())
        <div class="form-text">{!! $field->getDescription() !!}</div>
        @endif
    </div>
    @endforeach
    <input class="btn btn-primary btn-block" type="submit" value="Save">
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
</form>
