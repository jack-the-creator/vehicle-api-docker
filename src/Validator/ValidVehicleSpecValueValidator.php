<?php

namespace App\Validator;

use App\Entity\VehicleSpec;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ValidVehicleSpecValueValidator extends ConstraintValidator
{
    /**
     * @inheritDoc
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ValidVehicleSpecValue) {
            throw new UnexpectedTypeException($constraint, ValidVehicleSpecValue::class);
        }

        if (!$value instanceof VehicleSpec) {
            return;
        }

        $parameter = $value->getSpecParameter();
        $vehicleSpecValue = $value->getValue();

        switch ($parameter->getDataType()) {
            case 'integer':
            case 'int':
                if (!is_numeric($vehicleSpecValue)) {
                    $this->context->buildViolation($constraint->message)
                        ->setParameter('{{ value }}', (string)$vehicleSpecValue)
                        ->setParameter('{{ parameter }}', $parameter->getName())
                        ->setParameter('{{ type }}', $parameter->getDataType())
                        ->addViolation();
                }
                break;

            case 'string':
                if (is_numeric($vehicleSpecValue)) {
                    $this->context->buildViolation($constraint->message)
                        ->setParameter('{{ value }}', (string)$vehicleSpecValue)
                        ->setParameter('{{ parameter }}', $parameter->getName())
                        ->setParameter('{{ type }}', $parameter->getDataType())
                        ->addViolation();
                }
                break;
            default:
                break;
        }
    }
}
