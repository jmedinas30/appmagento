<?php

namespace Vexsoluciones\Delivery\Observer;

class AfterAddressSaveObserver implements \Magento\Framework\Event\ObserverInterface
{

    protected $_customerRepository;
    protected $_addressFactory;
    protected $_addressRepository;
    protected $_request;

    public function __construct(
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Customer\Model\AddressFactory $addressFactory,
        \Magento\Customer\Api\AddressRepositoryInterface $addressRepository,
        \Magento\Framework\App\RequestInterface $request
    )
    {
        $this->_customerRepository = $customerRepository;
        $this->_addressFactory     = $addressFactory;
        $this->_addressRepository  = $addressRepository;
        $this->_request  = $request;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $observer->getCustomerAddress();
        //$newCustomerCustomDataAddress = $observer->getCustomerAddress();
        $id = $observer->getCustomerAddress()->getEntityId();
        $prov_id = $this->_request->getParam('provincia_id');
        $dist_id = $this->_request->getParam('distrito');
        $validar = $this->_request->getParam('validar');
        $tipodireccion = $this->_request->getParam('tipodireccion');

        $email = $this->_request->getParam('email');

        $newCustomerCustomDataAddress = $this->_addressRepository->getById($id);

        if($email!=""){
            $validar = 1;
        }


        if($validar==1){

            $newCustomerCustomDataAddress->setCustomAttribute('province',$prov_id);

            if($tipodireccion==1){
            	$newCustomerCustomDataAddress->setCity($dist_id);

                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
                $connection = $resource->getConnection();

                $sql = "SELECT * FROM vexsoluciones_directorio_distrito 
                        inner join vexsoluciones_directorio_provincia on vexsoluciones_directorio_distrito.provincia_id = vexsoluciones_directorio_provincia.id where vexsoluciones_directorio_distrito.id='".$dist_id."';";
                $ubigeo = $connection->fetchAll($sql);

                $prov_label = '';
                $dist_label = '';
                foreach ($ubigeo as $key) {
                    $prov_label = $key['nombre_provincia'];
                    $dist_label = $key['nombre_distrito'];
                }

                if(!empty($dist_label)){
                    $newCustomerCustomDataAddress->setData('provincia_label',$prov_label); 
                    $newCustomerCustomDataAddress->setData('distrito_label',$dist_label);
                    $newCustomerCustomDataAddress->setCustomAttribute('provincia_label',$prov_label); 
                    $newCustomerCustomDataAddress->setCustomAttribute('distrito_label',$dist_label);
                }

                

            }else{
                $dist_id = $this->_request->getParam('cityaux');
                $newCustomerCustomDataAddress->setCity($dist_id);
            }
            

            $this->_request->setParams(array('validar'=>0));
            $this->_addressRepository->save($newCustomerCustomDataAddress);

        }

    }

    
}
