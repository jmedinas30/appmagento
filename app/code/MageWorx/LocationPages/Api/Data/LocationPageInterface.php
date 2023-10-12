<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\LocationPages\Api\Data;

/**
 * Location Page interface
 *
 * @api
 */
interface LocationPageInterface
{
    const CURRENT_LOCATION_PAGE = 'mageworx_locationpages_current_location_page';
    const CACHE_TAG             = 'mageworx_locationpages';

    /**
     * @param \MageWorx\Locations\Api\Data\LocationInterface $location
     * @return $this
     */
    public function setLocation($location);

    /**
     * Get page location
     *
     * @return \MageWorx\Locations\Api\Data\LocationInterface
     */
    public function getLocation();
}
