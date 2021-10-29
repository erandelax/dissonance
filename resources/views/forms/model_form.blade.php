@php
/** @var \App\Components\ModelForm $form */
/** @var \App\Components\FormField $field */
    $columns = $form->getColumns();
    $model = $form->getModel();
    $colWidth = floor(12/count($columns));
@endphp
@if ($form->hasSubmit())
<form action="{{$form->getAction()}}" method="{{$form->getMethod()}}" class="container-fluid" enctype="multipart/form-data">
    @csrf
@else
<div class="container-fluid">
@endif
    <div class="row">
    @foreach($columns as $fields)
    <div class="col-{{$colWidth}}">
        @foreach ($fields as $field)
            <div class="row form-group @if($field->hasErrors()) is-invalid @endif @if (!$field->hasLabelColumn()) pl-10 @endif">
                @if ($field->hasLabelColumn())
                <div class="column col-2 text-right p-5 pr-20">
                @endif
                @if ($field->getTitle())
                <label for="{{$field->getID()}}"
                       class="@if($field->isRequired() && !$field->isReadOnly()) required @endif"
                >{{$field->getTitle()}}</label>
                @endif
                @if ($field->hasLabelColumn())
                </div>
                <div class="column col-10">
                @endif
                {!! $field->render() !!}
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

                @if ($field->hasLabelColumn())
                </div>
                @endif
            </div>
        @endforeach
    </div>
    @endforeach
    </div>
    @if ($form->hasSubmit())
    <input class="btn btn-primary btn-block" type="submit" value="Save">
    @endif
@if ($form->hasSubmit()) </div> @else </form> @endif
