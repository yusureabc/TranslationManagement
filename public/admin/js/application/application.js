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