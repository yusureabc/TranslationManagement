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
    var language_id = $( '#language_id' ).val();
    var item = save.parents( '.source-item' );
    var key_id_selector = item.find( "input[name='key_id']" );
    var translated_selector = item.find( "textarea[name='translated']" );
    var _token = $( "input[name='_token']" ).val();

    var key_id = key_id_selector.val();
    var translated = translated_selector.val();
    /* 验证数据 */
    if ( ! validator( translated, translated_selector ) )
    {
        return;
    }

    var data = ajax_save( language_id, key_id, translated, _token );

    if ( data.status == 1 )
    {
        /* 页面提示 */
        // layer.msg( 'Success',{icon: 1, time: 1000} );
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
 * 保存 翻译完成的结果
 */
function ajax_save( language_id, key_id, translated, _token )
{
    var data = {
        language_id: language_id,
        key_id: key_id,
        translated: translated,
        _method: 'PATCH',
        _token: _token,
    }

    var result = [];
    $.ajaxSettings.async = false;
    $.post( "", data, function( res ) {
        result = res;
    }, 'json' );

    return result;
}

function validator( translated, translated_selector )
{
    if ( translated == '' )
    {
        translated_selector.parent().addClass( 'has-error' );
        return false;
    }
    else
    {
        translated_selector.parent().removeClass( 'has-error' );
    }

    return true;
}