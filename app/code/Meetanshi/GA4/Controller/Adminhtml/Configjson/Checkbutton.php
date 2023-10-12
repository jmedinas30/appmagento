<?php

namespace Meetanshi\GA4\Controller\Adminhtml\Configjson;

use Magento\Backend\App\Action;
use Magento\Framework\App\Response\Http;

/**
 * Class Checkbutton
 * @package Meetanshi\GA4\Controller\Adminhtml\Json
 */
class Checkbutton extends Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;
    /**
     * @var \Meetanshi\GA4\Model\JsonCreate
     */
    protected $jsonCreate;
    /**
     * @var Http
     */
    protected $http;

    /**
     * Check constructor.
     * @param \Meetanshi\GA4\Model\JsonCreate $jsonCreate
     * @param Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param Http $http
     */
    public function __construct(
        \Meetanshi\GA4\Model\JsonCreate $jsonCreate,
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        Http $http
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->jsonCreate = $jsonCreate;
        $this->http = $http;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        //$params = $this->getRequest()->getParams();
        $response = $this->jsonCreate->getJsonContent();

        if ($response != null) {
            $btn = 1;
        } else {
            $btn = 0;
        }
        $resultJson = $this->resultJsonFactory->create();
        $resultJson->setData([
            'btn' => $btn
        ]);
        return $resultJson;
    }

    /**
     * @return bool
     */
    public function _isAllowed()
    {
        return true;
    }
}
