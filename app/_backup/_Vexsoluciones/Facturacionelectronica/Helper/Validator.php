<?php
declare(strict_types=1);

namespace Vexsoluciones\Facturacionelectronica\Helper;

use Magento\Config\Model\Config;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;
//use Vexsoluciones\Facturacionelectronica\Logger\Logger;
use Vexsoluciones\Facturacionelectronica\Model\Method;
use Magento\Framework\App\Config\Storage\WriterInterface;

class Validator
{
    public const LICENSE_SECRET_KEY = '587423b988e403.69821411';
    public const LICENSE_SERVER_URL = 'https://www.pasarelasdepagos.com';

    /**
     * @var string
     */
    private $section;
    /**
     * @var string
     */
    private $moduleCode;
    /**
     * @var string
     */
    private $itemReference;
    /**
     * @var Config
     */
    private $config;

    /**
     * @var bool
     */
    private $isValid = false;
    /**
     * @var RequestInterface
     */
    private $httpRequest;
    /**
     * @var Logger

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    protected $configWriter;
    public function __construct(
        $section,
        $moduleCode,
        $itemReference,
        RequestInterface $httpRequest,
        ScopeConfigInterface $scopeConfig,
        WriterInterface $configWriter
    )
    {
        $this->section = $section;
        $this->moduleCode = $moduleCode;
        $this->itemReference = $itemReference;
        $this->httpRequest = $httpRequest;
        $this->scopeConfig = $scopeConfig;
        $this->configWriter = $configWriter;
    }

    /**
     * @param Config $config
     */
    public function verify(Config $config): void
    {

        $this->config = $config;

        if (!$this->isCorrectConfigSection()) {
            return;
        }

        /*if ($this->isTodayAlreadyVerified()) {
            return;
        }*/

        if ($this->isAlreadyRegistered()) {
            $this->checkStatus();
        } else {
            $this->register();
        }

        $this->updateLastDate();


        if ($this->isValidLicense()) {
            $this->doEnable();
        } else {
            $this->doDisable();
            return;
        }


    }

    /**
     * @return bool
     */
    private function isCorrectConfigSection(): bool
    {
        if ($this->config->getSection() !== $this->section) {
            return true;
        }

        if ($this->config->getDataByPath(sprintf('groups/%s', $this->moduleCode))) {
            return true;
        }

        return false;
    }

    private function isTodayAlreadyVerified(): bool
    {
        return $this->scopeConfig->getValue(
            sprintf('%s/%s/%s', $this->section, $this->moduleCode, 'last_date'),
            $this->config->getScope(),
            $this->config->getScopeCode()
        ) === date('Y-m-d');
    }

    /**
     * @param string $path
     * @return array|mixed|null
     */
    private function getData(string $path)
    {
        /*return $this->config->getDataByPath(
            sprintf('%s/%s/%s', $this->section, $this->moduleCode, $path)
        );*/

        return $this->scopeConfig->getValue('facturacionelectronica_service/general/'.$path);

    }

    private function isAlreadyRegistered(): bool
    {
        return $this->getData('is_registered') === '1';
    }

    private function checkStatus(): void
    {
        $license = $this->getData('license');
        $url = self::LICENSE_SERVER_URL . "?license_key=" . $license . "&slm_action=slm_check&secret_key=" . self::LICENSE_SECRET_KEY;

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ],
        ]);

        $response = curl_exec($curl);

        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($http_status !== 200 || empty($response)) {
            $this->isValid = false;
            return;
        }

        $response = json_decode($response, true);

        if (isset($response['result']) && $response['result'] === 'success') {
            $this->isValid = true;
        }
    }

    private function register(): void
    {

        $serverName = $this->httpRequest->getServer('SERVER_NAME');
        $license = $this->getData('license');
        $url = self::LICENSE_SERVER_URL . "?license_key=" . $license . "&slm_action=slm_activate&secret_key=" . self::LICENSE_SECRET_KEY . "&item_reference=" . urlencode($this->itemReference) . "&registered_domain=" . $serverName;

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ],
        ]);

        $response = curl_exec($curl);
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($http_status !== 200 || empty($response)) {
            $this->isValid = false;
            return;
        }

        $response = json_decode($response, true);

        if ($response['result'] === 'error'){
            $this->isValid = false;
            return;
        }

        if (isset($response['result']) && $response['result'] === 'success') {
            $this->isValid = true;
            $this->setData('is_registered', 1);
        }



        //$this->setData('activated', true);
        //$this->configWriter->save('facturacionelectronica_service/general/is_registered', 1);

    }

    /**
     * @param string $path
     * @param mixed $value
     */
    private function setData(string $path, $value): void
    {
        /*$this->config->setDataByPath(
            sprintf('%s/%s/%s', $this->section, $this->moduleCode, $path),
            $value
        );*/

        $path_complete = $this->section."/".$this->moduleCode."/".$path;
        $this->configWriter->save($path_complete, $value, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);


    }

    private function updateLastDate(): void
    {
        $this->setData('last_date', date('Y-m-d'));
    }

    private function isValidLicense(): bool
    {
        return $this->isValid;
    }

    private function doEnable(): void
    {
        $this->setData('active', 1);
    }

    private function doDisable(): void
    {
        $this->setData('active', 0);
    }
}