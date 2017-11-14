$(function () {
    $('.i-checks').iCheck({
      checkboxClass: 'icheckbox_square-green',
      radioClass: 'iradio_square-green',
    });
    // 关闭modal清空内容
    $(".modal").on("hidden.bs.modal",function(e){
       $(this).removeData("bs.modal");
    });
});

/**
 * 保存翻译结果
 */
function save_translated( save )
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