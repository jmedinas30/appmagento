<?PHP 

namespace Vexsoluciones\Delivery\Block;

class Scriptgoogle extends \Magento\Framework\View\Element\Template
{
	
    public function getApiKey()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $key = $objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface')->getValue('delivery/general/key');
        return $key;
    }

    public function getActiveMapa()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $key = $objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface')->getValue('delivery/general/activemapa');
        return $key;
    }
}