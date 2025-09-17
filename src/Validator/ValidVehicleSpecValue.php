<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class ValidVehicleSpecValue extends Constraint
{
    public string $message = 'This value "{{ value }}" is not valid for parameter "{{ parameter }}". Value must be a {{ type }}.';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
