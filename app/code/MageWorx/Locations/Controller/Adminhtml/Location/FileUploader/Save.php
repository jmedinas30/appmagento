<?php
/**
 * Copyright ©  MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Controller\Adminhtml\Location\FileUploader;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\MediaStorage\Model\File\Uploader;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Image\AdapterFactory;
use Magento\Framework\Filesystem;
use MageWorx\Locations\Helper\Data;

/**
 * File Uploads Action Controller
 */
class Save extends Action
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var UploaderFactory
     */
    protected $uploaderFactory;

    /**
     * @var AdapterFactory
     */
    protected $adapterFactory;

    /**
     * @var RawFactory
     */
    protected $resultRawFactory;

    /**
     * Authorization level
     */
    const ADMIN_RESOURCE = 'MageWorx_Locations::locations';

    /**
     * Save constructor.
     *
     * @param Filesystem $filesystem
     * @param UploaderFactory $uploaderFactory
     * @param AdapterFactory $adapterFactory
     * @param Data $helper
     * @param RawFactory $resultRawFactory
     * @param Context $context
     */
    public function __construct(
        Filesystem $filesystem,
        UploaderFactory $uploaderFactory,
        AdapterFactory $adapterFactory,
        Data $helper,
        RawFactory $resultRawFactory,
        Context $context
    ) {
        parent::__construct($context);
        $this->filesystem       = $filesystem;
        $this->uploaderFactory  = $uploaderFactory;
        $this->adapterFactory   = $adapterFactory;
        $this->helper           = $helper;
        $this->resultRawFactory = $resultRawFactory;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            /** @var Uploader $uploader */
            $uploader = $this->uploaderFactory->create(['fileId' => 'image_path']);
            $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png', 'svg']);
            /** @var \Magento\Framework\Image\Adapter\AdapterInterface $imageAdapter */
            $imageAdapter = $this->adapterFactory->create();
            $uploader->addValidateCallback(
                'catalog_product_image',
                $imageAdapter,
                'validateUploadFile'
            );
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            /** @var \Magento\Framework\Filesystem\Directory\Read $mediaDirectory */
            $mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
            $result         = $uploader->save($mediaDirectory->getAbsolutePath($this->helper->getBaseMediaPath()));

            $result['url'] = $this->helper->getMediaUrl($result['file']);
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }

        /** @var \Magento\Framework\Controller\Result\Raw $response */
        $response = $this->resultRawFactory->create();
        $response->setHeader('Content-type', 'text/plain');
        $response->setContents(json_encode($result));

        return $response;
    }
}
