<?PHP
/*
    Esta clase es un Wrapper para los metodos propios de la plataforma
*/

namespace Vexsoluciones\Facturacionelectronica\Vexfecore;


class Database{

    private $driver = '';

    public $connection = null;

    public function __construct($driver = ''){

        $this->driver = $driver;

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager

        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');

        $this->connection = $resource->getConnection();

    }

    public function query($sql){

        $result = $this->connection->fetchAll($sql);

        return $result;

    }

    public function execute($sql){

        $rs = $this->connection->query($sql);

        return ($rs ? true : false);

    }

    public function insert($table, $params = array()){
 
        $sql = "INSERT INTO "._VEXFE_DB_PREFIX_."$table ";

        $fields = array();
        $values = array();

        foreach ($params as $field => $value) {

            // Validacion
            array_push($fields, $field);
            array_push($values, $value);
        }

        $sql.="(".implode(',', $fields ).") ";
        $sql.=" VALUES ('".implode("','", $values)."') ";

        $rs = $this->connection->query($sql);

        if($rs != false){

            $sql ="SELECT LAST_INSERT_ID() as last_id; ";
            $rs = $this->connection->fetchAll($sql);
            $id = $rs[0]['last_id'];

            return $id;
        }
        else
        {
            return false;
        }

    }

    public function delete(){

    }




    private function __clone() {}

    private function __sleep() {}

    private function __wakeup() {}

}
