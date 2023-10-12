<?PHP

namespace Vexsoluciones\Facturacionelectronica\Vexfecore\DAO;


class envioFacturaDAO{

    private $cn = null;

    public $table = 'vexfe_envios_facturas';

    public $_fields = array(

        'id',
        'comprobante_id',
        'fecha_hora',
        'codigo_sunat',
        'sunat_estado',
        'resumen_respuesta'
    );

    public function __construct( \Vexsoluciones\Facturacionelectronica\Vexfecore\Database $cn){

        $this->cn = $cn;

    }

    public function registrar($params = array()){

        $ok = $this->cn->insert( $this->table, $params );

        return ($ok ? $ok : false);
    }


    public function get($params = array()){


        $sql = "SELECT * FROM "._VEXFE_DB_PREFIX_."vexfe_envios_facturas WHERE true ";


        if( array_key_exists('comprobante_id', $params) && trim($params['comprobante_id']) != ''){
            $sql.=" AND comprobante_id = ".$params['comprobante_id'];
        }

        if( array_key_exists('estado_sunat', $params) && trim($params['estado_sunat']) != ''){
            $sql.=" AND estado_sunat = ".$params['estado_sunat'];
        }

        if( array_key_exists('id', $params) && trim($params['id']) != ''){
            $sql.=" AND id = ".$params['id'];
        }

        if( array_key_exists('get_ultmo', $params) && $params['get_ultmo'] === true){
            $sql.=" ORDER BY fecha_hora DESC LIMIT 1";
        }

        $query_results = $this->cn->query( $sql );

        return $query_results;

    }

   //  public function_exists(function_name)

}
