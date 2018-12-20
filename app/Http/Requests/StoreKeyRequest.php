<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKeyRequest extends FormRequest
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
            'key_id' => 'required',
            'sort'   => 'required',
        ];

        return $rules;
    }

    /**
     * 验证信息
     */
    public function messages()
    {
        return [
            'required'  => trans('validation.required'),
        ];
    }

    /**
     * 字段名称
     */
    public function attributes()
    {
        return [
            // 'id'        => trans('admin/project.model.id'),
            // 'name'      => trans('admin/project.model.name'),
            // 'languages' => trans('admin/project.model.translation_language'),
        ];
    }
}