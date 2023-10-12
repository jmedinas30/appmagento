<?php

namespace Vexsoluciones\Facturacionelectronica\Model\Data;

use Magento\Framework\Api\AbstractExtensibleObject;
use Vexsoluciones\Facturacionelectronica\Api\Data\CheckoutFacturacionFieldsInterface;


class CheckoutFacturacionFields extends AbstractExtensibleObject implements CheckoutFacturacionFieldsInterface
{

    public function getRequiereFactura()
    {
        return $this->_get(self::REQUIERE_FACTURA);
    }

    public function getDocumento()
    {
        return $this->_get(self::NUMERO_DOCUMENTO);
    }

    public function getDenominacion()
    {
        return $this->_get(self::CLIENTE_DENOMINACION);
    }
 
    public function getDireccionFiscal()
    {
        return $this->_get(self::DIRECCION_FISCAL);
    }
 

    public function setRequiereFactura( $value = null )
    {
        return $this->setData(self::REQUIERE_FACTURA, $value);
    }


    public function setDocumento(string $documento = null)
    {
        return $this->setData(self::NUMERO_DOCUMENTO, $documento);
    }


    public function setDenominacion(string $denominacion = null)
    {
        return $this->setData(self::CLIENTE_DENOMINACION, $denominacion);
    }

    public function setDireccionFiscal(string $direccionfiscal = null)
    {
        return $this->setData(self::DIRECCION_FISCAL, $direccionfiscal);
    }
 
}
