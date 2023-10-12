<?php
namespace Vexsoluciones\Pagoefectivo\Model;

use Magento\Store\Model\ScopeInterface;

class PagoefectivoConfigProvider extends \Magento\Checkout\Model\DefaultConfigProvider
{
    public function getConfig()
    {
        $output = parent::getConfig();

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $url = $storeManager->getStore()->getBaseUrl();

        $detalle = $this->scopeConfig->getValue('payment/pagoefectivo_pay/instructions',ScopeInterface::SCOPE_STORE);
        $detallecuotealo = $this->scopeConfig->getValue('payment/cuotealo_pay/instructions',ScopeInterface::SCOPE_STORE);
        $output['pagoefectivo_detalle'] = (empty($detalle))?"":$detalle;
        $output['cuotealo_detalle'] = (empty($detallecuotealo))?"":$detallecuotealo;


        $assetRepo = $objectManager->get('\Magento\Framework\View\Asset\Repository');
        $request = $objectManager->get('\Magento\Framework\App\RequestInterface');
        $appEmulation = $objectManager->get('\Magento\Store\Model\App\Emulation');
        $storeId = $storeManager->getStore()->getId();

        $appEmulation->startEnvironmentEmulation($storeId, \Magento\Framework\App\Area::AREA_FRONTEND, true);

        $params = array('_secure' => $request->isSecure());
        $imagencuotealo = $assetRepo->getUrlWithParams('Vexsoluciones_Pagoefectivo::image/cuotealo.png', $params);
        $imagenpago = $assetRepo->getUrlWithParams('Vexsoluciones_Pagoefectivo::image/pagoefectivo.png', $params);

        $appEmulation->stopEnvironmentEmulation();

        $output['imagen_cuotealo'] = $imagencuotealo;
        $output['imagen_pagoefectivo'] = $imagenpago;
        return $output;
    }
}