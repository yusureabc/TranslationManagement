<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TranslateUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'translated_content'  => 'required',
        ];

        return $rules;
    }

    /**
     * 验证信息
     * @author Sheldon
     * @date   2017-04-21T14:52:55+0800
     * @return [type]                   [description]
     */
    public function messages()
    {
        return [
            'required'  => trans('validation.required'),
        ];
    }
    /**
     * 字段名称
     * @author Sheldon
     * @date   2017-04-21T14:52:38+0800
     * @return [type]                   [description]
     */
    public function attributes()
    {
        return [
            'translated_content' => trans('admin/project.translated'),
        ];
    }
}
