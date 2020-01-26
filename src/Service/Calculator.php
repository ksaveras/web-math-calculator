<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\CalculationException;
use App\Model\CalcRequest;
use App\Model\CalcResult;
use Ksaveras\MathCalculator\MathCalculator;

/**
 * Class Calculator.
 */
class Calculator
{
    /**
     * @var MathCalculator
     */
    private $mathCalculator;

    public function __construct(MathCalculator $mathCalculator)
    {
        $this->mathCalculator = $mathCalculator;
    }

    public function calculate(CalcRequest $request): CalcResult
    {
        try {
            $result = $this->mathCalculator->calculate($request->getExpression());

            return (new CalcResult())
                ->setExpression($request->getExpression())
                ->setResult((string) $result);
        } catch (\Exception $exception) {
            throw new CalculationException($exception->getMessage());
        }
    }
}
