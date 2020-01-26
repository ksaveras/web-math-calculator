<?php

namespace App\Controller;

use App\Model\CalcRequest;
use App\Service\Calculator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalculatorController extends AbstractController
{
    /**
     * @var Calculator
     */
    private $calculator;

    /**
     * CalculatorController constructor.
     */
    public function __construct(Calculator $calculator)
    {
        $this->calculator = $calculator;
    }

    /**
     * @Route("/calculate", methods={"POST"})
     * @ParamConverter(name="request", options={"validate": true})
     */
    public function calculate(CalcRequest $request): Response
    {
        return $this->json($this->calculator->calculate($request));
    }
}
