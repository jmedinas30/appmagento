<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Pickup\Block\Multishipping;

use Magento\Framework\Filter\Sprintf;
use Magento\Framework\View\Element\Template;
use Magento\Quote\Model\Quote\Address;

class FindAStore extends Template
{
    /**
     * @var \Magento\Framework\Filter\DataObject\GridFactory
     */
    protected $gridFilterFactory;
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $session;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * @var array
     */
    protected $sourceItems = [];

    /**
     * FindAStore constructor.
     *
     * @param \Magento\Framework\Filter\DataObject\GridFactory $gridFilterFactory
     * @param \Magento\Multishipping\Model\Checkout\Type\Multishipping $multishipping
     * @param \Magento\Framework\Registry $coreRegistry
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Filter\DataObject\GridFactory $gridFilterFactory,
        \Magento\Multishipping\Model\Checkout\Type\Multishipping $multishipping,
        \Magento\Framework\Registry $coreRegistry,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->gridFilterFactory = $gridFilterFactory;
        $this->multishipping     = $multishipping;
        $this->coreRegistry      = $coreRegistry;
    }

    /**
     * Prepare layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        $this->setTemplate('MageWorx_Pickup::multishipping/find_a_store.phtml');

        return parent::_prepareLayout();
    }

    /**
     * @return int
     */
    public function getAddressId()
    {
        return $this->getAddress() ? $this->getAddress()->getId() : 0;
    }

    /**
     * @param Address $address
     * @return \Magento\Framework\DataObject[]
     */
    public function getAddressItems()
    {
        $items = [];
        if (!$this->getAddress()) {
            return $items = [];
        }

        foreach ($this->getAddress()->getAllItems() as $item) {
            if (!$item->getParentItemId()) {
                $item->setQuoteItem($this->multishipping->getQuote()->getItemById($item->getQuoteItemId()));
                $items[] = $item;
            }
        }

        $itemFilter = $this->gridFilterFactory->create();
        $itemFilter->addFilter(new Sprintf('%d'), 'qty');

        return $itemFilter->filter($items);
    }

    /**
     * @return array
     */
    protected function getAddressData()
    {
        $data = $this->coreRegistry->registry('products_by_address');

        return $data ? $data : [];
    }

    /**
     * @return $this
     */
    protected function registerAddressProducts()
    {
        $data = $this->getAddressData();
        $ids  = [];
        foreach ($this->getAddressItems() as $item) {
            $ids[] = $item->getProductId();
        }
        $data[$this->getAddressId()] = $ids;
        $this->coreRegistry->unregister('products_by_address');
        $this->coreRegistry->register('products_by_address', $data);

        return $this;
    }

    /**
     * @return Template
     */
    protected function _beforeToHtml()
    {
        $this->registerAddressProducts();

        return parent::_beforeToHtml();
    }
}
