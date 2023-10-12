<?PHP

namespace Vexsoluciones\Facturacionelectronica\Vexfecore;

/*
    Esta clase es la que sabe como se deben realizar las operaciones
*/

class Vexfecore{

    private $configDAO = null;
    private $serieDAO = null;
    private $comprobanteDAO = null;
    private $comprobanteDetalleDAO = null;
    private $serviceFeAPI = null;

    public function __construct(DAO\configDAO $configDAO,
                                DAO\serieDAO $serieDAO,
                                DAO\comprobanteDAO $comprobanteDAO,
                                DAO\comprobanteDetalleDAO $comprobanteDetalleDAO,
                                serviceFeAPI $serviceFeAPI ){

        $this->configDAO = $configDAO;
        $this->serieDAO = $serieDAO;
        $this->comprobanteDAO = $comprobanteDAO;
        $this->comprobanteDetalleDAO = $comprobanteDetalleDAO;
        $this->serviceFeAPI = $serviceFeAPI;

    }

    /*
        Este método genera los comprobantes, hay que considerar que en caso de fallo el numero de serie se pierde
    */
    public function generarComprobante( $paramsCliente, $paramsPedido, $paramsDetalle ){

      //  var_dump($paramsCliente, $paramsPedido, $paramsDetalle); 
        $configParametros = array();

        $configParametros = $this->configDAO->getConfigParametros();

        list($serie, $numero) = $this->serieDAO->siguente_numero_serie_comprobante(array('tipo_comprobante' => trim($paramsPedido['tipo_de_comprobante']) ));

 
        $paramsComprobante = array(
                 'serie' => $serie,
                 'numero' => $numero,
                 'tipo_de_comprobante' => ( trim($paramsPedido['tipo_de_comprobante']) != '' ?  trim($paramsPedido['tipo_de_comprobante']) : '' ),
                 'cliente_email' =>  ( trim($paramsCliente['cliente_email']) != '' ?  trim($paramsCliente['cliente_email']) : '' ),
                 'cliente_tipo_de_documento' => ( trim($paramsCliente['cliente_tipo_de_documento']) != '' ?  trim($paramsCliente['cliente_tipo_de_documento']) : '' ),
                 'cliente_numero_de_documento' => ( trim($paramsCliente['cliente_numero_de_documento']) != '' ?  trim($paramsCliente['cliente_numero_de_documento']) : '' ),
                 'cliente_denominacion' => ( trim($paramsCliente['cliente_denominacion']) != '' ?  trim($paramsCliente['cliente_denominacion']) : '' ),
                 'cliente_direccion' => ( trim($paramsCliente['cliente_direccion']) != '' ?  trim($paramsCliente['cliente_direccion']) : '' ),
                 'ref_tipo' => $paramsPedido['ref_tipo'],
                 'ref_id' =>  $paramsPedido['ref_id'],
                 'fecha_de_emision' => $paramsPedido['fecha_de_emision'], // fecha actual
                 'moneda' => $configParametros['moneda'],
                 'porcentaje_de_igv' => $configParametros['igv'],
                 'total_gravada' => $paramsPedido['total_gravada'],
                 'total_igv' => $paramsPedido['total_igv'],
                 'total' => $paramsPedido['total']
        );
 
        $comprobante_id =  $this->comprobanteDAO->registrar($paramsComprobante);



        foreach ($paramsDetalle as $regDetalle) {


            if($regDetalle['precio_referencial'] == ''){

                $impuestoU = round( $regDetalle['precio_referencial'] *  $configParametros['igv'], 2 );
                $precio_referencial = round($regDetalle['precio_referencial'],2) + $impuestoU;
            }
            else{
                $precio_referencial = $regDetalle['precio_referencial'];
            }


            if($regDetalle['impuesto'] == ''){

                $impuestoST = round( $regDetalle['subtotal'] *  $configParametros['igv'], 2 );

            }
            else{
                $impuestoST = $regDetalle['impuesto'];
            }

            $params = array('comprobante_id' => $comprobante_id,
                            'detalle' => $regDetalle['detalle'],
                            'cantidad' => ($regDetalle['cantidad'] != '' ? $regDetalle['cantidad'] : '1' ),
                            'precio_unitario' => $regDetalle['precio_unitario'],
                            'precio_referencial' => $precio_referencial,
                            'unidad_medida' => ( $regDetalle['unidad_medida'] == '' ?  $configParametros['unidad_de_medida'] : $regDetalle['unidad_medida']),
                            'subtotal' =>  $regDetalle['subtotal'],
                            'impuesto' =>  $impuestoST,
                            'product_id' =>  $regDetalle['product_id']
                            );

            $this->comprobanteDetalleDAO->registrar($params);


        }

        $this->serieDAO->aumentar_serie_comprobante(array('tipo_comprobante' => trim($paramsPedido['tipo_de_comprobante']) ));


        $result = $this->serviceFeAPI->enviar_comprobante($comprobante_id, trim($paramsPedido['tipo_de_comprobante']) );

        if($result){

            return $comprobante_id;
        }
        else
        {

           return false;
        }


    }

}
