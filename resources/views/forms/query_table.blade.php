@php
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
                        <th>{{$column->getValue($item)}}</th>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {!! $paginator->render('forms.query_table_pagination', ['batchActions' => $batchActions]) !!}
    </div>
</form>
