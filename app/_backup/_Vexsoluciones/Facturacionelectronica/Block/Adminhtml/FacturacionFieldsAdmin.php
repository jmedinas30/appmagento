<?php


namespace Vexsoluciones\Facturacionelectronica\Block\Adminhtml;

use Magento\Sales\Model\Order;
use Vexsoluciones\Facturacionelectronica\Api\Data\CheckoutFacturacionFieldsInterface;
use Vexsoluciones\Facturacionelectronica\Api\CheckoutFacturacionFieldsRepositoryInterface;


class FacturacionFieldsAdmin extends \Magento\Backend\Block\Template
{

    protected $coreRegistry = null;

    //protected $_template = 'order/view/facturacion_fields.phtml';

    protected $facturacionFieldsRepository;

    private $comprobanteDAO = null;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        CheckoutFacturacionFieldsRepositoryInterface $facturacionFieldsRepository,
        \Vexsoluciones\Facturacionelectronica\Vexfecore\DAO\comprobanteDAO $comprobanteDAO,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        $this->facturacionFieldsRepository = $facturacionFieldsRepository;
        $this->_isScopePrivate = true;

        $this->comprobanteDAO = $comprobanteDAO;

        parent::__construct($context, $data);
    }


    public function getOrderId() 
    {
        
        $order = $this->coreRegistry->registry('current_order');
        
        $order_id = '';

        if(!$order){

            $invoice = $this->coreRegistry->registry('current_invoice');

            if($invoice){

               $order_id = $invoice->getOrderId();
                
            }
 
        }
        else
        {
            $order_id = $order->getId();
        } 
        
        return $order_id;
    }

    public function getComprobanteInfo(){

        //$orderId = $this->getOrderId();
        //list($infoComprobante) = $this->comprobanteDAO->get(['order_id' => $orderId]);
        //return $infoComprobante;
        $order = $this->coreRegistry->registry('current_order');
        $tipo_comprobante = $order->getData('vexfe_tipo_de_comprobante');
        if ($tipo_comprobante == 1){
            $documento = 'FACTURA';
            $ruc = $order->getData('vexfe_numero_de_documento');
            $razon_social = $order->getData('vexfe_denominacion_cliente');
            $direccion_fiscal = $order->getData('vexfe_direccion_fiscal');
        }else{
            $documento = 'BOLETA';
            $ruc = '';
            $razon_social = '';
            $direccion_fiscal = '';
        }

        $paymentmethod = $order->getPayment()->getMethod();
        if($paymentmethod=="visanet_pay"){
            $medio_pago = 'TARJETA';
        }else{
            $medio_pago = 'EFECTIVO';
        }

        return [
            'documento' => $documento,
            'ruc' => $ruc,
            'razon_social' => $razon_social,
            'direccion_fiscal' => $direccion_fiscal,
            'method_payment' => $medio_pago
        ];


        //
    }
 
}
