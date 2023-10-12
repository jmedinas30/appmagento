<?php
namespace Vexsoluciones\Pagoefectivo\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    
    public $_scopeConfig;
    public $_storeManager;
    public $tiendafactory;
    public $resource;
    public $_customerRepositoryInterface;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
        \Magento\Framework\App\ResourceConnection $resource
    ) {
        parent::__construct($context);
        $this->_scopeConfig = $context->getScopeConfig();
        $this->resource = $resource;
        $this->_storeManager = $storeManager;
        $this->_customerRepositoryInterface = $customerRepositoryInterface;
    }


    public function geturlapi($type,$tipopago){
        
        if($tipopago=="pagoefectivo"){
            $test = $this->_scopeConfig->getValue('payment/pagoefectivo_pay/test');
        }else{
            $test = $this->_scopeConfig->getValue('payment/cuotealo_pay/test');
        }
        

        $uri = '';
        if ( $test ) {
            if ( $type == 'auth' ) {
                $uri = "https://pre1a.services.pagoefectivo.pe/v1/authorizations";
            } else {
                $uri = "https://pre1a.services.pagoefectivo.pe/v1/cips";
            }

        //Sino el entorno es de pruebas
        } else {
            if ( $type == 'auth' ) {
                $uri = "https://services.pagoefectivo.pe/v1/authorizations";
            } else {
                $uri = "https://services.pagoefectivo.pe/v1/cips";
            }
        }

        return $uri;

    }

    

    public function get_authorize_cip_generation($tipopago)
    {
        $result = [];
        $data = [];
        $uri = $this->geturlapi('auth',$tipopago);

        if($tipopago=="pagoefectivo"){
            $idService = $this->_scopeConfig->getValue('payment/pagoefectivo_pay/id_servicio');
            $accesKey = $this->_scopeConfig->getValue('payment/pagoefectivo_pay/access_key');
            $secretKey = $this->_scopeConfig->getValue('payment/pagoefectivo_pay/secret_key');
        }else{
            $idService = $this->_scopeConfig->getValue('payment/cuotealo_pay/id_servicio');
            $accesKey = $this->_scopeConfig->getValue('payment/cuotealo_pay/access_key');
            $secretKey = $this->_scopeConfig->getValue('payment/cuotealo_pay/secret_key');
        }
        
        $dateRequest = $this->getDateTimeString();
        $hashString = hash("sha256", $idService.'.'.$accesKey.'.'.$secretKey.'.'.$dateRequest);

        //Definir header de la solicitud http                   
        $headers = [
            "Content-Type: application/json"
        ];            
        //Definir campos de información a incluir en la solicitud http 
        $fields = '
            {
                "idService"   : '.$idService.',
                "accessKey"   : "'.$accesKey.'",
                "dateRequest" : "'.$dateRequest.'",
                "hashString"  : "'.$hashString.'"
            }';
        //Configurar solicitud http
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $uri);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_HEADER, 0); 
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);                                
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); //Solo para depuración
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //Solo para depuración    
        

        $response = curl_exec($curl);          
        curl_close($curl);
        $object = json_decode($response, false);

        return $object;
        
    }


    public function get_generate_cip($order,$tipo_documento,$documento,$tipopago){
        
        $result = array("status"=>false,"msg"=>"");

        //Obtener id pedido
        $orderId = $order->getIncrementId();
        $billing = $order->getBillingAddress();

        //Obtener autorización
        $auth = $this->get_authorize_cip_generation($tipopago); 

        if (is_object( $auth ) && isset( $auth->code ) ) {

            if ( $auth->code == '100' ) {

                $data = ( isset( $auth->data ) ) ? $auth->data : [];
                

                if($tipopago=="pagoefectivo"){
                    //Obtener tiempo máximo de duración de CIP (horas)
                    $cip_max_time  = trim( $this->_scopeConfig->getValue('payment/pagoefectivo_pay/cip') );
                    $admin_email   = $this->_scopeConfig->getValue('payment/pagoefectivo_pay/email');
                    $empresa = $this->_scopeConfig->getValue('payment/pagoefectivo_pay/company');
                }else{
                    //Obtener tiempo máximo de duración de CIP (horas)
                    $cip_max_time  = trim( $this->_scopeConfig->getValue('payment/cuotealo_pay/cip') );
                    $admin_email   = $this->_scopeConfig->getValue('payment/cuotealo_pay/email');
                    $empresa = $this->_scopeConfig->getValue('payment/cuotealo_pay/company');
 
                }

                                //Sumar a la fecha actual el tiempo de duración de CIP
                $datetime = $this->getCurrentDateTime();  
                $datetime->modify('+'.$cip_max_time.' hours');          
                //Fecha de expiración de CIP
                $date_expiry = $this->getDateTimeString($datetime);
                //Datos de ubicación de la operación de compra (Lima-Lima-La Victoria).
                $ubigeo_emisor = '150115';
                //Obtener email admin
                
                
                //Código de País (Perú)
                $code_country  = '+51';
                //País de operación
                $country       = $billing->getData('country_id');
                //Si el país es Ecuador
                if ( $country == 'EC' ) $code_country = '+593';
                //Datos adicionales
                if($tipopago=="pagoefectivo"){
                    $additional_data = 'Plugin PagoEfectivo';  
                }else{
                    $additional_data = 'Plugin PagoEfectivo - cuotealo';  
                }
                

                //Obtener uri
                $uri = $this->geturlapi("",$tipopago);                  
                //Definir header de la solicitud http                   
                $headers = [
                    "Content-Type: application/json",
                    "Accept-Language: es-PE",
                    "Origin: web",
                    "Authorization: Bearer ". $data->token,
                ];              
                //Definir campos de información a incluir en la solicitud http 
                $fields = '
                    {
                        "currency"           : "'. $order->getData("order_currency_code") .'",
                        "amount"             :  '. $order->getGrandTotal() .',
                        "transactionCode"    : "'.$orderId.'",
                        "dateExpiry"         : "'. $date_expiry .'",
                        "paymentConcept"     : "'. $empresa .'",
                        "additionalData"     : "'. $additional_data .'",
                        "adminEmail"         : "'. $admin_email .'",
                        "userEmail"          : "'. $order->getCustomerEmail() .'",
                        "userName"           : "'. $billing->getData('firstname') .'",
                        "userLastName"       : "'. $billing->getData('lastname') .'",
                        "userUbigeo"         : "'. $ubigeo_emisor .'",
                        "userCountry"        : "'. $country .'",
                        
                        "userDocumentType"   : "'. $tipo_documento .'",
                        "userDocumentNumber" : "'. $documento .'",
                        "userPhone"          : "'. $billing->getData('telephone') .'",
                        "userCodeCountry"    : "'. $code_country .'"
                        
                    }';

                
                //Configurar solicitud http
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $uri);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($curl, CURLOPT_HEADER, 0); 
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);                                
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); //Solo para depuración
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //Solo para depuración    
                
                //Ejecutar solicitud http
                $response = curl_exec($curl);  
                //Cerrar sesión y liberar recursos           
                curl_close($curl);  

                

                //Convertir en objeto
                $object = json_decode($response, false);     
                if (is_object( $object ) && isset( $object->code ) ) {
                    //Si la respuesta es exitosa
                    if ( $object->code == '100' ) {
                        //Obtener datos 
                        $resultx = ( isset( $object->data ) ) ? $object->data : [];
                        $result = array("status"=>true,"msg"=>$resultx);

                    } else if(isset( $object->message )){
                        $result = array("status"=>false,"msg"=>$object->message);
                    } else {
                        $result = array("status"=>false,"msg"=>"No se pudo procesar la compra.");
                    }                     
                } else {
                    $result = array("status"=>false,"msg"=>"No se pudo procesar la compra.");
                }   

                
            } else if(isset( $auth->message )){
                $result = array("status"=>false,"msg"=>$auth->message);
            }else {
                $result = array("status"=>false,"msg"=>"No se pudo procesar la compra.");
            }                     
        } else {
            $result = array("status"=>false,"msg"=>"No se pudo procesar la compra.");
        }      

        
        return $result;
    }

    public function getCurrentDateTime()
    {
        $datetime = new \DateTime();
        $timezone = new \DateTimeZone('America/Lima');
        $datetime->setTimezone($timezone);

        return $datetime;       
    }

    public function getDateTimeString($datetime = null)
    {
        if ($datetime == null) {
            $datetime = $this->getCurrentDateTime();
        } 
        
        return $datetime->format('Y-m-d') .'T'. $datetime->format('H:i:s') .'-05:00';        
    }


    public function obtener_status($tipopago){
        if($tipopago=="pagoefectivo"){
            $status  = trim( $this->_scopeConfig->getValue('payment/pagoefectivo_pay/order_status') );
        }else{
            $status  = trim( $this->_scopeConfig->getValue('payment/cuotealo_pay/order_status') );
        }
        
        return $status;
    }

    
}
