<?php

namespace Omnipay\IcepayPayments\Tests\Message;

use Omnipay\IcepayPayments\Message\PaymentMethodsRequest;
use Omnipay\IcepayPayments\Message\PaymentMethodsResponse;
use Omnipay\IcepayPayments\Tests\AbstractTestCase;

/**
 * Class TransactionStatusResponseTest.
 */
class PaymentMethodsResponseTest extends AbstractTestCase
{
    /**
     * @var PaymentMethodsRequest
     */
    private $request;

    /**
     * Creates a new TransactionStatusRequest instance.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new PaymentMethodsRequest($this->httpClient, $this->httpRequest);
        $this->request->setTransactionReference('6e9096aa-7ab8-4cb6-83f6-2f4847e5608a');
    }

    /**
     * Tests if TransactionStatusResponse::isSuccessful will return true with the given json response.
     */
    public function testResponseReturnsSuccessful(): void
    {
        $responseJsonBody = json_decode(file_get_contents(__DIR__.'/../Mocks/PaymentMethodsSuccess.json'), true);
        $response = new PaymentMethodsResponse($this->request, $responseJsonBody, 200);

        $responseData = $response->getData();

        $this->assertTrue($response->isSuccessful());
        $this->assertIsArray($responseData['paymentMethods']);
    }

    /**
     * Tests if TransactionStatusResponse::isSuccessful will return true with the given json response.
     */
    public function testResponseReturnsError(): void
    {
        $responseJsonBody = json_decode(file_get_contents(__DIR__.'/../Mocks/PaymentMethodsFail.json'), true);
        $response = new PaymentMethodsResponse($this->request, $responseJsonBody, 400);

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals('contractProfileId = The value \'aaa95cb1e1c-d8b7-4c72-9b75-d76a89d18f2d\' is not valid. ;', $response->getMessage());
    }
}
