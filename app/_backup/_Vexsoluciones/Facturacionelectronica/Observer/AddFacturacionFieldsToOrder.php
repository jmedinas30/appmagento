<?php


namespace Vexsoluciones\Facturacionelectronica\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Vexsoluciones\Facturacionelectronica\Api\Data\CheckoutFacturacionFieldsInterface;


class AddFacturacionFieldsToOrder implements ObserverInterface
{
    /**
     * Execute observer method.
     *
     * @param Observer $observer Observer
     *
     * @return void
     */
    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $quote = $observer->getEvent()->getQuote();

        $order->setData(
            CheckoutFacturacionFieldsInterface::TIPO_DE_COMPROBANTE,
            $quote->getData(CheckoutFacturacionFieldsInterface::TIPO_DE_COMPROBANTE)
        );

        $order->setData(
            CheckoutFacturacionFieldsInterface::TIPO_DE_DOCUMENTO_CLIENTE,
            $quote->getData(CheckoutFacturacionFieldsInterface::TIPO_DE_DOCUMENTO_CLIENTE)
        );

        $order->setData(
            CheckoutFacturacionFieldsInterface::NUMERO_DOCUMENTO,
            $quote->getData(CheckoutFacturacionFieldsInterface::NUMERO_DOCUMENTO)
        );

        $order->setData(
            CheckoutFacturacionFieldsInterface::CLIENTE_DENOMINACION,
            $quote->getData(CheckoutFacturacionFieldsInterface::CLIENTE_DENOMINACION)
        );

        $order->setData(
            CheckoutFacturacionFieldsInterface::DIRECCION_FISCAL,
            $quote->getData(CheckoutFacturacionFieldsInterface::DIRECCION_FISCAL)
        );

        $order->setData(
            CheckoutFacturacionFieldsInterface::OBSERVACION,
            $quote->getData(CheckoutFacturacionFieldsInterface::OBSERVACION)
        );

    }
}
