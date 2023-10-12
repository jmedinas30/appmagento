<?php
/**
 * Copyright ©  MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Pickup\Model;

use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Payment\Helper\Data as PaymentHelper;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Address\Renderer;
use Magento\Sales\Model\Order\Email\Container\OrderIdentity;
use Magento\Sales\Model\Order\Email\Container\Template;
use Magento\Sales\Model\Order\Email\SenderBuilder;
use Magento\Sales\Model\ResourceModel\Order as OrderResource;
use MageWorx\Locations\Api\Data\LocationInterface;
use MageWorx\Locations\Api\LocationRepositoryInterface;

class StoreEmailSender extends \Magento\Sales\Model\Order\Email\Sender\OrderSender
{
    /**
     * @var \MageWorx\Pickup\Helper\Data
     */
    protected $helper;

    /**
     * @var LocationRepositoryInterface
     */
    protected $locationRepository;

    /**
     * @var \Magento\Framework\Session\SessionManagerInterface
     */
    protected $sessionManager;

    /**
     * @var \Magento\Framework\Stdlib\CookieManagerInterface
     */
    protected $cookieManager;

    /**
     * StoreEmailSender constructor.
     *
     * @param CookieManagerInterface $cookieManager
     * @param \Magento\Framework\Session\SessionManagerInterface $sessionManager
     * @param LocationRepositoryInterface $locationRepository
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
        CookieManagerInterface $cookieManager,
        \Magento\Framework\Session\SessionManagerInterface $sessionManager,
        LocationRepositoryInterface $locationRepository,
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
        $this->cookieManager      = $cookieManager;
        $this->sessionManager     = $sessionManager;
        $this->helper             = $helper;
        $this->locationRepository = $locationRepository;

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

        if (!$this->helper->getEmailToStoreTemplate()) {
            return false;
        }

        $result = $this->prepareTemplate($order);

        if (!$result) {
            return false;
        }

        /** @var SenderBuilder $sender */
        $sender = $this->getSender();

        if ($notify) {
            $sender->send();
        } else {
            $sender->sendCopyTo();
        }

        return true;
    }

    /**
     * @param Order $order
     * @return bool
     */
    protected function prepareTemplate(Order $order)
    {
        foreach ($order->getAllItems() as $item) {
            $item->setOrder($order);
        }

        parent::prepareTemplate($order);

        $locationId = $this->sessionManager->getData('mageworx_pickup_location_id');

        if (!$locationId) {
            $locationId = $this->cookieManager->getCookie('mageworx_location_id');
        }

        if (!$locationId) {
            $locationId = $order->getMageworxPickupLocationId();
        }

        if (!$locationId) {
            return false;
        }

        /** @var LocationInterface $location */
        $location = $this->locationRepository->getById($locationId);

        if (!$location->getEmail()) {
            return false;
        }

        $this->identityContainer->setCustomerName($location->getName());
        $this->identityContainer->setCustomerEmail($location->getEmail());

        $templateId = $this->helper->getEmailToStoreTemplate();
        $this->templateContainer->setTemplateId($templateId);

        return true;
    }
}
