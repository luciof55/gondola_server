
	@if ($btn_new && Gate::allows('create', $entity))
		<button id="button_new" type="button" onclick="crudInstance.create('{{route($actionCreate)}}');" class="btn btn-primary btn-md">@lang('messages.New')</button>
	@endif
	@if ($btn_view && Gate::allows('view', $entity))
		<button id="button_view" type="button" onclick="crudInstance.view('{{$entity}}', '{{$actionView}}');" class="btn btn-primary btn-md disabled">@lang('messages.View')</button>
	@endif
	@if ($btn_edit && Gate::allows('edit', $entity))
		<button id="button_edit" type="button" onclick="crudInstance.edit('{{$entity}}', '{{$actionEdit}}');" class="btn btn-primary btn-md disabled">@lang('messages.Edit')</button>
	@endif
	@if ($isSoftDelete && $btn_enable && Gate::allows('enable', $entity))
		<button id="button_enable" data-action="{{$actionEnable}}" data-method="GET" data-entity="{{$entity}}" data-toggle="modal" data-target="#confirmation-modal" data-selectrowtext="@lang('messages.selectrowtext')" data-title="@lang('messages.EnableTitle')" data-body="@lang('messages.EnableRow')" class="btn btn-primary btn-md disabled">@lang('messages.Enable')</button>
	@endif
	@if ($btn_remove && Gate::allows('remove', $entity))
		<button id="button_remove" data-action="{{$actionDelete}}" data-method="GET" data-entity="{{$entity}}" data-toggle="modal" data-target="#confirmation-modal" data-selectrowtext="@lang('messages.selectrowtext')" data-title="@lang('messages.RemoveTitle')" data-body="@lang('messages.RemoveRow')" class="btn btn-primary btn-md disabled">@lang('messages.Remove')</button>
	@endif
