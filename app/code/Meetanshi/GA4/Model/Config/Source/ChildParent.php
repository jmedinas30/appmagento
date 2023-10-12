<?php

namespace Meetanshi\GA4\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class ChildParent implements ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'child', 'label' => __('Child')],
            ['value' => 'parent', 'label' => __('Parent')]
        ];
    }
}
