<?php

namespace Omnipay\IcepayPayments\Message;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use Omnipay\Common\Http\Client;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\IcepayPayments\AbstractTestCase;

class RefundRequestTest extends AbstractTestCase
{
    /**
     * @var RefundRequest
     */
    private $request;

    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new RefundRequest($this->httpClient, $this->httpRequest);
        $this->request->setSecretKey('Wabalabadubdub');
        $this->request->setTimestamp(new \DateTime());
        $this->request->setBaseUrl('https://www.superbrave.nl');

        $this->request->setCurrencyCode('EUR');
        $this->request->setAmountInteger(1599);
        $this->request->setContractProfileId('1-4M-4-B1G-B1G-G1RL');
        $this->request->setReference('1N-4-B1G-B1G-W0RLD');
    }

    /**
     * Test a valid request for a refund
     */
    public function testRefundGetDataWithValidValues()
    {
        $expected = [
            'ContractProfileId' => '1-4M-4-B1G-B1G-G1RL',
            'AmountInCents' => 1599,
            'CurrencyCode' => 'EUR',
            'Reference' => '1N-4-B1G-B1G-W0RLD',
        ];

        $actual = $this->request->getData();

        $this->assertEquals($expected['ContractProfileId'], $actual['ContractProfileId']);
        $this->assertEquals($expected['AmountInCents'], $actual['AmountInCents']);
        $this->assertEquals($expected['CurrencyCode'], $actual['CurrencyCode']);
        $this->assertEquals($expected['Reference'], $actual['Reference']);
    }

    /**
     * Test actually sending the data to the client
     */
    public function testSendData()
    {
        $this->request->setTransactionReference('1M-MR-M33533K5-L00K-47-M3');
        $response = $this->request->sendData($this->request->getData());

        $this->assertInstanceOf(RefundResponse::class, $response);

        $expectedRequest = new Request(
            'POST',
            'https://www.superbrave.nl/transaction/1M-MR-M33533K5-L00K-47-M3/refund'
        );

        $this->assertEquals($expectedRequest->getMethod(), $this->clientMock->getLastRequest()->getMethod());
        $this->assertEquals($expectedRequest->getUri(), $this->clientMock->getLastRequest()->getUri());
    }

}
