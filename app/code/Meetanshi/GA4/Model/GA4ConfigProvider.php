<?php

namespace Meetanshi\GA4\Model;

use Meetanshi\GA4\Helper\Data;
use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\UrlInterface;

class GA4ConfigProvider implements ConfigProviderInterface
{
    /**
     * @var Data
     */
    protected $helper;
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * OrderUploadConfigProvider constructor.
     * @param UrlInterface $urlBuilder
     * @param Data $helper
     */
    public function __construct(UrlInterface $urlBuilder, Data $helper)
    {
        $this->urlBuilder = $urlBuilder;
        $this->helper = $helper;
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getConfig()
    {
        return [
            'ga4' => [
                'shipUrl' => $this->urlBuilder->getUrl('ga4/index/shipping'),
                'paymentUrl' => $this->urlBuilder->getUrl('ga4/index/payment'),
                'enabledModule' => $this->check(),
                'couponUrl' => $this->urlBuilder->getUrl('ga4/index/coupon')
            ]
        ];
    }

    /**
     * @return bool
     */
    public function check()
    {
        $moduleEnabled = $this->helper->isEnable();
        if ($moduleEnabled) {
            return true;
        }
        return false;
    }
}
