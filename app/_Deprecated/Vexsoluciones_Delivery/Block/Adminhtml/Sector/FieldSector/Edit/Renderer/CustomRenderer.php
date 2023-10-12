<?php 
namespace Vexsoluciones\Delivery\Block\Adminhtml\Sector\FieldSector\Edit\Renderer;
 
 use Magento\Directory\Model\ResourceModel\Country\CollectionFactory as CountryCollectionFactory;
/**
* CustomFormField Customformfield field renderer
*/
class CustomRenderer extends \Magento\Framework\Data\Form\Element\AbstractElement
{

    protected $_countryCollectionFactory;

    public function __construct(
        CountryCollectionFactory $countryCollectionFactory
    )
    {
        $this->_countryCollectionFactory = $countryCollectionFactory;
    }

    /**
    * Get the after element html.
    *
    * @return mixed
    */
    public function getElementHtml()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $urlstyla = $storeManager->getStore()->getBaseUrl();

        
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();

        $sql = "SELECT * FROM directory_country_region;";
        $regiones = $connection->fetchAll($sql);

        $sql = "SELECT * FROM vexsoluciones_directorio_provincia;";
        $provincias = $connection->fetchAll($sql);

        $sql = "SELECT * FROM vexsoluciones_directorio_distrito;";
        $distritos = $connection->fetchAll($sql);

        $collection = $this->_countryCollectionFactory->create()->loadByStore();
        $collection->addFieldToSelect('*');


        $sector = $objectManager->get('Magento\Framework\Registry')->registry('sector');
        $contador = 1;

        $contadorprecios = 1;

        $id = $sector->getId();
        $pais = $sector->getData("country_id");

        $departamento = $sector->getData("departamento_id");
        $provincia = $sector->getData("provincia_id");
        $distrito = $sector->getData("distrito_id");

        $listahorarios = array();
        $listaprecios = array();
        if($id){
            $sqlaux = "SELECT * FROM vexsoluciones_reglas_horario where sector_id='".$id."';";
            $listaaux = $connection->fetchAll($sqlaux);
            foreach ($listaaux as $key2) {
                $listahorarios[$key2['dia']]['inicio'] = $key2['hora_inicio'];
                $listahorarios[$key2['dia']]['fin'] = $key2['hora_fin'];
                $listahorarios[$key2['dia']]['status'] = $key2['status'];
            }

            $sqlaux = "SELECT * FROM vexsoluciones_reglas_precio where sector_id='".$id."';";
            $listaprecios = $connection->fetchAll($sqlaux);

        }



        $dia1 = isset($listahorarios[1])?$listahorarios[1]:array();
        $dia2 = isset($listahorarios[2])?$listahorarios[2]:array();
        $dia3 = isset($listahorarios[3])?$listahorarios[3]:array();
        $dia4 = isset($listahorarios[4])?$listahorarios[4]:array();
        $dia5 = isset($listahorarios[5])?$listahorarios[5]:array();
        $dia6 = isset($listahorarios[6])?$listahorarios[6]:array();
        $dia7 = isset($listahorarios[7])?$listahorarios[7]:array();


        // here you can write your code.
        //$customDiv = '';
        ob_start();
        ?>

        <style type="text/css">
            
            .admin__scope-old .form-inline .field-sectores.no-label > .control{
                width: 100%;
                margin-left: 0px;
            }
            /*.field-sectores select {
                width: 100%;
            }*/
            .field-sectores input[type=text]{
                width: 60px;
            }
            .field-sectores table td{
                padding: 1.5rem!important;
            }

            #contenido_nuevo_precio{
                text-align: right;
                margin-top: 15px;
            }
            #contenido_nuevo_precio #button_nuevo_precio{

            }

        </style>
        <div class="admin__field field field-status" data-ui-id="adminhtml-sector-edit-tab-general-0-fieldset-element-form-field-status">
            <label class="label admin__field-label" for="status" data-ui-id="adminhtml-sector-edit-tab-general-0-fieldset-element-select-status-label"><span>Pais</span></label>
            <div class="admin__field-control control" style="margin-left: 0px;">
                <select id="country_id" name="country_id" class="select admin__control-select" formelementhookid="elemIdsBhCx13SRe" data-ui-id="adminhtml-sector-edit-tab-general-0-fieldset-element-select-status" aria-required="true">
                    <option value="0">Todos los paises</option>
                    <?php foreach ($collection as $country) {
                            if($country->getName()!="") {
                     ?>
                        <option value="<?= $country->getCountryId() ?>" <?= ($pais==$country->getCountryId())?"selected":"" ?>><?= $country->getName() ?></option>
                    <?php
                            }
                        }
                    ?>
                    
                </select>
            </div>
        </div>

        <div class="admin__field field field-status" data-ui-id="adminhtml-sector-edit-tab-general-0-fieldset-element-form-field-status">
            <label class="label admin__field-label" for="status" data-ui-id="adminhtml-sector-edit-tab-general-0-fieldset-element-select-status-label"><span>Departamento</span></label>
            <div class="admin__field-control control" style="margin-left: 0px;">
                <select id="departamento_id" name="departamento_id" class="select admin__control-select" formelementhookid="elemIdsBhCx13SRe" data-ui-id="adminhtml-sector-edit-tab-general-0-fieldset-element-select-status" aria-required="true">
                    <option value="0">Todos los departamentos</option>
                </select>
            </div>
        </div>

        <div class="admin__field field field-status" data-ui-id="adminhtml-sector-edit-tab-general-0-fieldset-element-form-field-status">
            <label class="label admin__field-label" for="status" data-ui-id="adminhtml-sector-edit-tab-general-0-fieldset-element-select-status-label"><span>Provincia</span></label>
            <div class="admin__field-control control" style="margin-left: 0px;">
                <select id="provincia_id" name="provincia_id" class="select admin__control-select" formelementhookid="elemIdsBhCx13SRe" data-ui-id="adminhtml-sector-edit-tab-general-0-fieldset-element-select-status" aria-required="true">
                    <option value="0">Todas las provincias</option>
                </select>
            </div>
        </div>

        <div class="admin__field field field-status" data-ui-id="adminhtml-sector-edit-tab-general-0-fieldset-element-form-field-status">
            <label class="label admin__field-label" for="status" data-ui-id="adminhtml-sector-edit-tab-general-0-fieldset-element-select-status-label"><span>Distrito</span></label>
            <div class="admin__field-control control" style="margin-left: 0px;">
                <select id="distrito_id" name="distrito_id" class="select admin__control-select" formelementhookid="elemIdsBhCx13SRe" data-ui-id="adminhtml-sector-edit-tab-general-0-fieldset-element-select-status" aria-required="true">
                    <option value="0">Todas las ciudades</option>
                </select>
            </div>
        </div>


        <div class="admin__field field field-status" data-ui-id="adminhtml-sector-edit-tab-general-0-fieldset-element-form-field-status">
            <label class="label admin__field-label" for="status" data-ui-id="adminhtml-sector-edit-tab-general-0-fieldset-element-select-status-label"><span>Horarios</span></label>
            <div class="admin__field-control control" style="margin-left: 0px;">
                <table class="data-grid">
                    
                    <tr><th class="data-grid-th">DIA</th><th class="data-grid-th">FECHA INICIO</th><th class="data-grid-th">FECHA FIN</th><th class="data-grid-th">ESTADO</th></tr>
                    <tr class="data-grid-tr-no-data even">
                        <td>Lunes</td>
                        <td><select name="fechas[1][inicio]"><?= $this->generarcomboinicio($dia1) ?></select></td>
                        <td><select name="fechas[1][fin]"><?= $this->generarcombofin($dia1) ?></select></td>
                        <td><select name="fechas[1][status]"><?= $this->generarcombostatus($dia1) ?></select></td>
                    </tr>
                    <tr class="data-grid-tr-no-data even">
                        <td>Martes</td>
                        <td><select name="fechas[2][inicio]"><?= $this->generarcomboinicio($dia2) ?></select></td>
                        <td><select name="fechas[2][fin]"><?= $this->generarcombofin($dia2) ?></select></td>
                        <td><select name="fechas[2][status]"><?= $this->generarcombostatus($dia2) ?></select></td>
                    </tr>
                    <tr class="data-grid-tr-no-data even">
                        <td>Miercoles</td>
                        <td><select name="fechas[3][inicio]"><?= $this->generarcomboinicio($dia3) ?></select></td>
                        <td><select name="fechas[3][fin]"><?= $this->generarcombofin($dia3) ?></select></td>
                        <td><select name="fechas[3][status]"><?= $this->generarcombostatus($dia3) ?></select></td>
                    </tr>
                    <tr class="data-grid-tr-no-data even">
                        <td>Jueves</td>
                        <td><select name="fechas[4][inicio]"><?= $this->generarcomboinicio($dia4) ?></select></td>
                        <td><select name="fechas[4][fin]"><?= $this->generarcombofin($dia4) ?></select></td>
                        <td><select name="fechas[4][status]"><?= $this->generarcombostatus($dia4) ?></select></td>
                    </tr>
                    <tr class="data-grid-tr-no-data even">
                        <td>Viernes</td>
                        <td><select name="fechas[5][inicio]"><?= $this->generarcomboinicio($dia5) ?></select></td>
                        <td><select name="fechas[5][fin]"><?= $this->generarcombofin($dia5) ?></select></td>
                        <td><select name="fechas[5][status]"><?= $this->generarcombostatus($dia5) ?></select></td>
                    </tr>
                    <tr class="data-grid-tr-no-data even">
                        <td>Sabado</td>
                        <td><select name="fechas[6][inicio]"><?= $this->generarcomboinicio($dia6) ?></select></td>
                        <td><select name="fechas[6][fin]"><?= $this->generarcombofin($dia6) ?></select></td>
                        <td><select name="fechas[6][status]"><?= $this->generarcombostatus($dia6) ?></select></td>
                    </tr>
                    <tr class="data-grid-tr-no-data even">
                        <td>Domingo</td>
                        <td><select name="fechas[7][inicio]"><?= $this->generarcomboinicio($dia7) ?></select></td>
                        <td><select name="fechas[7][fin]"><?= $this->generarcombofin($dia7) ?></select></td>
                        <td><select name="fechas[7][status]"><?= $this->generarcombostatus($dia7) ?></select></td>
                    </tr>
                </table>
            </div>
        </div>


        <div class="admin__field field field-status" data-ui-id="adminhtml-sector-edit-tab-general-0-fieldset-element-form-field-status">
            <label class="label admin__field-label" for="status" data-ui-id="adminhtml-sector-edit-tab-general-0-fieldset-element-select-status-label"><span>Precios</span></label>
            <div class="admin__field-control control" style="margin-left: 0px;">
                <table class="data-grid" id="tabla-precios-delivery">
                    <thead><tr><th class="data-grid-th">Peso minimo</th><th class="data-grid-th">Peso maximo</th><th class="data-grid-th">Precio</th><th class="data-grid-th">ESTADO</th><th class="data-grid-th"></th></tr></thead>
                    
                    <tbody>
                    <?php foreach ($listaprecios as $key) { ?>
                        <tr class="data-grid-tr-no-data even" id="tr-precios-<?= $contadorprecios ?>">
                            <td><input type="text" value="<?= $key['peso_inicio'] ?>" name="precios[<?= $contadorprecios ?>][minimo]"></td>
                            <td><input type="text" value="<?= $key['peso_fin'] ?>" name="precios[<?= $contadorprecios ?>][maximo]"></td>
                            <td><input type="text" value="<?= $key['precio'] ?>" name="precios[<?= $contadorprecios ?>][precio]"></td>
                            <td><select name="precios[<?= $contadorprecios ?>][status]"><option <?= ($key['status']==1)?"selected":"" ?> value="1">Habilitado</option><option <?= ($key['status']==0)?"selected":"" ?> value="0">Deshabilitado</option></select></td>
                            <td><input type="hidden" name="precios[<?= $contadorprecios ?>][id]" value="<?= $key['id'] ?>"><button class="eliminar_precio" data-id="<?= $contadorprecios ?>" type="button">Eliminar</button></td>
                        '</tr>
                    <?php $contadorprecios = $contadorprecios + 1; } ?>
                    </tbody>
                </table>

                <div id="contenido_nuevo_precio">
                    <?php foreach ($listaprecios as $key) { ?>
                        <input type="hidden" name="listaprecios[]" value="<?= $key['id'] ?>">
                    <?php } ?>
                    <button id="button_nuevo_precio" type="button">Agregar nuevo precio</button>
                </div>
            </div>
        </div>


        <script type="text/javascript">
            require(
            [
                "jquery",
                "Magento_Ui/js/modal/modal",
                'mage/adminhtml/wysiwyg/tiny_mce/setup'
            ],
            function(
                $,
                modal
            ) {

                var urlstyla = '<?= $urlstyla ?>';
                var regiones = JSON.parse('<?= str_replace("'", "", json_encode($regiones)) ?>');
                var provincias = JSON.parse('<?= json_encode($provincias) ?>');
                var distritos = JSON.parse('<?= json_encode($distritos) ?>'); 

                var contadorprecios = <?= $contadorprecios ?>;

                var departamentoaux = '<?= $departamento ?>';
                var provinciaaux = '<?= $provincia ?>';
                var distritoaux = '<?= $distrito ?>';

                var departamentoauxstatus = true;
                var provinciaauxstatus = true;
                var distritoauxstatus = true;

                $(document).on('change', '#country_id', function (e) {
                    let regionactual = $("#country_id").val();
                    $("#departamento_id").empty();
                    $("#departamento_id").append(new Option("Todos las regiones", "0"));
                    $.each(regiones, function(index, el) {
                        if(el.country_id==regionactual){
                            $("#departamento_id").append(new Option(el.default_name, el.region_id));
                        }
                    });

                    if(departamentoauxstatus){
                        departamentoauxstatus = false;
                        if(departamentoaux!=""){
                            $("#departamento_id").val(departamentoaux);
                        }
                    }

                    $("#departamento_id").change();
                });
                $("#country_id").change();



                $(document).on('change', '#departamento_id', function (e) {

                    let departamentoactual = $('#departamento_id').val();

                    $("#provincia_id").empty();
                    $("#provincia_id").append(new Option("Todas las provincias", "0"));
                        

                    $.each(provincias, function(index, el) {
                        if(el.region_id==departamentoactual){
                            $("#provincia_id").append(new Option(el.nombre_provincia, el.id));
                        }
                    });

                    if(provinciaauxstatus){
                        provinciaauxstatus = false;
                        if(provinciaaux!=""){
                            $("#provincia_id").val(provinciaaux);
                        }
                    }
                        
                    $('#provincia_id').trigger("change");
                        
                });
                $("#departamento_id").change();



                $(document).on('change', '#provincia_id', function (e) {

                    let provinciaactual = $('#provincia_id').val();

                    $("#distrito_id").empty();
                    $("#distrito_id").append(new Option("Todas las ciudades", "0"));
                        

                    $.each(distritos, function(index, el) {
                        if(el.provincia_id==provinciaactual){
                            $("#distrito_id").append(new Option(el.nombre_distrito, el.id));
                        }
                    });

                    if(distritoauxstatus){
                        distritoauxstatus = false;
                        if(distritoaux!=""){
                            $("#distrito_id").val(distritoaux);
                        }
                    }
                        
                });
                $("#provincia_id").change();




                $(document).on('click', '#button_nuevo_precio', function (e) {
                    contadorprecios = contadorprecios + 1;
                    let html2 = '<tr class="data-grid-tr-no-data even" id="tr-precios-'+contadorprecios+'">'+
                            '<td><input type="text" name="precios['+contadorprecios+'][minimo]"></td>'+
                            '<td><input type="text" name="precios['+contadorprecios+'][maximo]"></td>'+
                            '<td><input type="text" name="precios['+contadorprecios+'][precio]"></td>'+
                            '<td><select name="precios['+contadorprecios+'][status]"><option value="1">Habilitado</option><option value="0">Deshabilitado</option></select></td>'+
                            '<td><button class="eliminar_precio" data-id="'+contadorprecios+'" type="button">Eliminar</button></td>'+
                        '</tr>';

                    $("#tabla-precios-delivery tbody").append(html2);

                });

                $(document).on('click', '.eliminar_precio', function (e) {
                    
                    let co = $(this).data("id");
                    $("#tabla-precios-delivery tbody #tr-precios-"+co).remove();

                });



                
            });
        </script>
        <?php
        $customDiv = ob_get_clean();
        //$customDiv = '';
        return $customDiv;
    }

    public function generarcombostatus($value=''){

        $value = (isset($value['status']))?$value['status']:1;

        $t = ($value==0)?" selected":"";
        $t1 = ($value==1)?" selected":"";

        $r = '<option value="1"'.$t1.'>Habilitado</option>';
        $r .= '<option value="0"'.$t.'>Deshabilitado</option>';

        return $r;
    }

    public function generarcomboinicio($value='')
    {

        $value = (isset($value['inicio']))?$value['inicio']:0;

        $t = ($value==0)?" selected":"";
        $t1 = ($value==1)?" selected":"";
        $t2 = ($value==2)?" selected":"";
        $t3 = ($value==3)?" selected":"";
        $t4 = ($value==4)?" selected":"";
        $t5 = ($value==5)?" selected":"";
        $t6 = ($value==6)?" selected":"";
        $t7 = ($value==7)?" selected":"";
        $t8 = ($value==8)?" selected":"";
        $t9 = ($value==9)?" selected":"";
        $t10 = ($value==10)?" selected":"";
        $t11 = ($value==11)?" selected":"";
        $t12 = ($value==12)?" selected":"";
        $t13 = ($value==13)?" selected":"";
        $t14 = ($value==14)?" selected":"";
        $t15 = ($value==15)?" selected":"";
        $t16 = ($value==16)?" selected":"";
        $t17 = ($value==17)?" selected":"";
        $t18 = ($value==18)?" selected":"";
        $t19 = ($value==19)?" selected":"";
        $t20 = ($value==20)?" selected":"";
        $t21 = ($value==21)?" selected":"";
        $t22 = ($value==22)?" selected":"";
        $t23 = ($value==23)?" selected":"";

        $r = '<option value="0"'.$t.'>00:00</option>';
        $r .= '<option value="1"'.$t1.'>01:00</option>';
        $r .= '<option value="2"'.$t2.'>02:00</option>';
        $r .= '<option value="3"'.$t3.'>03:00</option>';
        $r .= '<option value="4"'.$t4.'>04:00</option>';
        $r .= '<option value="5"'.$t5.'>05:00</option>';
        $r .= '<option value="6"'.$t6.'>06:00</option>';
        $r .= '<option value="7"'.$t7.'>07:00</option>';
        $r .= '<option value="8"'.$t8.'>08:00</option>';
        $r .= '<option value="9"'.$t9.'>09:00</option>';
        $r .= '<option value="10"'.$t10.'>10:00</option>';
        $r .= '<option value="11"'.$t11.'>11:00</option>';
        $r .= '<option value="12"'.$t12.'>12:00</option>';
        $r .= '<option value="13"'.$t13.'>13:00</option>';
        $r .= '<option value="14"'.$t14.'>14:00</option>';
        $r .= '<option value="15"'.$t15.'>15:00</option>';
        $r .= '<option value="16"'.$t16.'>16:00</option>';
        $r .= '<option value="17"'.$t17.'>17:00</option>';
        $r .= '<option value="18"'.$t18.'>18:00</option>';
        $r .= '<option value="19"'.$t19.'>19:00</option>';
        $r .= '<option value="20"'.$t20.'>20:00</option>';
        $r .= '<option value="21"'.$t21.'>21:00</option>';
        $r .= '<option value="22"'.$t22.'>22:00</option>';
        $r .= '<option value="23"'.$t23.'>23:00</option>';
        return $r;
    }


    public function generarcombofin($value='')
    {
        $value = (isset($value['fin']))?$value['fin']:0;

        $t1 = ($value==1)?" selected":"";
        $t2 = ($value==2)?" selected":"";
        $t3 = ($value==3)?" selected":"";
        $t4 = ($value==4)?" selected":"";
        $t5 = ($value==5)?" selected":"";
        $t6 = ($value==6)?" selected":"";
        $t7 = ($value==7)?" selected":"";
        $t8 = ($value==8)?" selected":"";
        $t9 = ($value==9)?" selected":"";
        $t10 = ($value==10)?" selected":"";
        $t11 = ($value==11)?" selected":"";
        $t12 = ($value==12)?" selected":"";
        $t13 = ($value==13)?" selected":"";
        $t14 = ($value==14)?" selected":"";
        $t15 = ($value==15)?" selected":"";
        $t16 = ($value==16)?" selected":"";
        $t17 = ($value==17)?" selected":"";
        $t18 = ($value==18)?" selected":"";
        $t19 = ($value==19)?" selected":"";
        $t20 = ($value==20)?" selected":"";
        $t21 = ($value==21)?" selected":"";
        $t22 = ($value==22)?" selected":"";
        $t23 = ($value==23)?" selected":"";
        $t24 = ($value==24)?" selected":"";

        
        $r = '<option value="1"'.$t1.'>01:00</option>';
        $r .= '<option value="2"'.$t2.'>02:00</option>';
        $r .= '<option value="3"'.$t3.'>03:00</option>';
        $r .= '<option value="4"'.$t4.'>04:00</option>';
        $r .= '<option value="5"'.$t5.'>05:00</option>';
        $r .= '<option value="6"'.$t6.'>06:00</option>';
        $r .= '<option value="7"'.$t7.'>07:00</option>';
        $r .= '<option value="8"'.$t8.'>08:00</option>';
        $r .= '<option value="9"'.$t9.'>09:00</option>';
        $r .= '<option value="10"'.$t10.'>10:00</option>';
        $r .= '<option value="11"'.$t11.'>11:00</option>';
        $r .= '<option value="12"'.$t12.'>12:00</option>';
        $r .= '<option value="13"'.$t13.'>13:00</option>';
        $r .= '<option value="14"'.$t14.'>14:00</option>';
        $r .= '<option value="15"'.$t15.'>15:00</option>';
        $r .= '<option value="16"'.$t16.'>16:00</option>';
        $r .= '<option value="17"'.$t17.'>17:00</option>';
        $r .= '<option value="18"'.$t18.'>18:00</option>';
        $r .= '<option value="19"'.$t19.'>19:00</option>';
        $r .= '<option value="20"'.$t20.'>20:00</option>';
        $r .= '<option value="21"'.$t21.'>21:00</option>';
        $r .= '<option value="22"'.$t22.'>22:00</option>';
        $r .= '<option value="23"'.$t23.'>23:00</option>';
        $r .= '<option value="24"'.$t24.'>00:00</option>';
        return $r;
    }

}