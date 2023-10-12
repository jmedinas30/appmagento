<?PHP 

namespace Vexsoluciones\Facturacionelectronica\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;

class Dataconfig extends AbstractHelper
{
 
    protected $encryptor;
 
    public function __construct(
        Context $context,
        EncryptorInterface $encryptor
    )
    {
        parent::__construct($context);
        $this->encryptor = $encryptor;
    }

    /*
     * @return Token
     */
    public function getToken($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        return $this->scopeConfig->getValue(
            'vexsoluciones/facturacionnubefact/token',
            $scope
        );
    }

    /*
     * @return urlEnvio
     */
    public function getUrlEnvio($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        return $this->scopeConfig->getValue(
            'vexsoluciones/facturacionnubefact/urlenvio',
            $scope
        );
    }

    public function getSqlserver(){
        $serverName = "http://azaecommerce.database.windows.net/";
        $connectionOptions = array(
            "Database" => "AZAECOMMERCE",
            "Uid" => "magento",
            "PWD" => "azaleia.2018"
        );
//Establishes the connection
        $conn = sqlsrv_connect($serverName, $connectionOptions);
        return $conn;
    }

}