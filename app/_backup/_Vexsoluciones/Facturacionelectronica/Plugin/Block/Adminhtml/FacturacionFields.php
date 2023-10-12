<?php

namespace Vexsoluciones\Facturacionelectronica\Plugin\Block\Adminhtml;

use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Block\Adminhtml\Order\View\Info;
use Vexsoluciones\Facturacionelectronica\Api\CheckoutFacturacionFieldsRepositoryInterface;


class FacturacionFields
{

    protected $facturacionFieldsRepository;


    public function __construct(CheckoutFacturacionFieldsRepositoryInterface $facturacionFieldsRepository)
    {
        $this->facturacionFieldsRepository = $facturacionFieldsRepository;
    }

    /**
     * Modify after to html.
     *
     * @param Info   $subject Info
     * @param string $result  Result
     *
     * @return string
     * @throws LocalizedException
     */
    public function afterToHtml(Info $subject, $result) {

        $block = $subject->getLayout()->getBlock('order_facturacion_fields');

        if ($block !== false) {
 

            $result = $result . $block->toHtml();
        }

        return $result;
    }
}
