<?php


namespace Vexsoluciones\Facturacionelectronica\Block\Order;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Registry;
use Magento\Sales\Model\Order;
use Vexsoluciones\Facturacionelectronica\Api\Data\CheckoutFacturacionFieldsInterface;
use Vexsoluciones\Facturacionelectronica\Api\CheckoutFacturacionFieldsRepositoryInterface;


class FacturacionFields extends Template
{

    protected $coreRegistry = null;
    protected $session = null;

    protected $facturacionFieldsRepository;

    private $comprobanteDAO = null;

    public function __construct(
        Context $context,
        Registry $registry,
        CheckoutFacturacionFieldsRepositoryInterface $facturacionFieldsRepository,
        \Vexsoluciones\Facturacionelectronica\Vexfecore\DAO\comprobanteDAO $comprobanteDAO,
        \Magento\Customer\Model\Session $session,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        $this->session = $session;
        $this->facturacionFieldsRepository = $facturacionFieldsRepository;
        $this->_isScopePrivate = true;
        $this->_template = 'order/view/facturacion_fields.phtml';

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

        $orderId = $this->getOrderId();

        list($infoComprobante) = $this->comprobanteDAO->get(['order_id' => $orderId]);

        return $infoComprobante;
    }
}
