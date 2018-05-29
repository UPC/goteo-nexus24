<?php
/*
 * This file was originally part of the Goteo Package, as DummyPaymentMethod.
 *
 * (c) Platoniq y Fundación Goteo <fundacion@goteo.org>
 *
 * Currently is part of Nexus24 plugin:
 *
 * (c) 2018 UPC BarcelonaTech
 *
 * For the full copyright and license information, please view the README.md
 * and LICENSE files that was distributed with this source code.
 */

namespace Goteo\Payment\Method;

use Omnipay\Common\Message\ResponseInterface;

use Goteo\Payment\Method\AbstractPaymentMethod;
use Goteo\Library\Currency;
use Goteo\Util\Omnipay\Message\EmptySuccessfulResponse;
use Goteo\Application\App;
use Goteo\Application\AppEvents;
use Goteo\Application\Event\FilterInvestEvent;

class Nexus24PaymentMethod extends AbstractPaymentMethod {
    private $simulating_gateway = false;

    public function getGatewayName() {
        return 'Nexus24';
    }

    public function getName() {
        return "M'hi implico!";
    }

    public function getDesc() {
        return "Passarel·la de pagament de m'hi implico!";
    }

    public function getIcon() {
        return SRC_URL . '/assets/img/pay/nexus24.png';
    }

    public function getDefaultHttpResponse(ResponseInterface $response) {
        if(!$this->simulating_gateway) return null;

        $this->completePurchase();
        return null;
    }

    public function purchase() {
        $this->simulating_gateway = true;
        return new EmptySuccessfulResponse();
    }

    public function completePurchase() {
        // See Omnipay\Dummy\Gateway for details about card number
        $card = 4242424242424242;
        $gateway = $this->getGateway();
        $gateway->setCurrency(Currency::getDefault('id'));
        $invest = $this->getInvest();
        $payment = $gateway->purchase([
                    'amount' => (float) $this->getInvest()->amount,
                    'card' => [
                        'number' => $card,
                        'expiryMonth' => '12',
                        'expiryYear' => '2038',
                        ],
                    'description' => $this->getInvestDescription(),
                    'returnUrl' => $this->getCompleteUrl(),
                    'cancelUrl' => $this->getCompleteUrl(),
        ]);
        $invest->setPayment($card);
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
