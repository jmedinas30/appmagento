 
define(
    ['jquery',
     'underscore',
     'ko',
     'Magento_Checkout/js/model/quote',
     'Magento_Checkout/js/model/url-builder',
     'Magento_Customer/js/model/customer'],
    function ($, 
              _, 
              ko, 
              quote, 
              urlBuilder, 
              customer) {
        
        'use strict';
        
        let _keyApp =  'KEYHERE'; //readyAppModel.getKey();

        let rq = null;

        return {
            requiereFactura: ko.observable(false),
            tipoDeDocumento: ko.observable(''),
            documento : ko.observable(''),
            denominacion : ko.observable(''),
            direccionFiscal : ko.observable(''),
            observacion : ko.observable(''),
            datosIncompletos : ko.observable(false),
            datosIncompletos2 : ko.observable(false),

            saveData : function(){
 
                let facturacionData = {
                    'requiere_factura' : this.requiereFactura() ? '1' : '0',
                    'documento' : this.documento(),
                    'denominacion' : this.denominacion(),
                    'direccionFiscal' : this.direccionFiscal()
                }
 
                let serviceUrl = null; 

                if (customer.isLoggedIn()) {
               
                    serviceUrl = urlBuilder.createUrl('/carts/mine/set-facturacion-fields', {});
               
                } else {

                    serviceUrl = urlBuilder.createUrl('/guest-carts/:quoteId/set-facturacion-fields', {
                        quoteId: quote.getQuoteId()
                    }); 

                }
            
                let payload = {
                    'facturacionFields' : facturacionData
                }


                let urlSaveDatosFactura = window.location.protocol + '//' + window.location.host + '/';


               if(rq != null && rq.xhr != 4){
                   rq.abort();
               }

               rq = $.ajax({
                    url: urlSaveDatosFactura + serviceUrl,
                    data: JSON.stringify(payload),
                    sync: true,
                    showLoader: false,
                    type: 'POST',
                    dataType: 'json',
                    context: this, 
                    beforeSend: function(request) {  
                        request.setRequestHeader('Content-Type', 'application/json');

                        console.log('1.- Enviando datos de facturacion ');
                    } 
                });
 
            }
 
        };
    } 
);
