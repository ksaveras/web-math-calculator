<?php

declare(strict_types=1);

namespace App\Model;

/**
 * Class CalcRequest.
 *
 * @codeCoverageIgnore
 */
class CalcRequest implements InputModelInterface
{
    /**
     * @var string
     */
    private $expression = '';

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
}
