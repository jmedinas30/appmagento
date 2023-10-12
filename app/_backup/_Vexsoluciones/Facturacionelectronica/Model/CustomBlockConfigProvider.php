<?php
namespace Vexsoluciones\Facturacionelectronica\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Store\Model\ScopeInterface;

class CustomBlockConfigProvider implements ConfigProviderInterface
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfiguration;


    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfiguration
     * @codeCoverageIgnore
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfiguration
    ) {
        $this->scopeConfiguration = $scopeConfiguration;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        $showHide = [];
        $enabled = $this->scopeConfiguration->getValue('facturacionelectronica_service/general/active', ScopeInterface::SCOPE_STORE);

        $showHide['enabled'] = ($enabled)?true:false;
        return $showHide;
    }
}