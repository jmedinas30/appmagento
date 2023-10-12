<?php 

namespace Vexsoluciones\Pagoefectivo\Block\Adminhtml\Order;

use Magento\Sales\Model\Order; 

class Pagoefectivo extends \Magento\Backend\Block\Template
{

    protected $stockMovimiento;
    protected $coreRegistry = null;

    
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry, 
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        $this->_isScopePrivate = true; 
        parent::__construct($context, $data);
    }


    public function getOrderData() 
    {
        
        $order = $this->coreRegistry->registry('current_order');
        return $order;
    } 
 
   
   
}
