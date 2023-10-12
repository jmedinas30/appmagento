<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Model\Source;

use MageWorx\Locations\Model\Source;

class Region extends Source
{
    const NO_REGIONS = 'No Regions';
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
     * Return list of country's regions as array
     *
     * @param bool $noEmpty
     * @param string|array|null $country
     * @return array
     */
    public function toOptionArray($noEmpty = false, $country = null)
    {
        $regions = $this->getPreparedRegions($country);

        if ($noEmpty) {
            if (isset($regions[0]) && $regions[0] == self::NO_REGIONS) {
                unset($regions[0]);
            }
        } else {
            if (!$regions) {
                $regions = [];
            }
        }

        return $regions;
    }

    /**
     * @param string $country
     * @return array
     */
    protected function getPreparedRegions($country = false)
    {
        $regions = [];
        $data    = $this->geoIpData->getMaxmindData();

        foreach ($data as $countryId => $countryData) {
            if ($country) {
                if ($countryId == $country) {
                    asort($countryData['regions']);
                    foreach ($countryData['regions'] as $code => $label) {
                        $regions[] = [
                            'value' => $code,
                            'label' => $label
                        ];
                    }

                    break;
                }
            } else {
                foreach ($countryData['regions'] as $code => $label) {
                    $regions[] = [
                        'value' => $code,
                        'label' => $label
                    ];
                }
            }
        }

        if (!$country || empty($regions)) {
            $regions[] = [
                'value' => self::NO_REGIONS,
                'label' => __('No Regions')
            ];
        }

        if (!$country) {
            uasort($regions, [$this, 'sortRegions']);
        }

        return $regions;
    }

    /**
     * @param array $a
     * @param array $b
     * @return int
     */
    protected function sortRegions($a, $b)
    {
        return strcmp($a['label'], $b['label']);
    }

    /**
     * @param null $country
     * @return string
     */
    public function getRegionsData($country = null)
    {
        $regions = [];
        $data    = $this->geoIpData->getMaxmindData();

        if ($country && isset($data[$country])) {
            if (count($data[$country]['regions'])) {
                $regions[$data[$country]] = $data[$country]['regions'];
            } else {
                $regions[$data[$country]] = [self::NO_REGIONS => __('No Regions')];
            }
        } else {
            foreach ($data as $countryId => $countryData) {
                if (count($countryData['regions'])) {
                    $regions[$countryId] = $countryData['regions'];
                } else {
                    $regions[$countryId] = [self::NO_REGIONS => __('No Regions')];
                }
            }
        }


        return json_encode($regions);
    }
}
