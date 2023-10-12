<?php
namespace Vexsoluciones\Delivery\Plugin;

use Magento\Directory\Model\ResourceModel\Country\CollectionFactory as CountryCollectionFactory;

class LayoutProcessorPlugin
{
    
    protected $_quote;
    protected $_helper;
    protected $_countryCollectionFactory;

    public function __construct(
        \Magento\Checkout\Model\Session $quote,
        CountryCollectionFactory $countryCollectionFactory,
        \Vexsoluciones\Delivery\Helper\Data $helper
    )
    {
        $this->_quote = $quote;
        $this->_helper = $helper;
        $this->_countryCollectionFactory = $countryCollectionFactory;
    }



    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        array  $jsLayout
    ) {
        

        $quote = $this->_quote->getQuote();

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
            ['children']['shippingAddress']['children']['shipping-address-fieldset']['children']['region_id']['label'] = __('Región/Departamento'); 


        /*if(isset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children']['postcode'])) {
                $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
                ['shippingAddress']['children']['shipping-address-fieldset']['children']['postcode']['visible'] = false;
                $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
                ['shippingAddress']['children']['shipping-address-fieldset']['children']['postcode']['value'] = '';
        }*/


        // Default country selection
        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['country_id']['value'] = 'PE';
        $jsLayout['components']['checkoutProvider']['dictionaries']['country_id'] = [["value"=>"PE","label"=>"Peru","is_region_visible"=>true]];


        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['country_id']['sortOrder'] = 70;
        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['region_id']['sortOrder'] = 71;

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['region_id']['validation'] = ["required-entry"=>true];

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['street']['sortOrder'] = 80;
       
        // Province selection
        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['province_id'] = [
            'component' => 'Magento_Ui/js/form/element/select',
            'config' => [
                'customScope' => 'shippingAddress',
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/select',
                'id' => 'province_id',
            ],
            'filterBy' => [
                'target' => '${ $.provider }:${ $.parentScope }.region_id',
                'field' => 'region_id',
            ],
            'dataScope' => 'shippingAddress.province_id',
            'label' => __('Provincia'),
            'provider' => 'checkoutProvider',
            'visible' => true,
            "imports" => [
                "initialOptions"=> "index = checkoutProvider:dictionaries.province_id",
                "setOptions"=> "index = checkoutProvider:dictionaries.province_id"
            ],
            'validation' => ["required-entry"=>true],
            'sortOrder' => 72,
            'options' => [
                [
                    'value' => '',
                    'label' => __('Por favor seleccione una provincia'),
                ]
            ],            
        ];


        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['province'] = [
            'component' => 'Magento_Ui/js/form/element/abstract',
            'config' => [
                'customScope' => 'shippingAddress.custom_attributes',
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/input',
                'id' => 'province',
            ],
            'dataScope' => 'shippingAddress.custom_attributes.province',
            'label' => __('Provincia'),
            'provider' => 'checkoutProvider',
            'visible' => false,
            'validation' => [],
            'sortOrder' => 72,
            'value' => $quote->getShippingAddress()->getProvince()
        ];


        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['city'] = [
            'component' => 'Magento_Ui/js/form/element/select',
            'config' => [
                'customScope' => 'shippingAddress',
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/select',
                'id' => 'city',
            ],
            'filterBy' => [
                'target' => '${ $.provider }:${ $.parentScope }.province_id',
                'field' => 'province_id',
            ],
            'dataScope' => 'shippingAddress.city',
            'label' => __('City'),
            'provider' => 'checkoutProvider',
            'visible' => true,
            "imports" => [
                "initialOptions"=> "index = checkoutProvider:dictionaries.city",
                "setOptions"=> "index = checkoutProvider:dictionaries.city"
            ],
            'validation' => ["required-entry"=>true],
            'sortOrder' => 73,
            'id' => 'city',
            'options' => [
                [
                    'value' => '',
                    'label' => __('Por favor selecciones una ciudad'),
                ]
            ],            
        ];        
        


        $jsLayout['components']['checkoutProvider']['dictionaries']['country_id'] = $this->getCountryOptions();
        $jsLayout['components']['checkoutProvider']['dictionaries']['province_id'] = $this->getProvinceOptions();
        $jsLayout['components']['checkoutProvider']['dictionaries']['region_id'] = $this->getRegionOptions();
        $jsLayout['components']['checkoutProvider']['dictionaries']['city'] = $this->getCityOptions();

        /*$customAttributeCodeDNI = 'dni';

        $customFieldDNI = [
            'component' => 'Magento_Ui/js/form/element/abstract',
            'config' => [
                // customScope is used to group elements within a single form (e.g. they can be validated separately)
                'customScope' => 'shippingAddress.custom_attributes',
                'customEntry' => null,
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/input',
                'tooltip' => [
                    'description' => 'Documento de identidad de quien hace el pedido',
                ],
            ],
            'dataScope' => 'shippingAddress.custom_attributes' . '.' . $customAttributeCodeDNI,
            'label' => 'DNI',
            'provider' => 'checkoutProvider',
            'sortOrder' => 41,
            'validation' => [
               'required-entry' => true
            ],
            'options' => [],
            'filterBy' => null,
            'customEntry' => null,
            'visible' => true,
        ];

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children'][$customAttributeCodeDNI] = $customFieldDNI;
*/
        //unset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children']['region_id']);
        

        //unset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children']['city']);
        //print_r($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children'])


        /*$jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['street'] = [
            'component' => 'Magento_Ui/js/form/components/group',
           // 'label' => __('Street Address'), // I removed main label
          //  'required' => true, //turn false because I removed main label
            'dataScope' => 'shippingAddress.street',
            'provider' => 'checkoutProvider',
            'sortOrder' => 70,
            'type' => 'group',
            'additionalClasses' => 'form-street',
            'children' => [
                [
                    'label' => __('Calle y número'),
                    'placeholder' => 'Ej: Urbanización Villa Fortuna G-30',
                    'additionalClasses' => 'vex-checkout-street',
                    'component' => 'Magento_Ui/js/form/element/abstract',
                    'config' => [
                        'customScope' => 'shippingAddress',
                        'template' => 'ui/form/field',
                        'elementTmpl' => 'ui/form/element/input'
                    ],
                    'dataScope' => '0',
                    'provider' => 'checkoutProvider',
                    'validation' => ['required-entry' => true, "min_text_len‌​gth" => 1, "max_text_length" => 255],
                ],
                [
                    'label' => __('Dept, Ofi'),
                    'placeholder' => 'Ej: Departamento 322',
                    'component' => 'Magento_Ui/js/form/element/abstract',
                    'config' => [
                        'customScope' => 'shippingAddress',
                        'template' => 'ui/form/field',
                        'elementTmpl' => 'ui/form/element/input'
                    ],
                    'dataScope' => '1',
                    'provider' => 'checkoutProvider',
                    'validation' => ['required-entry' => false, "min_text_len‌​gth" => 1, "max_text_length" => 255],
                ],
                [
                    'label' => __('Referencia'),
                    'placeholder' => 'A espaldas del colegio Simón Bolivar',
                    'component' => 'Magento_Ui/js/form/element/abstract',
                    'config' => [
                        'customScope' => 'extensionAttributes',
                        'template' => 'ui/form/field',
                        'elementTmpl' => 'ui/form/element/input'
                    ],
                    'dataScope' => '2',
                    'provider' => 'checkoutProvider',
                    'validation' => ['required-entry' => false, "min_text_len‌​gth" => 1, "max_text_length" => 255],
                ] 
            ]
        ];*/
        return $jsLayout;
    }


    public function getProvinceOptions()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $sql = "SELECT * FROM vexsoluciones_directorio_provincia;";
        $provincias = $connection->fetchAll($sql);

        $lista = array();
        foreach ($provincias as $key) {
            $lista[] = array(
                'value' => $key['id'],
                'title' => $key['nombre_provincia'],
                'label' => $key['nombre_provincia'],
                'region_id' => $key['region_id'],
            );
        }

        return $lista;
    }

    public function getRegionOptions(){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $sql = "SELECT * FROM directory_country_region;";
        $regiones = $connection->fetchAll($sql);

        $lista = array();

        $lista[] = array("value"=>"",
                    "title"=>"Por favor seleccione una región",
                    "label"=>"Por favor seleccione una región",
                    'country_id' => 'PE');

        foreach ($regiones as $key) {
            $lista[] = array(
                'value' => $key['region_id'],
                'title' => $key['default_name'],
                'label' => $key['default_name'],
                'country_id' => $key['country_id'],
            );
        }

        return $lista;
    }

    public function getCityOptions(){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $sql = "SELECT * FROM vexsoluciones_directorio_distrito;";
        $distritos = $connection->fetchAll($sql);

        $lista = array();
        foreach ($distritos as $key) {
            $lista[] = array(
                'value' => $key['id'],
                'title' => $key['nombre_distrito'],
                'label' => $key['nombre_distrito'],
                'province_id' => $key['provincia_id'],
            );
        }

        return $lista;
    }   

    public function getCountryOptions(){
        $array = array("value"=>"",
                    "title"=>"Por favor seleccione un pais",
                    "label"=>"Por favor seleccione un pais");
        $collection = $this->_countryCollectionFactory->create()->loadByStore();
        $collection->addFieldToSelect('*');
        foreach ($collection as $country) {
            if($country->getName()!="") {
                $array[] = array(
                    "value"=>$country->getCountryId(),
                    "title"=>$country->getName(),
                    "label"=>$country->getName()
                );
            }
        }
        return $array;
    }



}