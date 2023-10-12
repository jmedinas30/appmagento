<?php
namespace Vexsoluciones\Delivery\Model\Carrier;
 
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;

class Delivery extends AbstractCarrier implements CarrierInterface
{
    const CODE = 'delivery';
    private $scopeConfig;

    protected $_code = self::CODE;
    protected $_session;
    protected $checkoutSession;
    protected $request;
    protected $addressRepository;

    private $_rateResultFactory;
    private $_rateMethodFactory;
    private $trackStatusFactory;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        \Magento\Checkout\Model\Session $session,
        \Magento\Framework\Webapi\Rest\Request $request,
        CheckoutSession $checkoutSession,
        \Magento\Customer\Api\AddressRepositoryInterface $addressRepository,
        \Magento\Shipping\Model\Tracking\Result\StatusFactory $trackStatusFactory,
        array $data = []
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->_rateResultFactory = $rateResultFactory;
        $this->_rateMethodFactory = $rateMethodFactory;
        $this->request = $request;
        $this->_session = $session;
        $this->addressRepository = $addressRepository;
        $this->checkoutSession = $checkoutSession;
        $this->trackStatusFactory = $trackStatusFactory;

        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
    }
 
 
    public function getAllowedMethods()
    {
        return [
            $this->_code => $this->getConfigData('name')
        ];
    }
 

    public function collectRates(RateRequest $request)
    {
        $result = $this->_rateResultFactory->create();

        if (!$this->getConfigFlag('active')) {
            return false;
        }

        $codigopais = trim($request->getDestCountryId());
        
        $regular = $this->getConfigData('regular');
        $express = $this->getConfigData('express');
        $programado = $this->getConfigData('programado');

        $almacen = $this->getConfigData('almacen');
        
        date_default_timezone_set('America/Lima');
        $hora = date("G");
        $minuto = date("i");
        $dia = date("N");

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $helperdata = $objectManager->get('Vexsoluciones\Delivery\Helper\Data');


        $items = $request->getAllItems();

        $totalproductos = 0;
        $totalkilos = 0;
        $validarstock = false;
        foreach ($items as $item) {
            $producto = $objectManager->create('Magento\Catalog\Model\Product')->load($item->getProductId());
            if($producto->getTypeId()!="configurable" && $producto->getTypeId()!="virtual"){
                $productWeight = $item->getWeight() * $item->getQty();
                $totalkilos = $totalkilos + $productWeight;
                $totalproductos = $totalproductos + $item->getQty();

                if(!empty($almacen) && $almacen!=0){
                    $yu = $this->validarstock($almacen,$item->getSku(),$item->getQty());
                    if(!$yu){
                        $validarstock = true;
                    }
                }

            }
        }

        if($validarstock){
            return false;
        }

        
        $distrito = trim($request->getDestCity());
        $region = $request->getDestRegionId();
        $provincia = 0;

        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $sql = "SELECT directory_country_region.region_id as 'idregion',vexsoluciones_directorio_provincia.id as 'idprovincia',vexsoluciones_directorio_distrito.id as 'iddistrito' FROM vexsoluciones_directorio_distrito 
                    inner join vexsoluciones_directorio_provincia on vexsoluciones_directorio_provincia.id = vexsoluciones_directorio_distrito.provincia_id 
                    inner join directory_country_region on directory_country_region.region_id = vexsoluciones_directorio_provincia.region_id 
                    WHERE vexsoluciones_directorio_distrito.id='".str_replace("'", "", $distrito)."';";
        $ubigeo = $connection->fetchAll($sql);
        foreach ($ubigeo as $key) {
            $distrito = $key['iddistrito'];
            $region =$key['idregion'];
            $provincia = $key['idprovincia'];
        }

        $freeBoxes = $this->getFreeBoxesCount($request);

        if($regular){
            $tipoenvio = 1;
            $datos = $helperdata->obtenerprecio($codigopais,$tipoenvio,$region,$provincia,$distrito,$dia,$hora,$totalkilos);

            if($datos['status']){

                $precio = $datos['precio'];
                if ($request->getPackageQty() == $freeBoxes) {
                    $precio = '0.00';
                }
            	
            	$method = $this->_rateMethodFactory->create();
                $method->setCarrier($this->_code);
                $method->setMethod('regular');
                $method->setMethodTitle($this->getConfigData('nombreregular'));
                $method->setCarrierTitle($this->getConfigData('tituloregular'));
                $method->setPrice($precio);
                $method->setCost($precio);
                $result->append($method);
            }
        }

        if($express){
            $tipoenvio = 2;
            $datos = $helperdata->obtenerprecio($codigopais,$tipoenvio,$region,$provincia,$distrito,$dia,$hora,$totalkilos);

        	if($datos['status']){
                
                $precio = $datos['precio'];
                if ($request->getPackageQty() == $freeBoxes) {
                    $precio = '0.00';
                }
            	$method = $this->_rateMethodFactory->create();
                $method->setCarrier($this->_code);
                $method->setMethod('express');
                $method->setMethodTitle($this->getConfigData('nombreexpress'));
                $method->setCarrierTitle($this->getConfigData('tituloexpress'));
                $method->setPrice($precio);
                $method->setCost($precio);
                $result->append($method);
            }
        }

        if($programado){
            $tipoenvio = 3;
            $datos = $helperdata->obtenerprecio($codigopais,$tipoenvio,$region,$provincia,$distrito,$dia,$hora,$totalkilos);

        	if($datos['status']){
                $precio = $datos['precio'];
                if ($request->getPackageQty() == $freeBoxes) {
                    $precio = '0.00';
                }
            	$method = $this->_rateMethodFactory->create();
                $method->setCarrier($this->_code);
                $method->setMethod('programado'.$datos['id']);
                $method->setMethodTitle($this->getConfigData('nombreprogramado'));
                $method->setCarrierTitle($this->getConfigData('tituloprogramado'));
                $method->setPrice($precio);
                $method->setCost($precio);
                $result->append($method);
            }
        }

        /*
        if($same && $tipoenvio==1 && $estadoexpress && $hora>=9 && $hora<12 && ($dia==1 || $dia==2 || $dia==3 || $dia==4 || $dia==5) ){
            $estadoexpress = false;
            $method = $this->_rateMethodFactory->create();
            $method->setCarrier($this->_code);
            $method->setMethod('same');
            $method->setMethodTitle($this->getConfigData('name'));
            $method->setCarrierTitle($this->getConfigData('texto_same'));
            $method->setPrice($precio);
            $method->setCost($precio);
            $result->append($method);
        }
        
        if($next && $tipoenvio==2 && $estadoregular){
            $estadoregular = false;
            $method = $this->_rateMethodFactory->create();
            $method->setCarrier($this->_code);
            $method->setMethod('next');
            $method->setMethodTitle($this->getConfigData('name'));
            $method->setCarrierTitle($this->getConfigData('texto_next'));
            $method->setPrice($precio);
            $method->setCost($precio);
            $result->append($method);
        }
        */

        
        

        return $result;
    }

    public function validarstock($almacen,$sku,$cantidad){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $tableName = $resource->getTableName('inventory_source_item');

        $sql = "Select * FROM " . $tableName." where sku='".$sku."' and status=1 and source_code='".$almacen."' and quantity>=".$cantidad.";";
        $result = $connection->fetchAll($sql);
        $status = false;
        foreach ($result as $key) {
            $status = true;
        }
        return $status;
    }

    private function getFreeBoxesCount(RateRequest $request)
    {
        $freeBoxes = 0;
        if ($request->getAllItems()) {
            foreach ($request->getAllItems() as $item) {
                if ($item->getProduct()->isVirtual() || $item->getParentItem()) {
                    continue;
                }

                if ($item->getHasChildren() && $item->isShipSeparately()) {
                    $freeBoxes += $this->getFreeBoxesCountFromChildren($item);
                } elseif ($item->getFreeShipping()) {
                    $freeBoxes += $item->getQty();
                }
            }
        }
        return $freeBoxes;
    }
    private function getFreeBoxesCountFromChildren($item)
    {
        $freeBoxes = 0;
        foreach ($item->getChildren() as $child) {
            if ($child->getFreeShipping() && !$child->getProduct()->isVirtual()) {
                $freeBoxes += $item->getQty() * $child->getQty();
            }
        }
        return $freeBoxes;
    }

    

    
}