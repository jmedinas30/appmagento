<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Ui\Component\Listing\Column;

use Magento\Store\Ui\Component\Listing\Column\Store as UiStore;
use \Magento\Store\Model\Store as StoreModel;

/**
 * Class Store
 */
class Store extends UiStore
{
    /**
     * Get data
     *
     * @param array $item
     * @return string
     */
    protected function prepareItem(array $item)
    {
        $content    = '';
        $origStores = '';
        if (isset($item['store_ids'])) {
            $origStores = $item['store_ids'];
        }

        if (is_array($origStores) && empty($origStores)) {
            return '';
        }

        if ($origStores === '') {
            return '';
        }

        $isAll = false;
        if (!is_array($origStores)) {
            $origStores = [$origStores];
        }

        foreach ($origStores as $id) {
            if ($id == StoreModel::DEFAULT_STORE_ID) {
                $isAll = true;
            }
        }

        $data = $this->systemStore->getStoresStructure($isAll, $origStores);

        foreach ($data as $website) {
            $content .= $website['label'] . "<br/>";
            if (!empty($website['children'])) {
                foreach ($website['children'] as $group) {
                    $content .= str_repeat('&nbsp;', 3) . $this->escaper->escapeHtml($group['label']) . "<br/>";
                    foreach ($group['children'] as $store) {
                        $content .= str_repeat('&nbsp;', 6) . $this->escaper->escapeHtml($store['label']) . "<br/>";
                    }
                }
            }
        }

        return $content;
    }
}
