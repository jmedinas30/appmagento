<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\StoreLocator\Block\Adminhtml;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use MageWorx\StoreLocator\Model\Source\Layout;

class LayoutPreview extends Field
{
    protected $_template = 'MageWorx_StoreLocator::layout_preview.phtml';

    /**
     * Retrieve Element HTML fragment
     *
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $this->setSettingId($element->getHtmlId());
        $preview = $this->toHtml();

        return parent::_getElementHtml($element) . $preview;
    }

    /**
     * @return array
     */
    public function getPreviewImages()
    {
        if ($this->getSettingId() == 'mageworx_locations_product_page_popup_layout') {
            $path = 'popup';
        } else {
            $path = 'page';
        }

        return [
            Layout::FILTER_LEFT_MAP  => $this->getViewFileUrl(
                'MageWorx_StoreLocator::images/' . $path . '/filter_left_map.png'
            ),
            Layout::FILTER_ON_MAP    => $this->getViewFileUrl(
                'MageWorx_StoreLocator::images/' . $path . '/filter_on_map.png'
            ),
            Layout::LIST_BEFORE_MAP  => $this->getViewFileUrl(
                'MageWorx_StoreLocator::images/' . $path . '/list_before_map.png'
            ),
            Layout::LIST_AFTER_MAP   => $this->getViewFileUrl(
                'MageWorx_StoreLocator::images/' . $path . '/list_after_map.png'
            ),
            Layout::LIST_WITHOUT_MAP => $this->getViewFileUrl(
                'MageWorx_StoreLocator::images/' . $path . '/list_without_map.png'
            ),
        ];
    }
}
