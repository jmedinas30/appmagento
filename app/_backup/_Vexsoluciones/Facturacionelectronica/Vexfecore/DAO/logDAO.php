<?PHP

namespace Vexsoluciones\Facturacionelectronica\Vexfecore\DAO;


class logDAO{

    private $cn = null;

    public $table = 'vexfe_log';

    public $_fields = array(

        'id',
        'tipo_objeto',
        'objeto_id',
        'descripcion_operacion',
        'descripcion_respuesta'
    );

    public function __construct( \Vexsoluciones\Facturacionelectronica\Vexfecore\Database $cn){

        $this->cn = $cn;

    }

    public function registrar($params = array()){

        $ok = $this->cn->insert( $this->table, $params );

        return $ok;
    }

    public function get($params = array()){


         $sql = "SELECT *
                 FROM "._VEXFE_DB_PREFIX_."vexfe_log
                 WHERE TRUE ";

        if( array_key_exists('comprobante_id', $params) && $params['comprobante_id'] != ''){
            $sql.=" AND objeto_id = ".$params['comprobante_id'];
        }

        $results = $this->cn->query($sql);
        return $results;

    }

    public function map(){

    }

    public function actualizar(){

    }

}
