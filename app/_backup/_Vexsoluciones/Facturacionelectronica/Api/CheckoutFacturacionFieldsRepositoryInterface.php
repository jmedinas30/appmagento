<?php
/*
    Interfaz del servicio
*/
namespace Vexsoluciones\Facturacionelectronica\Api;


use Magento\Sales\Model\Order;
use Vexsoluciones\Facturacionelectronica\Api\Data\CheckoutFacturacionFieldsInterface;


interface CheckoutFacturacionFieldsRepositoryInterface
{
  /**
     * Save checkout custom fields
     *
     * @param string                   $cartId       Cart id
     * @param \Vexsoluciones\Facturacionelectronica\Api\Data\CheckoutFacturacionFieldsInterface $facturacionFields Custom fields
     *
     * @return \Vexsoluciones\Facturacionelectronica\Api\Data\CheckoutFacturacionFieldsInterface
     */
    public function saveFacturacionFields(
        string $cartId,
        CheckoutFacturacionFieldsInterface $facturacionFields
    ): CheckoutFacturacionFieldsInterface;

    /**
     * Save checkout custom fields
     *
     * @param string                   $cartId       Cart id
     * @param \Vexsoluciones\Facturacionelectronica\Api\Data\CheckoutFacturacionFieldsInterface $facturacionFields Custom fields
     *
     * @return \Vexsoluciones\Facturacionelectronica\Api\Data\CheckoutFacturacionFieldsInterface
     */
    public function saveFacturacionFieldsGuest(
        string $cartId,
        CheckoutFacturacionFieldsInterface $facturacionFields
    ): CheckoutFacturacionFieldsInterface;


    /**
     * Save store.
     * @api
     * @param mixed $data
     *
     * @return array
     */
    public function getRuc($data);
}
