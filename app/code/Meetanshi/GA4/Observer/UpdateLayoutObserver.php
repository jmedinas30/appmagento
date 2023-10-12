<?php

namespace Meetanshi\GA4\Observer;

use Magento\Framework\Event\ObserverInterface;
use Meetanshi\GA4\Helper\Data;
use Magento\Framework\Event\Observer;
use Magento\Framework\App\Request\Http;

/**
 * Class UpdateLayoutObserver
 * @package Meetanshi\GA4\Observer
 */
class UpdateLayoutObserver implements ObserverInterface
{
    /**
     * @var Data
     */
    protected $helper;
    /**
     * @var Http
     */
    protected $request;

    /**
     * UpdateLayoutObserver constructor.
     * @param Data $data
     * @param Http $request
     */
    public function __construct
    (
        Data $data,
        Http $request
    ) {
        $this->helper = $data;
        $this->request = $request;
    }

    /**
     * @param Observer $observer
     * @return $this
     */
    public function execute(Observer $observer)
    {
        $layout = $observer->getData('layout');

        $requestPath = $this->request->getModuleName() .
            DIRECTORY_SEPARATOR . $this->request->getControllerName() .
            DIRECTORY_SEPARATOR . $this->request->getActionName();

        if(!empty($this->helper->getSuccessPagePath())) {
            $customPath = explode(',', $this->helper->getSuccessPagePath());

            if ('checkout/onepage/success' != $requestPath && in_array($requestPath, $customPath)) {
                $layout->getUpdate()->addHandle('custom_layout');
            }
        }

        return $this;
    }
}
