<?php
if(!function_exists('flash_info')){
    function flash_info($result,$successMsg = 'success !',$errorMsg = 'something error !')
    {
        return $result ? flash($successMsg,'success')->important() : flash($errorMsg,'danger')->important();
    }
}

if(!function_exists('getUser')){
    function getUser($guards='')
    {
        return auth($guards)->user();
    }
}

if(!function_exists('getUerId')){
    function getUerId()
    {
        return getUser()->id;
    }
}

/**
 * XML 转 Array
 * @author Yusure  http://yusure.cn
 * @date   2017-11-20
 * @param  [param]
 * @param  [type]     $url [description]
 * @return [type]          [description]
 */
if ( ! function_exists( 'xmlToArray' ) )
{
    function xmlToArray( $url )
    {
        $attribute = [];
        $value = [];

        $reader = new \XMLReader(); 
        $reader->open( $url ); 

        while ( $reader->read() )
        {
            $attribute_name = $reader->getAttribute( 'name' );
            if ( $reader->nodeType == \XMLReader::ELEMENT && $attribute_name )
            {
                $nodeName = $reader->name;
                $attribute[] = $attribute_name;
            }
            
            if ( $reader->nodeType == \XMLReader::TEXT && $nodeName == 'string' )
            {
                $value[] = $reader->value;
            }
        }

        $reader->close(); 

        $attribute_num = count( $attribute );
        $value_num = count( $value );
        if ( $attribute_num != $value_num )
        {
            throw new Exception( "数据不匹配 key:" . $attribute_num . " value:" . $value_num );
        }

        $res = array_combine( $attribute, $value );
        return $res;
    }
}

if ( ! function_exists( 'replace_array_key' ) )
{
    /**
     * 用二维数组的某个元素做key
     */
    function replace_array_key( $data, $field, $append = [] )
    {
        $result = [];
        foreach ( (array)$data as $key => $val )
        {
            $append && $val = $val + $append;

            $result[ $val[$field] ] = $val;
        }

        return $result;
    }
}