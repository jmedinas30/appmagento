<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Pickup\Block\Onepage\Success;

use Magento\Framework\View\Element\Template;
use MageWorx\Locations\Api\Data\LocationInterface;
use MageWorx\Locations\Api\LocationRepositoryInterface;

class Additional extends Template
{
    /**
     * @var \MageWorx\Pickup\Helper\Data
     */
    private $helper;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    private $checkoutSession;

    /**
     * @var LocationRepositoryInterface
     */
    private $locationRepository;

    /**
     * @var \Magento\Cms\Model\GetBlockByIdentifier
     */
    private $getBlock;

    /**
     * @var \Magento\Email\Model\Template\Filter
     */
    private $templateFilter;

    /**
     * Additional constructor.
     *
     * @param \Magento\Email\Model\Template\Filter $templateFilter
     * @param \Magento\Cms\Model\GetBlockByIdentifier $getBlock
     * @param LocationRepositoryInterface $locationRepository
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \MageWorx\Pickup\Helper\Data $helper
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Email\Model\Template\Filter $templateFilter,
        \Magento\Cms\Model\GetBlockByIdentifier $getBlock,
        LocationRepositoryInterface $locationRepository,
        \Magento\Checkout\Model\Session $checkoutSession,
        \MageWorx\Pickup\Helper\Data $helper,
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        $this->templateFilter     = $templateFilter;
        $this->getBlock           = $getBlock;
        $this->locationRepository = $locationRepository;
        $this->checkoutSession    = $checkoutSession;
        $this->helper             = $helper;
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function toHtml()
    {
        $block = $this->helper->getSuccessPageBlock();
        $html  = '';
        if ($block) {
            $order = $this->checkoutSession->getLastRealOrder();
            if ($order->getShippingMethod() === 'mageworxpickup_mageworxpickup') {
                $locationId = $order->getMageworxPickupLocationId();
                /** @var LocationInterface $location */
                $location = $this->locationRepository->getById($locationId);
                $data     = $location->getPreparedDataForCustomer();
                $this->templateFilter->setVariables($data);

                $html = $this->getCmsBlockHtml($block);
            }
        }

        return parent::toHtml() . $html;
    }

    /**
     * @param string $id
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getCmsBlockHtml($id)
    {
        $block = $this->getBlock->execute($id, $this->_storeManager->getStore()->getId());
        $html  = $this->templateFilter->filter($block->getContent());

        return $html;
    }
}