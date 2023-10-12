<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Source model for countries
 */
class Country implements OptionSourceInterface
{
    /**
     * @var \MageWorx\GeoIP\Helper\Info
     */
    protected $geoIpData;

    /**
     * @var array
     */
    protected $options = [];

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

        foreach ($this->toArray() as $value => $label) {
            $options[] = ['value' => $value, 'label' => $label];
        }

        return $options;
    }

    /**
     * Get options in "key-value" format
     *
     * @return string[]
     */
    public function toArray(): array
    {
        if (empty($this->options)) {
            $data = $this->geoIpData->getMaxmindData();
            uasort($data, [$this, 'sortCountry']);
            foreach ($data as $countryData) {
                $this->options[$countryData['value']] = $countryData['label'];
            }
        }

        return $this->options;
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
