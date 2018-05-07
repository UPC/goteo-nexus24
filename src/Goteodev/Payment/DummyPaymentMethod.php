<?php
/*
 * This file is part of the Goteo Package.
 *
 * (c) Platoniq y Fundación Goteo <fundacion@goteo.org>
 *
 * For the full copyright and license information, please view the README.md
 * and LICENSE files that was distributed with this source code.
 */

namespace Goteodev\Payment;

use Symfony\Component\HttpFoundation\Response;
use Omnipay\Common\Message\ResponseInterface;

use Goteo\Payment\Method\AbstractPaymentMethod;
use Goteo\Library\Currency;
use Goteo\Util\Omnipay\Message\EmptyFailedResponse;
use Goteo\Util\Omnipay\Message\EmptySuccessfulResponse;
use Goteo\Application\App;
use Goteo\Application\AppEvents;
use Goteo\Application\Event\FilterInvestEvent;

/**
 * This class is just an example and must NOT be used in production
 * Creates a Payment Method that does nothing!
 * Does not use Omnipay
 */
class DummyPaymentMethod extends AbstractPaymentMethod {
    private $simulating_gateway = false;

    public function getGatewayName() {
        return 'Dummy';
    }

    public function getName() {
        return 'Dummy Payment';
    }

    public function getDesc() {
        return 'Not really a payment, just for testing';
    }

    public function getIcon() {
        return SRC_URL . '/assets/img/pay/cash.png';
    }

    public function getDefaultHttpResponse(ResponseInterface $response) {
        if(!$this->simulating_gateway) return null;

        $this->completePurchase();
        return null;
    }

    public function purchase() {
        $this->simulating_gateway = true;
        // return new EmptyFailedResponse();
        return new EmptySuccessfulResponse();
    }

    public function completePurchase() {

        // Let's obtain the gateway and the
        $gateway = $this->getGateway();
        $gateway->setCurrency(Currency::getDefault('id'));
        $request = $this->getRequest();
        $invest = $this->getInvest();
        $payment = $gateway->purchase([
                    'amount' => (float) $this->getInvest()->amount,
                    'card' => [
                        'number' => 4242424242424242,
                        'expiryMonth' => '12',
                        'expiryYear' => '2024',
                        ],
                    'description' => $this->getInvestDescription(),
                    'returnUrl' => $this->getCompleteUrl(),
                    'cancelUrl' => $this->getCompleteUrl(),
        ]);
        // set the dummy card as payment detail data
        $invest->setPayment(4242424242424242);

        return $payment->send();
    }

    public function refundable() {
        return true;
    }

    public function refund() {
        // Any plugin can throw a PaymentException here in order to abort the refund process
        App::dispatch(AppEvents::INVEST_REFUND, new FilterInvestEvent($this->getInvest(), $this));

        return new EmptySuccessfulResponse();
    }
}
