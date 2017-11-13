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
            "              <label name=\"key_id\" class=\"col-sm-2 control-label\"></label>\n" +
            "              <div class=\"col-sm-3\">\n" +
            "                <input type=\"text\" class=\"form-control\" name=\"key\" value=\"\" placeholder=\"key\">\n" +
            "              </div>\n" +
            "              <div class=\"col-sm-3\">\n" +
            "                <input type=\"text\" class=\"form-control\" name=\"source\" value=\"\" placeholder=\"源内容\">\n" +
            "              </div>\n" +
            "              <button type=\"button\" class=\"btn btn-default\" aria-label=\"Left Align\" title=\"保存\" onclick=\"save_key( $(this) );\">\n" +
            "                <span class=\"glyphicon glyphicon-saved\" aria-hidden=\"true\"></span>\n" +
            "              </button>\n" +
            "              <button type=\"button\" class=\"btn btn-default\" aria-label=\"Left Align\" title=\"删除\" onclick=\"remove_key( $(this) );\">\n" +
            "                <span class=\"glyphicon glyphicon-trash\" aria-hidden=\"true\"></span>\n" +
            "              </button>              \n" +
            "            </div>";
        var i = 0;
        for ( i; i < 10; i++ )
        {
            $( '.source-item:last' ).after( item_html );
        }
    });

});

/**
 * 保存翻译 key
 */
function save_key( save )
{
    var project_id = $( '#project_id' ).val();
    var item = save.parent( '.source-item' );
    var key_selector = item.find( "input[name='key']" );
    var source_selector = item.find( "input[name='source']" );
    var key_id_selector = item.find( "label[name='key_id']" );
    var _token = $( "input[name='_token']" ).val();

    var key = key_selector.val();
    var source = source_selector.val();
    var key_id = key_id_selector.html();
    /* 验证数据 */
    if ( ! validator( key, key_selector, source, source_selector ) )
    {
        return;
    }

    var data = ajax_save( project_id, key_id, key, source, _token );

    if ( data.status == 1 )
    {
        if ( data.id != undefined )
        {
            key_id_selector.html( data.id );
        }
        /* 页面提示 */
        layer.msg( 'Success',{icon: 1, time: 1000} );
        item.addClass( 'has-success' );
        setTimeout( function() { 
            item.removeClass( 'has-success' );
        }, 1000 ); 
    }
    else
    {
        /* 页面提示 */
        layer.msg( 'Error',{icon: 2, time: 1000} );
        item.addClass( 'has-error' );
        setTimeout( function() { 
            item.removeClass( 'has-error' );
        }, 1000 ); 
    }
}

/**
 * 保存 key + 源语言
 */
function ajax_save( project_id, key_id, key, source, _token )
{
    var data = {
        project_id: project_id,
        key_id: key_id,
        key: key,
        source: source,
        _token: _token,
    }

    var result = [];
    $.ajaxSettings.async = false;
    $.post( "", data, function( res ) {
        result = res;
    }, 'json' );

    return result;
}

function validator( key, key_selector, source, source_selector )
{
    if ( key == '' )
    {
        key_selector.parent().addClass( 'has-error' );
        return false;
    }
    else
    {
        key_selector.parent().removeClass( 'has-error' );
    }

    if ( source == '' )
    {
        source_selector.parent().addClass( 'has-error' );
        return false;
    }
    else
    {
        source_selector.parent().removeClass( 'has-error' );
    }

    return true;
}

/**
 * 移除 key + source
 */
function remove_key( remove )
{
    if ( confirm( '确定删除吗？' ) )
    {
        var result = trash_key( remove );
        if ( result == 1 )
        {
            layer.msg( 'Success', {icon: 1, time: 1000} );
        }
        else
        {
            layer.msg( 'Error', {icon: 2, time: 1000} );
        }
    }
}

function trash_key( remove )
{
    /* 获取 ID */
    var item = remove.parent( '.source-item' );

    var key_id_selector = item.find( "label[name='key_id']" );
    var _token = $( "input[name='_token']" ).val();

    var key_id = key_id_selector.html();

    /* 判断 是否存在ID */
    if ( key_id == '' )
    {
        /* 没有ID 直接remove */
        item.remove();
        return 1;
    }
    else
    {
        /* 有ID 调用接口删除，成功remove数据 */
        $.ajaxSettings.async = false;
        var url = '';
        var data = {
            key_id: key_id,
            _method: 'delete',
            _token: _token,
        };

        var result = [];
        $.post( url, data, function( res ) {
            result = res;
            if ( result == 1 ) { item.remove(); }
        } );

        return result;
    }
}