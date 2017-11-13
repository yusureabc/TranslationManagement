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
        'url' : '/admin/translate/ajaxIndex',
      },
      "pagingType": "full_numbers",
      "orderCellsTop": true,
      "dom" : '<"html5buttons"B>lTfgitp',
      "buttons": [

      ],
      "columns": [
        {
            "data": "id",
            "name" : "id",
        },
        {
            "data": "project_name",
            "name" : "project_name",
            "orderable" : false,
        },
        {
            "data": "language_name",
            "name": "language_name",
            "orderable" : false,
        },
        {
            "data": "status",
            "name": "status",
            "orderable" : false,
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
});