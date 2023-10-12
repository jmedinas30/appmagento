<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Controller\Adminhtml\Datatransfer;

use Magento\Framework\Controller\ResultFactory;

class ImportPost extends \MageWorx\Locations\Controller\Adminhtml\Datatransfer
{
    /**
     * @var \MageWorx\Locations\Model\Datatransfer\CsvImportHandler
     */
    protected $csvImportHandler;

    /**
     * ImportPost constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     * @param \MageWorx\Locations\Model\Datatransfer\CsvImportHandler $csvImportHandler
     * @param \MageWorx\Locations\Api\LocationRepositoryInterface $locationRepository
     * @param \Magento\Framework\Escaper $escaper
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \MageWorx\Locations\Model\Datatransfer\CsvImportHandler $csvImportHandler
    ) {
        $this->csvImportHandler = $csvImportHandler;
        parent::__construct($context, $fileFactory);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRedirectUrl());

        if (!$this->getRequest()->isPost()) {
            $this->addInvalidFileMessage();

            return $resultRedirect;
        }

        $file = $this->getRequest()->getFiles('import_locations_file');

        if (!$file || empty($file['tmp_name'])) {
            $this->addInvalidFileMessage();

            return $resultRedirect;
        }

        try {
            $result = $this->csvImportHandler->importFromCsvFile($file);
            if ($result['created']) {
                $this->messageManager->addSuccessMessage(
                    __('%1 stores were successfully created.', $result['created'])
                );
            }
            if ($result['success']) {
                $this->messageManager->addSuccessMessage(
                    __('%1 rows were successfully imported.', $result['success'])
                );
            }
            if ($result['error']) {
                $this->messageManager->addErrorMessage(
                    __(
                        '%1 errors generated. See system.log file for more information.',
                        $result['error']
                    )
                );
            }
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            if ($e->getMessage()) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
            $this->addInvalidFileMessage();
        }

        return $resultRedirect;
    }

    /**
     * show error
     */
    protected function addInvalidFileMessage()
    {
        $this->messageManager->addErrorMessage(__('Invalid file upload attempt'));
    }
}
