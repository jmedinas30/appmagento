<?php

namespace Vexsoluciones\Facturacionelectronica\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Store\Model\ScopeInterface;
use Psr\Log\LoggerInterface;
use Vexsoluciones\Facturacionelectronica\Model\Method;

class Util
{
    public const SAFETY_PAY_LICENSE_SECRET_KEY = '587423b988e403.69821411';
    public const SAFETY_PAY_LICENSE_SERVER_URL = 'https://www.pasarelasdepagos.com';
    public const SAFETY_PAY_ITEM_REFERENCE = 'Facturacion electronica - Magento 2';

    protected $scopeConfig;
    protected $configWriter;
    protected $messageManager;
    protected $logger;
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $httpRequest;

    public function __construct
    (
        ScopeConfigInterface $scopeConfig,
        ManagerInterface $messageManager,
        WriterInterface $configWriter,
        LoggerInterface $logger,
        \Magento\Framework\App\RequestInterface $httpRequest
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->configWriter = $configWriter;
        $this->messageManager = $messageManager;
        $this->logger = $logger;
        $this->httpRequest = $httpRequest;
    }

    /**
     * @param string $key
     * @param $value
     */
    public function setConfig(string $key, $value)
    {
        $this->configWriter->save(
            sprintf(
                'facturacionelectronica_service/%s/%s',
                Method::CODE,
                $key
            ),
            $value,
            'default',
            0);
    }

    public function verify()
    {
        $license = $this->getConfig('license');

        if (empty($license)) {
            $this->disabled();
            return;
        }

        $this->setConfig('activated', '0');
        $this->setConfig('last_date', '');

        /*$this->configWriter->save('facturacionelectronica_service/general/activated', '0', 'default', 0);
        $this->configWriter->save('facturacionelectronica_service/general/last_date', '', 'default', 0);*/
        $activated = $this->scopeConfig->getValue('facturacionelectronica_service/general/activated', ScopeInterface::SCOPE_STORE);
        $last_date = $this->scopeConfig->getValue('facturacionelectronica_service/general/last_date', ScopeInterface::SCOPE_STORE);

        $current_date = date('d/m/Y');

        if (empty($last_date) && $activated) {
            return;
        }

        if ($last_date != $current_date) {
            $this->_verify($license);
        }
    }

    /**
     * @param string $key
     * @return mixed
     */
    private function getConfig(string $key)
    {
        return $this->scopeConfig->getValue(
            sprintf(
                'payment/%s/%s',
                Method::CODE,
                $key
            ),
            ScopeInterface::SCOPE_STORE
        );
    }

    private function disabled()
    {

        $this->configWriter->save('facturacionelectronica_service/general/active', '0', 'default', 0);
        $this->configWriter->save('facturacionelectronica_service/general/setting', '', 'default', 0);

        $settings = $this->scopeConfig->getValue('facturacionelectronica_service/general/setting', ScopeInterface::SCOPE_STORE);

        if (empty($settings)) return;

        $this->configWriter->save('facturacionelectronica_service/general/setting', $settings, 'default', 0);
        $this->configWriter->save('facturacionelectronica_service/general/activated', '0', 'default', 0);

    }

    private function _verify($license)
    {

        $curl = curl_init(self::SAFETY_PAY_LICENSE_SERVER_URL . "?license_key=" . $license . "&slm_action=slm_check&secret_key=" . self::SAFETY_PAY_LICENSE_SECRET_KEY);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/json"
            )
        );
        $response = curl_exec($curl);
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if (200 != $http_status && !empty($response)) {
            $this->messageManager->addError(__($http_status . ' - ' . $response));
            return;
        } elseif (200 != $http_status) {
            $this->messageManager->addError(__('Unexpected Error! The query returned with an error.'));
            return;
        }

        $license_data = json_decode($response, true);

        if (isset($license_data['result']) && $license_data['result'] == 'success') {
            $this->_activate_plugin($license_data['message']);

            $license_registered = $this->scopeConfig->getValue('facturacionelectronica_service/general/license_registered', ScopeInterface::SCOPE_STORE);

            if ($license_registered == '' && $license != $license_registered) {
                $this->activate($license);
            }
            return;
        }

        $this->configWriter->save('facturacionelectronica_service/general/activated', '0', 'default', 0);
        $this->disabled();
    }

    private function _activate_plugin($message)
    {
        $this->configWriter->save('facturacionelectronica_service/general/activated', '1', 'default', 0);
        $this->configWriter->save('facturacionelectronica_service/general/last_date', date('d/m/Y'), 'default', 0);
    }

    public function activate($license)
    {
        $serverName = $this->httpRequest->getServer('SERVER_NAME');
//        $serverName = $_SERVER['SERVER_NAME'];


        $curl = curl_init(self::SAFETY_PAY_LICENSE_SERVER_URL . "?license_key=" . $license . "&slm_action=slm_activate&secret_key=" . self::SAFETY_PAY_LICENSE_SECRET_KEY . "&item_reference=" . urlencode(self::SAFETY_PAY_ITEM_REFERENCE) . "&registered_domain=" . $serverName);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/json"
            )
        );
        $response = curl_exec($curl);
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);


        if (200 != $http_status && !empty($response)) {
            $this->messageManager->addError(__($http_status . ' - ' . $response));
            return;
        } elseif (200 != $http_status) {
            $this->messageManager->addError(__('Unexpected Error! The query returned with an error.'));

            return;
        }

        $license_data = json_decode($response, true);

        if (isset($license_data['result']) && $license_data['result'] == 'success') {
            $this->configWriter->save('facturacionelectronica_service/general/license_registered', $license, 'default', 0);
            $this->_activate_plugin($license_data['message']);
            return;
        }

        $this->messageManager->addError(__($license_data['message']));
        $this->disabled();
    }

}
