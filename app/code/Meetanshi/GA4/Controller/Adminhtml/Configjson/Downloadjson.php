<?php

namespace Meetanshi\GA4\Controller\Adminhtml\Configjson;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\FileSystemException;
use Meetanshi\GA4\Model\JsonCreate;

/**
 * Class Downloadjson
 * @package Meetanshi\GA4\Controller\Adminhtml\Json
 */
class Downloadjson extends Action
{
    /**
     * @var Http
     */
    protected $http;
    /**
     * @var JsonCreate
     */
    protected $jsonCreate;
    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;
    /**
     * @var Http\FileFactory
     */
    protected $fileFactory;

    /**
     * Download constructor.
     * @param Context $context
     * @param Http $http
     * @param JsonCreate $jsonCreate
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     * @param Http\FileFactory $fileFactory
     */
    public function __construct(
        Context $context,
        Http $http,
        JsonCreate $jsonCreate,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory
    ) {
        $this->http = $http;
        $this->jsonCreate = $jsonCreate;
        $this->resultRawFactory = $resultRawFactory;
        $this->fileFactory = $fileFactory;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|ResultInterface|void
     * @throws FileSystemException
     */
    public function execute()
    {
        $response = $this->jsonCreate->getJsonContent();
        $fileName = 'GA4Import.json';

        if ($response == null) {
            $response = 'File not found please generate again and download.';
        }
        $this->fileFactory->create(
            $fileName,
            $response,
            DirectoryList::MEDIA,
            'application/octet-stream',
            null
        );
        $resultRaw = $this->resultRawFactory->create();
        $resultRaw->setContents('');
        return $resultRaw;
    }

    /**
     * @return bool
     */
    public function _isAllowed()
    {
        return true;
    }
}
