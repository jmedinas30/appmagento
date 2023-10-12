<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\LocationPages\Block\Adminhtml\Config\Field;

class ExtendedComment extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $comment = $element->getComment();
        $config  = $element->getFieldConfig();

        if (isset($config['comment_args'])) {
            $args = $config['comment_args'];
            $element->setComment(__($comment, array_values($args)));
        }

        return parent::render($element);
    }
}
