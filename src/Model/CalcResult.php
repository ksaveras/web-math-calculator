<?php

declare(strict_types=1);

namespace App\Model;

/**
 * Class CalcResult.
 */
class CalcResult
{
    /**
     * @var string
     */
    private $expression;

    /**
     * @var string
     */
    private $result;

    public function getExpression(): string
    {
        return $this->expression;
    }

    /**
     * @return $this
     */
    public function setExpression(string $expression): self
    {
        $this->expression = $expression;

        return $this;
    }

    public function getResult(): string
    {
        return $this->result;
    }

    /**
     * @return $this
     */
    public function setResult(string $result): self
    {
        $this->result = $result;

        return $this;
    }
}
