<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Controller\Adminhtml\Datatransfer;

use Magento\Framework\Controller\ResultFactory;

class ImportExport extends \MageWorx\Locations\Controller\Adminhtml\Datatransfer
{
    /**
     * @var \Magento\ImportExport\Helper\Data
     */
    protected $helperImportExport;

    /**
     * ImportExport constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     * @param \Magento\ImportExport\Helper\Data $helperImportExport
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\ImportExport\Helper\Data $helperImportExport
    ) {
        $this->helperImportExport = $helperImportExport;
        parent::__construct($context, $fileFactory);
    }

    /**
     * Import and export Page
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $this->messageManager->addNoticeMessage(
            $this->helperImportExport->getMaxUploadSizeMessage()
        );

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->prepend(__('Import/Export MageWorx Stores'));

        return $resultPage;
    }
}
