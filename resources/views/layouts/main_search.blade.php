<form class="form-inline" action="#" method="GET">
  @csrf
  <div class="input-group">
    <input type="search" class="form-control" placeholder="@lang('messages.Search')" aria-label="@lang('messages.Search')" aria-describedby="basic-addon2">
    <div class="input-group-append">
      <button class="btn btn-success" type="submit">@lang('messages.Search')</button>
    </div>
  </div>
</form>
