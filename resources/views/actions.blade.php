<div class="d-flex flex-column border rounded">
	<div class="p-2 bg-primary text-white">@lang('messages.Actions')</div>
	@guest
		<div class="p-2 bg-light border-bottom">
			<a class="text-body" href="#">Opciones públicas</a>
		</div>
	@else
		<div class="p-2 bg-light border-bottom">
			<a class="text-body" href="/clients">Clientes</a>
		</div>
		<div class="p-2 bg-light border-bottom">
			<a class="text-body" href="/tokens">Licencias</a>
		</div>
	@endguest
</div>
