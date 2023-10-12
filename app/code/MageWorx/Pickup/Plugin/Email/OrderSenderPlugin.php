<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Pickup\Plugin\Email;

use Magento\Sales\Model\Order;

class OrderSenderPlugin
{
    /**
     * @var
     */
    protected $coreRegistry;

    /**
     * OrderSenderPlugin constructor.
     *
     * @param \Magento\Framework\Registry $coreRegistry
     */
    public function __construct(
        \Magento\Framework\Registry $coreRegistry
    ) {
        $this->coreRegistry = $coreRegistry;
    }

    /**
     * @param Order\Email\Sender\OrderSender $subject
     * @param Order $order
     * @param bool $forceSyncMode
     * @return array
     */
    public function beforeSend(
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


}
