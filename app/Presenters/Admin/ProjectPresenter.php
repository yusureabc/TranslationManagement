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
        if ( ! empty( $projectLanguage ) )
        {
            $projectLanguage = explode( ',', $projectLanguage );
        }

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

    public function showInviteUser( $all_user, $invite_user = [] )
    {
        $html = '';
        foreach ( (array)$all_user as $id => $name )
        {
            $html .= '<label class="checkbox-inline"><div class="i-checks"><label> <input type="checkbox" name="user_id[]" '.$this->checkLanguage( $id, $invite_user ) . ' value="'. $id .'"> '. $name .'</label></div></label>';
        }

        return $html;
    }

    public function showApps( $apps, $app_id = 0 )
    {
        $html = '<div class="i-checks">';
        foreach ( $apps as $app )
        {
            $checked = $app_id == $app->id ? 'checked' : '';

            $html .= "<label class='radio-inline'>";
            $html .=   "<input type=\"radio\" name=\"app_id\" value=\"{$app->id}\" {$checked}>" . $app->name;
            $html .= "</label>";
        }
        $html .= '</div>';

        return $html;
    }

}