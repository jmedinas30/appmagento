<?PHP

namespace Vexsoluciones\Facturacionelectronica\Vexfecore\DAO;

use Vexsoluciones\Facturacionelectronica\Vexfecore\Database;

class comprobanteDAO{

    protected $cn = null;

    public $tipo_de_comprobante = '';

    public $table = 'vexfe_comprobantes';

    private $tipos_de_documentos = array(

        '1' => 'FACTURA',
        '2' => 'BOLETA',
        '3' => 'NOTA DE CRÉDITO',
        '4' => 'NOTA DE DÉBITO'

    );

    private $tipos_de_comprobantes = array(

        '2' => 'BOLETA',
        '1' => 'FACTURA'

    );

    private $tipo_de_documento_cliente = array(

       // '-'  => 'VARIOS',
        '6'  => 'RUC',
        '1'  => 'DNI',
        '4'  => 'CARNET DE EXTRANJERÍA',
        '7'  => 'PASAPORTE',
        'A'  => 'CÉDULA DIPLOMÁTICA DE IDENTIDAD',
        '0'  => 'NO DOMICILIADO, SIN RUC (EXPORTACIÓN)'

    );

    public function __construct( \Vexsoluciones\Facturacionelectronica\Vexfecore\Database $cn){

        $this->cn = $cn;

    }


    public function getTiposDocumentoCliente(){

        return $this->tipo_de_documento_cliente;

    }

    public function getTiposDeDocumentos(){

        return $this->tipos_de_documentos;

    }

    public function getTiposDeComprobantes(){

        return $this->tipos_de_comprobantes;

    }


    public $_fields = array(

        'ref_tipo',
        'ref_id',
        'tipo_de_comprobante',
        'serie',
        'numero',
        'cliente_tipo_de_documento',
        'cliente_numero_de_documento',
        'cliente_denominacion',
        'cliente_direccion',
        'fecha_de_emision',
        'moneda',
        'porcentaje_de_igv',
        'total_gravada',
        'total_igv',
        'total'
    );

    public function registrar($params = array()){

        $ok = $this->cn->insert( $this->table, $params );

        return ($ok ? $ok : false);
    }

    public function get($params = array()){

         /*
         // Version anterior
         $sql = "SELECT comprobante.*,
                        COALESCE(envio_sunat_fallidos.total,0) as sunat_envios_fallidos,
                        COALESCE(envio_sunat_conformes.total,0) as sunat_envios_conformes

                 FROM "._VEXFE_DB_PREFIX_."vexfe_comprobantes comprobante
                 LEFT JOIN (
                     SELECT comprobante_id,
                            count(*) as total
                     FROM "._VEXFE_DB_PREFIX_."vexfe_envios_facturas
                     WHERE estado_sunat = ".constants::$FACTURA_ENVIADA_OK."
                     GROUP BY comprobante_id
                     ORDER BY comprobante_id
                 ) as envio_sunat_conformes ON comprobante.comprobante_id = envio_sunat_conformes.comprobante_id

                 LEFT JOIN (
                     SELECT comprobante_id,
                            count(*) as total
                     FROM "._VEXFE_DB_PREFIX_."vexfe_envios_facturas
                     WHERE estado_sunat = ".constants::$FACTURA_ENVIO_FALLIDO."
                     GROUP BY comprobante_id
                     ORDER BY comprobante_id
                 ) as envio_sunat_fallidos ON comprobante.comprobante_id = envio_sunat_fallidos.comprobante_id

                 WHERE TRUE ";
        */


        $sql = "SELECT comprobante.*
                FROM "._VEXFE_DB_PREFIX_."vexfe_comprobantes comprobante
                WHERE TRUE ";


        if( array_key_exists('tipo_de_comprobante', $params) && $params['tipo_de_comprobante'] != ''){
            $sql.=" AND tipo_de_comprobante = ".$params['tipo_de_comprobante'];
        }

        if( array_key_exists('id', $params) && $params['id'] != ''){
            $sql.=" AND comprobante.comprobante_id = ".$params['id'];
        }

        if( array_key_exists('order_id', $params) && $params['order_id'] != ''){
            //$sql.=" AND comprobante.ref_id = ".$params['order_id'];
        }

        $results = $this->cn->query($sql);
        return $results;

    }

    public function map(){

    }

    public function actualizar(){

    }

    public function actualizar_respuesta_sunat( $params = array() ){


        $sql = "UPDATE "._VEXFE_DB_PREFIX_."vexfe_comprobantes
                SET sunat_respuesta_envio = '".$params['sunat_respuesta_envio']."'
                WHERE  comprobante_id = '".$params['comprobante_id']."' ";


        $row = $this->cn->execute($sql);
        return $row;
    }

    public function actualizar_respuesta_data( $params = array() ){


        $sql = "UPDATE "._VEXFE_DB_PREFIX_."vexfe_comprobantes
                SET data_respuesta = '".$params['data']."'
                WHERE  comprobante_id = '".$params['comprobante_id']."' ";


        $row = $this->cn->execute($sql);
        return $row;
    }


    public function generar_comprobante_impreso($comprobante_id){

        require_once _VEXFE_MODULE_DIR_.'/libs/TCPDF-6.2.13/tcpdf.php';

        $datosEnvio = array();

        $db = new Database();

        $objComprobante = new comprobanteFacturaDAO($db);
        list($infoComprobante) = $objComprobante->get(array('id' => $comprobante_id ));

        $objDetalleComprobante = new comprobanteDetalleDAO($db);

        $detalleComprobante = $objDetalleComprobante->get(array('comprobante_id' => $comprobante_id ));

        $objConfig = new configDAO($db);

        $objConfigInfo = $objConfig->get();

        $logo_name = $objConfigInfo['logo'];
        $certificado_name = $objConfigInfo['certificado'];

        $logo_url = '';

        $codigo =  $infoComprobante['serie'].'-'.$infoComprobante['numero'];

        $documento = array(
                           'tipo_de_comprobante' => 'FACTURA',
                           'razonsocial' => $objConfigInfo['razonsocial'],
                           'ruc' => $objConfigInfo['ruc'],
                           'direccion' => $objConfigInfo['direccion'],
                           'serie' => $codigo,
                           'cliente_denominacion' => $infoComprobante['cliente_denominacion'],
                           'cliente_ruc' => $infoComprobante['cliente_numero_de_documento'],
                           'cliente_direccion' =>  $infoComprobante['cliente_direccion'],
                           'cliente_moneda' =>  $infoComprobante['moneda'],
                           'fecha_de_emision' =>  $infoComprobante['fecha_de_emision'],
                           'operacion_gravada' => $infoComprobante['total_gravada'],
                           'igv' => $infoComprobante['total_igv'],
                           'total' => $infoComprobante['total'],
                           'url_consulta_comprobante' => 'https://portal.vexsoluciones.pe/appefacturacion',
                           'hash_documento' => md5('2132173') );


        $documento_detalle = array();
        $cod = 1;
        foreach ($detalleComprobante as $reg) {

            $row = array(
                'codigo' => $cod,
                'descripcion' => $reg['detalle'],
                'unidad' => $reg['unidad_medida'],
                'cantidad' => $reg['cantidad'],
                'precio_unitario' => $reg['precio_unitario'],
                'subtotal' => $reg['subtotal']
            );

            array_push($documento_detalle, $row );
            $cod++;
        }


        $pdf_nombre = $objConfigInfo['ruc'].'-'.$codigo.'.pdf';


        $path_archivo = _VEXFE_DIR_DOCUMENTOS_.DIRECTORY_SEPARATOR.$pdf_nombre;
        $path_logo = _VEXFE_DIR_CONFIG_FILES_.DIRECTORY_SEPARATOR.$logo_name;


        require_once _VEXFE_MODULE_DIR_.'/Vexfecore/templates/PDF/factura.php';

    }


    public function getResumenTotales($params = array()){

        return array();
    }
}
