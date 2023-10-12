<?php

namespace Vexsoluciones\Delivery\Block\Adminhtml\Order;

use Magento\Sales\Model\Order;  

class DeliveryData extends \Magento\Backend\Block\Template
{
 
    protected $coreRegistry = null;


    //protected $_template = 'order/view/facturacion_fields.phtml';
  
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        $this->_isScopePrivate = true;  
        parent::__construct($context, $data);
    }




    public function getDataOrder(){

        $order = $this->coreRegistry->registry('current_order');
        
        if($order){

        	return $order;
        
        }    

        return false;
    }
      
}
