<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

class UserValidator
{
    public function validateCreate(array $data): array
    {
        $validator = Validation::createValidator();

        $constraints = new Assert\Collection([
            'email' => [
                new Assert\NotBlank(),
                new Assert\Email(),
            ],
            'name' => [
                new Assert\NotBlank(),
                new Assert\Length(['min' => 3]),
            ],
            'password' => [
                new Assert\NotBlank(),
                new Assert\Length(['min' => 6]),
            ],
            'roles' => [
                new Assert\NotBlank(),
                new Assert\Type('array'),
            ],
        ]);

        $violations = $validator->validate($data, $constraints);

        $errors = [];
        foreach ($violations as $violation) {
            $errors[$violation->getPropertyPath()][] = $violation->getMessage();
        }

        return $errors;
    }

    public function validateUpdate(array $data): array
    {
        $validator = Validation::createValidator();

        $baseConstraints = [
            'email' => [
                new Assert\NotBlank(),
                new Assert\Email(),
            ],
            'name' => [
                new Assert\NotBlank(),
                new Assert\Length(['min' => 3]),
            ],
            'roles' => [
                new Assert\NotBlank(),
                new Assert\Type('array'),
            ],
        ];

        if (!empty($data['password'])) {
            $baseConstraints['password'] = new Assert\Length(['min' => 6]);
        }

        $constraints = new Assert\Collection(
            $baseConstraints,
            allowExtraFields: true,
            allowMissingFields: true
        );

        $violations = $validator->validate($data, $constraints);

        $errors = [];
        foreach ($violations as $violation) {
            $errors[$violation->getPropertyPath()][] = $violation->getMessage();
        }

        return $errors;
    }
}