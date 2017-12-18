$(function () {

});

/**
 * 保存翻译结果
 */
function save_translated( save )
{
    var language_id = $( '#language_id' ).val();
    var item = save.parents( '.source-item' );
    var key_id_selector = item.find( "input[name='key_id']" );
    var translated_selector = item.find( "div[name='translated']" );
    var content_id_selector = item.find( "input[name='content_id']" );

    var _token = $( "input[name='_token']" ).val();

    var key_id = key_id_selector.val();
    var translated = translated_selector.html();
    translated = removeHTMLTag( translated );

    var data = ajax_save( language_id, key_id, translated, _token );

    if ( data.status == 1 )
    {
        content_id_selector.val( data['content_id'] );
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

/**
 * 去除空行 html 标签
 */
function removeHTMLTag( str )
{
    str = str.replace(/<\/?[^>]*>/g,''); //去除HTML tag
    str = str.replace(/[ | ]*\n/g,'\n'); //去除行尾空白
    //str = str.replace(/\n[\s| | ]*\r/g,'\n'); //去除多余空行
    str=str.replace(/&nbsp;/ig,'');//去掉&nbsp;
    return str;
}

/**
 * 评论框
 */
function comments( this_item )
{
    var item = this_item.parents( '.source-item' );
    // var key_id_selector = item.find( "input[name='key_id']" );
    // var translated_selector = item.find( "div[name='translated']" );
    var content_id_selector = item.find( "input[name='content_id']" );
    var content_id = content_id_selector.val();
    var url = '/admin/translate/' + content_id + '/comment';
    layer.open({
      type: 2,
      title: 'Comments',
      skin: 'layui-layer-rim', //加上边框
      area: ['800px', '600px'], //宽高
      content: url
    });
}

/**
 * 翻译标记
 */
function flag( this_item )
{
    var item = this_item.parents( '.source-item' );
    var content_id_selector = item.find( "input[name='content_id']" );
    var content_id = content_id_selector.val();
    var flag_selector = item.find( "span[name='flag']" );
    var class_val = flag_selector.attr( 'class' );
    var flag = 0;
    if ( class_val == 'fa fa-flag' )
    {
        class_val = 'fa fa-flag-o';
        flag = 0;
    }
    else
    {
        class_val = 'fa fa-flag';
        flag = 1;
    }

    var result = [];
    var url = '/admin/translate/' + content_id + '/flag/' + flag;
    $.get( url, function( res ) {
        result = res;
        if ( result == 1 )
        {
            flag_selector.attr( 'class', class_val );
        }
    }, 'json' );
}