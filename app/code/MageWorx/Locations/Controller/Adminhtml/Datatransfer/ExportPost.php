<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Controller\Adminhtml\Datatransfer;

use Magento\Framework\App\Filesystem\DirectoryList;
use MageWorx\Locations\Model\Datatransfer\CsvExportHandler;

class ExportPost extends \MageWorx\Locations\Controller\Adminhtml\Datatransfer
{
    /**
     * @var CsvExportHandler
     */
    protected $csvExportHandler;

    /**
     * ExportPost constructor.
     *
     * @param CsvExportHandler $csvExportHandler
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     */
    public function __construct(
        CsvExportHandler $csvExportHandler,
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory
    ) {
        $this->csvExportHandler = $csvExportHandler;
        parent::__construct($context, $fileFactory);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws \Exception
     */
    public function execute()
    {
        try {
            return $this->fileFactory->create(
                $this->csvExportHandler->getFileName(),
                $this->csvExportHandler->export($this->getRequest()->getParam('export_only_default')),
                DirectoryList::VAR_DIR
            );
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, $e->getMessage());
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('mageworx_locations/mageworx_locations/datatransfer', []);

        return $resultRedirect;
    }
}
