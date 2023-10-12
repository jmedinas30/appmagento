<?php

namespace Meetanshi\GA4\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class Identifier implements ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => '1', 'label' => __('ID')],
            ['value' => '2', 'label' => __('SKU')]
        ];
    }
}
