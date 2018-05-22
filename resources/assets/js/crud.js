function crud() {
  var _values_id = {};

	this.setCurrentRowId = function (id) {
		var array_id = id.split('_');
		var variable_name = 'crud_' + array_id[0] + '_id';
		old_id = this.getEntityId(array_id[0]);
		if (old_id != '' && old_id != null) {
			$('#' + array_id[0] + '_' + old_id).removeClass('table-active');
			if (old_id == array_id[1]) {
				_values_id[variable_name] = null;
				$('#button_view').addClass('disabled');
				$('#button_edit').addClass('disabled');
				$('#button_enable').addClass('disabled');
				$('#button_remove').addClass('disabled');
				return;
			};
		};
		_values_id[variable_name] = array_id[1];
		$('#button_view').removeClass('disabled');
		$('#button_edit').removeClass('disabled');
		$('#button_enable').removeClass('disabled');
		$('#button_remove').removeClass('disabled');
		
		if ($('#' + id).hasClass('text-muted')) {
			$('#button_enable').text('Habilitar');
		} else {
			$('#button_enable').text('Deshabilitar');
		}
		
		$('#' + id).addClass('table-active');
	};

	this.postForm = function (action, method) {
		//alert('uno ' + $('#method').val());
		$('#_method').val(method);
		//alert('dos ' + $('#method').val());
		$('#actionForm').attr('action', action);
		$("#actionForm").submit();
	};

	this.getEntityId = function (entity) {
		//alert(entity);
		var variable_name = 'crud_' + entity + '_id';
		//alert(_values_id[variable_name]);
		return _values_id[variable_name];
	};
	
	this.create = function (action) {
		//alert(action);
		this.postForm(action, 'GET');
	};

	this.edit = function (entity, action) {
		//alert(entity);
		var id = this.getEntityId(entity);
		if (id == '' || id == null) {
			return 'empty!';
		} else {
			action = action.replace("|id|", id);
			this.postForm(action, 'GET');
		};
	};

	this.view = function (entity, action) {
		//alert(entity);
		var id = this.getEntityId(entity);
		if (id == '' || id == null) {
			return 'empty!';
		} else {
			action = action.replace("|id|", id);
			this.postForm(action, 'GET');
		};
	};
	
	/*
	*Esta funcion asume que el formulario ya ha sido seteado.
	*/
	this.executeAction = function() {
		$("#actionForm").submit();
	};
	
	/*
	*Esta funcion asume que el formulario ya ha sido seteado.
	*/
	this.submitForm = function(formName) {
		$("#" + formName).submit();
	};
	
	$(document).on('show.bs.modal','#confirmation-modal', function (e) {
		entity = $(e.relatedTarget).data('entity');
		var id = crudInstance.getEntityId(entity);
		title = $(e.relatedTarget).data('title');
		$('#header_confirm_title').text(title);
		if (id == '' || id == null) {
			//alert('empty');
			$('#button_confirm_modal').hide();
			selectRowText = $(e.relatedTarget).data('selectrowtext');
			$('#body_div_confirm_modal').text(selectRowText);
			return 'empty!';
		} else {
			dataBody = $(e.relatedTarget).data('body');
			$('#body_div_confirm_modal').text(dataBody);
			var action = $(e.relatedTarget).data('action');
			action = action.replace("|id|", id);
			method = $(e.relatedTarget).data('method');
			$('#_method').val(method);
			$('#id').val(id);
			$('#actionForm').attr('action', action);
			$('#button_confirm_modal').show();
			//alert('Set done: ' + $('#_method').val());
		};
    });
	
	this.postFormPagination = function (page) {
		//alert(page);
		$('#page').val(page);
		//alert('page ' + $('#page').val())
		$("#actionForm").submit();
	};
	
	this.postFormBack = function (form, action, method) {
		$('#_method').val(method);
		$('#'+form).attr('action', action);
		$('#'+form).submit();
	};
};

module.exports = crud;