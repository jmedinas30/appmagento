<?php

namespace Vexsoluciones\Pagoefectivo\Model\Adminhtml\Notifications;

class Unconfigured implements \Magento\Framework\Notification\MessageInterface
{
    public $configurations = null;
    public $displayedText = null;

    private $scopeConfig;
    private $configWriter;
    private $messageManager;

    const FAC_LICENSE_SECRET_KEY = '587423b988e403.69821411';
    const FAC_LICENSE_SERVER_URL = 'https://www.pasarelasdepagos.com';
    const FAC_ITEM_REFERENCE = 'Metodo de pago - Magento 2';

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\App\Config\Storage\WriterInterface $configWriter,
        \Magento\Framework\UrlInterface $urlBuilder
    ) {
        $this->urlBuilder = $urlBuilder;

        $this->scopeConfig = $scopeConfig;
        $this->configWriter = $configWriter;
        $this->messageManager = $messageManager;


        $license = $this->scopeConfig->getValue('payment/pagoefectivo_pay/license', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        if(empty( $license ) ){
           // $this->disabled();
//	 $this->enabled();
            $this->displayedText = __("Please enter the license to activate the PagoEfectivo module.");
            return;
        }else{

            $curl = curl_init(self::FAC_LICENSE_SERVER_URL."?license_key=".$license."&slm_action=slm_check&secret_key=".self::FAC_LICENSE_SECRET_KEY);                                                                      
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);                                                              
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/json"
                )                                                                       
            );
            $response = curl_exec($curl);
            $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if ( 200 != $http_status && ! empty( $response ) ) {
  //              $this->disabled();
                $this->displayedText = $http_status.' - '.$response;
                return;
                
            } elseif ( 200 != $http_status ) {
    //            $this->disabled();
                $this->displayedText = __('The license of the PagoEfectivo module could not be consulted.');
                return;
            }



            $license_data = json_decode($response,true);

            if( isset($license_data['result']) && $license_data['result'] == 'success' ){
                
                return;
            }else{
             //   $this->disabled();
             //   $this->displayedText = __("The license expired for the PagoEfectivo module.");
                return;
            }

            

            
        }

        

        $this->storeManager = $storeManager;

        $stores = $this->storeManager->getStores();
        $configurations = array();


    }

    public function getUrl($path)
    {
        return $this->urlBuilder->getUrl($path, ['_secure' => $this->request->isSecure()]);
    }

    public function replaceLastOccuranceOf($search, $replace, $subject)
    {
        $pos = strrpos($subject, $search);

        if($pos !== false)
        {
            $subject = substr_replace($subject, $replace, $pos, strlen($search));
        }

        return $subject;
    }

    public function getIdentity()
    {
        return 'pagoefectivo_billing_notification_unconfigured';
    }

    public function isDisplayed()
    {
        return !empty($this->displayedText);
    }

    public function getText()
    {
        return $this->displayedText;
    }

    public function getSeverity()
    {
        // SEVERITY_CRITICAL, SEVERITY_MAJOR, SEVERITY_MINOR, SEVERITY_NOTICE
        return self::SEVERITY_MAJOR;
    }

    private function disabled(){
        $this->configWriter->save('payment/pagoefectivo_pay/active', '0', 'default', 0);
        $this->configWriter->save('payment/cuotealo_pay/active', '0', 'default', 0);

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $_cacheTypeList = $objectManager->create('Magento\Framework\App\Cache\TypeListInterface');
        $_cacheFrontendPool = $objectManager->create('Magento\Framework\App\Cache\Frontend\Pool');
        $types = array('config');
        foreach ($types as $type) {
            $_cacheTypeList->cleanType($type);
        }
        foreach ($_cacheFrontendPool as $cacheFrontend) {
            $cacheFrontend->getBackend()->clean();
        }

    }

}
