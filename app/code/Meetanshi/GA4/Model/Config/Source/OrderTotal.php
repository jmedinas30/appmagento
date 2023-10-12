<?php

namespace Meetanshi\GA4\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class OrderTotal implements ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'subtotal', 'label' => __('SubTotal')],
            ['value' => 'grandtotal', 'label' => __('GrandTotal')]
        ];
    }
}
