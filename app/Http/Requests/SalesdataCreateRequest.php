<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalesdataCreateRequest extends FormRequest
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
            'num'           => 'required|integer',
            'platform_id'   => 'required|integer|exists:platforms,id',
            'product_id'    => 'required|integer|exists:products,id',
            'data_time'     => 'required|date_format:Y-m-d',
            'amount'        => 'required|numeric',
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
            'required'      => trans('validation.required'),
            'integer'       => trans('validation.integer'),
            'exists'        => trans('validation.exists'),
            'date_format'   => trans('validation.date_format'),
            'numeric'       => trans('validation.numeric'),
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
            'id'                => trans('admin/salesdata.model.id'),
            'num'               => trans('admin/salesdata.model.num'),
            'platform_id'       => trans('admin/salesdata.model.platform_id'),
            'product_id'        => trans('admin/salesdata.model.product_id'),
            'data_time'         => trans('admin/salesdata.model.data_time'),
            'amount'            => trans('admin/salesdata.model.amount'),
        ];
    }
}
