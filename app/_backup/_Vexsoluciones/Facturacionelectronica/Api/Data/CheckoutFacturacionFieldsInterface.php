<?php

/*
    Intefaz de los datos
*/

namespace Vexsoluciones\Facturacionelectronica\Api\Data;

/**
 * Interface CheckoutFacturacionFieldsInterface
 */

interface CheckoutFacturacionFieldsInterface
{   
    const REQUIERE_FACTURA = 'requiere_factura';
    const TIPO_DE_COMPROBANTE = 'vexfe_tipo_de_comprobante';
    const TIPO_DE_DOCUMENTO_CLIENTE = 'vexfe_tipo_de_documento_cliente';
    const NUMERO_DOCUMENTO = 'vexfe_numero_de_documento';  
    const CLIENTE_DENOMINACION = 'vexfe_denominacion_cliente'; 
    const DIRECCION_FISCAL = 'vexfe_direccion_fiscal'; 
    const OBSERVACION = 'vexfe_comentario';

    const COMPROBANTE_BOLETA = '3';
    const COMPROBANTE_FACTURA = '1';
    
    const COMPROBANTE_DNI = '1'; 
    const COMPROBANTE_RUC = '6';
    
    /**
     * Get Requiere facura
     *
     * @return string|null
     */
    public function getRequiereFactura();
    
    /**
     * Get Documento
     *
     * @return string|null
     */
    public function getDocumento();
    
    /**
     * Get Denominacion
     *
     * @return string|null
     */
    public function getDenominacion();
    
    /**
     * Get Direccion Fiscal
     *
     * @return string|null
     */
    public function getDireccionFiscal();
  
    /**
     * Set Requiere factura
     *
     * @param string|null $value
     *
     * @return CheckoutFacturacionFieldsInterface
     */
    public function setRequiereFactura($value = 0);

    /**
     * set Documento
     *
     * @param string|null $value
     *
     * @return CheckoutFacturacionFieldsInterface
     */
    public function setDocumento(string $documento = '');

    /**
     * Set Denominacion
     *
     * @param string|null $value
     *
     * @return CheckoutFacturacionFieldsInterface
     */
    public function setDenominacion(string $denominacion = null );

    /**
     * Set Direccion Fiscal
     *
     * @param string|null $value
     *
     * @return CheckoutFacturacionFieldsInterface
     */
    public function setDireccionFiscal(string $direccionfiscal = '' );
 
}
