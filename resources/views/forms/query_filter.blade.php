@php
    $value = $filter->getValue();
    $errorClass = $filter->hasErrors() ? 'is-invalid' : '';
@endphp
<div style="min-width: 9rem" class="input-group">
    @switch($filter->getStyle())
        @case(\App\Forms\QueryFilter::STYLE_TEXT)
        <input type="text" class="form-control {{$errorClass}}" name="form-filter[{{$filter->getID()}}]"
               value="{{$value}}">
        @break
        @case(\App\Forms\QueryFilter::STYLE_DATE)
        <input type="date" class="form-control {{ $errorClass }}" name="form-filter[{{$filter->getID()}}]" onchange="event.target.form.submit()"
               value="{{$value}}">
        @break
        @case(\App\Forms\QueryFilter::STYLE_OPTIONS)
        <select class="form-control {{$errorClass}}" id="if-6-select" name="form-filter[{{$filter->getID()}}]" onchange="event.target.form.submit()">
            @foreach($filter->getOptions() as $key => $label)
                <option @if($key === $value) selected @endif value="{{$key}}">{{$label}}</option>
            @endforeach
        </select>
        @break
    @endswitch
    @if (\App\Forms\QueryFilter::STYLE_OPTIONS !== $filter->getStyle())
    <div class="input-group-append">
        <button class="btn" type="button" data-name-flush="form-filter[{{$filter->getID()}}]"><i class="fa fa-close"></i></button>
    </div>
    @endif
    @if($filter->hasErrors())
    <span class="invalid-feedback">
    @foreach ($filter->getErrors() as $error)
        {{$error}}
    @endforeach
    </span>
    @endif
</div>
