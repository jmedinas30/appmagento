<?php
namespace Vexsoluciones\Delivery\Model;

use Magento\Framework\Data\OptionSourceInterface;


class TipoEnvio implements OptionSourceInterface
{
    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $tipos_envio = $objectManager->get('Vexsoluciones\Delivery\Helper\Data')->tiposenvio();

        $listaenvios = array();
        foreach ($tipos_envio as $key => $value) {
            $listaenvios[] = array(
                "value" => $key,
                "label"=> $value
            );
        }

        return $listaenvios;
    }
}
