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

    /**
     * @return string
     */
    public function getExpression(): string
    {
        return $this->expression;
    }

    /**
     * @param string $expression
     *
     * @return $this
     */
    public function setExpression(string $expression): self
    {
        $this->expression = $expression;

        return $this;
    }

    /**
     * @return string
     */
    public function getResult(): string
    {
        return $this->result;
    }

    /**
     * @param string $result
     *
     * @return $this
     */
    public function setResult(string $result): self
    {
        $this->result = $result;

        return $this;
    }
}
