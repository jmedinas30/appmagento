<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Block\Adminhtml\Location\Edit;

use Magento\Framework\View\Element\Template;
use MageWorx\Locations\Api\Data\LocationInterface;

class AssignSwitcher extends Template
{
    /**
     * Core registry
     *
     * @var \MageWorx\Locations\Model\Registry
     */
    protected $registry;

    /**
     * AssignSwitcher constructor.
     *
     * @param \MageWorx\Locations\Model\Registry $registry
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        \MageWorx\Locations\Model\Registry $registry,
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        $this->registry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getCurrentSourceCode()
    {
        $sourceCode = '';
        /** @var LocationInterface $location */
        $location = $this->registry->registry(LocationInterface::CURRENT_LOCATION);
        if ($location) {
            $sourceCode = $location->getSourceCode();
        }

        return $sourceCode;
    }
}
