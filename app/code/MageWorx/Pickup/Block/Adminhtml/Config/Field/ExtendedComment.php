<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Pickup\Block\Adminhtml\Config\Field;

use MageWorx\Locations\Api\Data\LocationInterface;

class ExtendedComment extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * Use text from 'comment_args' as argument in comment Phrase
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $comment = $element->getComment();
        $element->setComment($comment . ' {{var ' .
                             implode('}}<br/> {{var ', LocationInterface::DATA_FOR_CUSTOMER) . '}}');

        return parent::render($element);
    }
}
