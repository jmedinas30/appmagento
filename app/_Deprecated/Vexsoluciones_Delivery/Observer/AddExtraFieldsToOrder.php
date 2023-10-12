<?php 

namespace Vexsoluciones\Delivery\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer; 


class AddExtraFieldsToOrder implements ObserverInterface
{
    
    protected $addressRepository;

    public function __construct(
        \Magento\Customer\Api\AddressRepositoryInterface $addressRepository){

        $this->addressRepository = $addressRepository;
    }
 
    public function execute(Observer $observer)
    {

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();

        $order = $observer->getEvent()->getOrder();
        $quote = $observer->getEvent()->getQuote();
  
        $quoteShippingAddress = $quote->getShippingAddress();
        $orderShippingAddress = $order->getShippingAddress();

        $quoteBillingAddress = $quote->getBillingAddress();


        if($quoteShippingAddress && $quoteShippingAddress->getCustomerAddressId() != '' && is_numeric($quoteShippingAddress->getCustomerAddressId()) && $quoteShippingAddress->getCustomerAddressId() != 0 ){
            
            
            $customerAddress = $this->addressRepository->getById($quoteShippingAddress->getCustomerAddressId());
            $distrito = $customerAddress->getCity();

            $provincia_actualizar = '';
            $prov_label = '';
            $dist_label = '';
            $sql = "SELECT * FROM vexsoluciones_directorio_distrito 
                        inner join vexsoluciones_directorio_provincia on vexsoluciones_directorio_distrito.provincia_id = vexsoluciones_directorio_provincia.id where vexsoluciones_directorio_distrito.id='".$distrito."';";
            $distrito = $connection->fetchAll($sql);
            foreach ($distrito as $key) {
                $provincia_actualizar = $key['provincia_id'];
                $prov_label = $key['nombre_provincia'];
                $dist_label = $key['nombre_distrito'];
            }
                
            if($provincia_actualizar != ''){
                $customerAddress->setCustomAttribute('province', $provincia_actualizar);
                $customerAddress->setCustomAttribute('provincia_label',$prov_label); 
                $customerAddress->setCustomAttribute('distrito_label',$dist_label);
            }

            $this->addressRepository->save($customerAddress);

        }


        if($quoteBillingAddress && $quoteBillingAddress->getCustomerAddressId() != '' && is_numeric($quoteBillingAddress->getCustomerAddressId()) && $quoteBillingAddress->getCustomerAddressId() != 0 ){
            
            
            $customerAddress = $this->addressRepository->getById($quoteBillingAddress->getCustomerAddressId());
            $distrito = $customerAddress->getCity();

            $provincia_actualizar = '';
            $prov_label = '';
            $dist_label = '';
            $sql = "SELECT * FROM vexsoluciones_directorio_distrito 
                        inner join vexsoluciones_directorio_provincia on vexsoluciones_directorio_distrito.provincia_id = vexsoluciones_directorio_provincia.id where vexsoluciones_directorio_distrito.id='".$distrito."';";
            $distrito = $connection->fetchAll($sql);
            foreach ($distrito as $key) {
                $provincia_actualizar = $key['provincia_id'];
                $prov_label = $key['nombre_provincia'];
                $dist_label = $key['nombre_distrito'];
            }
                
            if($provincia_actualizar != ''){
                $customerAddress->setCustomAttribute('province', $provincia_actualizar);
                $customerAddress->setCustomAttribute('provincia_label',$prov_label); 
                $customerAddress->setCustomAttribute('distrito_label',$dist_label);
            }

            $this->addressRepository->save($customerAddress);

        }


        if($orderShippingAddress){ 

            $coordenadas = $quoteShippingAddress->getData("vexcoordenadas");
            $dia = $quoteShippingAddress->getData("vexdiaprogramado");
            $hora = $quoteShippingAddress->getData("vexhoraprogramado");

            $orderShippingAddress->setData("vexcoordenadas",$coordenadas); 
            $orderShippingAddress->setData("vexdiaprogramado",$dia); 
            $orderShippingAddress->setData("vexhoraprogramado",$hora);
        
        }
    }
}
