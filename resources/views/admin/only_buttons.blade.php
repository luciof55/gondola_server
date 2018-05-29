
	@if ($btn_new && Gate::allows('create', $entity))
		<button id="button_new" type="button" onclick="crudInstance.create('{{route($actionCreate)}}');" class="d-none d-md-block btn btn-primary btn-md"><i class="pr-2 fa fa-plus mr-1"></i>@lang('messages.New')</button>
		<button id="button_new_sm" type="button" onclick="crudInstance.create('{{route($actionCreate)}}');" class="d-block d-md-none btn btn-primary btn-md"><i class="fa fa-plus"></i></button>
	@endif
	@if ($btn_view && Gate::allows('view', $entity))
		<button id="button_view" type="button" onclick="crudInstance.view('{{$entity}}', '{{$actionView}}');" class="d-none d-md-block btn btn-primary btn-md disabled"><i class="pr-2 fa fa-file mr-1"></i>@lang('messages.View')</button>
		<button id="button_view_sm" type="button" onclick="crudInstance.view('{{$entity}}', '{{$actionView}}');" class="d-block d-md-none btn btn-primary btn-md disabled"><i class="fa fa-file"></i></button>
	@endif
	@if ($btn_edit && Gate::allows('edit', $entity))
		<button id="button_edit" type="button" onclick="crudInstance.edit('{{$entity}}', '{{$actionEdit}}');" class="d-none d-md-block btn btn-primary btn-md disabled"><i class="pr-2 fa fa-edit mr-1"></i>@lang('messages.Edit')</button>
		<button id="button_edit_sm" type="button" onclick="crudInstance.edit('{{$entity}}', '{{$actionEdit}}');" class="d-block d-md-none btn btn-primary btn-md disabled"><i class="fa fa-edit"></i></button>
	@endif
	@if ($isSoftDelete && $btn_enable && Gate::allows('enable', $entity))
		<button id="button_enable" data-action="{{$actionEnable}}" data-method="GET" data-entity="{{$entity}}" data-toggle="modal" data-target="#confirmation-modal" data-selectrowtext="@lang('messages.selectrowtext')" data-title="@lang('messages.EnableTitle')" data-body="@lang('messages.EnableRow')" class="btn btn-primary btn-md disabled">@lang('messages.Enable')</button>
	@endif
	@if ($btn_remove && Gate::allows('remove', $entity))
		<button id="button_remove" data-action="{{$actionDelete}}" data-method="GET" data-entity="{{$entity}}" data-toggle="modal" data-target="#confirmation-modal" data-selectrowtext="@lang('messages.selectrowtext')" data-title="@lang('messages.RemoveTitle')" data-body="@lang('messages.RemoveRow')" class="d-none d-md-block btn btn-primary btn-md disabled"><i class="pr-2 fa fa-trash mr-1"></i>@lang('messages.Remove')</button>
		<button id="button_remove_sm" data-action="{{$actionDelete}}" data-method="GET" data-entity="{{$entity}}" data-toggle="modal" data-target="#confirmation-modal" data-selectrowtext="@lang('messages.selectrowtext')" data-title="@lang('messages.RemoveTitle')" data-body="@lang('messages.RemoveRow')" class="d-block d-md-none btn btn-primary btn-md disabled"><i class="fa fa-trash"></i></button>
	@endif
