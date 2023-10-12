<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */
declare(strict_types=1);

namespace MageWorx\StoreLocator\Block\LocationInfo;

use Magento\Framework\View\Element\Template;
use MageWorx\Locations\Helper\Data as LocationHelper;

class WorkingHours extends Template
{
    /**
     * @var LocationHelper
     */
    protected $locationHelper;

    /**
     * WorkingHours constructor.
     *
     * @param LocationHelper $locationHelper
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        LocationHelper $locationHelper,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->locationHelper     = $locationHelper;
    }

    /**
     * Prepare URL rewrite editing layout
     *
     * @return $this
     */
    protected function _prepareLayout(): WorkingHours
    {
        parent::_prepareLayout();
        $this->setTemplate('location_info/working_hours.phtml');

        return $this;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function toHtml(): string
    {
        if (!$this->locationHelper->isWorkingHoursEnabled()
                || !$this->getLocation()->getIsWorkingHoursEnabled()
        ) {
            return '';
        }

        return parent::toHtml();
    }
}
