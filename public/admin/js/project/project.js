

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
    var _token = $( "input[name='_token']" ).val();
    if ( key_id == '' || index == '' )
    {
        return;
    }

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
 * 修改 tag
 */
function tag_change( key_id, tag, tag_name )
{
    /* 替换页面 tag 文本 */
    $( '#dropdown_show_' + key_id ).html( tag_name );

    var _token = $( "input[name='_token']" ).val();
    var data = {
        key_id: key_id,
        tag: tag,
        _token: _token
    }

    var result = [];
    $.ajaxSettings.async = false;
    $.post( "/admin/project/tag_change", data, function( res ) {
        result = res;
    }, 'json' );

    return result;
}

/**
 * 修改 lengthType
 */
function length_change( key_id, length, length_name )
{
    /* 替换页面 */
    $( '#length_show_' + key_id ).html( length_name );

    var _token = $( "input[name='_token']" ).val();
    var data = {
        key_id: key_id,
        length: length,
        _token: _token
    }

    var result = [];
    $.ajaxSettings.async = false;
    $.post( "/admin/project/length_change", data, function( res ) {
        result = res;
    }, 'json' );

    return result;
}

/**
 * 新增 key 时 tag 选择
 */
function tag_change_empty( tag, this_item )
{
    var tag_name = this_item.html();
    this_item.parents( '.source-item' ).find( "span[name='dropdown_show']" ).html( tag_name );
    this_item.parents( '.source-item' ).find( "input[name='tag']" ).val( tag );

    save_key( this_item );
}

function length_change_empty( tag, this_item )
{
    var tag_name = this_item.html();
    this_item.parents( '.source-item' ).find( "span[name='length_show']" ).html( tag_name );
    this_item.parents( '.source-item' ).find( "input[name='length']" ).val( tag );

    save_key( this_item );
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
    var tag_selector = item.find( "input[name='tag']" );
    var length_selector = item.find( "input[name='length']" );
    
    var _token = $( "input[name='_token']" ).val();

    var key = key_selector.val();
    var source = source_selector.val();
    var key_id = key_id_selector.html();
    var tag = tag_selector.val();
    var length = length_selector.val();
    /* 验证数据 */
    if ( ! validator( key, key_selector, source, source_selector ) )
    {
        return;
    }

    var data = ajax_save( project_id, key_id, key, source, tag, length, _token );

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
function ajax_save( project_id, key_id, key, source, tag, length, _token )
{
    var data = {
        project_id: project_id,
        key_id: key_id,
        key: key,
        source: source,
        tag: tag,
        length: length,
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