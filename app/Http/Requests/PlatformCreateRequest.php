<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlatformCreateRequest extends FormRequest
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
            'name'  => 'required|min:2|max:30',
            'slug'  => 'required|min:2|max:30|unique:platforms,slug',
            'url'   => 'required|url',
            'logo'  => 'required|image',
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
            'min'       => trans('validation.min'),
            'max'       => trans('validation.max'),
            'unique'    => trans('validation.unique'),
            'url'       => trans('validation.url'),
            'image'     => trans('validation.image'),
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
            'id'        => trans('admin/platform.model.id'),
            'name'      => trans('admin/platform.model.name'),
            'slug'      => trans('admin/platform.model.slug'),
            'url'       => trans('admin/platform.model.url'),
            'logo'      => trans('admin/platform.model.logo'),
        ];
    }
}
