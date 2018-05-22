@if ($btn_save)
	<div class="offset-md-9">
		<button type="submit" class="btn btn-primary">@lang('messages.Save')</button>
		<button formnovalidate class="btn btn-outline-secondary" onclick="crudInstance.postFormBack('commandForm', '{{ $actionBack }}', 'GET');return;">@lang('messages.Cancel')</button>
	</div>
@else
	<div class="offset-md-10">
		 <button type="submit" class="btn btn-outline-secondary">@lang('messages.Cancel')</button>
	</div>
@endif
