<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\LocationPages\Block;

/**
 * Class View
 */
class Map extends \MageWorx\StoreLocator\Block\Map
{

    /**
     * Prepare layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $this->setTemplate('location/map.phtml');

        return $this;
    }
}
