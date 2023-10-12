<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Pickup\Block\Adminhtml\Order\View;

use Magento\Sales\Model\ConfigInterface;
use MageWorx\Locations\Helper\VersionResolver;

class ReadyForPickupButton extends \Magento\Sales\Block\Adminhtml\Order\View
{
    /**
     * @var \MageWorx\Pickup\Helper\Data
     */
    protected $helperPickup;

    /**
     * ReadyForPickupButton constructor.
     *
     * @param VersionResolver $versionResolver
     * @param \MageWorx\Pickup\Helper\Data $helperPickup
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param ConfigInterface $salesConfigInterface
     * @param \Magento\Sales\Model\Config $salesConfig
     * @param \Magento\Sales\Helper\Reorder $reorderHelper
     * @param array $data
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function __construct(
        VersionResolver $versionResolver,
        \MageWorx\Pickup\Helper\Data $helperPickup,
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        ConfigInterface $salesConfigInterface,
        \Magento\Sales\Model\Config $salesConfig,
        \Magento\Sales\Helper\Reorder $reorderHelper,
        array $data = []
    ) {
        $this->helperPickup = $helperPickup;

        //Check Magento CE > 2.3.3
        if ($versionResolver->checkModuleVersion('Magento_Sales', '102.0.3')) {
            $salesConfig = $salesConfigInterface;
        }

        parent::__construct($context, $registry, $salesConfig, $reorderHelper, $data);
    }

    /**
     * @return $this
     *
     */
    protected function _construct()
    {
        parent::_construct();

        if (!$this->getOrderId()) {
            return $this;
        }

        if (!$this->helperPickup->isReadyForPickupEnabled()) {
            return $this;
        }

        if ($this->getOrder()->getShippingMethod() !== 'mageworxpickup_mageworxpickup') {
            return $this;
        }

        $buttonUrl = $this->_urlBuilder->getUrl(
            'mageworx_pickup/ready/sendEmail',
            ['order_id' => $this->getOrderId()]
        );

        $message = __('Are you sure you want to send a "Ready For Pickup" email to customer?');
        $this->addButton(
            'mw_ready_for_pickup_button',
            [
                'label' => __('Ready For Pickup'),
                'onclick' => "confirmSetLocation(' $message ', ' $buttonUrl ')"
            ]
        );

        return $this;
    }

}
