<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class CalculatorControllerTest.
 *
 * @group functional
 */
class CalculatorControllerTest extends WebTestCase
{
    public function testHomePage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        self::assertResponseIsSuccessful();
    }

    public function testCalcRequest(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/calculate',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode(['expression' => '10 - 5'])
        );

        $response = $client->getResponse();

        self::assertResponseIsSuccessful();
        self::assertResponseHasHeader('content-type');
        self::assertResponseHeaderSame('content-type', 'application/json');
        $this->assertJson($response->getContent());
        $this->assertEquals('{"expression":"10 - 5","result":"5"}', $response->getContent());
    }

    public function testInvalidCalcExpression(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/calculate',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode(['expression' => '1/0'])
        );

        $response = $client->getResponse();

        self::assertResponseStatusCodeSame(422);
        self::assertResponseHasHeader('content-type');
        self::assertResponseHeaderSame('content-type', 'application/json');
        $this->assertJson($response->getContent());
        $this->assertEquals('{"message":"Division by zero","code":0}', $response->getContent());
    }

    public function testInvalidCalcRequest(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/calculate',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode(['expression' => ''])
        );

        $response = $client->getResponse();

        self::assertResponseStatusCodeSame(422);
        self::assertResponseHasHeader('content-type');
        self::assertResponseHeaderSame('content-type', 'application/json');
        $this->assertJson($response->getContent());
        $this->assertEquals(
            '{"message":"Validation error: \u0022expression\u0022 - This value should not be blank.","code":422}',
            $response->getContent()
        );
    }
}
