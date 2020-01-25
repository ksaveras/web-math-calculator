<?php

declare(strict_types=1);

namespace App\Model;

/**
 * Class CalcRequest.
 */
class CalcRequest implements InputModelInterface
{
    /**
     * @var string
     */
    private $expression = '';

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
}
