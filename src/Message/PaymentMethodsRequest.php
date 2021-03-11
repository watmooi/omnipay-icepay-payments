<?php

namespace Omnipay\IcepayPayments\Message;

use Omnipay\Common\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * The request for getting the transaction status at Icepay.
 */
class PaymentMethodsRequest extends AbstractRequest
{
    /**
     * {@inheritdoc}
     */
    public function getData(): array
    {
        $data = parent::getData();

        $data['ContractProfileId'] = $this->getContractProfileId();

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function sendData($data): ResponseInterface
    {

        $this->sendRequest(
            Request::METHOD_GET,
            sprintf(
                '/paymentmethods?ContractProfileId=%s',
                $this->getContractProfileId()
            )
        );

        return new PaymentMethodsResponse(
            $this,
            $this->getResponseBody(),
            $this->getResponse()->getStatusCode()
        );
    }
}
