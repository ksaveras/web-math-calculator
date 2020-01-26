<?php

declare(strict_types=1);

namespace App\Tests\ParamConverter;

use App\Model\InputModelInterface;
use App\ParamConverter\InputModelParamConverter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class InputModelParamConverterTest extends TestCase
{
    /**
     * @var InputModelParamConverter
     */
    private $paramConverter;

    /**
     * @var SerializerInterface&MockObject
     */
    private $serializer;

    /**
     * @var ValidatorInterface&MockObject
     */
    private $validator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->validator = $this->createMock(ValidatorInterface::class);

        $this->paramConverter = new InputModelParamConverter($this->serializer, $this->validator);
    }

    public function testApply(): void
    {
        $content = '{"title": "testing data"}';
        $inputModel = (new TestingModel())->setTitle('testing data');

        $request = Request::create('', 'POST', [], [], [], ['CONTENT_TYPE' => 'application/json'], $content);

        $this->serializer
            ->expects($this->once())
            ->method('deserialize')
            ->with($content, TestingModel::class, 'json', [])
            ->willReturn($inputModel);

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with($inputModel, null, null)
            ->willReturn(new ConstraintViolationList());

        $configuration = new ParamConverter(
            [
                'name' => 'payload',
                'class' => TestingModel::class,
                'options' => [
                    'validate' => true,
                ],
            ]
        );

        $this->paramConverter->apply($request, $configuration);

        $this->assertTrue($request->attributes->has('payload'));

        $payload = $request->attributes->get('payload');
        $this->assertInstanceOf(TestingModel::class, $payload);
        $this->assertEquals('testing data', $payload->getTitle());
    }

    /**
     * @dataProvider configurationDataProvider
     */
    public function testSupports(ParamConverter $configuration, bool $expected): void
    {
        $this->assertEquals($expected, $this->paramConverter->supports($configuration));
    }

    public function configurationDataProvider(): \Generator
    {
        yield [new ParamConverter([]), false];
        yield [new ParamConverter(['class' => TestingModel::class]), true];
    }
}

class TestingModel implements InputModelInterface
{
    /**
     * @var string
     */
    private $title;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }
}
