@php
$actions = $modal->getActions();
@endphp
<div class="modal" id="{{$modal->getID()}}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <a href="#" class="close" role="button" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </a>
            @if($modal->hasTitle())
            <h5 class="modal-title">{!! $modal->getTitle() !!}</h5>
            @endif
            @if ($modal->hasBody())
            <p>
                {!! $modal->getBody() !!}
            </p>
            @endif
            @if (!empty($actions))
            <div class="text-right mt-20">
                @foreach ($actions as $action)
                    @if($action->isCancel())
                    <a href="#" class="btn mr-5" role="button">Close</a>
                    @elseif($action->isModal())
                        @push('modals'){!! $action->getAction()->render() !!}@endpush
                        <a class="btn btn-block" type="button" href="#{{$action->getAction()->getID()}}">{!! $action->getTitle() !!}</a>
                    @else
                        <button
                            class="btn btn-primary"
                            type="button"
                            @if ($action->isForForm())form="{{$action->getForForm()->getID()}}"@endif
                            data-submit-form-action="{{$action->getAction()}}"
                        >{!! $action->getTitle() !!}</button>
                    @endif
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
