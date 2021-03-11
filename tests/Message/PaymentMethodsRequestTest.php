<?php

namespace Omnipay\IcepayPayments\Tests\Message;

use GuzzleHttp\Psr7\Request;
use Omnipay\IcepayPayments\Message\PaymentMethodsRequest;
use Omnipay\IcepayPayments\Message\PaymentMethodsResponse;
use Omnipay\IcepayPayments\Tests\AbstractTestCase;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

/**
 * Class TransactionStatusRequestTest.
 */
class PaymentMethodsRequestTest extends AbstractTestCase
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * Creates a new TransactionStatusRequestTest instance.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new PaymentMethodsRequest($this->httpClient, $this->httpRequest);
        $this->request->setBaseUrl('https://www.superbrave.nl');
        $this->request->setSecretKey('NjRlYjM3MTctOGI1ZC00MDg4LTgxMDgtOTMyMjQ2NzVlNTM4');
        $this->request->setContractProfileId('64eb3717-8b5d-4088-8108-93224675e538');
        $this->request->setTransactionReference('e7ca29c8-f1f4-4a4c-a968-0f9667d0519d');
    }

    /**
     * Tests if TransactionStatusRequestTest::getData validates the basic keys and returns an array of data.
     */
    public function testGetData(): void
    {
        $expectedData = [
            'ContractProfileId' => '64eb3717-8b5d-4088-8108-93224675e538',
        ];
        $this->assertEquals($expectedData, $this->request->getData());
    }

    /**
     * Tests if TransactionStatusRequest::sendData returns a TransactionStatusResponse.
     */
    public function testSendData(): void
    {
        $data = [
        ];
        $response = $this->request->sendData($data);

        $this->assertInstanceOf(PaymentMethodsResponse::class, $response);

        $expectedRequest = new Request(
            SymfonyRequest::METHOD_GET,
            'https://www.superbrave.nl/paymentmethods?ContractProfileId=64eb3717-8b5d-4088-8108-93224675e538'
        );

        $this->assertEquals($expectedRequest->getMethod(), $this->clientMock->getLastRequest()->getMethod());
        $this->assertEquals($expectedRequest->getUri(), $this->clientMock->getLastRequest()->getUri());
    }
}
