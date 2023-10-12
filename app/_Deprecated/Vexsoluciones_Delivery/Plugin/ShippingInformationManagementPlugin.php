<?php

namespace Vexsoluciones\Delivery\Plugin;

class ShippingInformationManagementPlugin
{

    protected $quoteRepository;

    public function __construct(
        \Magento\Quote\Model\QuoteRepository $quoteRepository
    ) {
        $this->quoteRepository = $quoteRepository;
    }

 
    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    ) {

        $extensionAttributes = $addressInformation->getExtensionAttributes();
        $coordenadas = $extensionAttributes->getVexcoordenadas();
        $dia = $extensionAttributes->getVexdiaprogramado();
        $hora = $extensionAttributes->getVexhoraprogramado();

        $shippingAddress = $addressInformation->getShippingAddress();
        $shippingAddress->setVexcoordenadas($coordenadas);
        $shippingAddress->setVexdiaprogramado($dia);
        $shippingAddress->setVexhoraprogramado($hora);


    }
}