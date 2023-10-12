<?php

namespace Vexsoluciones\Facturacionelectronica\Plugin;

use Magento\Config\Model\Config;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Vexsoluciones\Facturacionelectronica\Helper\Util;
use Vexsoluciones\Facturacionelectronica\Helper\Validator;
//use Vexsoluciones\Facturacionelectronica\Logger\Logger;
use Vexsoluciones\Facturacionelectronica\Model\Method;

class AfterConfigSavePlugin
{
    /**
     * @var Logger
     */
   // private $logger;
    /**
     * @var Util
     */
    private $util;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var Validator
     */
    private $validator;

    public function __construct(
        //Logger $logger,
        Util $util,
        StoreManagerInterface $storeManager,
        Validator $validator
    )
    {
        //$this->logger = $logger;
        $this->util = $util;
        $this->storeManager = $storeManager;
        $this->validator = $validator;
    }

    /**
     * @param Config $subject
     * @throws NoSuchEntityException
     */
    //public function afterSave(Config $config, $result)
    /*public function beforeSave(Config $subject): void
    {
        $this->validator->verify($subject);
    }*/

    public function afterSave(Config $config, $result)
    {
        /*$baseUrl = $this->storeManager->getStore()->getBaseUrl();

        $this->util->setConfig(
            'notification_url',
            $baseUrl . sprintf('%s/action/notify', Method::CODE)
        );*/

        $this->validator->verify($result);
        //$this->util->verify();

        return $result;
    }
}
