<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\StoreLocator\Controller\Location;

use Magento\Framework\App\Action\Action;

/**
 * Class UpdateMainPage
 */
class UpdateMainPage extends Action
{
    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        if (!$this->getRequest()->isAjax()) {
            return;
        }

        $block = $this->_view->getLayout()
                             ->createBlock(\MageWorx\StoreLocator\Block\Locations::class);

        $this->_view->renderLayout();

        $this->getResponse()->setHeader('Content-Type', 'text/html', true);
        $this->getResponse()->setContent($block->toHtml());
    }
}
