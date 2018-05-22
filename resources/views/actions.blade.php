<div class="d-flex flex-column border rounded">
	<div class="p-2 bg-info text-white">@lang('messages.Actions')</div>
	@guest
		<div class="p-2 bg-light border-bottom">
			<a class="text-body" href="#">Opciones públicas</a>
		</div>
	@else
		<div class="p-2 bg-light border-bottom">
			<a class="text-body" href="#">ITEM 1</a>
		</div>
	@endguest
</div>
