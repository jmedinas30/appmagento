<?php
/**
 * Copyright ©  MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Pickup\Model;

use Magento\Framework\Event\ManagerInterface;
use Magento\Payment\Helper\Data as PaymentHelper;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Address\Renderer;
use Magento\Sales\Model\Order\Email\Container\OrderIdentity;
use Magento\Sales\Model\Order\Email\Container\Template;
use Magento\Sales\Model\Order\Email\SenderBuilder;
use Magento\Sales\Model\ResourceModel\Order as OrderResource;

class ReadyForPickupSender extends \Magento\Sales\Model\Order\Email\Sender\OrderSender
{
    /**
     * @var \MageWorx\Pickup\Helper\Data
     */
    protected $helper;

    /**
     * ReadyForPickupSender constructor.
     *
     * @param \MageWorx\Pickup\Helper\Data $helper
     * @param Template $templateContainer
     * @param OrderIdentity $identityContainer
     * @param Order\Email\SenderBuilderFactory $senderBuilderFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param Renderer $addressRenderer
     * @param PaymentHelper $paymentHelper
     * @param OrderResource $orderResource
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $globalConfig
     * @param ManagerInterface $eventManager
     */
    public function __construct(
        \MageWorx\Pickup\Helper\Data $helper,
        Template $templateContainer,
        OrderIdentity $identityContainer,
        Order\Email\SenderBuilderFactory $senderBuilderFactory,
        \Psr\Log\LoggerInterface $logger,
        Renderer $addressRenderer,
        PaymentHelper $paymentHelper,
        OrderResource $orderResource,
        \Magento\Framework\App\Config\ScopeConfigInterface $globalConfig,
        ManagerInterface $eventManager
    ) {
        $this->helper = $helper;
        parent::__construct(
            $templateContainer,
            $identityContainer,
            $senderBuilderFactory,
            $logger,
            $addressRenderer,
            $paymentHelper,
            $orderResource,
            $globalConfig,
            $eventManager
        );
    }

    /**
     * @param Order $order
     * @param bool $notify
     * @return bool
     */
    public function send(Order $order, $notify = true)
    {
        return $this->checkAndSend($order, $notify);
    }

    /**
     * Send email to customer
     *
     * @param Order $order
     * @param bool $notify
     * @return bool
     */
    protected function checkAndSend(Order $order, $notify = true)
    {
        $this->identityContainer->setStore($order->getStore());

        if (!$this->identityContainer->isEnabled()) {
            return false;
        }

        $templateId = $this->helper->getReadyForPickupEmailTemplate();
        if (!$templateId) {
            return false;
        }

        $this->prepareTemplate($order);
         $this->templateContainer->setTemplateId($templateId);


        /** @var SenderBuilder $sender */
        $sender = $this->getSender();

        if ($notify) {
            $sender->send();
        } else {
            $sender->sendCopyTo();
        }

        return true;
    }
}
