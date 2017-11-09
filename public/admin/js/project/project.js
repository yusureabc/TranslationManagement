$(function () {
    $('.i-checks').iCheck({
      checkboxClass: 'icheckbox_square-green',
      radioClass: 'iradio_square-green',
    });
    // 关闭modal清空内容
    $(".modal").on("hidden.bs.modal",function(e){
       $(this).removeData("bs.modal");
    });
    /* 追加行按钮 */
    $( '#append_line' ).click( function() {
        var item_html = "<div class=\"form-group source-item\">\n" +
            "  <label class=\"col-sm-2 control-label\">1</label>\n" +
            "  <div class=\"col-sm-3\">\n" +
            "    <input type=\"text\" class=\"form-control\" name=\"name\" value=\"\" placeholder=\"key\">\n" +
            "  </div>\n" +
            "  <div class=\"col-sm-3\">\n" +
            "    <input type=\"text\" class=\"form-control\" name=\"name\" value=\"\" placeholder=\"源内容\">\n" +
            "  </div>\n" +
            "  <button type=\"button\" class=\"btn btn-default\" aria-label=\"Left Align\">\n" +
            "    <span class=\"glyphicon glyphicon-minus\" aria-hidden=\"true\"></span>\n" +
            "  </button>\n" +
            "  <button type=\"button\" class=\"btn btn-default\" aria-label=\"Left Align\">\n" +
            "    <span class=\"glyphicon glyphicon-saved\" aria-hidden=\"true\"></span>\n" +
            "  </button>\n" +
            "</div>";
        var i = 0;
        for ( i; i < 10; i++ )
        {
            $( '.source-item:last' ).after( item_html );
        }
    });
});