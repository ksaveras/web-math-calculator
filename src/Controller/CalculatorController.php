<?php

namespace App\Controller;

use App\Exception\CalculationException;
use App\Model\CalcRequest;
use App\Model\CalcResult;
use Ksaveras\MathCalculator\MathCalculator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalculatorController extends AbstractController
{
    /**
     * @var MathCalculator
     */
    private $calculator;

    /**
     * CalculatorController constructor.
     *
     * @param MathCalculator $calculator
     */
    public function __construct(MathCalculator $calculator)
    {
        $this->calculator = $calculator;
    }

    /**
     * @Route("/calculate", methods={"POST"})
     * @ParamConverter(name="request", options={"validate": true})
     */
    public function calculate(CalcRequest $request): Response
    {
        try {
            $result = new CalcResult();
            $result->setExpression($request->getExpression());
            $result->setResult($this->calculator->calculate($request->getExpression()));
        } catch (\Exception $exception) {
            throw new CalculationException($exception->getMessage());
        }

        return $this->json($result);
    }
}
