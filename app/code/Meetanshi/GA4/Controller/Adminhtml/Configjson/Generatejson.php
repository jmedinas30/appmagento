<?php
// @codingStandardsIgnoreFile
namespace Meetanshi\GA4\Controller\Adminhtml\Configjson;

use Magento\Backend\App\Action;
use Magento\Framework\App\Response\Http;
use Meetanshi\GA4\Model\JsonCreate;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;

/**
 * Class Generatejson
 * @package Meetanshi\GA4\Controller\Adminhtml\Json
 */
class Generatejson extends Action
{
    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;
    /**
     * @var JsonCreate
     */
    protected $jsonCreate;
    /**
     * @var Http
     */
    protected $http;

    /**
     * Generate constructor.
     * @param JsonCreate $jsonCreate
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param Http $http
     */
    public function __construct
    (
        JsonCreate $jsonCreate,
        Context $context,
        JsonFactory $resultJsonFactory,
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
        $params = $this->getRequest()->getParams();
        $data = $this->validateJsonParams($params);
        $jsonFileUrl = null;

        if (sizeof($data) <= 0) {
            try {
                $jsonFileUrl = $this->jsonCreate->generateJsonData(
                    trim($params['account_id']),
                    trim($params['container_id']),
                    trim($params['ua_tracking_id']),
                    trim($params['public_id']));

                $data[] = __('Json was generated successfully. You can download the file by clicking on the Download Json button.');
            } catch (\Exception $ex) {
                $data[] = $ex->getMessage();
            }
        }

        $resultJson = $this->resultJsonFactory->create();
        $resultJson->setData([
            'resMsg' => $data,
            'jsonFileUrl' => $jsonFileUrl
        ]);

        return $resultJson;
    }

    /**
     * @param $params
     * @return array
     */
    protected function validateJsonParams($params)
    {
        $msg = [];
        if (isset($params['account_id'])) {
            $accountId = $params['account_id'];
            if (!strlen(trim($accountId))) {
                $msg[] = __('Account ID must be specified');
            }
        } else {
            $msg[] = __('Account ID not specified');
        }

        if (isset($params['container_id'])) {
            $containerId = $params['container_id'];
            if (!strlen(trim($containerId))) {
                $msg[] = __('Container ID must be specified');
            }
        } else {
            $msg[] = __('Container ID not specified');
        }

        if (isset($params['ua_tracking_id'])) {
            $uaTrackingId = $params['ua_tracking_id'];
            if (!strlen(trim($uaTrackingId))) {
                $msg[] = __('Measurement ID must be specified');
            }
        } else {
            $msg[] = __('Measurement  ID not specified');
        }

        if (isset($params['public_id'])) {
            $publicId = $params['public_id'];
            if (!strlen(trim($publicId))) {
                $msg[] = __('Public ID must be specified');
            }
        } else {
            $msg[] = __('Public ID not specified');
        }

        return $msg;
    }
}
