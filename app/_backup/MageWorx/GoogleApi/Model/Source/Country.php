<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\GoogleApi\Model\Source;

use MageWorx\GoogleApi\Model\Source;

/**
 * Source model for countries
 */
class Country extends Source
{
    /**
     * @var \MageWorx\GeoIP\Helper\Info
     */
    protected $geoIpData;

    /**
     * @param \MageWorx\GeoIP\Helper\Info $geoIpData
     */
    public function __construct(\MageWorx\GeoIP\Helper\Info $geoIpData)
    {
        $this->geoIpData = $geoIpData;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        $options = [];
        $data    = $this->geoIpData->getMaxmindData();
        uasort($data, [$this, 'sortCountry']);
        foreach ($data as $countryId => $countryData) {
            $options[] = [
                'value' => $countryData['value'],
                'label' => $countryData['label']
            ];
        }

        return $options;
    }

    /**
     * @param array $a
     * @param array $b
     * @return int
     */
    protected function sortCountry($a, $b)
    {
        return strcmp($a['label'], $b['label']);
    }
}
