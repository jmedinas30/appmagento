<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Pickup\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\Store;

class Data extends AbstractHelper
{
    const XML_PATH_EMAIL_TEMPLATE = 'mageworx_locations/general/email_template';

    const XML_PATH_SUCCESS_PAGE_BLOCK = 'mageworx_locations/general/success_page_block';

    const XML_PATH_ENABLE_READY_FOR_PICKUP = 'mageworx_locations/general/enable_ready_for_pickup';

    const XML_PATH_EMAIL_TEMPLATE_READY_FOR_PICKUP = 'mageworx_locations/general/email_template_ready_for_pickup';

    const XML_PATH_ENABLE_EMAIL_TO_STORE = 'mageworx_locations/general/email_to_store';

    const XML_PATH_EMAIL_EMAIL_TO_STORE_TEMPLATE = 'mageworx_locations/general/email_to_store_template';

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Data constructor.
     *
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Helper\Context $context
    ) {
        parent::__construct($context);
        $this->storeManager = $storeManager;
    }

    /**
     * @param int $storeId
     * @return string
     */
    public function getEmailTemplate($storeId = Store::DEFAULT_STORE_ID)
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->getValue(
            self::XML_PATH_EMAIL_TEMPLATE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return string
     */
    public function getSuccessPageBlock($storeId = Store::DEFAULT_STORE_ID)
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->getValue(
            self::XML_PATH_SUCCESS_PAGE_BLOCK,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @return int
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getStoreId()
    {
        return $this->storeManager->getStore()->getId();
    }

    /**
     * @param int $storeId
     * @return string
     */
    public function getReadyForPickupEmailTemplate($storeId = Store::DEFAULT_STORE_ID)
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->getValue(
            self::XML_PATH_EMAIL_TEMPLATE_READY_FOR_PICKUP,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return bool|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function isReadyForPickupEnabled($storeId = 0): ?bool
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLE_READY_FOR_PICKUP,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return string
     */
    public function getEmailToStoreTemplate($storeId = Store::DEFAULT_STORE_ID)
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->getValue(
            self::XML_PATH_EMAIL_EMAIL_TO_STORE_TEMPLATE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return bool|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function isEmailToStoreEnabled($storeId = 0): ?bool
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLE_EMAIL_TO_STORE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
