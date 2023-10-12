<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MageWorx\Locations\Model\Source;

use MageWorx\Locations\Model\Source;
use Magento\Config\Model\Config\Source\Locale\Timezone as ConfigTimezoneOptions;

class Timezone extends Source
{
    const USE_DEFAULT = 'use_default';

    /**
     * @var ConfigTimezoneOptions
     */
    protected $configTimezoneOptions;

    /**
     * Timezone constructor.
     *
     * @param ConfigTimezoneOptions $configTimezoneOptions
     */
    public function __construct(ConfigTimezoneOptions $configTimezoneOptions)
    {
        $this->configTimezoneOptions = $configTimezoneOptions;
    }

    /**
     * @return string[]
     */
    public function toOptionArray()
    {
        return array_merge(
            [['value' => self::USE_DEFAULT, 'label' => __('Use default')]],
            $this->configTimezoneOptions->toOptionArray()
        );
    }
}
