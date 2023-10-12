<?php
namespace Vexsoluciones\Delivery\Model;

use Vexsoluciones\Delivery\Api\DeliveryInterface;
use Magento\Directory\Model\ResourceModel\Country\CollectionFactory as CountryCollectionFactory;
 
class DeliveryApi implements DeliveryInterface
{

    protected $scopeConfig;
    protected $_countryCollectionFactory;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        CountryCollectionFactory $countryCollectionFactory
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->_countryCollectionFactory = $countryCollectionFactory;
    }


    public function listarhorario() {

        $id = (isset($_GET['id']))?intval($_GET['id']):0;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $sql = "SELECT dia,hora_inicio as 'inicio', hora_fin as 'fin' FROM vexsoluciones_reglas_horario where sector_id='".$id."' and status=1;";
        $horarios = $connection->fetchAll($sql);
        $array = array("horarios"=>$horarios);
        return $array;
    }

    
    
}