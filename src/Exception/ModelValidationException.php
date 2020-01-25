<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class ModelValidationException.
 */
class ModelValidationException extends \RuntimeException
{
    /**
     * @var ConstraintViolationListInterface
     */
    private $violationList;

    public function __construct(
        ConstraintViolationListInterface $violationList,
        string $message = 'Validation error',
        int $code = 0,
        \Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);

        $this->violationList = $violationList;
    }

    /**
     * @return ConstraintViolationListInterface
     */
    public function getViolationList(): ConstraintViolationListInterface
    {
        return $this->violationList;
    }
}
