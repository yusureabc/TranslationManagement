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
        'url' : '/admin/language/ajaxIndex',
        'data': function (data) {
            data.project_id = $( '#project_id' ).val();
        }
      },
      "pagingType": "full_numbers",
      "orderCellsTop": true,
      "dom" : '<"html5buttons"B>lTfgitp',
      "buttons": [
        {extend: 'copy',title: 'language'},
        {extend: 'csv',title: 'language'},
        {extend: 'excel', title: 'language'},
        {extend: 'pdf', title: 'language'},
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
            "data": "language",
            "name" : "language",
            "orderable" : false,
        },
        {
            "data": "name",
            "name" : "name",
            "orderable" : false,
        },
        { 
            "data": "submit_at",
            "name": "submit_at",
            "orderable" : true,
        },
        { 
            "data": "download_at",
            "name": "download_at",
            "orderable" : true,
        },
        { 
            "data": "status",
            "name": "status",
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