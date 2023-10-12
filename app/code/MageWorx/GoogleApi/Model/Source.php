<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\GoogleApi\Model;

use Magento\Framework\Option\ArrayInterface;

abstract class Source implements ArrayInterface
{
    /**
     * Return array of options as value-label pairs
     *
     * @return string[] Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    abstract public function toOptionArray();

    /**
     * Get options in "key-value" format
     *
     * @return string[]
     */
    public function toArray()
    {
        $_tmpOptions = $this->toOptionArray();
        $_options    = [];
        foreach ($_tmpOptions as $option) {
            $_options[$option['value']] = $option['label'];
        }

        return $_options;
    }
}
