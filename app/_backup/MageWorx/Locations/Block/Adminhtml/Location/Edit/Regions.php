<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Block\Adminhtml\Location\Edit;

use MageWorx\Locations\Model\Source\Region;

class Regions extends \Magento\Backend\Block\Template
{
    /**
     * @var Region
     */
    protected $region;

    /**
     * Regions constructor.
     *
     * @param Region $region
     * @param \Magento\Backend\Block\Template\Context $context
     * @param string[] $data
     */
    public function __construct(
        Region $region,
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    ) {
        $this->region = $region;
        parent::__construct($context, $data);
    }

    /**
     * @return string[]
     */
    public function getRegionsByCountry()
    {
        return $this->region->getRegionsData();
    }
}
