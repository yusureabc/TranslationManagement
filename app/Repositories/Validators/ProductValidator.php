<?php

namespace App\Repositories\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class ProductValidator extends LaravelValidator
{

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'name'  => 'required|min:2|max:30',
            'model'  => 'required|min:2|max:30|unique:products,model',
            'code'   => 'required|min:2|max:30',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'name'  => 'required|min:2|max:30',
            'model'  => 'required|min:2|max:30',
            'code'   => 'required|min:2|max:30',
        ],
   ];
}
