@if($orders->get($column_order) == '')
  <a class="dropdown-toggle-none text-white" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">@lang($column_text)</a>
@endif
@if($orders->get($column_order) == 'desc')
  <a class="dropdown-toggle-down text-white" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">@lang($column_text)</a>
@endif
@if($orders->get($column_order) == 'asc')
  <a class="dropdown-toggle-up text-white" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">@lang($column_text)</a>
@endif
<div class="dropdown-menu">
  @if($orders->get($column_order) == '')
    <a class="dropdown-item" onclick="potencialInstance.columnOrder('{{$column_order}}')" href="#">Asc<i class="pointer pl-2 fa fa-chevron-up" ></i></a>
  @endif
  @if($orders->get($column_order) == 'desc')
    <a class="dropdown-item" onclick="potencialInstance.columnOrder('{{$column_order}}')" href="#">Asc<i class="pointer pl-2 fa fa-chevron-down"></i></a>
  @endif
  @if($orders->get($column_order) == 'asc')
    <a class="dropdown-item" onclick="potencialInstance.columnOrder('{{$column_order}}')" href="#">Desc<i class="pointer pl-2 fa fa-chevron-up"></i></a>
  @endif
  <div class="dropdown-divider"></div>
    <a class="dropdown-item" onclick="potencialInstance.removeColumnOrder('{{$column_order}}')" href="#">Quitar orden<i class="pointer pl-2 fa fa-minus-square"></i></a>
  </div>
