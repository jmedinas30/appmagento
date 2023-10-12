<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Pickup\Plugin;

/**
 * Class ShowLocationsOnMultishipping
 *
 * @package MageWorx\Pickup\Plugin
 */
class ShowLocationsOnMultishipping
{
    public function afterGetItemsBoxTextAfter(
        \Magento\Multishipping\Block\Checkout\Shipping $subject,
        $result,
        \Magento\Framework\DataObject $addressEntity
    ) {
        $groups = $addressEntity->getGroupedAllShippingRates();
        if (empty($groups['mageworxpickup'])) {
            return $result;
        }

        $locationBlock = $subject->getLayout()->createBlock(\MageWorx\Pickup\Block\Multishipping\FindAStore::class);
        $locationBlock->setAddress($addressEntity);

        return $locationBlock->toHtml() . $result;
    }
}
