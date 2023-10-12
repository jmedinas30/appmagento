<?PHP

namespace Vexsoluciones\Pagoefectivo\Plugin\Block\Adminhtml;

use Magento\Framework\Exception\LocalizedException;     


class Pagoefectivo
{
  
    public function afterToHtml(\Magento\Sales\Block\Adminhtml\Order\View\Items $subject, $result) {
 
        
        $order = $subject->getOrder();
         
        $blockStockMovimientos = $subject->getLayout()->createBlock(
            'Vexsoluciones\Pagoefectivo\Block\Adminhtml\Order\Pagoefectivo'
        );
 
        $blockStockMovimientos->setTemplate('Vexsoluciones_Pagoefectivo::order/pagoefectivo.phtml');
    
        if ($blockStockMovimientos !== false) {
  
            $result = $result.$blockStockMovimientos->toHtml();
        }
 
        return $result;
    }
}