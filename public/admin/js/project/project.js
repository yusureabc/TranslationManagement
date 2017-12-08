var item_html = "<div class=\"form-group source-item\">\n" +
    "                <input type=\"hidden\" name=\"sort\" value=\"0\" onchange=\"sort_change( this.value );\">\n" +
    "                <label name=\"key_id\" class=\"col-sm-2 control-label\"></label>\n" +
    "                <div class=\"col-sm-3\">\n" +
    "                  <input type=\"text\" class=\"form-control\" name=\"key\" value=\"\" placeholder=\"key\">\n" +
    "                </div>\n" +
    "                <div class=\"col-sm-3\">\n" +
    "                  <input type=\"text\" class=\"form-control\" name=\"source\" value=\"\" onchange=\"save_key( $(this) );\" placeholder=\"源语言\">\n" +
    "                </div>\n" +
    "                <button type=\"button\" class=\"btn btn-default\" aria-label=\"Left Align\" title=\"下方插入\" onclick=\"below_insert( $(this) );\">\n" +
    "                  <span class=\"fa fa-plus\" aria-hidden=\"true\"></span>\n" +
    "                </button>\n" +
    "                <button type=\"button\" class=\"btn btn-default\" aria-label=\"Left Align\" title=\"删除\" onclick=\"remove_key( $(this) );\">\n" +
    "                  <span class=\"glyphicon glyphicon-trash\" aria-hidden=\"true\"></span>\n" +
    "                </button>\n" +
    "            </div>";

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
        
        var i = 0;
        for ( i; i < 10; i++ )
        {
            $( '.source-item:last' ).after( item_html );
        }
    });

});

/**
 * 下方插入一行
 */
function below_insert( item_this )
{
    var item = item_this.parents( '.source-item' );
    item.after( item_html );
}

/**
 * 排序
 */
function sort()
{
    var key_id_set = [];
    var sort_set = [];

    $( '.source-item' ).each( function ( index ) {
        var sort_selector = $(this).children("input[name='sort']");
        var sort = sort_selector.val();
        var key_id = $(this).find( "label[name='key_id']" ).html();
        if ( key_id == '' ) return true;

        if ( sort != index )
        {
            sort_selector.val( index );
            key_id_set.push( key_id );
            sort_set.push( index );            
        }
    });

    sort_change( key_id_set, sort_set );
}

/**
 * 改变排序
 */
function sort_change( key_id, index )
{
    console.log( key_id, index );
    var _token = $( "input[name='_token']" ).val();
    var data = {
        key_id: key_id,
        sort: index,
        _token: _token
    }

    var result = [];
    $.ajaxSettings.async = false;
    $.post( "/admin/project/key/sort", data, function( res ) {
        result = res;
    }, 'json' );

    return result;
}

/**
 * 保存翻译 key
 */
function save_key( save )
{
    var project_id = $( '#project_id' ).val();
    var item = save.parents( '.source-item' );
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
            sort();
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

/* 拖动排序 */
$('.ibox-content').DDSort({
    target: '.source-item',       // 示例而用，默认即 li，
    delay: 100,         // 延时处理，默认为 50 ms，防止手抖点击 A 链接无效
    floatStyle: {
        'border': '1px solid #ccc',
        'background-color': '#fff'
    },
    down: function ( left, top ) {
        // console.log( 'down' );
    },
    move: function ( left, top ) {
        // console.log( 'move' );
    },
    /* 鼠标抬起时执行的函数 */
    up: function ( left, top ) {
        sort();
    }
});