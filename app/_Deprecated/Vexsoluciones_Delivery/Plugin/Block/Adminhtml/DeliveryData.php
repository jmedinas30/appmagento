<?PHP

namespace Vexsoluciones\Delivery\Plugin\Block\Adminhtml;

use Magento\Framework\Exception\LocalizedException;     


class DeliveryData
{
  
    public function afterToHtml(\Magento\Sales\Block\Adminhtml\Order\View\Info $subject, $result) {
 
        
        $order = $subject->getOrder();
         
        $blockShippingDate = $subject->getLayout()->createBlock(
            'Vexsoluciones\Delivery\Block\Adminhtml\Order\DeliveryData'
        );
 
        $blockShippingDate->setTemplate('Vexsoluciones_Delivery::order/delivery.phtml'); // FeFacturacionFieldsView
    
        if ($blockShippingDate !== false) {
  
            $result = $result.$blockShippingDate->toHtml();
        }
 
        return $result;
    }
}