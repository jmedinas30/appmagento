<?php
/**
 * Copyright ©  MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Pickup\Controller\Adminhtml\Ready;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use MageWorx\Pickup\Model\ReadyForPickupSender;

class SendEmail extends Action
{
    const ADMIN_RESOURCE = 'MageWorx_Locations::locations';

    /**
     * @var ReadyForPickupSender
     */
    private $readyForPickupSender;

    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * ReadyForPickup constructor.
     *
     * @param ReadyForPickupSender $readyForPickupSender
     * @param \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
     * @param Context $context
     */
    public function __construct(
        ReadyForPickupSender $readyForPickupSender,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        Context $context
    ) {
        $this->readyForPickupSender = $readyForPickupSender;
        $this->orderRepository = $orderRepository;
        parent::__construct($context);
    }

    /**
     * Edit location
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $orderId = $this->getRequest()->getParam('order_id');

        if ($orderId) {
            try {
                $order = $this->orderRepository->get($orderId);
                $isSent = $this->readyForPickupSender->send($order, true);
                if ($isSent) {
                    $this->messageManager->addSuccessMessage(__('The email has been sent successfully.'));
                } else {
                    $this->messageManager->addErrorMessage(__('The email wasn\'t sent.'));
                }
                $resultRedirect->setPath('sales/order/view', ['order_id' => $orderId]);

                return $resultRedirect;
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $resultRedirect->setPath('sales/order/view', ['order_id' => $orderId]);

                return $resultRedirect;
            }
        }
        $this->messageManager->addErrorMessage(__('Order id is incorrect.'));
        $resultRedirect->setPath('sales/*/');

        return $resultRedirect;
    }
}
