<?PHP

namespace Vexsoluciones\Facturacionelectronica\Vexfecore\DAO;


class comprobanteDetalleDAO{

    private $cn = null;

    public $table = 'vexfe_comprobante_detalle';

    public $_fields = array(

        'id',
        'comprobante_id',
        'tipo_de_comprobante',
        'detalle',
        'cantidad',
        'precio_unitario',
        'subtotal',
        'sys_estado'
    );

    public function __construct( \Vexsoluciones\Facturacionelectronica\Vexfecore\Database $cn){

        $this->cn = $cn;

    }

    public function registrar($params = array()){


        $ok = $this->cn->insert( $this->table, $params );

        return ($ok ? $ok : false);
    }

    public function get($params = array()){

        $sql = "SELECT * FROM "._VEXFE_DB_PREFIX_."vexfe_comprobante_detalle WHERE true ";

        if(array_key_exists('id', $params) && $params['id'] != ''){
            $sql.=" AND id = ".$params['id'];
        }

        if( array_key_exists('comprobante_id', $params) && $params['comprobante_id'] != ''){
            $sql.=" AND comprobante_id = ".$params['comprobante_id'];
        }


        $results = $this->cn->query($sql);
        return $results;

    }

    public function map(){

    }

    public function actualizar(){

    }

}
