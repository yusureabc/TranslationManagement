<?php
namespace App\Presenters\Admin;

class ProjectPresenter
{

    /**
     * 显示待翻译语言
     * @author Yusure  http://yusure.cn
     * @date   2017-11-06
     * @param  [param]
     * @return [type]     [description]
     */
    public function showLanguages( $projectLanguage = [] )
    {
        $languages = config( 'languages' );

        $html = '';
        foreach ( (array)$languages as $key => $value )
        {
            $html .= '<label class="checkbox-inline"><div class="i-checks"><label> <input type="checkbox" name="languages[]" '.$this->checkLanguage( $key, $projectLanguage ) . ' value="'. $key .'"> '. trans( 'languages.'.$key ) .'</label></div></label>';
        }

        return $html;
    }

    /**
     * 有选中的语言返回 checked
     */
    public function checkLanguage( $key, $projectLanguage = [] )
    {
        if ( in_array( $key, $projectLanguage ) )
        {
            return 'checked';
        }
        else
        {
            return '';
        }
    }

}