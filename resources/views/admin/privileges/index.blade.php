@extends('admin.layout')
@section('securityContent')
@include('confirmation_modal', ['headerText' => '', 'bodyText' => ''])
    <div class="row justify-content-center">
        <div class="col-md-2">
            @include('admin.actions')
        </div>
		<div class="col-md-10">
            <div class="card">
                <div class="card-header">
					<nav class="navbar navbar-expand-sm  navbar-dark">
						<form id="actionForm" class="form-inline" action="{{$action}}" method="GET">
							@csrf
							<input type="hidden" id="_method" name="_method" value="{{ $method }}">
							<span class="navbar-text text-dark">@lang('messages.Privileges')</span>
							<input type="hidden" id="page" name="page" value="{{ $page }}">
							<input type="hidden" id="id" name="id" value="">
							@foreach ($filters->keys() as $filterKey)
								@if ($filterKey == 'resource_id_filter')
									{{ Form::select($filterKey, $resources, $filters->get($filterKey), ['autofocus', 'placeholder' => 'Pick a resource...', 'class' => 'form-control mr-sm-2'])}}
								@endif
								@if ($filterKey == 'role_id_filter')
									{{ Form::select($filterKey, $roles, $filters->get($filterKey), ['autofocus', 'placeholder' => 'Pick a role...', 'class' => 'form-control mr-sm-2'])}}
								@endif
							@endforeach
							<button class="btn btn-primary" type="submit">@lang('messages.Search')</button>
						</form>
					</nav>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="container">
							@include('admin.status')
							<div class="table-responsive">
								<table class="table table-bordered table-hover">
									<thead>
									  <tr>
										<th>@lang('messages.Resource')</th>
										<th>@lang('messages.Role')</th>
									  </tr>
									</thead>
									<tbody>
										@foreach ($list as $command)
											<tr id="{{$entity}}_{{$command->id}}" onclick="crudInstance.setCurrentRowId('{{$entity}}_{{$command->id}}');">
												<td>{{ $command->resource->display_name }}</td>
												<td>{{ $command->role->name}}</td>
											</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="container">
							<div class="float-left">{{ $list->links() }}</div>
							<div class="float-right">
								@include('admin.buttons', ['btn_new' => true, 'btn_view' => true, 'btn_edit' => false, 'btn_enable' => true, 'btn_remove' => true])
							</div>
						</div>
					</div>
				</div>
			</div>
        </div>
    </div>
</div>
@endsection