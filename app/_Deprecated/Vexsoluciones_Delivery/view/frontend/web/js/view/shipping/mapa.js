define([
    'ko',
    'uiComponent',
    'jquery',
    'Magento_Checkout/js/model/quote',
    'Magento_Customer/js/model/address-list',
    'Magento_Customer/js/customer-data',
    'Magento_Checkout/js/model/shipping-rate-registry',
    'Magento_Checkout/js/model/shipping-rate-processor/new-address'
], function (ko, Component,$,quote,addressList,customerData,rateRegistry,ratesUbigeo) {
    'use strict';

    var countryData = customerData.get('directory-data');

    return Component.extend({
        defaults: {
            template: 'Vexsoluciones_Delivery/shipping/mapa'
        },
        mapGeoCode : null,
        marcadorDelivery : null,
        marcadorCoordenadas : ko.observable({}),
        verificareventoend : null,
        isFormInline: addressList().length === 0,
        callemapa : ko.observable(''),
        initObservable: function () {
            this.mapa = window.statusmapa;

            if(this.mapa){
                var self = this;
                var interval = setInterval(function () {
                    
                    if($("#mapaGeoDecode").length == 0) {

                    }else{

                        clearInterval(interval);
                        $("#contenedor-mapa").show();
                        self.generarMapaDelivery();


                        var interval2 = setInterval(function () {
                            let addressaux = quote.shippingAddress();
                            let pais = self.getCountryName(addressaux.countryId);
                            let calle = '';

                            if (addressList().length === 0) {
                                calle = $("input[name='street[0]']").val()+" "+addressaux.region+" "+pais;
                            }else{
                                calle = addressaux.street ? addressaux.street[0]+" "+addressaux.region+" "+pais : '';
                            }
                            
                            if(self.callemapa()!= calle){

                                self.callemapa(calle);
                                self.generarMapaDelivery();
                            }

                        }, 1500);


                    }
                    
                }, 1000);
            }

            if (addressList().length === 0) {
                
                $(document).on("change","select[name='city']",function () {
                    let address = quote.shippingAddress();
                    address.city = $("select[name='city']").val();
                    address.country_id = $("select[name='country_id']").val();
                    rateRegistry.set(address.getCacheKey(), null);
                    ratesUbigeo.getRates(address);
                });

                $(document).on("change","select[name='region_id']",function () {
                    let valorregion = $("select[name='region_id']").val();
                    if(valorregion!=""){
                       let address = quote.shippingAddress();
                        address.regionId = parseInt(valorregion);
                        address.country_id = $("select[name='country_id']").val();
                        rateRegistry.set(address.getCacheKey(), null);
                        ratesUbigeo.getRates(address); 
                    }
                    
                });
                

            }

            return this;
        },
        generarMapaDelivery : function(){

            let self = this;  

            if(this.mapGeoCode==null){
                
                this.mapGeoCode = new google.maps.Map(document.getElementById('mapaGeoDecode'), {
                      zoom: 15,
                      center: {lat: -12.1103058, lng: -77.0513356}
                });

                this.geocoder = new google.maps.Geocoder();
            }

            let addressaux = quote.shippingAddress();
            let pais = this.getCountryName(addressaux.countryId);
            let calle = "";

            if (addressList().length === 0) {
                calle = $("input[name='street[0]']").val()+" "+addressaux.region+" "+pais;
            }else{
                calle = addressaux.street ? addressaux.street[0]+" "+addressaux.region+" "+pais : '';
            }
            
            let address = (calle!="")?calle:"Lima, Perú";

            this.coberturas = [];
            this.geocoder.geocode({'address': address}, function(results, status) {
            
              if (status === 'OK') {

                try{

                    self.mapGeoCode.setCenter(results[0].geometry.location);

                    self.marcadorCoordenadas({ lat: results[0].geometry.location.lat(), 
                                               lng: results[0].geometry.location.lng() });
                    $("#coordenadas-mapa").val(results[0].geometry.location.lat()+","+results[0].geometry.location.lng());

                    if(self.marcadorDelivery==null){
                        self.marcadorDelivery = new google.maps.Marker({
                              map: self.mapGeoCode,
                              position: results[0].geometry.location,
                              draggable: true
                        });
                    }else{   
                        self.marcadorDelivery.setPosition(results[0].geometry.location);
                    }

                }
                catch(err){
                    console.log(err);
                }

                if(self.verificareventoend==null){
                    self.verificareventoend = true;
                    self.marcadorDelivery.addListener('dragend', function(event){
                         self.marcadorCoordenadas({lat: event.latLng.lat(), lng: event.latLng.lng() });
                         $("#coordenadas-mapa").val(event.latLng.lat()+","+event.latLng.lng());
                    });
                }
                

             
              } else {
            
                console.log('Geocode was not successful for the following reason: ' + status);
            
              }
            
            });


        },
        getCountryName: function (countryId) {
            return countryData()[countryId] != undefined ? countryData()[countryId].name : ''; //eslint-disable-line
        },


    });
});
