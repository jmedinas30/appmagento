<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Pickup\Plugin\Email;

use Magento\Sales\Model\Order;

class IdentityPlugin
{
    /**
     * @var
     */
    protected $coreRegistry;

    /**
     * @var \MageWorx\Pickup\Helper\Data
     */
    protected $helper;

    /**
     * IdentityPlugin constructor.
     *
     * @param \MageWorx\Pickup\Helper\Data $helper
     * @param \Magento\Framework\Registry $coreRegistry
     */
    public function __construct(
        \MageWorx\Pickup\Helper\Data $helper,
        \Magento\Framework\Registry $coreRegistry
    ) {
        $this->helper       = $helper;
        $this->coreRegistry = $coreRegistry;
    }

    /**
     * @param Order\Email\Sender\OrderSender $subject
     * @param Order $order
     * @param bool $forceSyncMode
     * @return array
     */
    public function afterSend(
        \Magento\Sales\Model\Order\Email\Sender\OrderSender $subject,
        Order $order,
        $forceSyncMode = false
    ) {
        if ($this->coreRegistry->registry('mw_current_order')) {
            $this->coreRegistry->unregister('mw_current_order');
        }
        $this->coreRegistry->register('mw_current_order', $order);


        return [$order, $forceSyncMode];
    }

    /**
     * @param Order\Email\Container\IdentityInterface $subject
     * @param $result
     * @return mixed
     */
    public function afterGetTemplateId(
        \Magento\Sales\Model\Order\Email\Container\IdentityInterface $subject,
        $result
    ) {
        if ($this->coreRegistry->registry('mw_current_order')) {
            $email  = $this->getEmailTemplate();
            $result = $email ? $email : $result;
        }

        return $result;
    }

    /**
     * @param Order\Email\Container\IdentityInterface $subject
     * @param $result
     * @return mixed
     */
    public function afterGetGuestTemplateId(
        \Magento\Sales\Model\Order\Email\Container\IdentityInterface $subject,
        $result
    ) {
        if ($this->coreRegistry->registry('mw_current_order')) {
            $email  = $this->getEmailTemplate();
            $result = $email ? $email : $result;
        }

        return $result;
    }

    /**
     * @return string
     */
    protected function getEmailTemplate()
    {
        $result = 0;
        $order  = $this->coreRegistry->registry('mw_current_order');
        if ($order->getShippingMethod() == 'mageworxpickup_mageworxpickup' && $this->helper->getEmailTemplate()) {
            $result = $this->helper->getEmailTemplate();
        }

        $this->coreRegistry->unregister('mw_current_order');

        return $result === 0 ? false : $result;
    }


}
