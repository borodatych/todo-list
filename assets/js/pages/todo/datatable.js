"use strict";
// Class definition

const KTDatatableRemoteAjax = function(){
	// Private functions
	let isAdmin;

	// basic demo
	let ToDoList = function(){

		let columns = [
			{
				field: 'id',
				title: '#',
				sortable: false,
				width: 30,
				type: 'number',
				selector: false,
				textAlign: 'center',
			}, {
				field: 'name',
				title: 'Name',
			}, {
				field: 'status',
				title: 'Status',
				width: 80,
				// callback function support for column rendering
				template: (row) => {
					let status = {
						0: {'title': 'Pending', 'class': 'kt-badge--metal'},
						1: {'title': 'Success', 'class': ' kt-badge--success'},
						'-1': {'title': 'Canceled', 'class': ' kt-badge--danger'},
					};
					return '<span class="kt-badge ' + status[row.completed].class + ' kt-badge--inline kt-badge--pill">' + status[row.completed].title + '</span>';
				},
			}, {
				field: 'create',
				title: 'Add',
				type: 'date',
				format: 'MM/DD/YYYY',
			},{
				field: 'update',
				title: 'Upd',
				type: 'date',
				format: 'MM/DD/YYYY',
			}];
		if ( isAdmin ) {
			columns.push({
				field: 'Actions',
				title: 'Actions',
				sortable: false,
				width: 110,
				overflow: 'visible',
				autoHide: false,
				template: (row) => {
					return '\
						<button class="btn btn-sm btn-clean btn-icon btn-icon-sm btn-action" title="Edit Task" data-id="' + row.id + '" data-action="edit">\
							<i class="flaticon2-edit"></i>\
						</button>\
						<button class="btn btn-sm btn-clean btn-icon btn-icon-sm btn-action" title="Delete Task" data-id="' + row.id + '" data-action="remove">\
							<i class="flaticon2-trash"></i>\
						</button>\
					';
				},
			});
		}

		let datatable = $('.kt_datatable').KTDatatable({
			// datasource definition
			data: {
				type: 'remote',
				source: {
					read: {
						/// https://keenthemes.com/keen/tools/preview/api/datatables/demos/default2.php
						url: '/todo/load',
						map: (raw) => {
							console.log('| .:: TODO LIST LOAD ::. |', raw);
							// sample data mapping
							let dataSet = raw;
							if (typeof raw.items !== 'undefined') {
								dataSet = raw.items;
							}
							return dataSet;
						},
					},
				},
				pageSize: 3,
				/// Можно настроить ajax пагинацию, фильтрацию и сортировку
				serverPaging: false,
				serverFiltering: false,
				serverSorting: false,
			},

			// layout definition
			layout: {
				scroll: false,
				footer: false,
			},

			// column sorting
			sortable: true,

			pagination: true,

			search: {
				input: $('#generalSearch'),
			},

			// columns definition
			columns: columns,

		});

		$('#kt_form_status').on('change', function(){
			datatable.search($(this).val().toLowerCase(), 'completed');
		});

		datatable.on( 'kt-datatable--on-init', function(){
			$('.kt_datatable button.btn-action').on('click',function(){
				let $ths = $(this);
				let id = $ths.data('id');
				let action = $ths.data('action');
				///console.log('| .:: TODO ACTIONS ::. |',{id:id, action:action, this:this, $ths:$ths,});
				if ( 'edit' === action ) {
					TDListForm.editTask(id);
				}
				if ( 'remove' === action ) {
					TDListForm.removeTask(id);
				}
			});
		});

		/// $('#kt_form_status,#kt_form_type').selectpicker();
	};

	return {
		// public functions
		init: function(){
			isAdmin = $('#profileInfo').data('is-admin');
			ToDoList();
		},

	};
}();

jQuery(document).ready(function(){
	KTDatatableRemoteAjax.init();
});
