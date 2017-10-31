<?php

namespace App\Repositories\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class SalesdataValidator extends LaravelValidator
{

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'num'           => 'required|integer',
            'platform_id'   => 'required|integer|exists:platforms',
            'product_id'    => 'required|integer|exists:products',
            'data_time'     => 'required|date_format:Y-m-d',
            'amount'        => 'required|numeric',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'num'           => 'required|integer',
            'platform_id'   => 'required|integer|exists:platforms',
            'product_id'    => 'required|integer|exists:products',
            'data_time'     => 'required|date_format:Y-m-d',
            'amount'        => 'required|numeric',
        ],
   ];
}
