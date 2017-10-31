<?php

namespace App\Repositories\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class PlatformValidator extends LaravelValidator
{

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'name'  => 'required|min:2|max:30',
            'slug'  => 'required|min:2|max:30|unique:platforms,slug',
            'url'   => 'required|url',
            'logo'  => 'required|image',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'name'  => 'required|min:2|max:30',
            'slug'  => 'required|min:2|max:30',
            'url'   => 'required|url',
            'logo'  => 'required|image',
        ],
   ];
}
