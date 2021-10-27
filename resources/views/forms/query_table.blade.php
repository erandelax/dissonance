@php
/** @var \App\Forms\QueryTable $table */
/** @var \App\Forms\ModelColumn $column */
$paginator = $table->getPaginator();
$items = $paginator->items();
$columns = $table->getColumns();
$batchActions = $table->getBatchActions();
$notice = $table->getNotice();
@endphp
<!-- Responsive table -->
<form id="{{$table->getID()}}" action="{{request()->url()}}" method="get" class="mw-full" enctype="multipart/form-data">
    <input type="hidden" name="form" value="{{$table->getID()}}">
    <input type="hidden" name="form-action" value="">
    <input type="hidden" name="form-model" value="">
    <input type="submit" style="display: none">
    <div class="card p-0">
        @if ($table->hasNotice())
            <div class="alert {{$notice->getStyle()}}" role="alert">
                @if ($notice->isDismissible())
                    <button class="close" data-dismiss="alert" type="button" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                @endif
                @if ($notice->hasTitle())
                    <h4 class="alert-heading">{{$notice->getTitle()}}</h4>
                @endif
                @if ($notice->hasMessage())
                    {!! $notice->getMessage() !!}
                @endif
            </div>
        @endif
        <div class="table-responsive">
            <table class="table table-inner-bordered">
                <thead>
                    <tr>
                        @if(!empty($batchActions))
                        <th>
                            <div class="form-group">
                                <div class="custom-checkbox">
                                    <input type="checkbox" id="item-all-{{$table->getID()}}">
                                    <label for="item-all-{{$table->getID()}}" data-switch-all="form-item"></label>
                                </div>
                            </div>
                        </th>
                        @endif
                        @foreach($columns as $column)
                        <th>
                            <div>{{$column->getTitle()}}</div>
                            @if ($column->hasFilter())
                                <hr>
                                {!! $column->getFilter()->render() !!}
                            @endif
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                    <tr>
                        @if(!empty($batchActions))
                            <th>
                                <div class="form-group">
                                    <div class="custom-checkbox">
                                        <input type="checkbox" id="item-{{$item->getKey()}}" name="form-item[{{$item->getKey()}}]">
                                        <label for="item-{{$item->getKey()}}"></label>
                                    </div>
                                </div>
                            </th>
                        @endif
                        @foreach($columns as $column)
                        <th>
                            @if ($column->isAttribute())
                                @if ($column->isStyleCallable())
                                    {!! $column->getStyle()($column->getValue($item)) !!}
                                @else
                                    @switch($column->getStyle())
                                        @case(\App\Forms\ModelColumn::STYLE_TEXT)
                                        {{$column->getValue($item)}}
                                        @break
                                        @case(\App\Forms\ModelColumn::STYLE_IMAGE)
                                        <img class="img-fluid w-100" src="{{$column->getValue($item)}}" style="object-fit: contain">
                                        @break
                                    @endswitch
                                @endif
                            @endif
                            @if ($column->hasModelActions())
                            <div class="btn-group" role="group" aria-label="row actions">
                                @foreach ($column->getModelActions() as $modelAction)
                                    @if ($modelAction instanceof \App\Forms\ModelUrlAction)
                                        <a class="btn btn-block {{$modelAction->getStyle()}}" type="button" target="_blank" href="{{$modelAction->makeUrl($item)}}">{!! $modelAction->getTitle() !!}</a>
                                    @elseif($modelAction->isModal())
                                        @push('modals'){!! $modelAction->getAction()->render() !!}@endpush
                                        <a class="btn btn-block {{$modelAction->getStyle()}}" type="button" href="#{{$modelAction->getAction()->getID()}}">{!! $modelAction->getTitle() !!}</a>
                                    @else
                                        <button class="btn btn-block {{$modelAction->getStyle()}}" type="button" data-submit-form-model="{{$item->getKey()}}" data-submit-form-action="{{$modelAction->getAction()}}">{!! $modelAction->getTitle() !!}</button>
                                    @endif
                                @endforeach
                            </div>
                            @endif
                        </th>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {!! $paginator->render('forms.query_table_pagination', ['batchActions' => $batchActions]) !!}
    </div>
</form>
