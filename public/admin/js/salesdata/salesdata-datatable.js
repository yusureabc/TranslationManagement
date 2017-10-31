var TableDatatablesAjax = function() {
  var datatableAjax = function(){
    dt = $('.dataTablesAjax');
		ajax_datatable = dt.DataTable({
			"processing": true,
      "serverSide": true,
      "searching" : true,
      "searchDelay": 800,
      "search": {
        "regex": true
      },
      "ajax": {
        'url' : '/admin/salesdata/ajaxIndex',
          'data' : function (data) {
              data.data_time_start = $("#data_time [name='data_time_start']").val();
              data.data_time_end = $("#data_time [name='data_time_end']").val();
              data.platform_id = $("#platform_id [name='platform_id']").val();
              data.product_id = $("#product_id [name='product_id']").val();
              //console.log(data.data_time_start, data.data_time_end, data.platform_id, data.productt_id);
          }
      },
      "pagingType": "full_numbers",
      "orderCellsTop": true,
      "dom" : '<"html5buttons"B>lTgitp',
       "order": [[ 5, 'desc' ]],
      "buttons": [
        {extend: 'copy',title: 'salesdata'},
        {extend: 'csv',title: 'salesdata'},
        {extend: 'excel', title: 'salesdata'},
        {extend: 'pdf', title: 'salesdata'},
        {extend: 'print',
         customize: function (win){
            $(win.document.body).addClass('white-bg');
            $(win.document.body).css('font-size', '10px');
            $(win.document.body).find('table')
                    .addClass('compact')
                    .css('font-size', 'inherit');
          }
        }
      ],
      "columns": [
        {
        	"data": "id",
        	"name" : "id",
      	},
        {
        	"data": "platform",
        	"name" : "platform",
        	"orderable" : false,
        },
        {
        	"data": "product",
        	"name": "product",
        	"orderable" : false,
        },
      {
          "data": "num",
          "name": "num",
          "orderable" : true,
      },
      {
          "data": "amount",
          "name": "amount",
          "orderable" : true,
      },
      {
          "data": "data_time",
          "name": "data_time",
          "orderable" : true,
      },
        { 
        	"data": "created_at",
        	"name": "created_at",
        	"orderable" : true,
        },
        { 
        	"data": "updated_at",
        	"name": "updated_at",
        	"orderable" : true,
        },
        { 
          "data": "actionButton",
          "name": "actionButton",
          "type": "html",
          "orderable" : false,
        },
    	],
      "drawCallback": function( settings ) {
        ajax_datatable.$('.tooltips').tooltip( {
          placement : 'top',
          html : true
        });  
      },
      "language": {
        url: '/admin/i18n'
      }
    });
  };
	return {
		init : datatableAjax
	}
}();
$(function () {
  TableDatatablesAjax.init();
    $('#search_salesdata').click(function () {
        ajax_datatable.draw();
    });
});