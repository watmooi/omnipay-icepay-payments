<?php

namespace Omnipay\IcepayPayments;

use DateTime;
use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\IcepayPayments\Message\CompleteAuthoriseAndCaptureRequest;
use Omnipay\IcepayPayments\Message\CreateTransactionRequest;
use Omnipay\IcepayPayments\Message\RefundRequest;
use Omnipay\IcepayPayments\Message\TransactionStatusRequest;
use Omnipay\IcepayPayments\Message\PaymentMethodsRequest;

/**
 * Icepay gateway for Omnipay.
 *
 * ### Settings
 * - contractProfileId (required): A string provided by Icepay.
 * - secretKey         (required): A string provided by Icepay.
 * - testMode          (optional): Changes the API to the test API. By default false.
 *
 * ### Workflow
 * 1. The authorize() method initializes a new payment and returns with a purchase url.
 * 2. The customer gets redirected to the provided purchase URL to pay with iDEAL or Bancontact.
 * 3. Validate payment by doing a status check.
 *
 * Class Gateway.
 */
class Gateway extends AbstractGateway
{
    /**
     * @var string
     */
    public const API_BASE_URL = 'https://interconnect.icepay.com/api';

    /**
     * @var string
     */
    public const TEST_API_BASE_URL = 'https://acc-interconnect.icepay.com/api';

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public function getName(): string
    {
        return 'Icepay Payments';
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultParameters(): array
    {
        return [
            'contractProfileId' => '',
            'secretKey' => '',
            'testMode' => false,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(array $parameters = []): self
    {
        parent::initialize($parameters);

        $baseUrl = self::API_BASE_URL;
        if ($this->getTestMode()) {
            $baseUrl = self::TEST_API_BASE_URL;
        }

        $this->setBaseUrl($baseUrl);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function createRequest($class, array $parameters)
    {
        $parameters['timestamp'] = new DateTime();

        return parent::createRequest($class, $parameters); // TODO: Change the autogenerated stub
    }

    /**
     * Create an authorize request.
     * This is not an 'authorisation function' as icepay puts it, but a 'transaction function'.
     *
     * @param array $parameters Data to be sent to icepay
     *
     * @return RequestInterface
     */
    public function authorize(array $parameters = []): RequestInterface
    {
        return $this->createRequest(CreateTransactionRequest::class, $parameters);
    }

    /**
     * Create completeAuthorize request.
     * This is not an 'authorisation function' as icepay puts it, but a 'transaction function'.
     *
     * @param array $parameters Data to be sent to icepay
     *
     * @return RequestInterface
     */
    public function completeAuthorize(array $parameters = []): RequestInterface
    {
        return $this->createRequest(CompleteAuthoriseAndCaptureRequest::class, $parameters);
    }

    /**
     * Get the status of the transaction.
     *
     * @param array $options Data to be sent to Icepay
     *
     * @return RequestInterface
     */
    public function fetchTransaction(array $options = []): RequestInterface
    {
        return $this->createRequest(TransactionStatusRequest::class, $options);
    }

    /**
     * Get payment methods.
     *
     * @param array $options
     * @return RequestInterface
     */
    public function fetchPaymentMethods(array $options = []): RequestInterface
    {
        return $this->createRequest(PaymentMethodsRequest::class, $options);
    }

    /**
     * Refund transaction.
     *
     * @param array $parameters Data to be sent to icepay
     *
     * @return RequestInterface
     */
    public function refund(array $parameters = []): RequestInterface
    {
        return $this->createRequest(RefundRequest::class, $parameters);
    }

    /**
     * Create a capture request.
     *
     * @param array $parameters Data to be sent to Icepay
     *
     * @return RequestInterface
     */
    public function capture(array $parameters = []): RequestInterface
    {
        return $this->createRequest(CompleteAuthoriseAndCaptureRequest::class, $parameters);
    }

    /**
     * Returns the base URL of the API.
     *
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->getParameter('baseUrl');
    }

    /**
     * Sets the base URL of the API.
     *
     * @param string $baseUrl
     *
     * @return self
     */
    public function setBaseUrl(string $baseUrl): self
    {
        return $this->setParameter('baseUrl', $baseUrl);
    }

    /**
     * Get Contract Profile Id (also known as the user id).
     *
     * Use the Contract Profile Id assigned by Allied wallet.
     *
     * @return string
     */
    public function getContractProfileId(): string
    {
        return $this->getParameter('contractProfileId');
    }

    /**
     * Set Contract Profile Id (also known as the user id).
     *
     * @param string $contractProfileId
     *
     * @return self
     */
    public function setContractProfileId(string $contractProfileId): self
    {
        return $this->setParameter('contractProfileId', $contractProfileId);
    }

    /**
     * Get Secret Key.
     *
     * @return string
     */
    public function getSecretKey(): string
    {
        return $this->getParameter('secretKey');
    }

    /**
     * Set Secret Key.
     *
     * @param string $secretKey
     *
     * @return self
     */
    public function setSecretKey($secretKey): self
    {
        return $this->setParameter('secretKey', $secretKey);
    }
}
