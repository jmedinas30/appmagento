<?php

namespace Vexsoluciones\Pagoefectivo\Model;

use Magento\Framework\Option\ArrayInterface;


class Status implements ArrayInterface
{
    

    public function toOptionArray()
    {
        return [
            [
                'value' => 'processing',
                'label' => __('Processing')
            ],
            [
                'value' => 'complete',
                'label' => __('Complete')
            ]
        ];
    }
}