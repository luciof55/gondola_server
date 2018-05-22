<form id="actionForm" class="form-inline" action="{{$action}}" method="GET">
	@csrf
	<input type="hidden" id="_method" name="_method" value="{{ $method }}">
	<span class="navbar-text text-dark">@lang($title)</span>
	<input type="hidden" id="page" name="page" value="{{ $page }}">
	<input type="hidden" id="id" name="id" value="">
	<div class="d-flex flex-fill flex-row d-inline-flex bg-light">
		@foreach ($filters->keys() as $filterKey)
			<div class="p-1">
				<input class="form-control mr-sm-2" type="text" placeholder="@lang('messages.Search')" id="{{$filterKey}}" name="{{$filterKey}}" value="{{ $filters->get($filterKey) }}" autofocus>
			</div>
		@endforeach
		<div class="p-1">
			<button class="btn btn-primary" type="submit">@lang('messages.Search')</button>
		</div>
		<div class="p-1">
			<input class="btn btn-primary" type="reset" value="Reset">
		</div>
	</div>
</form>