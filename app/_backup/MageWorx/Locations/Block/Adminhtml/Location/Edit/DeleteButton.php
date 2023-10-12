<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Block\Adminhtml\Location\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class DeleteButton
 */
class DeleteButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return string[]
     */
    public function getButtonData()
    {
        $data       = [];
        $locationId = $this->getLocationId();
        if ($locationId && $this->canRender('delete')) {
            $data = [
                'label'      => __('Delete'),
                'class'      => 'delete',
                'on_click'   => 'deleteConfirm(\'' . __(
                    'Are you sure you want to delete store?'
                ) . '\', \'' . $this->urlBuilder->getUrl('*/*/delete', ['entity_id' => $locationId]) . '\')',
                'sort_order' => 20,
            ];
        }

        return $data;
    }
}
