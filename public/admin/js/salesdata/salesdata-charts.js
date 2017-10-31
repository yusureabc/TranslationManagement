$('#charts_show').on('click',function () {
    var data_time_start = $("#data_time [name='data_time_start']").val();
    var data_time_end = $("#data_time [name='data_time_end']").val();
    var platform_id = $("#platform_id [name='platform_id']").val();
    var product_id = $("#product_id [name='product_id']").val();
    window.location.href = '/admin/salesdata/charts?' + 'platform_id=' + platform_id + '&product_id=' + product_id + '&data_time_start=' + data_time_start + '&data_time_end=' + data_time_end;
});