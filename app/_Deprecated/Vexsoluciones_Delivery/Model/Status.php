<?php
namespace Vexsoluciones\Delivery\Model;

use Magento\Framework\Data\OptionSourceInterface;


class Status implements OptionSourceInterface
{
    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {

        $options = [
                        [
                            'label' => 'Deshabilitado',
                            'value' => 0
                        ],
                        [
                            'label' => 'Habilitado',
                            'value' => 1
                        ]
                    ];
        return $options;
    }
}
