<?php

namespace Vexsoluciones\Delivery\Helper;

use Magento\Framework\View\Asset\Repository;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    
    public $_scopeConfig;
    public $_storeManager;
    public $tiendafactory;
    public $resource;
    public $_customerRepositoryInterface;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
        \Magento\Framework\App\ResourceConnection $resource,
        \Vexsoluciones\Delivery\Model\TiendaFactory $tiendafactory
    ) {
        parent::__construct($context);
        $this->_scopeConfig = $context->getScopeConfig();
        $this->resource = $resource;
        $this->_storeManager = $storeManager;
        $this->tiendafactory = $tiendafactory;
        $this->_customerRepositoryInterface = $customerRepositoryInterface;
    }



    public function obtenerprecio($pais,$tipoenvio,$region,$provincia,$distrito,$dia,$hora,$peso){
        $precio = 0;

        $sqlaux = "";
        if($tipoenvio!="3"){
            $sqlaux = " and vexsoluciones_reglas_horario.dia='".$dia."' and vexsoluciones_reglas_horario.hora_inicio<=".$hora." and vexsoluciones_reglas_horario.hora_fin>".$hora." ";
        }else{
            $sqlaux = " and vexsoluciones_reglas_horario.hora_inicio<vexsoluciones_reglas_horario.hora_fin ";
        }

        $sql = "select vexsoluciones_reglas_precio.precio,vexsoluciones_reglas_sector.id from vexsoluciones_reglas_sector 
                inner join vexsoluciones_reglas_horario on vexsoluciones_reglas_horario.sector_id = vexsoluciones_reglas_sector.id 
                inner join vexsoluciones_reglas_precio on vexsoluciones_reglas_precio.sector_id = vexsoluciones_reglas_sector.id
                where vexsoluciones_reglas_sector.status=1 and vexsoluciones_reglas_horario.status=1 and vexsoluciones_reglas_precio.status=1
                    and vexsoluciones_reglas_precio.peso_inicio<=".$peso." and peso_fin>=".$peso." and vexsoluciones_reglas_sector.tipo_envio='".$tipoenvio."' 
                    and (vexsoluciones_reglas_sector.country_id='0' OR vexsoluciones_reglas_sector.country_id='".$pais."') 
                    and (vexsoluciones_reglas_sector.departamento_id='0' OR vexsoluciones_reglas_sector.departamento_id='".$region."') 
                    and (vexsoluciones_reglas_sector.provincia_id='0' OR vexsoluciones_reglas_sector.provincia_id='".$provincia."') 
                    and (vexsoluciones_reglas_sector.distrito_id='0' OR vexsoluciones_reglas_sector.distrito_id='".$distrito."')".$sqlaux." limit 1;";

        //echo $sql;die;

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $listaprecios = $connection->fetchAll($sql);

        $status = false;
        $id = 0;
        foreach ($listaprecios as $key) {
            $precio = $key['precio'];
            $id = $key['id'];
            $status = true;
        }

        return array("id"=>$id,"status"=>$status,"precio"=>$precio);
    }

    public function tiposenvio()
    {
        return array("1"=>"Envio regular","2"=>"Envio express","3"=>"Envio programado");
    }
    /*public function setIdeasbookDetalle($detalle,$datos,$cliente,$tipo,$elementoid,$foto,$album){

        $model = $this->_commentFactoryAlbumDetalle->create();
        $model->setData('detalle', $detalle);
        $model->setData('detalle_datos', $datos);
        $model->setData('clienteid', $cliente);
        $model->setData('tipo', $tipo);
        $model->setData('elementoid', $elementoid);
        $model->setData('status', 1);
        $model->setData('fecha', date("Y-m-d H:i:s"));
        $model->setData('imagen_foto',$foto);
        $model->setData('album_id',$album);
        $model->save();
        return $model;
    }
    public function getideasbookById($id){
        $model = $this->_commentFactoryAlbum->create()->load($id);
        return $model;
    }
    public function validarpathideasbook($string){
        $path = $this->crear_permalink($string);
        $model = $this->_commentFactoryAlbum->create()->load($path, 'album_permalink');
        if($model->getId()){
            return false;
        }
        return $path;
    }


    public function setContacto($profesional,$correo,$mensaje){

        $model = $this->_commentFactoryContactos->create()->getCollection();
        $model->addFieldToFilter('profesional', $profesional);
        $model->addFieldToFilter('correo', $correo);
        $model->addFieldToFilter('status', 1);
        $model = $model->getFirstItem();

        if($model->getId()){
            $model->setMensaje($mensaje)->save();
            return $model;
        }else{
            $model2 = $this->_commentFactoryContactos->create();
            $model2->setData('profesional', $profesional);
            $model2->setData('correo', $correo);
            $model2->setData('mensaje', $mensaje);
            $model2->setData('status', 1);
            $model2->setData('fecha', date("Y-m-d H:i:s"));
            $model2->save();
            return $model2;
        }
        
        
    }
*/

    
}
