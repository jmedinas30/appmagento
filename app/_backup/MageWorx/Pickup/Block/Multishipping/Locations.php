<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Pickup\Block\Multishipping;

use MageWorx\Locations\Model\ResourceModel\Location\Collection;

/**
 * Class Locations
 *
 * @package MageWorx\Pickup\Block\Multishipping
 */
class Locations extends \MageWorx\StoreLocator\Block\Locations
{
    /**
     * @return Collection|string[]
     */
    public function getLocations()
    {
        $locations = $this->locationRepository->getListLocationByProductIds(
            $this->getProductIds(),
            null,
            false
        );

        return $locations;
    }
}
