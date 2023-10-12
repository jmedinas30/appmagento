define(
    ['Vexsoluciones_Facturacionelectronica/js/model/datos'],
    function (dataFacturacion) {
        'use strict';
        return {
           
            validate: function() {
            

                if(dataFacturacion.requiereFactura() ){
 
                    if( dataFacturacion.documento() == '' || dataFacturacion.denominacion() == '' || dataFacturacion.direccionFiscal() == ''  ){

                        return false;
                    }
                    dataFacturacion.saveData();
                }

                return true;
            }
        }
    }
);