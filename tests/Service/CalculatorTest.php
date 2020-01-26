<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Exception\CalculationException;
use App\Model\CalcRequest;
use App\Service\Calculator;
use Ksaveras\MathCalculator\MathCalculator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    /**
     * @var MathCalculator&MockObject
     */
    private $engine;

    /**
     * @var Calculator
     */
    private $calculator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->engine = $this->createMock(MathCalculator::class);

        $this->calculator = new Calculator($this->engine);
    }

    public function testCalculate(): void
    {
        $request = (new CalcRequest())->setExpression('2+6');

        $this->engine
            ->expects($this->once())
            ->method('calculate')
            ->with('2+6')
            ->willReturn(8);

        $result = $this->calculator->calculate($request);

        $this->assertNotNull($result);
        $this->assertEquals($request->getExpression(), $result->getExpression());
        $this->assertEquals(8, $result->getResult());
    }

    public function testCalculateException(): void
    {
        $this->expectException(CalculationException::class);

        $request = (new CalcRequest())->setExpression('4///4');

        $this->engine
            ->expects($this->once())
            ->method('calculate')
            ->with('4///4')
            ->willThrowException(new \RuntimeException('Invalid calculation'));

        $this->calculator->calculate($request);
    }
}
