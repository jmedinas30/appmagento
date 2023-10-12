<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Pickup\Model\Source;

use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Rate\Result;

class EmailTemplates extends \Magento\Config\Model\Config\Source\Email\Template
{
    /**
     * Generate list of email templates
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = parent::toOptionArray();

        $result[] = ['value' => '0', 'label' => __('Please select'), '__disableTmpl' => true];

        return $result;
    }
}