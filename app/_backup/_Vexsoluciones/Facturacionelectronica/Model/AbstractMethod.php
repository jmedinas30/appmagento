<?php
namespace Vexsoluciones\Facturacionelectronica\Model;

abstract class AbstractMethod extends \Magento\Payment\Model\Method\AbstractMethod
{
    protected $_supportedCurrencyCodes = [
        'USD', 'PEN', 'COP',
    ];

    public function isMethodActive(): bool
    {
        return $this->getConfigData('active');
    }
}