<?php
/**
 * Copyright © 2016 MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\GeoIP\Helper;

/**
 * GeoIP Info helper
 */
class Info extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     *
     * To avoid inconsistency between Magento and Maxmind region names.
     * 01-11-2016 - http://geolite.maxmind.com/download/geoip/database/GeoLite2-City-CSV.zip
     *
     * @return array
     */
    public function getMaxmindData()
    {
        $data =
            [
                'AD' =>
                    [
                        'value' => 'AD',
                        'label' => __('Andorra'),
                        'regions' =>
                            [
                                'Andorra la Vella' => __('Andorra la Vella'),
                                'Canillo' => __('Canillo'),
                                'Encamp' => __('Encamp'),
                                'Escaldes-Engordany' => __('Escaldes-Engordany'),
                                'La Massana' => __('La Massana'),
                                'Ordino' => __('Ordino'),
                                'Sant Julià de Loria' => __('Sant Julià de Loria'),
                            ],
                    ],
                'AE' =>
                    [
                        'value' => 'AE',
                        'label' => __('United Arab Emirates'),
                        'regions' =>
                            [
                                'Abu Dhabi' => __('Abu Dhabi'),
                                'Ajman' => __('Ajman'),
                                'Al Fujayrah' => __('Al Fujayrah'),
                                'Ash Shariqah' => __('Ash Shariqah'),
                                'Dubai' => __('Dubai'),
                                'Ra\'s al Khaymah' => __('Ra\'s al Khaymah'),
                            ],
                    ],
                'AF' =>
                    [
                        'value' => 'AF',
                        'label' => __('Afghanistan'),
                        'regions' =>
                            [
                                'Herat' => __('Herat'),
                                'Kabul' => __('Kabul'),
                                'Kandahar' => __('Kandahar'),
                                'Zabul' => __('Zabul'),
                            ],
                    ],
                'AG' =>
                    [
                        'value' => 'AG',
                        'label' => __('Antigua and Barbuda'),
                        'regions' =>
                            [
                                'Barbuda' => __('Barbuda'),
                                'Parish of Saint George' => __('Parish of Saint George'),
                                'Parish of Saint John' => __('Parish of Saint John'),
                                'Parish of Saint Mary' => __('Parish of Saint Mary'),
                                'Parish of Saint Paul' => __('Parish of Saint Paul'),
                                'Parish of Saint Peter' => __('Parish of Saint Peter'),
                                'Parish of Saint Philip' => __('Parish of Saint Philip'),
                            ],
                    ],
                'AI' =>
                    [
                        'value' => 'AI',
                        'label' => __('Anguilla'),
                        'regions' =>
                            [
                            ],
                    ],
                'AL' =>
                    [
                        'value' => 'AL',
                        'label' => __('Albania'),
                        'regions' =>
                            [
                                'Qarku i Beratit' => __('Qarku i Beratit'),
                                'Qarku i Dibres' => __('Qarku i Dibres'),
                                'Qarku i Durresit' => __('Qarku i Durresit'),
                                'Qarku i Elbasanit' => __('Qarku i Elbasanit'),
                                'Qarku i Fierit' => __('Qarku i Fierit'),
                                'Qarku i Gjirokastres' => __('Qarku i Gjirokastres'),
                                'Qarku i Korces' => __('Qarku i Korces'),
                                'Qarku i Kukesit' => __('Qarku i Kukesit'),
                                'Qarku i Lezhes' => __('Qarku i Lezhes'),
                                'Qarku i Shkodres' => __('Qarku i Shkodres'),
                                'Qarku i Tiranes' => __('Qarku i Tiranes'),
                                'Qarku i Vlores' => __('Qarku i Vlores'),
                            ],
                    ],
                'AM' =>
                    [
                        'value' => 'AM',
                        'label' => __('Armenia'),
                        'regions' =>
                            [
                                'Aragatsotn Province' => __('Aragatsotn Province'),
                                'Ararat Province' => __('Ararat Province'),
                                'Armavir Province' => __('Armavir Province'),
                                'Gegharkunik Province' => __('Gegharkunik Province'),
                                'Kotayk Province' => __('Kotayk Province'),
                                'Lori Province' => __('Lori Province'),
                                'Shirak Province' => __('Shirak Province'),
                                'Syunik Province' => __('Syunik Province'),
                                'Tavush Province' => __('Tavush Province'),
                                'Yerevan' => __('Yerevan'),
                            ],
                    ],
                'AO' =>
                    [
                        'value' => 'AO',
                        'label' => __('Angola'),
                        'regions' =>
                            [
                                'Bengo Province' => __('Bengo Province'),
                                'Benguela' => __('Benguela'),
                                'Bíe' => __('Bíe'),
                                'Cabinda' => __('Cabinda'),
                                'Cuando Cobango' => __('Cuando Cobango'),
                                'Cuanza Norte Province' => __('Cuanza Norte Province'),
                                'Cunene Province' => __('Cunene Province'),
                                'Huambo' => __('Huambo'),
                                'Luanda Norte' => __('Luanda Norte'),
                                'Luanda Province' => __('Luanda Province'),
                                'Lunda Sul' => __('Lunda Sul'),
                                'Malanje Province' => __('Malanje Province'),
                                'Moxico' => __('Moxico'),
                                'Namibe Province' => __('Namibe Province'),
                                'Uíge' => __('Uíge'),
                            ],
                    ],
                'AQ' =>
                    [
                        'value' => 'AQ',
                        'label' => __('Antarctica'),
                        'regions' =>
                            [
                            ],
                    ],
                'AR' =>
                    [
                        'value' => 'AR',
                        'label' => __('Argentina'),
                        'regions' =>
                            [
                                'Buenos Aires' => __('Buenos Aires'),
                                'Buenos Aires F.D.' => __('Buenos Aires F.D.'),
                                'Catamarca Province' => __('Catamarca Province'),
                                'Chaco Province' => __('Chaco Province'),
                                'Chubut Province' => __('Chubut Province'),
                                'Cordoba Province' => __('Cordoba Province'),
                                'Corrientes Province' => __('Corrientes Province'),
                                'Entre Ríos Province' => __('Entre Ríos Province'),
                                'Formosa Province' => __('Formosa Province'),
                                'Jujuy Province' => __('Jujuy Province'),
                                'La Pampa Province' => __('La Pampa Province'),
                                'La Rioja Province' => __('La Rioja Province'),
                                'Mendoza Province' => __('Mendoza Province'),
                                'Misiones Province' => __('Misiones Province'),
                                'Neuquén Province' => __('Neuquén Province'),
                                'Río Negro Province' => __('Río Negro Province'),
                                'Salta Province' => __('Salta Province'),
                                'San Juan Province' => __('San Juan Province'),
                                'San Luis Province' => __('San Luis Province'),
                                'Santa Cruz Province' => __('Santa Cruz Province'),
                                'Santa Fe Province' => __('Santa Fe Province'),
                                'Santiago del Estero Province' => __('Santiago del Estero Province'),
                                'Tierra del Fuego Province' => __('Tierra del Fuego Province'),
                                'Tucumán Province' => __('Tucumán Province'),
                            ],
                    ],
                'AS' =>
                    [
                        'value' => 'AS',
                        'label' => __('American Samoa'),
                        'regions' =>
                            [
                                'Eastern District' => __('Eastern District'),
                            ],
                    ],
                'AT' =>
                    [
                        'value' => 'AT',
                        'label' => __('Austria'),
                        'regions' =>
                            [
                                'Burgenland' => __('Burgenland'),
                                'Carinthia' => __('Carinthia'),
                                'Lower Austria' => __('Lower Austria'),
                                'Salzburg' => __('Salzburg'),
                                'Styria' => __('Styria'),
                                'Tyrol' => __('Tyrol'),
                                'Upper Austria' => __('Upper Austria'),
                                'Vienna' => __('Vienna'),
                                'Vorarlberg' => __('Vorarlberg'),
                            ],
                    ],
                'AU' =>
                    [
                        'value' => 'AU',
                        'label' => __('Australia'),
                        'regions' =>
                            [
                                'Australian Capital Territory' => __('Australian Capital Territory'),
                                'New South Wales' => __('New South Wales'),
                                'Northern Territory' => __('Northern Territory'),
                                'Queensland' => __('Queensland'),
                                'South Australia' => __('South Australia'),
                                'Tasmania' => __('Tasmania'),
                                'Victoria' => __('Victoria'),
                                'Western Australia' => __('Western Australia'),
                            ],
                    ],
                'AW' =>
                    [
                        'value' => 'AW',
                        'label' => __('Aruba'),
                        'regions' =>
                            [
                            ],
                    ],
                'AX' =>
                    [
                        'value' => 'AX',
                        'label' => __('Åland'),
                        'regions' =>
                            [
                            ],
                    ],
                'AZ' =>
                    [
                        'value' => 'AZ',
                        'label' => __('Azerbaijan'),
                        'regions' =>
                            [
                                'Baku City' => __('Baku City'),
                                'Imishli Rayon' => __('Imishli Rayon'),
                                'Nakhichevan' => __('Nakhichevan'),
                                'Qusar Rayon' => __('Qusar Rayon'),
                                'Sabirabad Rayon' => __('Sabirabad Rayon'),
                                'Sumqayit City' => __('Sumqayit City'),
                            ],
                    ],
                'BA' =>
                    [
                        'value' => 'BA',
                        'label' => __('Bosnia and Herzegovina'),
                        'regions' =>
                            [
                                'Brčko' => __('Brčko'),
                                'Federation of Bosnia and Herzegovina' => __('Federation of Bosnia and Herzegovina'),
                                'Republic of Srspka' => __('Republic of Srspka'),
                            ],
                    ],
                'BB' =>
                    [
                        'value' => 'BB',
                        'label' => __('Barbados'),
                        'regions' =>
                            [
                                'Christ Church' => __('Christ Church'),
                                'Saint Andrew' => __('Saint Andrew'),
                                'Saint George' => __('Saint George'),
                                'Saint James' => __('Saint James'),
                                'Saint Joseph' => __('Saint Joseph'),
                                'Saint Lucy' => __('Saint Lucy'),
                                'Saint Michael' => __('Saint Michael'),
                                'Saint Philip' => __('Saint Philip'),
                                'Saint Thomas' => __('Saint Thomas'),
                            ],
                    ],
                'BD' =>
                    [
                        'value' => 'BD',
                        'label' => __('Bangladesh'),
                        'regions' =>
                            [
                                'Barisal Division' => __('Barisal Division'),
                                'Chittagong' => __('Chittagong'),
                                'Dhaka Division' => __('Dhaka Division'),
                                'Khulna Division' => __('Khulna Division'),
                                'Rajshahi Division' => __('Rajshahi Division'),
                                'Rangpur Division' => __('Rangpur Division'),
                                'Sylhet Division' => __('Sylhet Division'),
                            ],
                    ],
                'BE' =>
                    [
                        'value' => 'BE',
                        'label' => __('Belgium'),
                        'regions' =>
                            [
                                'Brussels Capital' => __('Brussels Capital'),
                                'Flanders' => __('Flanders'),
                                'Wallonia' => __('Wallonia'),
                            ],
                    ],
                'BF' =>
                    [
                        'value' => 'BF',
                        'label' => __('Burkina Faso'),
                        'regions' =>
                            [
                                'Cascades Region' => __('Cascades Region'),
                                'Centre' => __('Centre'),
                                'Hauts-Bassins' => __('Hauts-Bassins'),
                            ],
                    ],
                'BG' =>
                    [
                        'value' => 'BG',
                        'label' => __('Bulgaria'),
                        'regions' =>
                            [
                                'Blagoevgrad' => __('Blagoevgrad'),
                                'Burgas' => __('Burgas'),
                                'Gabrovo' => __('Gabrovo'),
                                'Haskovo' => __('Haskovo'),
                                'Lovech' => __('Lovech'),
                                'Oblast Dobrich' => __('Oblast Dobrich'),
                                'Oblast Kardzhali' => __('Oblast Kardzhali'),
                                'Oblast Kyustendil' => __('Oblast Kyustendil'),
                                'Oblast Montana' => __('Oblast Montana'),
                                'Oblast Pleven' => __('Oblast Pleven'),
                                'Oblast Razgrad' => __('Oblast Razgrad'),
                                'Oblast Ruse' => __('Oblast Ruse'),
                                'Oblast Shumen' => __('Oblast Shumen'),
                                'Oblast Silistra' => __('Oblast Silistra'),
                                'Oblast Sliven' => __('Oblast Sliven'),
                                'Oblast Smolyan' => __('Oblast Smolyan'),
                                'Oblast Stara Zagora' => __('Oblast Stara Zagora'),
                                'Oblast Targovishte' => __('Oblast Targovishte'),
                                'Oblast Veliko Tarnovo' => __('Oblast Veliko Tarnovo'),
                                'Oblast Vidin' => __('Oblast Vidin'),
                                'Oblast Vratsa' => __('Oblast Vratsa'),
                                'Oblast Yambol' => __('Oblast Yambol'),
                                'Pazardzhik' => __('Pazardzhik'),
                                'Pernik' => __('Pernik'),
                                'Plovdiv' => __('Plovdiv'),
                                'Sofia Province' => __('Sofia Province'),
                                'Sofia-Capital' => __('Sofia-Capital'),
                                'Varna' => __('Varna'),
                            ],
                    ],
                'BH' =>
                    [
                        'value' => 'BH',
                        'label' => __('Bahrain'),
                        'regions' =>
                            [
                                'Central Governorate' => __('Central Governorate'),
                                'Manama' => __('Manama'),
                                'Muharraq' => __('Muharraq'),
                                'Southern Governorate' => __('Southern Governorate'),
                            ],
                    ],
                'BI' =>
                    [
                        'value' => 'BI',
                        'label' => __('Burundi'),
                        'regions' =>
                            [
                                'Bujumbura Mairie Province' => __('Bujumbura Mairie Province'),
                            ],
                    ],
                'BJ' =>
                    [
                        'value' => 'BJ',
                        'label' => __('Benin'),
                        'regions' =>
                            [
                                'Atlantique Department' => __('Atlantique Department'),
                                'Littoral' => __('Littoral'),
                            ],
                    ],
                'BL' =>
                    [
                        'value' => 'BL',
                        'label' => __('Saint-Barthélemy'),
                        'regions' =>
                            [
                            ],
                    ],
                'BM' =>
                    [
                        'value' => 'BM',
                        'label' => __('Bermuda'),
                        'regions' =>
                            [
                                'Hamilton city' => __('Hamilton city'),
                                'Saint George' => __('Saint George'),
                                'Sandys Parish' => __('Sandys Parish'),
                            ],
                    ],
                'BN' =>
                    [
                        'value' => 'BN',
                        'label' => __('Brunei'),
                        'regions' =>
                            [
                                'Belait District' => __('Belait District'),
                                'Brunei and Muara District' => __('Brunei and Muara District'),
                                'Temburong District' => __('Temburong District'),
                                'Tutong District' => __('Tutong District'),
                            ],
                    ],
                'BO' =>
                    [
                        'value' => 'BO',
                        'label' => __('Bolivia'),
                        'regions' =>
                            [
                                'Departamento de Chuquisaca' => __('Departamento de Chuquisaca'),
                                'Departamento de Cochabamba' => __('Departamento de Cochabamba'),
                                'Departamento de La Paz' => __('Departamento de La Paz'),
                                'Departamento de Pando' => __('Departamento de Pando'),
                                'Departamento de Potosi' => __('Departamento de Potosi'),
                                'Departamento de Santa Cruz' => __('Departamento de Santa Cruz'),
                                'Departamento de Tarija' => __('Departamento de Tarija'),
                                'El Beni' => __('El Beni'),
                                'Oruro' => __('Oruro'),
                            ],
                    ],
                'BQ' =>
                    [
                        'value' => 'BQ',
                        'label' => __('Bonaire, Sint Eustatius, and Saba'),
                        'regions' =>
                            [
                                'Bonaire' => __('Bonaire'),
                                'Saba' => __('Saba'),
                            ],
                    ],
                'BR' =>
                    [
                        'value' => 'BR',
                        'label' => __('Brazil'),
                        'regions' =>
                            [
                                'Acre' => __('Acre'),
                                'Alagoas' => __('Alagoas'),
                                'Amapa' => __('Amapa'),
                                'Amazonas' => __('Amazonas'),
                                'Bahia' => __('Bahia'),
                                'Ceara' => __('Ceara'),
                                'Espirito Santo' => __('Espirito Santo'),
                                'Federal District' => __('Federal District'),
                                'Goias' => __('Goias'),
                                'Maranhao' => __('Maranhao'),
                                'Mato Grosso' => __('Mato Grosso'),
                                'Mato Grosso do Sul' => __('Mato Grosso do Sul'),
                                'Minas Gerais' => __('Minas Gerais'),
                                'Para' => __('Para'),
                                'Parana' => __('Parana'),
                                'Paraíba' => __('Paraíba'),
                                'Pernambuco' => __('Pernambuco'),
                                'Piaui' => __('Piaui'),
                                'Rio Grande do Norte' => __('Rio Grande do Norte'),
                                'Rio Grande do Sul' => __('Rio Grande do Sul'),
                                'Rio de Janeiro' => __('Rio de Janeiro'),
                                'Rondonia' => __('Rondonia'),
                                'Roraima' => __('Roraima'),
                                'Santa Catarina' => __('Santa Catarina'),
                                'Sao Paulo' => __('Sao Paulo'),
                                'Sergipe' => __('Sergipe'),
                                'Tocantins' => __('Tocantins'),
                            ],
                    ],
                'BS' =>
                    [
                        'value' => 'BS',
                        'label' => __('Bahamas'),
                        'regions' =>
                            [
                                'Bimini' => __('Bimini'),
                                'Central Abaco District' => __('Central Abaco District'),
                                'City of Freeport District' => __('City of Freeport District'),
                                'Harbour Island' => __('Harbour Island'),
                                'New Providence District' => __('New Providence District'),
                                'North Andros District' => __('North Andros District'),
                                'North Eleuthera' => __('North Eleuthera'),
                            ],
                    ],
                'BT' =>
                    [
                        'value' => 'BT',
                        'label' => __('Bhutan'),
                        'regions' =>
                            [
                                'Chukha District' => __('Chukha District'),
                                'Mongar District' => __('Mongar District'),
                                'Thimphu Dzongkhag' => __('Thimphu Dzongkhag'),
                            ],
                    ],
                'BW' =>
                    [
                        'value' => 'BW',
                        'label' => __('Botswana'),
                        'regions' =>
                            [
                                'Central District' => __('Central District'),
                                'Kweneng District' => __('Kweneng District'),
                                'North-East' => __('North-East'),
                                'North-West' => __('North-West'),
                                'South-East' => __('South-East'),
                            ],
                    ],
                'BY' =>
                    [
                        'value' => 'BY',
                        'label' => __('Belarus'),
                        'regions' =>
                            [
                                'Brest' => __('Brest'),
                                'Gomel' => __('Gomel'),
                                'Grodnenskaya' => __('Grodnenskaya'),
                                'Minsk' => __('Minsk'),
                                'Minsk City' => __('Minsk City'),
                                'Mogilev' => __('Mogilev'),
                                'Vitebsk' => __('Vitebsk'),
                            ],
                    ],
                'BZ' =>
                    [
                        'value' => 'BZ',
                        'label' => __('Belize'),
                        'regions' =>
                            [
                                'Belize District' => __('Belize District'),
                                'Cayo District' => __('Cayo District'),
                                'Corozal District' => __('Corozal District'),
                                'Orange Walk District' => __('Orange Walk District'),
                                'Stann Creek District' => __('Stann Creek District'),
                                'Toledo District' => __('Toledo District'),
                            ],
                    ],
                'CA' =>
                    [
                        'value' => 'CA',
                        'label' => __('Canada'),
                        'regions' =>
                            [
                                'Alberta' => __('Alberta'),
                                'British Columbia' => __('British Columbia'),
                                'Manitoba' => __('Manitoba'),
                                'New Brunswick' => __('New Brunswick'),
                                'Newfoundland and Labrador' => __('Newfoundland and Labrador'),
                                'Northwest Territories' => __('Northwest Territories'),
                                'Nova Scotia' => __('Nova Scotia'),
                                'Nunavut' => __('Nunavut'),
                                'Ontario' => __('Ontario'),
                                'Prince Edward Island' => __('Prince Edward Island'),
                                'Quebec' => __('Quebec'),
                                'Saskatchewan' => __('Saskatchewan'),
                                'Yukon' => __('Yukon'),
                            ],
                    ],
                'CC' =>
                    [
                        'value' => 'CC',
                        'label' => __('Cocos [Keeling] Islands'),
                        'regions' =>
                            [
                            ],
                    ],
                'CD' =>
                    [
                        'value' => 'CD',
                        'label' => __('Congo'),
                        'regions' =>
                            [
                                'Bas-Congo' => __('Bas-Congo'),
                                'Katanga Province' => __('Katanga Province'),
                                'Kinshasa City' => __('Kinshasa City'),
                                'Nord Kivu' => __('Nord Kivu'),
                                'South Kivu Province' => __('South Kivu Province'),
                            ],
                    ],
                'CF' =>
                    [
                        'value' => 'CF',
                        'label' => __('Central African Republic'),
                        'regions' =>
                            [
                                'Bangui' => __('Bangui'),
                                'Mbomou' => __('Mbomou'),
                            ],
                    ],
                'CG' =>
                    [
                        'value' => 'CG',
                        'label' => __('Republic of the Congo'),
                        'regions' =>
                            [
                                'Brazzaville' => __('Brazzaville'),
                                'Pointe-Noire' => __('Pointe-Noire'),
                                'Sangha' => __('Sangha'),
                            ],
                    ],
                'CH' =>
                    [
                        'value' => 'CH',
                        'label' => __('Switzerland'),
                        'regions' =>
                            [
                                'Aargau' => __('Aargau'),
                                'Appenzell Ausserrhoden' => __('Appenzell Ausserrhoden'),
                                'Appenzell Innerrhoden' => __('Appenzell Innerrhoden'),
                                'Basel-City' => __('Basel-City'),
                                'Basel-Landschaft' => __('Basel-Landschaft'),
                                'Bern' => __('Bern'),
                                'Fribourg' => __('Fribourg'),
                                'Geneva' => __('Geneva'),
                                'Glarus' => __('Glarus'),
                                'Grisons' => __('Grisons'),
                                'Jura' => __('Jura'),
                                'Lucerne' => __('Lucerne'),
                                'Neuchâtel' => __('Neuchâtel'),
                                'Nidwalden' => __('Nidwalden'),
                                'Obwalden' => __('Obwalden'),
                                'Saint Gallen' => __('Saint Gallen'),
                                'Schaffhausen' => __('Schaffhausen'),
                                'Schwyz' => __('Schwyz'),
                                'Solothurn' => __('Solothurn'),
                                'Thurgau' => __('Thurgau'),
                                'Ticino' => __('Ticino'),
                                'Uri' => __('Uri'),
                                'Valais' => __('Valais'),
                                'Vaud' => __('Vaud'),
                                'Zug' => __('Zug'),
                                'Zurich' => __('Zurich'),
                            ],
                    ],
                'CI' =>
                    [
                        'value' => 'CI',
                        'label' => __('Ivory Coast'),
                        'regions' =>
                            [
                                'Abidjan' => __('Abidjan'),
                                'Comoe' => __('Comoe'),
                                'District des Montagnes' => __('District des Montagnes'),
                                'Sassandra-Marahoue' => __('Sassandra-Marahoue'),
                            ],
                    ],
                'CK' =>
                    [
                        'value' => 'CK',
                        'label' => __('Cook Islands'),
                        'regions' =>
                            [
                            ],
                    ],
                'CL' =>
                    [
                        'value' => 'CL',
                        'label' => __('Chile'),
                        'regions' =>
                            [
                                'Antofagasta' => __('Antofagasta'),
                                'Atacama' => __('Atacama'),
                                'Aysen' => __('Aysen'),
                                'Coquimbo' => __('Coquimbo'),
                                'Los Lagos' => __('Los Lagos'),
                                'Maule' => __('Maule'),
                                'Region de Arica y Parinacota' => __('Region de Arica y Parinacota'),
                                'Region de Los Rios' => __('Region de Los Rios'),
                                'Region de Magallanes y de la Antartica Chilena' => __('Region de Magallanes y de la Antartica Chilena'),
                                'Region de Valparaiso' => __('Region de Valparaiso'),
                                'Region de la Araucania' => __('Region de la Araucania'),
                                'Region del Biobio' => __('Region del Biobio'),
                                'Region del Libertador General Bernardo O\'Higgins' => __('Region del Libertador General Bernardo O\'Higgins'),
                                'Santiago Metropolitan' => __('Santiago Metropolitan'),
                                'Tarapacá' => __('Tarapacá'),
                            ],
                    ],
                'CM' =>
                    [
                        'value' => 'CM',
                        'label' => __('Cameroon'),
                        'regions' =>
                            [
                                'Adamaoua Region' => __('Adamaoua Region'),
                                'Centre' => __('Centre'),
                                'Littoral' => __('Littoral'),
                                'North Region' => __('North Region'),
                                'North-West Region' => __('North-West Region'),
                                'South' => __('South'),
                                'South-West Region' => __('South-West Region'),
                                'West Region' => __('West Region'),
                            ],
                    ],
                'CN' =>
                    [
                        'value' => 'CN',
                        'label' => __('China'),
                        'regions' =>
                            [
                                'Anhui' => __('Anhui'),
                                'Beijing' => __('Beijing'),
                                'Chongqing' => __('Chongqing'),
                                'Fujian' => __('Fujian'),
                                'Gansu' => __('Gansu'),
                                'Guangdong' => __('Guangdong'),
                                'Guangxi Zhuang Autonomous Region' => __('Guangxi Zhuang Autonomous Region'),
                                'Guizhou' => __('Guizhou'),
                                'Hainan' => __('Hainan'),
                                'Hebei' => __('Hebei'),
                                'Heilongjiang' => __('Heilongjiang'),
                                'Henan' => __('Henan'),
                                'Hubei' => __('Hubei'),
                                'Hunan' => __('Hunan'),
                                'Inner Mongolia Autonomous Region' => __('Inner Mongolia Autonomous Region'),
                                'Jiangsu' => __('Jiangsu'),
                                'Jiangxi' => __('Jiangxi'),
                                'Jilin' => __('Jilin'),
                                'Liaoning' => __('Liaoning'),
                                'Ningsia Hui Autonomous Region' => __('Ningsia Hui Autonomous Region'),
                                'Qinghai' => __('Qinghai'),
                                'Shaanxi' => __('Shaanxi'),
                                'Shandong' => __('Shandong'),
                                'Shanghai' => __('Shanghai'),
                                'Shanxi' => __('Shanxi'),
                                'Sichuan' => __('Sichuan'),
                                'Tianjin' => __('Tianjin'),
                                'Tibet Autonomous Region' => __('Tibet Autonomous Region'),
                                'Xinjiang Uyghur Autonomous Region' => __('Xinjiang Uyghur Autonomous Region'),
                                'Yunnan' => __('Yunnan'),
                                'Zhejiang' => __('Zhejiang'),
                            ],
                    ],
                'CO' =>
                    [
                        'value' => 'CO',
                        'label' => __('Colombia'),
                        'regions' =>
                            [
                                'Amazonas' => __('Amazonas'),
                                'Antioquia' => __('Antioquia'),
                                'Atlántico' => __('Atlántico'),
                                'Bogota D.C.' => __('Bogota D.C.'),
                                'Cundinamarca' => __('Cundinamarca'),
                                'Departamento de Bolivar' => __('Departamento de Bolivar'),
                                'Departamento de Boyaca' => __('Departamento de Boyaca'),
                                'Departamento de Caldas' => __('Departamento de Caldas'),
                                'Departamento de Casanare' => __('Departamento de Casanare'),
                                'Departamento de Cordoba' => __('Departamento de Cordoba'),
                                'Departamento de La Guajira' => __('Departamento de La Guajira'),
                                'Departamento de Narino' => __('Departamento de Narino'),
                                'Departamento de Norte de Santander' => __('Departamento de Norte de Santander'),
                                'Departamento de Risaralda' => __('Departamento de Risaralda'),
                                'Departamento de Santander' => __('Departamento de Santander'),
                                'Departamento de Sucre' => __('Departamento de Sucre'),
                                'Departamento de Tolima' => __('Departamento de Tolima'),
                                'Departamento del Caqueta' => __('Departamento del Caqueta'),
                                'Departamento del Cauca' => __('Departamento del Cauca'),
                                'Departamento del Cesar' => __('Departamento del Cesar'),
                                'Departamento del Choco' => __('Departamento del Choco'),
                                'Departamento del Guainia' => __('Departamento del Guainia'),
                                'Departamento del Guaviare' => __('Departamento del Guaviare'),
                                'Departamento del Huila' => __('Departamento del Huila'),
                                'Departamento del Magdalena' => __('Departamento del Magdalena'),
                                'Departamento del Meta' => __('Departamento del Meta'),
                                'Departamento del Valle del Cauca' => __('Departamento del Valle del Cauca'),
                                'Departamento del Vichada' => __('Departamento del Vichada'),
                                'Providencia y Santa Catalina, Departamento de Archipielago de San Andres' => __('Providencia y Santa Catalina, Departamento de Archipielago de San Andres'),
                                'Quindio Department' => __('Quindio Department'),
                            ],
                    ],
                'CR' =>
                    [
                        'value' => 'CR',
                        'label' => __('Costa Rica'),
                        'regions' =>
                            [
                                'Provincia de Alajuela' => __('Provincia de Alajuela'),
                                'Provincia de Cartago' => __('Provincia de Cartago'),
                                'Provincia de Guanacaste' => __('Provincia de Guanacaste'),
                                'Provincia de Heredia' => __('Provincia de Heredia'),
                                'Provincia de Limon' => __('Provincia de Limon'),
                                'Provincia de Puntarenas' => __('Provincia de Puntarenas'),
                                'Provincia de San Jose' => __('Provincia de San Jose'),
                            ],
                    ],
                'CU' =>
                    [
                        'value' => 'CU',
                        'label' => __('Cuba'),
                        'regions' =>
                            [
                                'La Habana' => __('La Habana'),
                                'Provincia de Camagueey' => __('Provincia de Camagueey'),
                                'Provincia de Ciego de Avila' => __('Provincia de Ciego de Avila'),
                                'Provincia de Matanzas' => __('Provincia de Matanzas'),
                                'Provincia de Villa Clara' => __('Provincia de Villa Clara'),
                            ],
                    ],
                'CV' =>
                    [
                        'value' => 'CV',
                        'label' => __('Cape Verde'),
                        'regions' =>
                            [
                                'Ribeira Grande' => __('Ribeira Grande'),
                                'Ribeira Grande de Santiago' => __('Ribeira Grande de Santiago'),
                                'São Domingos' => __('São Domingos'),
                            ],
                    ],
                'CW' =>
                    [
                        'value' => 'CW',
                        'label' => __('Curaçao'),
                        'regions' =>
                            [
                            ],
                    ],
                'CX' =>
                    [
                        'value' => 'CX',
                        'label' => __('Christmas Island'),
                        'regions' =>
                            [
                            ],
                    ],
                'CY' =>
                    [
                        'value' => 'CY',
                        'label' => __('Cyprus'),
                        'regions' =>
                            [
                                'Ammochostos' => __('Ammochostos'),
                                'Keryneia' => __('Keryneia'),
                                'Larnaka' => __('Larnaka'),
                                'Limassol' => __('Limassol'),
                                'Nicosia' => __('Nicosia'),
                                'Pafos' => __('Pafos'),
                            ],
                    ],
                'CZ' =>
                    [
                        'value' => 'CZ',
                        'label' => __('Czechia'),
                        'regions' =>
                            [
                                'Central Bohemia' => __('Central Bohemia'),
                                'Hlavni mesto Praha' => __('Hlavni mesto Praha'),
                                'Jihocesky kraj' => __('Jihocesky kraj'),
                                'Karlovarsky kraj' => __('Karlovarsky kraj'),
                                'Kraj Vysocina' => __('Kraj Vysocina'),
                                'Kralovehradecky kraj' => __('Kralovehradecky kraj'),
                                'Liberecky kraj' => __('Liberecky kraj'),
                                'Moravskoslezsky kraj' => __('Moravskoslezsky kraj'),
                                'Olomoucky kraj' => __('Olomoucky kraj'),
                                'Pardubicky kraj' => __('Pardubicky kraj'),
                                'Plzensky kraj' => __('Plzensky kraj'),
                                'South Moravian' => __('South Moravian'),
                                'Ustecky kraj' => __('Ustecky kraj'),
                                'Zlín' => __('Zlín'),
                            ],
                    ],
                'DE' =>
                    [
                        'value' => 'DE',
                        'label' => __('Germany'),
                        'regions' =>
                            [
                                'Baden-Württemberg Region' => __('Baden-Württemberg Region'),
                                'Bavaria' => __('Bavaria'),
                                'Brandenburg' => __('Brandenburg'),
                                'Bremen' => __('Bremen'),
                                'Hamburg' => __('Hamburg'),
                                'Hesse' => __('Hesse'),
                                'Land Berlin' => __('Land Berlin'),
                                'Lower Saxony' => __('Lower Saxony'),
                                'Mecklenburg-Vorpommern' => __('Mecklenburg-Vorpommern'),
                                'North Rhine-Westphalia' => __('North Rhine-Westphalia'),
                                'Rheinland-Pfalz' => __('Rheinland-Pfalz'),
                                'Saarland' => __('Saarland'),
                                'Saxony' => __('Saxony'),
                                'Saxony-Anhalt' => __('Saxony-Anhalt'),
                                'Schleswig-Holstein' => __('Schleswig-Holstein'),
                                'Thuringia' => __('Thuringia'),
                            ],
                    ],
                'DJ' =>
                    [
                        'value' => 'DJ',
                        'label' => __('Djibouti'),
                        'regions' =>
                            [
                            ],
                    ],
                'DK' =>
                    [
                        'value' => 'DK',
                        'label' => __('Denmark'),
                        'regions' =>
                            [
                                'Capital Region' => __('Capital Region'),
                                'Central Jutland' => __('Central Jutland'),
                                'North Denmark' => __('North Denmark'),
                                'South Denmark' => __('South Denmark'),
                                'Zealand' => __('Zealand'),
                            ],
                    ],
                'DM' =>
                    [
                        'value' => 'DM',
                        'label' => __('Dominica'),
                        'regions' =>
                            [
                                'Saint Andrew' => __('Saint Andrew'),
                                'Saint David' => __('Saint David'),
                                'Saint George' => __('Saint George'),
                                'Saint John' => __('Saint John'),
                                'Saint Patrick' => __('Saint Patrick'),
                                'Saint Paul' => __('Saint Paul'),
                            ],
                    ],
                'DO' =>
                    [
                        'value' => 'DO',
                        'label' => __('Dominican Republic'),
                        'regions' =>
                            [
                                'Nacional' => __('Nacional'),
                                'Provincia Duarte' => __('Provincia Duarte'),
                                'Provincia Espaillat' => __('Provincia Espaillat'),
                                'Provincia Sanchez Ramirez' => __('Provincia Sanchez Ramirez'),
                                'Provincia de Barahona' => __('Provincia de Barahona'),
                                'Provincia de El Seibo' => __('Provincia de El Seibo'),
                                'Provincia de Hato Mayor' => __('Provincia de Hato Mayor'),
                                'Provincia de Hermanas Mirabal' => __('Provincia de Hermanas Mirabal'),
                                'Provincia de Independencia' => __('Provincia de Independencia'),
                                'Provincia de La Altagracia' => __('Provincia de La Altagracia'),
                                'Provincia de La Romana' => __('Provincia de La Romana'),
                                'Provincia de La Vega' => __('Provincia de La Vega'),
                                'Provincia de Monsenor Nouel' => __('Provincia de Monsenor Nouel'),
                                'Provincia de Monte Cristi' => __('Provincia de Monte Cristi'),
                                'Provincia de Monte Plata' => __('Provincia de Monte Plata'),
                                'Provincia de Pedernales' => __('Provincia de Pedernales'),
                                'Provincia de Peravia' => __('Provincia de Peravia'),
                                'Provincia de San Cristobal' => __('Provincia de San Cristobal'),
                                'Provincia de San Jose de Ocoa' => __('Provincia de San Jose de Ocoa'),
                                'Provincia de San Juan' => __('Provincia de San Juan'),
                                'Provincia de San Pedro de Macoris' => __('Provincia de San Pedro de Macoris'),
                                'Provincia de Santiago' => __('Provincia de Santiago'),
                                'Provincia de Santiago Rodriguez' => __('Provincia de Santiago Rodriguez'),
                                'Provincia de Santo Domingo' => __('Provincia de Santo Domingo'),
                                'Puerto Plata' => __('Puerto Plata'),
                            ],
                    ],
                'DZ' =>
                    [
                        'value' => 'DZ',
                        'label' => __('Algeria'),
                        'regions' =>
                            [
                                'Adrar' => __('Adrar'),
                                'Algiers' => __('Algiers'),
                                'Annaba' => __('Annaba'),
                                'Aïn Defla' => __('Aïn Defla'),
                                'Aïn Témouchent' => __('Aïn Témouchent'),
                                'Batna' => __('Batna'),
                                'Biskra' => __('Biskra'),
                                'Blida' => __('Blida'),
                                'Bouira' => __('Bouira'),
                                'Boumerdes' => __('Boumerdes'),
                                'Béchar' => __('Béchar'),
                                'Béjaïa' => __('Béjaïa'),
                                'Chlef' => __('Chlef'),
                                'Constantine' => __('Constantine'),
                                'Djelfa' => __('Djelfa'),
                                'El Bayadh' => __('El Bayadh'),
                                'El Tarf' => __('El Tarf'),
                                'Ghardaia' => __('Ghardaia'),
                                'Guelma' => __('Guelma'),
                                'Illizi' => __('Illizi'),
                                'Jijel' => __('Jijel'),
                                'Khenchela' => __('Khenchela'),
                                'Laghouat' => __('Laghouat'),
                                'M\'Sila' => __('M\'Sila'),
                                'Mascara' => __('Mascara'),
                                'Medea' => __('Medea'),
                                'Mila' => __('Mila'),
                                'Mostaganem' => __('Mostaganem'),
                                'Naama' => __('Naama'),
                                'Oran' => __('Oran'),
                                'Ouargla' => __('Ouargla'),
                                'Oum el Bouaghi' => __('Oum el Bouaghi'),
                                'Relizane' => __('Relizane'),
                                'Saida' => __('Saida'),
                                'Sidi Bel Abbès' => __('Sidi Bel Abbès'),
                                'Skikda' => __('Skikda'),
                                'Sétif' => __('Sétif'),
                                'Tamanrasset' => __('Tamanrasset'),
                                'Tiaret' => __('Tiaret'),
                                'Tindouf' => __('Tindouf'),
                                'Tipaza' => __('Tipaza'),
                                'Tissemsilt' => __('Tissemsilt'),
                                'Tizi Ouzou' => __('Tizi Ouzou'),
                                'Tlemcen' => __('Tlemcen'),
                            ],
                    ],
                'EC' =>
                    [
                        'value' => 'EC',
                        'label' => __('Ecuador'),
                        'regions' =>
                            [
                                'Provincia de Bolivar' => __('Provincia de Bolivar'),
                                'Provincia de Cotopaxi' => __('Provincia de Cotopaxi'),
                                'Provincia de El Oro' => __('Provincia de El Oro'),
                                'Provincia de Esmeraldas' => __('Provincia de Esmeraldas'),
                                'Provincia de Francisco de Orellana' => __('Provincia de Francisco de Orellana'),
                                'Provincia de Imbabura' => __('Provincia de Imbabura'),
                                'Provincia de Loja' => __('Provincia de Loja'),
                                'Provincia de Los Rios' => __('Provincia de Los Rios'),
                                'Provincia de Manabi' => __('Provincia de Manabi'),
                                'Provincia de Morona-Santiago' => __('Provincia de Morona-Santiago'),
                                'Provincia de Napo' => __('Provincia de Napo'),
                                'Provincia de Pichincha' => __('Provincia de Pichincha'),
                                'Provincia de Santa Elena' => __('Provincia de Santa Elena'),
                                'Provincia de Santo Domingo de los Tsachilas' => __('Provincia de Santo Domingo de los Tsachilas'),
                                'Provincia de Sucumbios' => __('Provincia de Sucumbios'),
                                'Provincia de Zamora-Chinchipe' => __('Provincia de Zamora-Chinchipe'),
                                'Provincia del Azuay' => __('Provincia del Azuay'),
                                'Provincia del Canar' => __('Provincia del Canar'),
                                'Provincia del Carchi' => __('Provincia del Carchi'),
                                'Provincia del Chimborazo' => __('Provincia del Chimborazo'),
                                'Provincia del Guayas' => __('Provincia del Guayas'),
                                'Provincia del Pastaza' => __('Provincia del Pastaza'),
                                'Provincia del Tungurahua' => __('Provincia del Tungurahua'),
                            ],
                    ],
                'EE' =>
                    [
                        'value' => 'EE',
                        'label' => __('Estonia'),
                        'regions' =>
                            [
                                'Harjumaa' => __('Harjumaa'),
                                'Hiiumaa' => __('Hiiumaa'),
                                'Ida-Virumaa' => __('Ida-Virumaa'),
                                'Järvamaa' => __('Järvamaa'),
                                'Jõgevamaa' => __('Jõgevamaa'),
                                'Lääne' => __('Lääne'),
                                'Lääne-Virumaa' => __('Lääne-Virumaa'),
                                'Pärnumaa' => __('Pärnumaa'),
                                'Põlvamaa' => __('Põlvamaa'),
                                'Raplamaa' => __('Raplamaa'),
                                'Saare' => __('Saare'),
                                'Tartu' => __('Tartu'),
                                'Valgamaa' => __('Valgamaa'),
                                'Viljandimaa' => __('Viljandimaa'),
                                'Võrumaa' => __('Võrumaa'),
                            ],
                    ],
                'EG' =>
                    [
                        'value' => 'EG',
                        'label' => __('Egypt'),
                        'regions' =>
                            [
                                'Alexandria' => __('Alexandria'),
                                'Aswan' => __('Aswan'),
                                'Asyut' => __('Asyut'),
                                'Beheira' => __('Beheira'),
                                'Beni Suweif' => __('Beni Suweif'),
                                'Cairo Governorate' => __('Cairo Governorate'),
                                'Dakahlia' => __('Dakahlia'),
                                'Damietta Governorate' => __('Damietta Governorate'),
                                'Faiyum' => __('Faiyum'),
                                'Gharbia' => __('Gharbia'),
                                'Giza' => __('Giza'),
                                'Ismailia Governorate' => __('Ismailia Governorate'),
                                'Kafr el-Sheikh' => __('Kafr el-Sheikh'),
                                'Luxor' => __('Luxor'),
                                'Minya' => __('Minya'),
                                'Monufia' => __('Monufia'),
                                'North Sinai' => __('North Sinai'),
                                'Port Said' => __('Port Said'),
                                'Qalyubia' => __('Qalyubia'),
                                'Qena' => __('Qena'),
                                'Red Sea' => __('Red Sea'),
                                'Sharqia' => __('Sharqia'),
                                'Sohag' => __('Sohag'),
                                'Suez' => __('Suez'),
                            ],
                    ],
                'ER' =>
                    [
                        'value' => 'ER',
                        'label' => __('Eritrea'),
                        'regions' =>
                            [
                                'Maekel Region' => __('Maekel Region'),
                            ],
                    ],
                'ES' =>
                    [
                        'value' => 'ES',
                        'label' => __('Spain'),
                        'regions' =>
                            [
                                'Andalusia' => __('Andalusia'),
                                'Aragon' => __('Aragon'),
                                'Balearic Islands' => __('Balearic Islands'),
                                'Basque Country' => __('Basque Country'),
                                'Canary Islands' => __('Canary Islands'),
                                'Cantabria' => __('Cantabria'),
                                'Castille and León' => __('Castille and León'),
                                'Castille-La Mancha' => __('Castille-La Mancha'),
                                'Catalonia' => __('Catalonia'),
                                'Ceuta' => __('Ceuta'),
                                'Extremadura' => __('Extremadura'),
                                'Galicia' => __('Galicia'),
                                'La Rioja' => __('La Rioja'),
                                'Madrid' => __('Madrid'),
                                'Melilla' => __('Melilla'),
                                'Murcia' => __('Murcia'),
                                'Navarre' => __('Navarre'),
                                'Principality of Asturias' => __('Principality of Asturias'),
                                'Valencia' => __('Valencia'),
                            ],
                    ],
                'ET' =>
                    [
                        'value' => 'ET',
                        'label' => __('Ethiopia'),
                        'regions' =>
                            [
                                'Addis Ababa' => __('Addis Ababa'),
                                'Afar Region' => __('Afar Region'),
                                'Amhara' => __('Amhara'),
                                'Bīnshangul Gumuz' => __('Bīnshangul Gumuz'),
                                'Dire Dawa' => __('Dire Dawa'),
                                'Gambela' => __('Gambela'),
                                'Harari Region' => __('Harari Region'),
                                'Oromiya' => __('Oromiya'),
                                'Somali' => __('Somali'),
                                'Southern Nations, Nationalities, and People\'s Region' => __('Southern Nations, Nationalities, and People\'s Region'),
                                'Tigray' => __('Tigray'),
                            ],
                    ],
                'FI' =>
                    [
                        'value' => 'FI',
                        'label' => __('Finland'),
                        'regions' =>
                            [
                                'Central Finland' => __('Central Finland'),
                                'Central Ostrobothnia' => __('Central Ostrobothnia'),
                                'Haeme' => __('Haeme'),
                                'Kainuu' => __('Kainuu'),
                                'Kymenlaakso' => __('Kymenlaakso'),
                                'Lapland' => __('Lapland'),
                                'Lapponia' => __('Lapponia'),
                                'North Karelia' => __('North Karelia'),
                                'Northern Ostrobothnia' => __('Northern Ostrobothnia'),
                                'Northern Savo' => __('Northern Savo'),
                                'Päijänne Tavastia' => __('Päijänne Tavastia'),
                                'Satakunta' => __('Satakunta'),
                                'South Karelia' => __('South Karelia'),
                                'Southern Ostrobothnia' => __('Southern Ostrobothnia'),
                                'Southern Savonia' => __('Southern Savonia'),
                                'Southwest Finland' => __('Southwest Finland'),
                                'Uusimaa' => __('Uusimaa'),
                                'Western Finland' => __('Western Finland'),
                            ],
                    ],
                'FJ' =>
                    [
                        'value' => 'FJ',
                        'label' => __('Fiji'),
                        'regions' =>
                            [
                                'Central' => __('Central'),
                                'Western' => __('Western'),
                            ],
                    ],
                'FK' =>
                    [
                        'value' => 'FK',
                        'label' => __('Falkland Islands'),
                        'regions' =>
                            [
                            ],
                    ],
                'FM' =>
                    [
                        'value' => 'FM',
                        'label' => __('Federated States of Micronesia'),
                        'regions' =>
                            [
                                'State of Yap' => __('State of Yap'),
                            ],
                    ],
                'FO' =>
                    [
                        'value' => 'FO',
                        'label' => __('Faroe Islands'),
                        'regions' =>
                            [
                            ],
                    ],
                'FR' =>
                    [
                        'value' => 'FR',
                        'label' => __('France'),
                        'regions' =>
                            [
                                'Ain' => __('Ain'),
                                'Aisne' => __('Aisne'),
                                'Allier' => __('Allier'),
                                'Ardennes' => __('Ardennes'),
                                'Ardèche' => __('Ardèche'),
                                'Ariège' => __('Ariège'),
                                'Aube' => __('Aube'),
                                'Aude' => __('Aude'),
                                'Aveyron' => __('Aveyron'),
                                'Bas-Rhin' => __('Bas-Rhin'),
                                'Brittany' => __('Brittany'),
                                'Calvados' => __('Calvados'),
                                'Cantal' => __('Cantal'),
                                'Centre' => __('Centre'),
                                'Charente' => __('Charente'),
                                'Charente-Maritime' => __('Charente-Maritime'),
                                'Corrèze' => __('Corrèze'),
                                'Corsica' => __('Corsica'),
                                'Cote d\'Or' => __('Cote d\'Or'),
                                'Creuse' => __('Creuse'),
                                'Deux-Sèvres' => __('Deux-Sèvres'),
                                'Dordogne' => __('Dordogne'),
                                'Doubs' => __('Doubs'),
                                'Drôme' => __('Drôme'),
                                'Eure' => __('Eure'),
                                'Gard' => __('Gard'),
                                'Gers' => __('Gers'),
                                'Gironde' => __('Gironde'),
                                'Haut-Rhin' => __('Haut-Rhin'),
                                'Haute-Loire' => __('Haute-Loire'),
                                'Haute-Marne' => __('Haute-Marne'),
                                'Haute-Savoie' => __('Haute-Savoie'),
                                'Haute-Saône' => __('Haute-Saône'),
                                'Haute-Vienne' => __('Haute-Vienne'),
                                'Hautes-Pyrénées' => __('Hautes-Pyrénées'),
                                'Hérault' => __('Hérault'),
                                'Isère' => __('Isère'),
                                'Jura' => __('Jura'),
                                'Landes' => __('Landes'),
                                'Loire' => __('Loire'),
                                'Lot' => __('Lot'),
                                'Lot-et-Garonne' => __('Lot-et-Garonne'),
                                'Lozère' => __('Lozère'),
                                'Manche' => __('Manche'),
                                'Marne' => __('Marne'),
                                'Meurthe et Moselle' => __('Meurthe et Moselle'),
                                'Meuse' => __('Meuse'),
                                'Moselle' => __('Moselle'),
                                'Nièvre' => __('Nièvre'),
                                'North' => __('North'),
                                'Oise' => __('Oise'),
                                'Orne' => __('Orne'),
                                'Pas-de-Calais' => __('Pas-de-Calais'),
                                'Pays de la Loire' => __('Pays de la Loire'),
                                'Provence-Alpes-Côte d\'Azur' => __('Provence-Alpes-Côte d\'Azur'),
                                'Puy-de-Dôme' => __('Puy-de-Dôme'),
                                'Pyrénées-Atlantiques' => __('Pyrénées-Atlantiques'),
                                'Pyrénées-Orientales' => __('Pyrénées-Orientales'),
                                'Rhône' => __('Rhône'),
                                'Savoy' => __('Savoy'),
                                'Saône-et-Loire' => __('Saône-et-Loire'),
                                'Seine-Maritime' => __('Seine-Maritime'),
                                'Somme' => __('Somme'),
                                'Tarn' => __('Tarn'),
                                'Tarn-et-Garonne' => __('Tarn-et-Garonne'),
                                'Territoire de Belfort' => __('Territoire de Belfort'),
                                'Upper Garonne' => __('Upper Garonne'),
                                'Vienne' => __('Vienne'),
                                'Vosges' => __('Vosges'),
                                'Yonne' => __('Yonne'),
                                'Île-de-France' => __('Île-de-France'),
                            ],
                    ],
                'GA' =>
                    [
                        'value' => 'GA',
                        'label' => __('Gabon'),
                        'regions' =>
                            [
                                'Estuaire' => __('Estuaire'),
                                'Haut-Ogooué' => __('Haut-Ogooué'),
                                'Moyen-Ogooué' => __('Moyen-Ogooué'),
                                'Ngouni' => __('Ngouni'),
                                'Ogooué-Maritime' => __('Ogooué-Maritime'),
                            ],
                    ],
                'GB' =>
                    [
                        'value' => 'GB',
                        'label' => __('United Kingdom'),
                        'regions' =>
                            [
                                'England' => __('England'),
                                'Northern Ireland' => __('Northern Ireland'),
                                'Scotland' => __('Scotland'),
                                'Wales' => __('Wales'),
                            ],
                    ],
                'GD' =>
                    [
                        'value' => 'GD',
                        'label' => __('Grenada'),
                        'regions' =>
                            [
                                'Saint Andrew' => __('Saint Andrew'),
                                'Saint George' => __('Saint George'),
                                'Saint Mark' => __('Saint Mark'),
                                'Saint Patrick' => __('Saint Patrick'),
                            ],
                    ],
                'GE' =>
                    [
                        'value' => 'GE',
                        'label' => __('Georgia'),
                        'regions' =>
                            [
                                'Abkhazia' => __('Abkhazia'),
                                'Ajaria' => __('Ajaria'),
                                'Guria' => __('Guria'),
                                'Imereti' => __('Imereti'),
                                'K\'alak\'i T\'bilisi' => __('K\'alak\'i T\'bilisi'),
                                'Kakheti' => __('Kakheti'),
                                'Mtskheta-Mtianeti' => __('Mtskheta-Mtianeti'),
                                'Racha-Lechkhumi and Kvemo Svaneti' => __('Racha-Lechkhumi and Kvemo Svaneti'),
                                'Samegrelo and Zemo Svaneti' => __('Samegrelo and Zemo Svaneti'),
                                'Shida Kartli' => __('Shida Kartli'),
                            ],
                    ],
                'GF' =>
                    [
                        'value' => 'GF',
                        'label' => __('French Guiana'),
                        'regions' =>
                            [
                            ],
                    ],
                'GG' =>
                    [
                        'value' => 'GG',
                        'label' => __('Guernsey'),
                        'regions' =>
                            [
                            ],
                    ],
                'GH' =>
                    [
                        'value' => 'GH',
                        'label' => __('Ghana'),
                        'regions' =>
                            [
                                'Ashanti Region' => __('Ashanti Region'),
                                'Brong-Ahafo' => __('Brong-Ahafo'),
                                'Central Region' => __('Central Region'),
                                'Eastern Region' => __('Eastern Region'),
                                'Greater Accra Region' => __('Greater Accra Region'),
                                'Upper East Region' => __('Upper East Region'),
                                'Upper West Region' => __('Upper West Region'),
                                'Volta Region' => __('Volta Region'),
                                'Western Region' => __('Western Region'),
                            ],
                    ],
                'GI' =>
                    [
                        'value' => 'GI',
                        'label' => __('Gibraltar'),
                        'regions' =>
                            [
                            ],
                    ],
                'GL' =>
                    [
                        'value' => 'GL',
                        'label' => __('Greenland'),
                        'regions' =>
                            [
                                'Kujalleq' => __('Kujalleq'),
                                'Qaasuitsup' => __('Qaasuitsup'),
                                'Qeqqata' => __('Qeqqata'),
                                'Sermersooq' => __('Sermersooq'),
                            ],
                    ],
                'GM' =>
                    [
                        'value' => 'GM',
                        'label' => __('Gambia'),
                        'regions' =>
                            [
                                'City of Banjul' => __('City of Banjul'),
                                'Western Division' => __('Western Division'),
                            ],
                    ],
                'GN' =>
                    [
                        'value' => 'GN',
                        'label' => __('Guinea'),
                        'regions' =>
                            [
                                'Boke Region' => __('Boke Region'),
                                'Conakry Region' => __('Conakry Region'),
                                'Faranah' => __('Faranah'),
                                'Kankan Region' => __('Kankan Region'),
                                'Kindia' => __('Kindia'),
                                'Labe Region' => __('Labe Region'),
                                'Mamou Region' => __('Mamou Region'),
                                'Nzerekore Region' => __('Nzerekore Region'),
                            ],
                    ],
                'GP' =>
                    [
                        'value' => 'GP',
                        'label' => __('Guadeloupe'),
                        'regions' =>
                            [
                            ],
                    ],
                'GQ' =>
                    [
                        'value' => 'GQ',
                        'label' => __('Equatorial Guinea'),
                        'regions' =>
                            [
                                'Bioko Norte' => __('Bioko Norte'),
                                'Wele-Nzas' => __('Wele-Nzas'),
                            ],
                    ],
                'GR' =>
                    [
                        'value' => 'GR',
                        'label' => __('Greece'),
                        'regions' =>
                            [
                                'Attica' => __('Attica'),
                                'Central Greece' => __('Central Greece'),
                                'Central Macedonia' => __('Central Macedonia'),
                                'Crete' => __('Crete'),
                                'East Macedonia and Thrace' => __('East Macedonia and Thrace'),
                                'Epirus' => __('Epirus'),
                                'Ionian Islands' => __('Ionian Islands'),
                                'North Aegean' => __('North Aegean'),
                                'Peloponnese' => __('Peloponnese'),
                                'South Aegean' => __('South Aegean'),
                                'Thessaly' => __('Thessaly'),
                                'West Greece' => __('West Greece'),
                                'West Macedonia' => __('West Macedonia'),
                            ],
                    ],
                'GS' =>
                    [
                        'value' => 'GS',
                        'label' => __('South Georgia and the South Sandwich Islands'),
                        'regions' =>
                            [
                            ],
                    ],
                'GT' =>
                    [
                        'value' => 'GT',
                        'label' => __('Guatemala'),
                        'regions' =>
                            [
                                'Departamento de Alta Verapaz' => __('Departamento de Alta Verapaz'),
                                'Departamento de Chimaltenango' => __('Departamento de Chimaltenango'),
                                'Departamento de Escuintla' => __('Departamento de Escuintla'),
                                'Departamento de Guatemala' => __('Departamento de Guatemala'),
                                'Departamento de Huehuetenango' => __('Departamento de Huehuetenango'),
                                'Departamento de Jalapa' => __('Departamento de Jalapa'),
                                'Departamento de Jutiapa' => __('Departamento de Jutiapa'),
                                'Departamento de Quetzaltenango' => __('Departamento de Quetzaltenango'),
                                'Departamento de Retalhuleu' => __('Departamento de Retalhuleu'),
                                'Departamento de Sacatepequez' => __('Departamento de Sacatepequez'),
                                'Departamento de Zacapa' => __('Departamento de Zacapa'),
                                'Suchitepeque' => __('Suchitepeque'),
                            ],
                    ],
                'GU' =>
                    [
                        'value' => 'GU',
                        'label' => __('Guam'),
                        'regions' =>
                            [
                            ],
                    ],
                'GW' =>
                    [
                        'value' => 'GW',
                        'label' => __('Guinea-Bissau'),
                        'regions' =>
                            [
                                'Bissau' => __('Bissau'),
                                'Bolama and Bijagos' => __('Bolama and Bijagos'),
                                'Cacheu Region' => __('Cacheu Region'),
                            ],
                    ],
                'GY' =>
                    [
                        'value' => 'GY',
                        'label' => __('Guyana'),
                        'regions' =>
                            [
                                'Demerara-Mahaica Region' => __('Demerara-Mahaica Region'),
                                'East Berbice-Corentyne Region' => __('East Berbice-Corentyne Region'),
                                'Upper Demerara-Berbice Region' => __('Upper Demerara-Berbice Region'),
                            ],
                    ],
                'HK' =>
                    [
                        'value' => 'HK',
                        'label' => __('Hong Kong'),
                        'regions' =>
                            [
                                'Central and Western District' => __('Central and Western District'),
                                'Eastern' => __('Eastern'),
                                'Kowloon City' => __('Kowloon City'),
                                'North' => __('North'),
                                'Sha Tin' => __('Sha Tin'),
                                'Sham Shui Po' => __('Sham Shui Po'),
                                'Southern' => __('Southern'),
                                'Wanchai' => __('Wanchai'),
                                'Wong Tai Sin' => __('Wong Tai Sin'),
                                'Yau Tsim Mong' => __('Yau Tsim Mong'),
                                'Yuen Long District' => __('Yuen Long District'),
                            ],
                    ],
                'HN' =>
                    [
                        'value' => 'HN',
                        'label' => __('Honduras'),
                        'regions' =>
                            [
                                'Bay Islands' => __('Bay Islands'),
                                'Departamento de Atlantida' => __('Departamento de Atlantida'),
                                'Departamento de Choluteca' => __('Departamento de Choluteca'),
                                'Departamento de Colon' => __('Departamento de Colon'),
                                'Departamento de Comayagua' => __('Departamento de Comayagua'),
                                'Departamento de Copan' => __('Departamento de Copan'),
                                'Departamento de Cortes' => __('Departamento de Cortes'),
                                'Departamento de El Paraiso' => __('Departamento de El Paraiso'),
                                'Departamento de Francisco Morazan' => __('Departamento de Francisco Morazan'),
                                'Departamento de Gracias a Dios' => __('Departamento de Gracias a Dios'),
                                'Departamento de La Paz' => __('Departamento de La Paz'),
                                'Departamento de Lempira' => __('Departamento de Lempira'),
                                'Departamento de Olancho' => __('Departamento de Olancho'),
                                'Departamento de Santa Barbara' => __('Departamento de Santa Barbara'),
                                'Departamento de Valle' => __('Departamento de Valle'),
                                'Departamento de Yoro' => __('Departamento de Yoro'),
                            ],
                    ],
                'HR' =>
                    [
                        'value' => 'HR',
                        'label' => __('Croatia'),
                        'regions' =>
                            [
                                'Bjelovarsko-Bilogorska Zupanija' => __('Bjelovarsko-Bilogorska Zupanija'),
                                'City of Zagreb' => __('City of Zagreb'),
                                'Dubrovacko-Neretvanska Zupanija' => __('Dubrovacko-Neretvanska Zupanija'),
                                'Istarska Zupanija' => __('Istarska Zupanija'),
                                'Karlovacka Zupanija' => __('Karlovacka Zupanija'),
                                'Koprivnicko-Krizevacka Zupanija' => __('Koprivnicko-Krizevacka Zupanija'),
                                'Krapinsko-Zagorska Zupanija' => __('Krapinsko-Zagorska Zupanija'),
                                'Licko-Senjska Zupanija' => __('Licko-Senjska Zupanija'),
                                'Megimurska Zupanija' => __('Megimurska Zupanija'),
                                'Osjecko-Baranjska Zupanija' => __('Osjecko-Baranjska Zupanija'),
                                'Pozesko-Slavonska Zupanija' => __('Pozesko-Slavonska Zupanija'),
                                'Primorsko-Goranska Zupanija' => __('Primorsko-Goranska Zupanija'),
                                'Sibensko-Kninska Zupanija' => __('Sibensko-Kninska Zupanija'),
                                'Sisacko-Moslavacka Zupanija' => __('Sisacko-Moslavacka Zupanija'),
                                'Slavonski Brod-Posavina' => __('Slavonski Brod-Posavina'),
                                'Splitsko-Dalmatinska Zupanija' => __('Splitsko-Dalmatinska Zupanija'),
                                'Varazdinska Zupanija' => __('Varazdinska Zupanija'),
                                'Viroviticko-Podravska Zupanija' => __('Viroviticko-Podravska Zupanija'),
                                'Vukovar-Sirmium' => __('Vukovar-Sirmium'),
                                'Zadarska Zupanija' => __('Zadarska Zupanija'),
                                'Zagreb County' => __('Zagreb County'),
                            ],
                    ],
                'HT' =>
                    [
                        'value' => 'HT',
                        'label' => __('Haiti'),
                        'regions' =>
                            [
                                'Departement de l\'Ouest' => __('Departement de l\'Ouest'),
                                'Nord' => __('Nord'),
                                'Sud' => __('Sud'),
                                'Sud-Est' => __('Sud-Est'),
                            ],
                    ],
                'HU' =>
                    [
                        'value' => 'HU',
                        'label' => __('Hungary'),
                        'regions' =>
                            [
                                'Baranya' => __('Baranya'),
                                'Bekes' => __('Bekes'),
                                'Borsod-Abaúj-Zemplén' => __('Borsod-Abaúj-Zemplén'),
                                'Budapest' => __('Budapest'),
                                'Bács-Kiskun' => __('Bács-Kiskun'),
                                'Csongrad megye' => __('Csongrad megye'),
                                'Fejér' => __('Fejér'),
                                'Győr-Moson-Sopron' => __('Győr-Moson-Sopron'),
                                'Hajdú-Bihar' => __('Hajdú-Bihar'),
                                'Heves megye' => __('Heves megye'),
                                'Jász-Nagykun-Szolnok' => __('Jász-Nagykun-Szolnok'),
                                'Komárom-Esztergom' => __('Komárom-Esztergom'),
                                'Nograd megye' => __('Nograd megye'),
                                'Pest megye' => __('Pest megye'),
                                'Somogy megye' => __('Somogy megye'),
                                'Szabolcs-Szatmár-Bereg' => __('Szabolcs-Szatmár-Bereg'),
                                'Tolna megye' => __('Tolna megye'),
                                'Vas' => __('Vas'),
                                'Veszprem megye' => __('Veszprem megye'),
                                'Zala' => __('Zala'),
                            ],
                    ],
                'ID' =>
                    [
                        'value' => 'ID',
                        'label' => __('Indonesia'),
                        'regions' =>
                            [
                                'Aceh' => __('Aceh'),
                                'Bali' => __('Bali'),
                                'Bangka–Belitung Islands' => __('Bangka–Belitung Islands'),
                                'Banten' => __('Banten'),
                                'Bengkulu' => __('Bengkulu'),
                                'Central Java' => __('Central Java'),
                                'Central Kalimantan' => __('Central Kalimantan'),
                                'Central Sulawesi' => __('Central Sulawesi'),
                                'East Java' => __('East Java'),
                                'East Kalimantan' => __('East Kalimantan'),
                                'East Nusa Tenggara' => __('East Nusa Tenggara'),
                                'Gorontalo' => __('Gorontalo'),
                                'Jakarta' => __('Jakarta'),
                                'Jambi' => __('Jambi'),
                                'Lampung' => __('Lampung'),
                                'Maluku' => __('Maluku'),
                                'North Kalimantan' => __('North Kalimantan'),
                                'North Maluku' => __('North Maluku'),
                                'North Sulawesi' => __('North Sulawesi'),
                                'North Sumatra' => __('North Sumatra'),
                                'Papua' => __('Papua'),
                                'Riau' => __('Riau'),
                                'Riau Islands' => __('Riau Islands'),
                                'South Kalimantan' => __('South Kalimantan'),
                                'South Sulawesi' => __('South Sulawesi'),
                                'South Sumatra' => __('South Sumatra'),
                                'Southeast Sulawesi' => __('Southeast Sulawesi'),
                                'West Java' => __('West Java'),
                                'West Kalimantan' => __('West Kalimantan'),
                                'West Nusa Tenggara' => __('West Nusa Tenggara'),
                                'West Sulawesi' => __('West Sulawesi'),
                                'West Sumatra' => __('West Sumatra'),
                                'Yogyakarta' => __('Yogyakarta'),
                            ],
                    ],
                'IE' =>
                    [
                        'value' => 'IE',
                        'label' => __('Ireland'),
                        'regions' =>
                            [
                                'Connaught' => __('Connaught'),
                                'Leinster' => __('Leinster'),
                                'Munster' => __('Munster'),
                                'Ulster' => __('Ulster'),
                            ],
                    ],
                'IL' =>
                    [
                        'value' => 'IL',
                        'label' => __('Israel'),
                        'regions' =>
                            [
                                'Central District' => __('Central District'),
                                'Haifa' => __('Haifa'),
                                'Jerusalem' => __('Jerusalem'),
                                'Northern District' => __('Northern District'),
                                'Southern District' => __('Southern District'),
                                'Tel Aviv' => __('Tel Aviv'),
                            ],
                    ],
                'IM' =>
                    [
                        'value' => 'IM',
                        'label' => __('Isle of Man'),
                        'regions' =>
                            [
                            ],
                    ],
                'IN' =>
                    [
                        'value' => 'IN',
                        'label' => __('India'),
                        'regions' =>
                            [
                                'Andhra Pradesh' => __('Andhra Pradesh'),
                                'Arunachal Pradesh' => __('Arunachal Pradesh'),
                                'Assam' => __('Assam'),
                                'Bihar' => __('Bihar'),
                                'Chandigarh' => __('Chandigarh'),
                                'Chhattisgarh' => __('Chhattisgarh'),
                                'Dadra and Nagar Haveli' => __('Dadra and Nagar Haveli'),
                                'Daman and Diu' => __('Daman and Diu'),
                                'Goa' => __('Goa'),
                                'Gujarat' => __('Gujarat'),
                                'Haryana' => __('Haryana'),
                                'Himachal Pradesh' => __('Himachal Pradesh'),
                                'Jharkhand' => __('Jharkhand'),
                                'Karnataka' => __('Karnataka'),
                                'Kashmir' => __('Kashmir'),
                                'Kerala' => __('Kerala'),
                                'Laccadives' => __('Laccadives'),
                                'Madhya Pradesh' => __('Madhya Pradesh'),
                                'Maharashtra' => __('Maharashtra'),
                                'Manipur' => __('Manipur'),
                                'Meghalaya' => __('Meghalaya'),
                                'Mizoram' => __('Mizoram'),
                                'Nagaland' => __('Nagaland'),
                                'National Capital Territory of Delhi' => __('National Capital Territory of Delhi'),
                                'Odisha' => __('Odisha'),
                                'Punjab' => __('Punjab'),
                                'Rajasthan' => __('Rajasthan'),
                                'Sikkim' => __('Sikkim'),
                                'Tamil Nadu' => __('Tamil Nadu'),
                                'Telangana' => __('Telangana'),
                                'Tripura' => __('Tripura'),
                                'Union Territory of Andaman and Nicobar Islands' => __('Union Territory of Andaman and Nicobar Islands'),
                                'Union Territory of Puducherry' => __('Union Territory of Puducherry'),
                                'Uttar Pradesh' => __('Uttar Pradesh'),
                                'Uttarakhand' => __('Uttarakhand'),
                                'West Bengal' => __('West Bengal'),
                            ],
                    ],
                'IO' =>
                    [
                        'value' => 'IO',
                        'label' => __('British Indian Ocean Territory'),
                        'regions' =>
                            [
                            ],
                    ],
                'IQ' =>
                    [
                        'value' => 'IQ',
                        'label' => __('Iraq'),
                        'regions' =>
                            [
                                'An Najaf' => __('An Najaf'),
                                'Anbar' => __('Anbar'),
                                'Basra Governorate' => __('Basra Governorate'),
                                'Mayorality of Baghdad' => __('Mayorality of Baghdad'),
                                'Maysan' => __('Maysan'),
                                'Muhafazat Arbil' => __('Muhafazat Arbil'),
                                'Muhafazat Babil' => __('Muhafazat Babil'),
                                'Muhafazat Karbala\'' => __('Muhafazat Karbala\''),
                                'Muhafazat Kirkuk' => __('Muhafazat Kirkuk'),
                                'Muhafazat Ninawa' => __('Muhafazat Ninawa'),
                                'Muhafazat Wasit' => __('Muhafazat Wasit'),
                                'Muhafazat as Sulaymaniyah' => __('Muhafazat as Sulaymaniyah'),
                            ],
                    ],
                'IR' =>
                    [
                        'value' => 'IR',
                        'label' => __('Iran'),
                        'regions' =>
                            [
                                'Alborz' => __('Alborz'),
                                'Bushehr' => __('Bushehr'),
                                'East Azerbaijan' => __('East Azerbaijan'),
                                'Fars' => __('Fars'),
                                'Hormozgan' => __('Hormozgan'),
                                'Isfahan' => __('Isfahan'),
                                'Kerman' => __('Kerman'),
                                'Khuzestan' => __('Khuzestan'),
                                'Markazi' => __('Markazi'),
                                'Māzandarān' => __('Māzandarān'),
                                'Ostan-e Ardabil' => __('Ostan-e Ardabil'),
                                'Ostan-e Azarbayjan-e Gharbi' => __('Ostan-e Azarbayjan-e Gharbi'),
                                'Ostan-e Chahar Mahal va Bakhtiari' => __('Ostan-e Chahar Mahal va Bakhtiari'),
                                'Ostan-e Gilan' => __('Ostan-e Gilan'),
                                'Ostan-e Golestan' => __('Ostan-e Golestan'),
                                'Ostan-e Hamadan' => __('Ostan-e Hamadan'),
                                'Ostan-e Ilam' => __('Ostan-e Ilam'),
                                'Ostan-e Kermanshah' => __('Ostan-e Kermanshah'),
                                'Ostan-e Khorasan-e Shomali' => __('Ostan-e Khorasan-e Shomali'),
                                'Ostan-e Kordestan' => __('Ostan-e Kordestan'),
                                'Ostan-e Qazvin' => __('Ostan-e Qazvin'),
                                'Ostan-e Tehran' => __('Ostan-e Tehran'),
                                'Razavi Khorasan' => __('Razavi Khorasan'),
                                'Semnān' => __('Semnān'),
                                'Sistan and Baluchestan' => __('Sistan and Baluchestan'),
                                'Yazd' => __('Yazd'),
                                'Zanjan' => __('Zanjan'),
                            ],
                    ],
                'IS' =>
                    [
                        'value' => 'IS',
                        'label' => __('Iceland'),
                        'regions' =>
                            [
                                'Capital Region' => __('Capital Region'),
                                'East' => __('East'),
                                'Northeast' => __('Northeast'),
                                'Northwest' => __('Northwest'),
                                'South' => __('South'),
                                'Southern Peninsula' => __('Southern Peninsula'),
                                'West' => __('West'),
                                'Westfjords' => __('Westfjords'),
                            ],
                    ],
                'IT' =>
                    [
                        'value' => 'IT',
                        'label' => __('Italy'),
                        'regions' =>
                            [
                                'Abruzzo' => __('Abruzzo'),
                                'Aosta Valley' => __('Aosta Valley'),
                                'Apulia' => __('Apulia'),
                                'Basilicate' => __('Basilicate'),
                                'Calabria' => __('Calabria'),
                                'Campania' => __('Campania'),
                                'Emilia-Romagna' => __('Emilia-Romagna'),
                                'Friuli Venezia Giulia' => __('Friuli Venezia Giulia'),
                                'Latium' => __('Latium'),
                                'Liguria' => __('Liguria'),
                                'Lombardy' => __('Lombardy'),
                                'Molise' => __('Molise'),
                                'Piedmont' => __('Piedmont'),
                                'Sardinia' => __('Sardinia'),
                                'Sicily' => __('Sicily'),
                                'The Marches' => __('The Marches'),
                                'Trentino-Alto Adige' => __('Trentino-Alto Adige'),
                                'Tuscany' => __('Tuscany'),
                                'Umbria' => __('Umbria'),
                                'Veneto' => __('Veneto'),
                            ],
                    ],
                'JE' =>
                    [
                        'value' => 'JE',
                        'label' => __('Jersey'),
                        'regions' =>
                            [
                            ],
                    ],
                'JM' =>
                    [
                        'value' => 'JM',
                        'label' => __('Jamaica'),
                        'regions' =>
                            [
                                'Clarendon' => __('Clarendon'),
                                'Kingston' => __('Kingston'),
                                'Manchester' => __('Manchester'),
                                'Parish of Saint Ann' => __('Parish of Saint Ann'),
                                'Portland' => __('Portland'),
                                'Saint Catherine' => __('Saint Catherine'),
                                'Saint Elizabeth' => __('Saint Elizabeth'),
                                'Saint James' => __('Saint James'),
                                'Saint Mary' => __('Saint Mary'),
                                'Saint Thomas' => __('Saint Thomas'),
                                'Westmoreland' => __('Westmoreland'),
                            ],
                    ],
                'JO' =>
                    [
                        'value' => 'JO',
                        'label' => __('Hashemite Kingdom of Jordan'),
                        'regions' =>
                            [
                                'Ajloun' => __('Ajloun'),
                                'Amman Governorate' => __('Amman Governorate'),
                                'Aqaba' => __('Aqaba'),
                                'Balqa' => __('Balqa'),
                                'Irbid' => __('Irbid'),
                                'Jerash' => __('Jerash'),
                                'Karak' => __('Karak'),
                                'Madaba' => __('Madaba'),
                                'Mafraq' => __('Mafraq'),
                                'Ma’an' => __('Ma’an'),
                                'Tafielah' => __('Tafielah'),
                                'Zarqa' => __('Zarqa'),
                            ],
                    ],
                'JP' =>
                    [
                        'value' => 'JP',
                        'label' => __('Japan'),
                        'regions' =>
                            [
                                'Aichi' => __('Aichi'),
                                'Akita' => __('Akita'),
                                'Aomori' => __('Aomori'),
                                'Chiba' => __('Chiba'),
                                'Ehime' => __('Ehime'),
                                'Fukui' => __('Fukui'),
                                'Fukuoka' => __('Fukuoka'),
                                'Fukushima-ken' => __('Fukushima-ken'),
                                'Gifu' => __('Gifu'),
                                'Gunma' => __('Gunma'),
                                'Hiroshima' => __('Hiroshima'),
                                'Hokkaido' => __('Hokkaido'),
                                'Hyōgo' => __('Hyōgo'),
                                'Ibaraki' => __('Ibaraki'),
                                'Ishikawa' => __('Ishikawa'),
                                'Iwate' => __('Iwate'),
                                'Kagawa' => __('Kagawa'),
                                'Kagoshima' => __('Kagoshima'),
                                'Kanagawa' => __('Kanagawa'),
                                'Kochi' => __('Kochi'),
                                'Kumamoto' => __('Kumamoto'),
                                'Kyoto' => __('Kyoto'),
                                'Mie' => __('Mie'),
                                'Miyagi' => __('Miyagi'),
                                'Miyazaki' => __('Miyazaki'),
                                'Nagano' => __('Nagano'),
                                'Nagasaki' => __('Nagasaki'),
                                'Nara' => __('Nara'),
                                'Niigata' => __('Niigata'),
                                'Oita' => __('Oita'),
                                'Okayama' => __('Okayama'),
                                'Okinawa' => __('Okinawa'),
                                'Saga Prefecture' => __('Saga Prefecture'),
                                'Saitama' => __('Saitama'),
                                'Shiga Prefecture' => __('Shiga Prefecture'),
                                'Shimane' => __('Shimane'),
                                'Shizuoka' => __('Shizuoka'),
                                'Tochigi' => __('Tochigi'),
                                'Tokushima' => __('Tokushima'),
                                'Tokyo' => __('Tokyo'),
                                'Tottori' => __('Tottori'),
                                'Toyama' => __('Toyama'),
                                'Wakayama' => __('Wakayama'),
                                'Yamagata' => __('Yamagata'),
                                'Yamaguchi' => __('Yamaguchi'),
                                'Yamanashi' => __('Yamanashi'),
                                'Ōsaka' => __('Ōsaka'),
                            ],
                    ],
                'KE' =>
                    [
                        'value' => 'KE',
                        'label' => __('Kenya'),
                        'regions' =>
                            [
                                'Bomet District' => __('Bomet District'),
                                'Garissa District' => __('Garissa District'),
                                'Homa Bay District' => __('Homa Bay District'),
                                'Kericho District' => __('Kericho District'),
                                'Kiambu District' => __('Kiambu District'),
                                'Kilifi District' => __('Kilifi District'),
                                'Kisii District' => __('Kisii District'),
                                'Kisumu' => __('Kisumu'),
                                'Kwale District' => __('Kwale District'),
                                'Mandera District' => __('Mandera District'),
                                'Mombasa District' => __('Mombasa District'),
                                'Murang\'a District' => __('Murang\'a District'),
                                'Nairobi Province' => __('Nairobi Province'),
                                'Nakuru District' => __('Nakuru District'),
                                'Nyeri District' => __('Nyeri District'),
                                'Siaya District' => __('Siaya District'),
                                'Tharaka District' => __('Tharaka District'),
                                'Trans Nzoia District' => __('Trans Nzoia District'),
                                'Uasin Gishu' => __('Uasin Gishu'),
                            ],
                    ],
                'KG' =>
                    [
                        'value' => 'KG',
                        'label' => __('Kyrgyzstan'),
                        'regions' =>
                            [
                                'Batken' => __('Batken'),
                                'Chuyskaya Oblast\'' => __('Chuyskaya Oblast\''),
                                'Gorod Bishkek' => __('Gorod Bishkek'),
                                'Issyk-Kul Region' => __('Issyk-Kul Region'),
                                'Jalal-Abad oblast' => __('Jalal-Abad oblast'),
                                'Naryn oblast' => __('Naryn oblast'),
                                'Osh Oblasty' => __('Osh Oblasty'),
                                'Talas' => __('Talas'),
                            ],
                    ],
                'KH' =>
                    [
                        'value' => 'KH',
                        'label' => __('Cambodia'),
                        'regions' =>
                            [
                                'Banteay Meanchey' => __('Banteay Meanchey'),
                                'Battambang' => __('Battambang'),
                                'Kandal' => __('Kandal'),
                                'Phnom Penh' => __('Phnom Penh'),
                                'Preah Sihanouk' => __('Preah Sihanouk'),
                            ],
                    ],
                'KI' =>
                    [
                        'value' => 'KI',
                        'label' => __('Kiribati'),
                        'regions' =>
                            [
                                'Gilbert Islands' => __('Gilbert Islands'),
                            ],
                    ],
                'KM' =>
                    [
                        'value' => 'KM',
                        'label' => __('Comoros'),
                        'regions' =>
                            [
                                'Grande Comore' => __('Grande Comore'),
                                'Ndzuwani' => __('Ndzuwani'),
                            ],
                    ],
                'KN' =>
                    [
                        'value' => 'KN',
                        'label' => __('Saint Kitts and Nevis'),
                        'regions' =>
                            [
                                'Saint George Basseterre' => __('Saint George Basseterre'),
                                'Saint Mary Cayon' => __('Saint Mary Cayon'),
                                'Saint Paul Charlestown' => __('Saint Paul Charlestown'),
                            ],
                    ],
                'KP' =>
                    [
                        'value' => 'KP',
                        'label' => __('North Korea'),
                        'regions' =>
                            [
                                'Chagang-do' => __('Chagang-do'),
                                'Pyongyang' => __('Pyongyang'),
                            ],
                    ],
                'KR' =>
                    [
                        'value' => 'KR',
                        'label' => __('Republic of Korea'),
                        'regions' =>
                            [
                                'Busan' => __('Busan'),
                                'Chungcheongbuk-do' => __('Chungcheongbuk-do'),
                                'Chungcheongnam-do' => __('Chungcheongnam-do'),
                                'Daegu' => __('Daegu'),
                                'Daejeon' => __('Daejeon'),
                                'Gangwon-do' => __('Gangwon-do'),
                                'Gwangju' => __('Gwangju'),
                                'Gyeonggi-do' => __('Gyeonggi-do'),
                                'Gyeongsangbuk-do' => __('Gyeongsangbuk-do'),
                                'Gyeongsangnam-do' => __('Gyeongsangnam-do'),
                                'Incheon' => __('Incheon'),
                                'Jeju-do' => __('Jeju-do'),
                                'Jeollabuk-do' => __('Jeollabuk-do'),
                                'Jeollanam-do' => __('Jeollanam-do'),
                                'Seoul' => __('Seoul'),
                                'Ulsan' => __('Ulsan'),
                            ],
                    ],
                'KW' =>
                    [
                        'value' => 'KW',
                        'label' => __('Kuwait'),
                        'regions' =>
                            [
                                'Al Asimah' => __('Al Asimah'),
                                'Al Aḩmadī' => __('Al Aḩmadī'),
                                'Al Farwaniyah' => __('Al Farwaniyah'),
                                'Hawalli' => __('Hawalli'),
                            ],
                    ],
                'KY' =>
                    [
                        'value' => 'KY',
                        'label' => __('Cayman Islands'),
                        'regions' =>
                            [
                            ],
                    ],
                'KZ' =>
                    [
                        'value' => 'KZ',
                        'label' => __('Kazakhstan'),
                        'regions' =>
                            [
                                'Aktyubinskaya Oblast\'' => __('Aktyubinskaya Oblast\''),
                                'Almaty Oblysy' => __('Almaty Oblysy'),
                                'Almaty Qalasy' => __('Almaty Qalasy'),
                                'Aqmola Oblysy' => __('Aqmola Oblysy'),
                                'Astana Qalasy' => __('Astana Qalasy'),
                                'Atyrau Oblysy' => __('Atyrau Oblysy'),
                                'East Kazakhstan' => __('East Kazakhstan'),
                                'Mangistauskaya Oblast\'' => __('Mangistauskaya Oblast\''),
                                'Pavlodar Oblysy' => __('Pavlodar Oblysy'),
                                'Qaraghandy Oblysy' => __('Qaraghandy Oblysy'),
                                'Qostanay Oblysy' => __('Qostanay Oblysy'),
                                'Qyzylorda Oblysy' => __('Qyzylorda Oblysy'),
                                'Severo-Kazakhstanskaya Oblast\'' => __('Severo-Kazakhstanskaya Oblast\''),
                                'Yuzhno-Kazakhstanskaya Oblast\'' => __('Yuzhno-Kazakhstanskaya Oblast\''),
                                'Zapadno-Kazakhstanskaya Oblast\'' => __('Zapadno-Kazakhstanskaya Oblast\''),
                                'Zhambyl Oblysy' => __('Zhambyl Oblysy'),
                            ],
                    ],
                'LA' =>
                    [
                        'value' => 'LA',
                        'label' => __('Laos'),
                        'regions' =>
                            [
                                'Khammouan' => __('Khammouan'),
                                'Vientiane' => __('Vientiane'),
                            ],
                    ],
                'LB' =>
                    [
                        'value' => 'LB',
                        'label' => __('Lebanon'),
                        'regions' =>
                            [
                                'Beyrouth' => __('Beyrouth'),
                                'Mohafazat Aakkar' => __('Mohafazat Aakkar'),
                                'Mohafazat Baalbek-Hermel' => __('Mohafazat Baalbek-Hermel'),
                                'Mohafazat Liban-Nord' => __('Mohafazat Liban-Nord'),
                                'Mohafazat Mont-Liban' => __('Mohafazat Mont-Liban'),
                                'South Governorate' => __('South Governorate'),
                            ],
                    ],
                'LC' =>
                    [
                        'value' => 'LC',
                        'label' => __('Saint Lucia'),
                        'regions' =>
                            [
                                'Anse-la-Raye' => __('Anse-la-Raye'),
                                'Castries Quarter' => __('Castries Quarter'),
                                'Choiseul Quarter' => __('Choiseul Quarter'),
                                'Dennery Quarter' => __('Dennery Quarter'),
                                'Gros-Islet' => __('Gros-Islet'),
                                'Laborie Quarter' => __('Laborie Quarter'),
                                'Micoud Quarter' => __('Micoud Quarter'),
                                'Quarter of Dauphin' => __('Quarter of Dauphin'),
                                'Quarter of Praslin' => __('Quarter of Praslin'),
                                'Soufriere' => __('Soufriere'),
                                'Vieux-Fort' => __('Vieux-Fort'),
                            ],
                    ],
                'LI' =>
                    [
                        'value' => 'LI',
                        'label' => __('Liechtenstein'),
                        'regions' =>
                            [
                                'Balzers' => __('Balzers'),
                                'Eschen' => __('Eschen'),
                                'Gemeinde Gamprin' => __('Gemeinde Gamprin'),
                                'Mauren' => __('Mauren'),
                                'Ruggell' => __('Ruggell'),
                                'Schaan' => __('Schaan'),
                                'Schellenberg' => __('Schellenberg'),
                                'Triesen' => __('Triesen'),
                                'Triesenberg' => __('Triesenberg'),
                                'Vaduz' => __('Vaduz'),
                            ],
                    ],
                'LK' =>
                    [
                        'value' => 'LK',
                        'label' => __('Sri Lanka'),
                        'regions' =>
                            [
                                'Central Province' => __('Central Province'),
                                'Eastern Province' => __('Eastern Province'),
                                'North Central Province' => __('North Central Province'),
                                'North Western Province' => __('North Western Province'),
                                'Province of Sabaragamuwa' => __('Province of Sabaragamuwa'),
                                'Province of Uva' => __('Province of Uva'),
                                'Southern Province' => __('Southern Province'),
                                'Western Province' => __('Western Province'),
                            ],
                    ],
                'LR' =>
                    [
                        'value' => 'LR',
                        'label' => __('Liberia'),
                        'regions' =>
                            [
                                'Montserrado County' => __('Montserrado County'),
                                'Nimba County' => __('Nimba County'),
                            ],
                    ],
                'LS' =>
                    [
                        'value' => 'LS',
                        'label' => __('Lesotho'),
                        'regions' =>
                            [
                                'Berea' => __('Berea'),
                                'Leribe' => __('Leribe'),
                                'Maseru' => __('Maseru'),
                            ],
                    ],
                'LT' =>
                    [
                        'value' => 'LT',
                        'label' => __('Republic of Lithuania'),
                        'regions' =>
                            [
                                'Alytus County' => __('Alytus County'),
                                'Kaunas County' => __('Kaunas County'),
                                'Klaipėda County' => __('Klaipėda County'),
                                'Marijampolė County' => __('Marijampolė County'),
                                'Panevėžys' => __('Panevėžys'),
                                'Tauragė County' => __('Tauragė County'),
                                'Telšiai County' => __('Telšiai County'),
                                'Utena County' => __('Utena County'),
                                'Vilnius County' => __('Vilnius County'),
                                'Šiauliai County' => __('Šiauliai County'),
                            ],
                    ],
                'LU' =>
                    [
                        'value' => 'LU',
                        'label' => __('Luxembourg'),
                        'regions' =>
                            [
                                'District de Diekirch' => __('District de Diekirch'),
                                'District de Grevenmacher' => __('District de Grevenmacher'),
                                'District de Luxembourg' => __('District de Luxembourg'),
                            ],
                    ],
                'LV' =>
                    [
                        'value' => 'LV',
                        'label' => __('Latvia'),
                        'regions' =>
                            [
                                'Aizkraukles Rajons' => __('Aizkraukles Rajons'),
                                'Aizpute' => __('Aizpute'),
                                'Aloja' => __('Aloja'),
                                'Babīte' => __('Babīte'),
                                'Baldone' => __('Baldone'),
                                'Balvu Novads' => __('Balvu Novads'),
                                'Bauskas Novads' => __('Bauskas Novads'),
                                'Brocēni' => __('Brocēni'),
                                'Burtnieki' => __('Burtnieki'),
                                'Carnikava' => __('Carnikava'),
                                'Cesu Novads' => __('Cesu Novads'),
                                'Daugavpils' => __('Daugavpils'),
                                'Daugavpils municipality' => __('Daugavpils municipality'),
                                'Dobeles Rajons' => __('Dobeles Rajons'),
                                'Dundaga' => __('Dundaga'),
                                'Engure' => __('Engure'),
                                'Garkalne' => __('Garkalne'),
                                'Grobiņa' => __('Grobiņa'),
                                'Gulbenes Rajons' => __('Gulbenes Rajons'),
                                'Ikšķile' => __('Ikšķile'),
                                'Inčukalns' => __('Inčukalns'),
                                'Jaunpiebalga' => __('Jaunpiebalga'),
                                'Jelgava' => __('Jelgava'),
                                'Jelgavas Rajons' => __('Jelgavas Rajons'),
                                'Jurmala' => __('Jurmala'),
                                'Jēkabpils Municipality' => __('Jēkabpils Municipality'),
                                'Kandava' => __('Kandava'),
                                'Koknese' => __('Koknese'),
                                'Kuldigas Rajons' => __('Kuldigas Rajons'),
                                'Lecava' => __('Lecava'),
                                'Lielvārde' => __('Lielvārde'),
                                'Liepaja' => __('Liepaja'),
                                'Limbazu Rajons' => __('Limbazu Rajons'),
                                'Ludzas Rajons' => __('Ludzas Rajons'),
                                'Līvāni' => __('Līvāni'),
                                'Madona Municipality' => __('Madona Municipality'),
                                'Mārupe' => __('Mārupe'),
                                'Ogre' => __('Ogre'),
                                'Olaine' => __('Olaine'),
                                'Ozolnieku Novads' => __('Ozolnieku Novads'),
                                'Plavinu Novads' => __('Plavinu Novads'),
                                'Preili Municipality' => __('Preili Municipality'),
                                'Rezekne' => __('Rezekne'),
                                'Riga' => __('Riga'),
                                'Rojas Novads' => __('Rojas Novads'),
                                'Ropazu Novads' => __('Ropazu Novads'),
                                'Rugaju Novads' => __('Rugaju Novads'),
                                'Rujienas Novads' => __('Rujienas Novads'),
                                'Salacgrivas Novads' => __('Salacgrivas Novads'),
                                'Salaspils Novads' => __('Salaspils Novads'),
                                'Saldus Municipality' => __('Saldus Municipality'),
                                'Saulkrastu Novads' => __('Saulkrastu Novads'),
                                'Siguldas Novads' => __('Siguldas Novads'),
                                'Skrundas Novads' => __('Skrundas Novads'),
                                'Stopinu Novads' => __('Stopinu Novads'),
                                'Talsi Municipality' => __('Talsi Municipality'),
                                'Tukuma Rajons' => __('Tukuma Rajons'),
                                'Vainodes Novads' => __('Vainodes Novads'),
                                'Valka Municipality' => __('Valka Municipality'),
                                'Valmiera District' => __('Valmiera District'),
                                'Varaklanu Novads' => __('Varaklanu Novads'),
                                'Ventspils' => __('Ventspils'),
                                'Viesites Novads' => __('Viesites Novads'),
                                'Zilupes Novads' => __('Zilupes Novads'),
                                'Ādaži' => __('Ādaži'),
                                'Ķegums' => __('Ķegums'),
                                'Ķekava' => __('Ķekava'),
                            ],
                    ],
                'LY' =>
                    [
                        'value' => 'LY',
                        'label' => __('Libya'),
                        'regions' =>
                            [
                                'Sha\'biyat Banghazi' => __('Sha\'biyat Banghazi'),
                                'Sha\'biyat Misratah' => __('Sha\'biyat Misratah'),
                                'Sha\'biyat Sabha' => __('Sha\'biyat Sabha'),
                                'Sha`biyat Nalut' => __('Sha`biyat Nalut'),
                                'Tripoli' => __('Tripoli'),
                            ],
                    ],
                'MA' =>
                    [
                        'value' => 'MA',
                        'label' => __('Morocco'),
                        'regions' =>
                            [
                                'Chaouia-Ouardigha' => __('Chaouia-Ouardigha'),
                                'Doukkala-Abda' => __('Doukkala-Abda'),
                                'Gharb-Chrarda-Beni Hssen' => __('Gharb-Chrarda-Beni Hssen'),
                                'Guelmim-Es Semara' => __('Guelmim-Es Semara'),
                                'Laayoune-Boujdour-Sakia El Hamra' => __('Laayoune-Boujdour-Sakia El Hamra'),
                                'Marrakech-Tensift-Al Haouz' => __('Marrakech-Tensift-Al Haouz'),
                                'Oriental' => __('Oriental'),
                                'Oued-Ed-Dahab' => __('Oued-Ed-Dahab'),
                                'Region de Fes-Boulemane' => __('Region de Fes-Boulemane'),
                                'Region de Meknes-Tafilalet' => __('Region de Meknes-Tafilalet'),
                                'Region de Rabat-Sale-Zemmour-Zaer' => __('Region de Rabat-Sale-Zemmour-Zaer'),
                                'Region de Souss-Massa-Draa' => __('Region de Souss-Massa-Draa'),
                                'Region de Tanger-Tetouan' => __('Region de Tanger-Tetouan'),
                                'Region du Grand Casablanca' => __('Region du Grand Casablanca'),
                                'Tadla-Azilal' => __('Tadla-Azilal'),
                                'Taza-Al Hoceima-Taounate' => __('Taza-Al Hoceima-Taounate'),
                            ],
                    ],
                'MC' =>
                    [
                        'value' => 'MC',
                        'label' => __('Monaco'),
                        'regions' =>
                            [
                            ],
                    ],
                'MD' =>
                    [
                        'value' => 'MD',
                        'label' => __('Republic of Moldova'),
                        'regions' =>
                            [
                                'Anenii Noi' => __('Anenii Noi'),
                                'Basarabeasca' => __('Basarabeasca'),
                                'Briceni' => __('Briceni'),
                                'Cahul' => __('Cahul'),
                                'Cantemir' => __('Cantemir'),
                                'Cimişlia' => __('Cimişlia'),
                                'Criuleni' => __('Criuleni'),
                                'Donduşeni' => __('Donduşeni'),
                                'Drochia' => __('Drochia'),
                                'Floreşti' => __('Floreşti'),
                                'Făleşti' => __('Făleşti'),
                                'Gagauzia' => __('Gagauzia'),
                                'Glodeni' => __('Glodeni'),
                                'Hînceşti' => __('Hînceşti'),
                                'Laloveni' => __('Laloveni'),
                                'Leova' => __('Leova'),
                                'Municipiul Balti' => __('Municipiul Balti'),
                                'Municipiul Bender' => __('Municipiul Bender'),
                                'Municipiul Chisinau' => __('Municipiul Chisinau'),
                                'Nisporeni' => __('Nisporeni'),
                                'Orhei' => __('Orhei'),
                                'Raionul Causeni' => __('Raionul Causeni'),
                                'Raionul Dubasari' => __('Raionul Dubasari'),
                                'Raionul Edineţ' => __('Raionul Edineţ'),
                                'Raionul Ocniţa' => __('Raionul Ocniţa'),
                                'Raionul Soroca' => __('Raionul Soroca'),
                                'Raionul Stefan Voda' => __('Raionul Stefan Voda'),
                                'Rezina' => __('Rezina'),
                                'Rîşcani' => __('Rîşcani'),
                                'Strășeni' => __('Strășeni'),
                                'Sîngerei' => __('Sîngerei'),
                                'Taraclia' => __('Taraclia'),
                                'Teleneşti' => __('Teleneşti'),
                                'Ungheni' => __('Ungheni'),
                                'Unitatea Teritoriala din Stinga Nistrului' => __('Unitatea Teritoriala din Stinga Nistrului'),
                                'Şoldăneşti' => __('Şoldăneşti'),
                            ],
                    ],
                'ME' =>
                    [
                        'value' => 'ME',
                        'label' => __('Montenegro'),
                        'regions' =>
                            [
                                'Berane' => __('Berane'),
                                'Budva' => __('Budva'),
                                'Danilovgrad' => __('Danilovgrad'),
                                'Herceg Novi' => __('Herceg Novi'),
                                'Kotor' => __('Kotor'),
                                'Podgorica' => __('Podgorica'),
                                'Ulcinj' => __('Ulcinj'),
                            ],
                    ],
                'MF' =>
                    [
                        'value' => 'MF',
                        'label' => __('Saint Martin'),
                        'regions' =>
                            [
                            ],
                    ],
                'MG' =>
                    [
                        'value' => 'MG',
                        'label' => __('Madagascar'),
                        'regions' =>
                            [
                            ],
                    ],
                'MH' =>
                    [
                        'value' => 'MH',
                        'label' => __('Marshall Islands'),
                        'regions' =>
                            [
                                'Majuro Atoll' => __('Majuro Atoll'),
                            ],
                    ],
                'MK' =>
                    [
                        'value' => 'MK',
                        'label' => __('Macedonia'),
                        'regions' =>
                            [
                                'Bitola' => __('Bitola'),
                                'Bogdanci' => __('Bogdanci'),
                                'Bogovinje' => __('Bogovinje'),
                                'Debar' => __('Debar'),
                                'Demir Hisar' => __('Demir Hisar'),
                                'Gevgelija' => __('Gevgelija'),
                                'Gostivar' => __('Gostivar'),
                                'Gradsko' => __('Gradsko'),
                                'Kavadarci' => __('Kavadarci'),
                                'Kisela Voda' => __('Kisela Voda'),
                                'Kratovo' => __('Kratovo'),
                                'Kumanovo' => __('Kumanovo'),
                                'Makedonski Brod' => __('Makedonski Brod'),
                                'Negotino' => __('Negotino'),
                                'Novo Selo' => __('Novo Selo'),
                                'Ohrid' => __('Ohrid'),
                                'Opstina Karpos' => __('Opstina Karpos'),
                                'Opstina Kicevo' => __('Opstina Kicevo'),
                                'Opstina Kocani' => __('Opstina Kocani'),
                                'Opstina Lipkovo' => __('Opstina Lipkovo'),
                                'Opstina Probistip' => __('Opstina Probistip'),
                                'Opstina Radovis' => __('Opstina Radovis'),
                                'Opstina Stip' => __('Opstina Stip'),
                                'Opstina Vrapciste' => __('Opstina Vrapciste'),
                                'Prilep' => __('Prilep'),
                                'Resen Municipality' => __('Resen Municipality'),
                                'Struga' => __('Struga'),
                                'Strumica' => __('Strumica'),
                                'Tetovo' => __('Tetovo'),
                                'Valandovo Municipality' => __('Valandovo Municipality'),
                                'Veles' => __('Veles'),
                            ],
                    ],
                'ML' =>
                    [
                        'value' => 'ML',
                        'label' => __('Mali'),
                        'regions' =>
                            [
                                'Bamako Region' => __('Bamako Region'),
                            ],
                    ],
                'MM' =>
                    [
                        'value' => 'MM',
                        'label' => __('Myanmar [Burma]'),
                        'regions' =>
                            [
                                'Kayah State' => __('Kayah State'),
                                'Magway Region' => __('Magway Region'),
                                'Mandalay Region' => __('Mandalay Region'),
                                'Yangon Region' => __('Yangon Region'),
                            ],
                    ],
                'MN' =>
                    [
                        'value' => 'MN',
                        'label' => __('Mongolia'),
                        'regions' =>
                            [
                                'Arhangay Aymag' => __('Arhangay Aymag'),
                                'Bayan-OElgiy Aymag' => __('Bayan-OElgiy Aymag'),
                                'Bayanhongor Aymag' => __('Bayanhongor Aymag'),
                                'Central Aimak' => __('Central Aimak'),
                                'East Gobi Aymag' => __('East Gobi Aymag'),
                                'Govi-Altay Aymag' => __('Govi-Altay Aymag'),
                                'Govi-Sumber' => __('Govi-Sumber'),
                                'Hentiy Aymag' => __('Hentiy Aymag'),
                                'Hovd' => __('Hovd'),
                                'Hovsgol Aymag' => __('Hovsgol Aymag'),
                                'Middle Govĭ' => __('Middle Govĭ'),
                                'Selenge Aymag' => __('Selenge Aymag'),
                                'Suhbaatar Aymag' => __('Suhbaatar Aymag'),
                                'Ulaanbaatar Hot' => __('Ulaanbaatar Hot'),
                                'Ömnögovĭ' => __('Ömnögovĭ'),
                                'Övörhangay' => __('Övörhangay'),
                            ],
                    ],
                'MO' =>
                    [
                        'value' => 'MO',
                        'label' => __('Macao'),
                        'regions' =>
                            [
                            ],
                    ],
                'MP' =>
                    [
                        'value' => 'MP',
                        'label' => __('Northern Mariana Islands'),
                        'regions' =>
                            [
                                'Saipan' => __('Saipan'),
                            ],
                    ],
                'MQ' =>
                    [
                        'value' => 'MQ',
                        'label' => __('Martinique'),
                        'regions' =>
                            [
                            ],
                    ],
                'MR' =>
                    [
                        'value' => 'MR',
                        'label' => __('Mauritania'),
                        'regions' =>
                            [
                                'District de Nouakchott' => __('District de Nouakchott'),
                            ],
                    ],
                'MS' =>
                    [
                        'value' => 'MS',
                        'label' => __('Montserrat'),
                        'regions' =>
                            [
                            ],
                    ],
                'MT' =>
                    [
                        'value' => 'MT',
                        'label' => __('Malta'),
                        'regions' =>
                            [
                                'Attard' => __('Attard'),
                                'Balzan' => __('Balzan'),
                                'Birkirkara' => __('Birkirkara'),
                                'Birzebbuga' => __('Birzebbuga'),
                                'Bormla' => __('Bormla'),
                                'Ghajnsielem' => __('Ghajnsielem'),
                                'Hal Gharghur' => __('Hal Gharghur'),
                                'Hal Ghaxaq' => __('Hal Ghaxaq'),
                                'Haz-Zabbar' => __('Haz-Zabbar'),
                                'Haz-Zebbug' => __('Haz-Zebbug'),
                                'Il-Belt Valletta' => __('Il-Belt Valletta'),
                                'Il-Birgu' => __('Il-Birgu'),
                                'Il-Fgura' => __('Il-Fgura'),
                                'Il-Furjana' => __('Il-Furjana'),
                                'Il-Gudja' => __('Il-Gudja'),
                                'Il-Gzira' => __('Il-Gzira'),
                                'Il-Hamrun' => __('Il-Hamrun'),
                                'Il-Kalkara' => __('Il-Kalkara'),
                                'Il-Marsa' => __('Il-Marsa'),
                                'Il-Mellieha' => __('Il-Mellieha'),
                                'Il-Mosta' => __('Il-Mosta'),
                                'Il-Munxar' => __('Il-Munxar'),
                                'Il-Qala' => __('Il-Qala'),
                                'Il-Qrendi' => __('Il-Qrendi'),
                                'In-Naxxar' => __('In-Naxxar'),
                                'Ir-Rabat' => __('Ir-Rabat'),
                                'Is-Siggiewi' => __('Is-Siggiewi'),
                                'Is-Swieqi' => __('Is-Swieqi'),
                                'Ix-Xaghra' => __('Ix-Xaghra'),
                                'Ix-Xewkija' => __('Ix-Xewkija'),
                                'Iz-Zebbug' => __('Iz-Zebbug'),
                                'Iz-Zejtun' => __('Iz-Zejtun'),
                                'Iz-Zurrieq' => __('Iz-Zurrieq'),
                                'Kirkop' => __('Kirkop'),
                                'L-Gharb' => __('L-Gharb'),
                                'L-Ghasri' => __('L-Ghasri'),
                                'L-Iklin' => __('L-Iklin'),
                                'L-Imdina' => __('L-Imdina'),
                                'L-Imgarr' => __('L-Imgarr'),
                                'L-Imqabba' => __('L-Imqabba'),
                                'L-Imsida' => __('L-Imsida'),
                                'L-Imtarfa' => __('L-Imtarfa'),
                                'L-Isla' => __('L-Isla'),
                                'Lija' => __('Lija'),
                                'Luqa' => __('Luqa'),
                                'Marsaskala' => __('Marsaskala'),
                                'Marsaxlokk' => __('Marsaxlokk'),
                                'Paola' => __('Paola'),
                                'Qormi' => __('Qormi'),
                                'Safi' => __('Safi'),
                                'Saint John' => __('Saint John'),
                                'Saint Julian' => __('Saint Julian'),
                                'Saint Lawrence' => __('Saint Lawrence'),
                                'Saint Lucia' => __('Saint Lucia'),
                                'Saint Paul’s Bay' => __('Saint Paul’s Bay'),
                                'Saint Venera' => __('Saint Venera'),
                                'Sannat' => __('Sannat'),
                                'Ta\' Xbiex' => __('Ta\' Xbiex'),
                                'Tal-Pieta' => __('Tal-Pieta'),
                                'Tarxien' => __('Tarxien'),
                                'Tas-Sliema' => __('Tas-Sliema'),
                                'Victoria' => __('Victoria'),
                            ],
                    ],
                'MU' =>
                    [
                        'value' => 'MU',
                        'label' => __('Mauritius'),
                        'regions' =>
                            [
                                'Black River District' => __('Black River District'),
                                'Flacq District' => __('Flacq District'),
                                'Moka District' => __('Moka District'),
                                'Pamplemousses District' => __('Pamplemousses District'),
                                'Plaines Wilhems District' => __('Plaines Wilhems District'),
                                'Port Louis District' => __('Port Louis District'),
                                'Riviere du Rempart District' => __('Riviere du Rempart District'),
                                'Rodrigues' => __('Rodrigues'),
                                'Savanne District' => __('Savanne District'),
                            ],
                    ],
                'MV' =>
                    [
                        'value' => 'MV',
                        'label' => __('Maldives'),
                        'regions' =>
                            [
                                'Kaafu Atoll' => __('Kaafu Atoll'),
                            ],
                    ],
                'MW' =>
                    [
                        'value' => 'MW',
                        'label' => __('Malawi'),
                        'regions' =>
                            [
                                'Central Region' => __('Central Region'),
                                'Northern Region' => __('Northern Region'),
                                'Southern Region' => __('Southern Region'),
                            ],
                    ],
                'MX' =>
                    [
                        'value' => 'MX',
                        'label' => __('Mexico'),
                        'regions' =>
                            [
                                'Aguascalientes' => __('Aguascalientes'),
                                'Baja California Sur' => __('Baja California Sur'),
                                'Campeche' => __('Campeche'),
                                'Chiapas' => __('Chiapas'),
                                'Chihuahua' => __('Chihuahua'),
                                'Coahuila' => __('Coahuila'),
                                'Colima' => __('Colima'),
                                'Durango' => __('Durango'),
                                'Estado de Baja California' => __('Estado de Baja California'),
                                'Estado de Mexico' => __('Estado de Mexico'),
                                'Guanajuato' => __('Guanajuato'),
                                'Guerrero' => __('Guerrero'),
                                'Hidalgo' => __('Hidalgo'),
                                'Jalisco' => __('Jalisco'),
                                'Mexico City' => __('Mexico City'),
                                'Michoacán' => __('Michoacán'),
                                'Morelos' => __('Morelos'),
                                'Nayarit' => __('Nayarit'),
                                'Nuevo León' => __('Nuevo León'),
                                'Oaxaca' => __('Oaxaca'),
                                'Puebla' => __('Puebla'),
                                'Querétaro' => __('Querétaro'),
                                'Quintana Roo' => __('Quintana Roo'),
                                'San Luis Potosí' => __('San Luis Potosí'),
                                'Sinaloa' => __('Sinaloa'),
                                'Sonora' => __('Sonora'),
                                'Tabasco' => __('Tabasco'),
                                'Tamaulipas' => __('Tamaulipas'),
                                'Tlaxcala' => __('Tlaxcala'),
                                'Veracruz' => __('Veracruz'),
                                'Yucatán' => __('Yucatán'),
                                'Zacatecas' => __('Zacatecas'),
                            ],
                    ],
                'MY' =>
                    [
                        'value' => 'MY',
                        'label' => __('Malaysia'),
                        'regions' =>
                            [
                                'Johor' => __('Johor'),
                                'Kedah' => __('Kedah'),
                                'Kelantan' => __('Kelantan'),
                                'Kuala Lumpur' => __('Kuala Lumpur'),
                                'Labuan' => __('Labuan'),
                                'Melaka' => __('Melaka'),
                                'Negeri Sembilan' => __('Negeri Sembilan'),
                                'Pahang' => __('Pahang'),
                                'Penang' => __('Penang'),
                                'Perak' => __('Perak'),
                                'Perlis' => __('Perlis'),
                                'Putrajaya' => __('Putrajaya'),
                                'Sabah' => __('Sabah'),
                                'Sarawak' => __('Sarawak'),
                                'Selangor' => __('Selangor'),
                                'Terengganu' => __('Terengganu'),
                            ],
                    ],
                'MZ' =>
                    [
                        'value' => 'MZ',
                        'label' => __('Mozambique'),
                        'regions' =>
                            [
                                'Cabo Delgado Province' => __('Cabo Delgado Province'),
                                'Cidade de Maputo' => __('Cidade de Maputo'),
                                'Gaza Province' => __('Gaza Province'),
                                'Inhambane Province' => __('Inhambane Province'),
                                'Manica Province' => __('Manica Province'),
                                'Maputo Province' => __('Maputo Province'),
                                'Nampula' => __('Nampula'),
                                'Niassa Province' => __('Niassa Province'),
                                'Provincia de Zambezia' => __('Provincia de Zambezia'),
                                'Sofala Province' => __('Sofala Province'),
                                'Tete' => __('Tete'),
                            ],
                    ],
                'NA' =>
                    [
                        'value' => 'NA',
                        'label' => __('Namibia'),
                        'regions' =>
                            [
                                'Erongo' => __('Erongo'),
                                'Karas' => __('Karas'),
                                'Kavango East' => __('Kavango East'),
                                'Khomas' => __('Khomas'),
                                'Kunene' => __('Kunene'),
                                'Omaheke' => __('Omaheke'),
                                'Omusati' => __('Omusati'),
                                'Oshana' => __('Oshana'),
                                'Oshikoto' => __('Oshikoto'),
                                'Otjozondjupa' => __('Otjozondjupa'),
                                'Zambezi Region' => __('Zambezi Region'),
                            ],
                    ],
                'NC' =>
                    [
                        'value' => 'NC',
                        'label' => __('New Caledonia'),
                        'regions' =>
                            [
                                'South Province' => __('South Province'),
                            ],
                    ],
                'NE' =>
                    [
                        'value' => 'NE',
                        'label' => __('Niger'),
                        'regions' =>
                            [
                                'Niamey' => __('Niamey'),
                            ],
                    ],
                'NF' =>
                    [
                        'value' => 'NF',
                        'label' => __('Norfolk Island'),
                        'regions' =>
                            [
                            ],
                    ],
                'NG' =>
                    [
                        'value' => 'NG',
                        'label' => __('Nigeria'),
                        'regions' =>
                            [
                                'Abia State' => __('Abia State'),
                                'Adamawa' => __('Adamawa'),
                                'Akwa Ibom State' => __('Akwa Ibom State'),
                                'Anambra' => __('Anambra'),
                                'Bauchi' => __('Bauchi'),
                                'Bayelsa State' => __('Bayelsa State'),
                                'Cross River State' => __('Cross River State'),
                                'Delta' => __('Delta'),
                                'Ebonyi State' => __('Ebonyi State'),
                                'Edo' => __('Edo'),
                                'Ekiti State' => __('Ekiti State'),
                                'Enugu State' => __('Enugu State'),
                                'Federal Capital Territory' => __('Federal Capital Territory'),
                                'Gombe State' => __('Gombe State'),
                                'Imo State' => __('Imo State'),
                                'Kaduna State' => __('Kaduna State'),
                                'Kano State' => __('Kano State'),
                                'Katsina State' => __('Katsina State'),
                                'Kebbi State' => __('Kebbi State'),
                                'Kogi State' => __('Kogi State'),
                                'Kwara State' => __('Kwara State'),
                                'Lagos' => __('Lagos'),
                                'Nasarawa State' => __('Nasarawa State'),
                                'Niger State' => __('Niger State'),
                                'Ogun State' => __('Ogun State'),
                                'Ondo State' => __('Ondo State'),
                                'Osun State' => __('Osun State'),
                                'Oyo State' => __('Oyo State'),
                                'Plateau State' => __('Plateau State'),
                                'Rivers State' => __('Rivers State'),
                                'Sokoto State' => __('Sokoto State'),
                                'Taraba State' => __('Taraba State'),
                                'Yobe State' => __('Yobe State'),
                                'Zamfara State' => __('Zamfara State'),
                            ],
                    ],
                'NI' =>
                    [
                        'value' => 'NI',
                        'label' => __('Nicaragua'),
                        'regions' =>
                            [
                                'Departamento de Boaco' => __('Departamento de Boaco'),
                                'Departamento de Carazo' => __('Departamento de Carazo'),
                                'Departamento de Chinandega' => __('Departamento de Chinandega'),
                                'Departamento de Chontales' => __('Departamento de Chontales'),
                                'Departamento de Esteli' => __('Departamento de Esteli'),
                                'Departamento de Granada' => __('Departamento de Granada'),
                                'Departamento de Jinotega' => __('Departamento de Jinotega'),
                                'Departamento de Leon' => __('Departamento de Leon'),
                                'Departamento de Managua' => __('Departamento de Managua'),
                                'Departamento de Masaya' => __('Departamento de Masaya'),
                                'Departamento de Matagalpa' => __('Departamento de Matagalpa'),
                                'Departamento de Nueva Segovia' => __('Departamento de Nueva Segovia'),
                                'Departamento de Rivas' => __('Departamento de Rivas'),
                                'Region Autonoma Atlantico Sur' => __('Region Autonoma Atlantico Sur'),
                            ],
                    ],
                'NL' =>
                    [
                        'value' => 'NL',
                        'label' => __('Netherlands'),
                        'regions' =>
                            [
                                'Friesland' => __('Friesland'),
                                'Groningen' => __('Groningen'),
                                'Limburg' => __('Limburg'),
                                'North Brabant' => __('North Brabant'),
                                'North Holland' => __('North Holland'),
                                'Provincie Drenthe' => __('Provincie Drenthe'),
                                'Provincie Flevoland' => __('Provincie Flevoland'),
                                'Provincie Gelderland' => __('Provincie Gelderland'),
                                'Provincie Overijssel' => __('Provincie Overijssel'),
                                'Provincie Utrecht' => __('Provincie Utrecht'),
                                'Provincie Zeeland' => __('Provincie Zeeland'),
                                'South Holland' => __('South Holland'),
                            ],
                    ],
                'NO' =>
                    [
                        'value' => 'NO',
                        'label' => __('Norway'),
                        'regions' =>
                            [
                                'Akershus' => __('Akershus'),
                                'Aust-Agder' => __('Aust-Agder'),
                                'Buskerud' => __('Buskerud'),
                                'Finnmark Fylke' => __('Finnmark Fylke'),
                                'Hedmark' => __('Hedmark'),
                                'Hordaland Fylke' => __('Hordaland Fylke'),
                                'More og Romsdal fylke' => __('More og Romsdal fylke'),
                                'Nord-Trondelag Fylke' => __('Nord-Trondelag Fylke'),
                                'Nordland Fylke' => __('Nordland Fylke'),
                                'Oppland' => __('Oppland'),
                                'Oslo County' => __('Oslo County'),
                                'Rogaland Fylke' => __('Rogaland Fylke'),
                                'Sogn og Fjordane Fylke' => __('Sogn og Fjordane Fylke'),
                                'Sor-Trondelag Fylke' => __('Sor-Trondelag Fylke'),
                                'Telemark' => __('Telemark'),
                                'Troms Fylke' => __('Troms Fylke'),
                                'Vest-Agder Fylke' => __('Vest-Agder Fylke'),
                                'Vestfold' => __('Vestfold'),
                                'Østfold' => __('Østfold'),
                            ],
                    ],
                'NP' =>
                    [
                        'value' => 'NP',
                        'label' => __('Nepal'),
                        'regions' =>
                            [
                                'Central Region' => __('Central Region'),
                                'Eastern Region' => __('Eastern Region'),
                                'Far Western' => __('Far Western'),
                                'Western Region' => __('Western Region'),
                            ],
                    ],
                'NR' =>
                    [
                        'value' => 'NR',
                        'label' => __('Nauru'),
                        'regions' =>
                            [
                                'Anabar' => __('Anabar'),
                            ],
                    ],
                'NU' =>
                    [
                        'value' => 'NU',
                        'label' => __('Niue'),
                        'regions' =>
                            [
                            ],
                    ],
                'NZ' =>
                    [
                        'value' => 'NZ',
                        'label' => __('New Zealand'),
                        'regions' =>
                            [
                                'Auckland' => __('Auckland'),
                                'Bay of Plenty Region' => __('Bay of Plenty Region'),
                                'Canterbury' => __('Canterbury'),
                                'Chatham Islands' => __('Chatham Islands'),
                                'Gisborne' => __('Gisborne'),
                                'Hawke\'s Bay' => __('Hawke\'s Bay'),
                                'Manawatu-Wanganui' => __('Manawatu-Wanganui'),
                                'Marlborough' => __('Marlborough'),
                                'Nelson' => __('Nelson'),
                                'Northland Region' => __('Northland Region'),
                                'Otago' => __('Otago'),
                                'Southland' => __('Southland'),
                                'Taranaki' => __('Taranaki'),
                                'Tasman' => __('Tasman'),
                                'Waikato' => __('Waikato'),
                                'Wellington' => __('Wellington'),
                                'West Coast' => __('West Coast'),
                            ],
                    ],
                'OM' =>
                    [
                        'value' => 'OM',
                        'label' => __('Oman'),
                        'regions' =>
                            [
                                'Al Batinah North Governorate' => __('Al Batinah North Governorate'),
                                'Muhafazat Masqat' => __('Muhafazat Masqat'),
                                'Muhafazat Zufar' => __('Muhafazat Zufar'),
                                'Muhafazat ad Dakhiliyah' => __('Muhafazat ad Dakhiliyah'),
                            ],
                    ],
                'PA' =>
                    [
                        'value' => 'PA',
                        'label' => __('Panama'),
                        'regions' =>
                            [
                                'Embera-Wounaan' => __('Embera-Wounaan'),
                                'Guna Yala' => __('Guna Yala'),
                                'Ngoebe-Bugle' => __('Ngoebe-Bugle'),
                                'Provincia de Bocas del Toro' => __('Provincia de Bocas del Toro'),
                                'Provincia de Chiriqui' => __('Provincia de Chiriqui'),
                                'Provincia de Cocle' => __('Provincia de Cocle'),
                                'Provincia de Colon' => __('Provincia de Colon'),
                                'Provincia de Herrera' => __('Provincia de Herrera'),
                                'Provincia de Los Santos' => __('Provincia de Los Santos'),
                                'Provincia de Panama' => __('Provincia de Panama'),
                                'Provincia de Veraguas' => __('Provincia de Veraguas'),
                                'Provincia del Darien' => __('Provincia del Darien'),
                            ],
                    ],
                'PE' =>
                    [
                        'value' => 'PE',
                        'label' => __('Peru'),
                        'regions' =>
                            [
                                'Amazonas' => __('Amazonas'),
                                'Ancash' => __('Ancash'),
                                'Apurimac' => __('Apurimac'),
                                'Arequipa' => __('Arequipa'),
                                'Ayacucho' => __('Ayacucho'),
                                'Cajamarca' => __('Cajamarca'),
                                'Callao' => __('Callao'),
                                'Cusco' => __('Cusco'),
                                'Departamento de Moquegua' => __('Departamento de Moquegua'),
                                'Huancavelica' => __('Huancavelica'),
                                'Ica' => __('Ica'),
                                'Junín Region' => __('Junín Region'),
                                'La Libertad' => __('La Libertad'),
                                'Lambayeque' => __('Lambayeque'),
                                'Lima region' => __('Lima region'),
                                'Loreto' => __('Loreto'),
                                'Pasco Region' => __('Pasco Region'),
                                'Piura' => __('Piura'),
                                'Provincia de Lima' => __('Provincia de Lima'),
                                'Puno' => __('Puno'),
                                'Region de Huanuco' => __('Region de Huanuco'),
                                'Region de San Martin' => __('Region de San Martin'),
                                'Tacna' => __('Tacna'),
                                'Tumbes' => __('Tumbes'),
                                'Ucayali' => __('Ucayali'),
                            ],
                    ],
                'PF' =>
                    [
                        'value' => 'PF',
                        'label' => __('French Polynesia'),
                        'regions' =>
                            [
                                'Iles du Vent' => __('Iles du Vent'),
                                'Leeward Islands' => __('Leeward Islands'),
                            ],
                    ],
                'PG' =>
                    [
                        'value' => 'PG',
                        'label' => __('Papua New Guinea'),
                        'regions' =>
                            [
                                'Bougainville' => __('Bougainville'),
                                'Central Province' => __('Central Province'),
                                'Chimbu Province' => __('Chimbu Province'),
                                'East New Britain Province' => __('East New Britain Province'),
                                'East Sepik Province' => __('East Sepik Province'),
                                'Eastern Highlands Province' => __('Eastern Highlands Province'),
                                'Enga Province' => __('Enga Province'),
                                'Gulf Province' => __('Gulf Province'),
                                'Madang Province' => __('Madang Province'),
                                'Manus Province' => __('Manus Province'),
                                'Milne Bay Province' => __('Milne Bay Province'),
                                'Morobe Province' => __('Morobe Province'),
                                'National Capital' => __('National Capital'),
                                'New Ireland' => __('New Ireland'),
                                'Northern Province' => __('Northern Province'),
                                'Southern Highlands Province' => __('Southern Highlands Province'),
                                'West New Britain Province' => __('West New Britain Province'),
                                'West Sepik Province' => __('West Sepik Province'),
                                'Western Highlands Province' => __('Western Highlands Province'),
                                'Western Province' => __('Western Province'),
                            ],
                    ],
                'PH' =>
                    [
                        'value' => 'PH',
                        'label' => __('Philippines'),
                        'regions' =>
                            [
                                'Autonomous Region in Muslim Mindanao' => __('Autonomous Region in Muslim Mindanao'),
                                'Bicol' => __('Bicol'),
                                'Cagayan Valley' => __('Cagayan Valley'),
                                'Calabarzon' => __('Calabarzon'),
                                'Caraga' => __('Caraga'),
                                'Central Luzon' => __('Central Luzon'),
                                'Central Visayas' => __('Central Visayas'),
                                'Cordillera' => __('Cordillera'),
                                'Davao' => __('Davao'),
                                'Eastern Visayas' => __('Eastern Visayas'),
                                'Ilocos' => __('Ilocos'),
                                'Mimaropa' => __('Mimaropa'),
                                'National Capital Region' => __('National Capital Region'),
                                'Northern Mindanao' => __('Northern Mindanao'),
                                'Soccsksargen' => __('Soccsksargen'),
                                'Western Visayas' => __('Western Visayas'),
                                'Zamboanga Peninsula' => __('Zamboanga Peninsula'),
                            ],
                    ],
                'PK' =>
                    [
                        'value' => 'PK',
                        'label' => __('Pakistan'),
                        'regions' =>
                            [
                                'Azad Kashmir' => __('Azad Kashmir'),
                                'Balochistan' => __('Balochistan'),
                                'Federally Administered Tribal Areas' => __('Federally Administered Tribal Areas'),
                                'Gilgit-Baltistan' => __('Gilgit-Baltistan'),
                                'Islamabad Capital Territory' => __('Islamabad Capital Territory'),
                                'Khyber Pakhtunkhwa' => __('Khyber Pakhtunkhwa'),
                                'Punjab' => __('Punjab'),
                                'Sindh' => __('Sindh'),
                            ],
                    ],
                'PL' =>
                    [
                        'value' => 'PL',
                        'label' => __('Poland'),
                        'regions' =>
                            [
                                'Greater Poland Voivodeship' => __('Greater Poland Voivodeship'),
                                'Kujawsko-Pomorskie' => __('Kujawsko-Pomorskie'),
                                'Lesser Poland Voivodeship' => __('Lesser Poland Voivodeship'),
                                'Lower Silesian Voivodeship' => __('Lower Silesian Voivodeship'),
                                'Lublin Voivodeship' => __('Lublin Voivodeship'),
                                'Lubusz' => __('Lubusz'),
                                'Masovian Voivodeship' => __('Masovian Voivodeship'),
                                'Opole Voivodeship' => __('Opole Voivodeship'),
                                'Podlasie' => __('Podlasie'),
                                'Pomeranian Voivodeship' => __('Pomeranian Voivodeship'),
                                'Silesian Voivodeship' => __('Silesian Voivodeship'),
                                'Subcarpathian Voivodeship' => __('Subcarpathian Voivodeship'),
                                'Warmian-Masurian Voivodeship' => __('Warmian-Masurian Voivodeship'),
                                'West Pomeranian Voivodeship' => __('West Pomeranian Voivodeship'),
                                'Łódź Voivodeship' => __('Łódź Voivodeship'),
                                'Świętokrzyskie' => __('Świętokrzyskie'),
                            ],
                    ],
                'PM' =>
                    [
                        'value' => 'PM',
                        'label' => __('Saint Pierre and Miquelon'),
                        'regions' =>
                            [
                                'Commune de Miquelon-Langlade' => __('Commune de Miquelon-Langlade'),
                                'Commune de Saint-Pierre' => __('Commune de Saint-Pierre'),
                            ],
                    ],
                'PN' =>
                    [
                        'value' => 'PN',
                        'label' => __('Pitcairn Islands'),
                        'regions' =>
                            [
                            ],
                    ],
                'PR' =>
                    [
                        'value' => 'PR',
                        'label' => __('Puerto Rico'),
                        'regions' =>
                            [
                            ],
                    ],
                'PS' =>
                    [
                        'value' => 'PS',
                        'label' => __('Palestine'),
                        'regions' =>
                            [
                            ],
                    ],
                'PT' =>
                    [
                        'value' => 'PT',
                        'label' => __('Portugal'),
                        'regions' =>
                            [
                                'Aveiro' => __('Aveiro'),
                                'Azores' => __('Azores'),
                                'Beja' => __('Beja'),
                                'Braga' => __('Braga'),
                                'Bragança' => __('Bragança'),
                                'Castelo Branco' => __('Castelo Branco'),
                                'Coimbra' => __('Coimbra'),
                                'Faro' => __('Faro'),
                                'Guarda' => __('Guarda'),
                                'Leiria' => __('Leiria'),
                                'Lisbon' => __('Lisbon'),
                                'Madeira' => __('Madeira'),
                                'Portalegre' => __('Portalegre'),
                                'Porto' => __('Porto'),
                                'Santarém' => __('Santarém'),
                                'Setúbal' => __('Setúbal'),
                                'Viana do Castelo' => __('Viana do Castelo'),
                                'Vila Real' => __('Vila Real'),
                                'Viseu' => __('Viseu'),
                                'Évora' => __('Évora'),
                            ],
                    ],
                'PW' =>
                    [
                        'value' => 'PW',
                        'label' => __('Palau'),
                        'regions' =>
                            [
                            ],
                    ],
                'PY' =>
                    [
                        'value' => 'PY',
                        'label' => __('Paraguay'),
                        'regions' =>
                            [
                                'Asuncion' => __('Asuncion'),
                                'Departamento Central' => __('Departamento Central'),
                                'Departamento de Alto Paraguay' => __('Departamento de Alto Paraguay'),
                                'Departamento de Boqueron' => __('Departamento de Boqueron'),
                                'Departamento de Caazapa' => __('Departamento de Caazapa'),
                                'Departamento de Itapua' => __('Departamento de Itapua'),
                                'Departamento de Misiones' => __('Departamento de Misiones'),
                                'Departamento de Paraguari' => __('Departamento de Paraguari'),
                                'Departamento de la Cordillera' => __('Departamento de la Cordillera'),
                                'Departamento del Alto Parana' => __('Departamento del Alto Parana'),
                                'Departamento del Amambay' => __('Departamento del Amambay'),
                            ],
                    ],
                'QA' =>
                    [
                        'value' => 'QA',
                        'label' => __('Qatar'),
                        'regions' =>
                            [
                                'Al Wakrah' => __('Al Wakrah'),
                                'Baladiyat Umm Salal' => __('Baladiyat Umm Salal'),
                                'Baladiyat ad Dawhah' => __('Baladiyat ad Dawhah'),
                                'Baladiyat al Khawr wa adh Dhakhirah' => __('Baladiyat al Khawr wa adh Dhakhirah'),
                                'Baladiyat ar Rayyan' => __('Baladiyat ar Rayyan'),
                                'Baladiyat ash Shamal' => __('Baladiyat ash Shamal'),
                            ],
                    ],
                'RE' =>
                    [
                        'value' => 'RE',
                        'label' => __('Réunion'),
                        'regions' =>
                            [
                            ],
                    ],
                'RO' =>
                    [
                        'value' => 'RO',
                        'label' => __('Romania'),
                        'regions' =>
                            [
                                'Arad' => __('Arad'),
                                'Bihor' => __('Bihor'),
                                'Bucuresti' => __('Bucuresti'),
                                'Constanta' => __('Constanta'),
                                'Covasna' => __('Covasna'),
                                'Dolj' => __('Dolj'),
                                'Giurgiu' => __('Giurgiu'),
                                'Gorj' => __('Gorj'),
                                'Harghita' => __('Harghita'),
                                'Hunedoara' => __('Hunedoara'),
                                'Ilfov' => __('Ilfov'),
                                'Judetul Alba' => __('Judetul Alba'),
                                'Judetul Arges' => __('Judetul Arges'),
                                'Judetul Bacau' => __('Judetul Bacau'),
                                'Judetul Bistrita-Nasaud' => __('Judetul Bistrita-Nasaud'),
                                'Judetul Botosani' => __('Judetul Botosani'),
                                'Judetul Braila' => __('Judetul Braila'),
                                'Judetul Brasov' => __('Judetul Brasov'),
                                'Judetul Buzau' => __('Judetul Buzau'),
                                'Judetul Calarasi' => __('Judetul Calarasi'),
                                'Judetul Caras-Severin' => __('Judetul Caras-Severin'),
                                'Judetul Cluj' => __('Judetul Cluj'),
                                'Judetul Dambovita' => __('Judetul Dambovita'),
                                'Judetul Galati' => __('Judetul Galati'),
                                'Judetul Ialomita' => __('Judetul Ialomita'),
                                'Judetul Iasi' => __('Judetul Iasi'),
                                'Judetul Mehedinti' => __('Judetul Mehedinti'),
                                'Judetul Mures' => __('Judetul Mures'),
                                'Judetul Neamt' => __('Judetul Neamt'),
                                'Judetul Salaj' => __('Judetul Salaj'),
                                'Judetul Sibiu' => __('Judetul Sibiu'),
                                'Judetul Timis' => __('Judetul Timis'),
                                'Judetul Valcea' => __('Judetul Valcea'),
                                'Maramureş' => __('Maramureş'),
                                'Olt County' => __('Olt County'),
                                'Prahova' => __('Prahova'),
                                'Satu Mare' => __('Satu Mare'),
                                'Suceava' => __('Suceava'),
                                'Teleorman' => __('Teleorman'),
                                'Tulcea' => __('Tulcea'),
                                'Vaslui' => __('Vaslui'),
                                'Vrancea' => __('Vrancea'),
                            ],
                    ],
                'RS' =>
                    [
                        'value' => 'RS',
                        'label' => __('Serbia'),
                        'regions' =>
                            [
                                'Vojvodina' => __('Vojvodina'),
                            ],
                    ],
                'RU' =>
                    [
                        'value' => 'RU',
                        'label' => __('Russia'),
                        'regions' =>
                            [
                                'Altai Krai' => __('Altai Krai'),
                                'Altai Republic' => __('Altai Republic'),
                                'Amurskaya Oblast\'' => __('Amurskaya Oblast\''),
                                'Arkhangelskaya' => __('Arkhangelskaya'),
                                'Astrakhanskaya Oblast\'' => __('Astrakhanskaya Oblast\''),
                                'Bashkortostan' => __('Bashkortostan'),
                                'Belgorodskaya Oblast\'' => __('Belgorodskaya Oblast\''),
                                'Bryanskaya Oblast\'' => __('Bryanskaya Oblast\''),
                                'Chechnya' => __('Chechnya'),
                                'Chelyabinsk' => __('Chelyabinsk'),
                                'Chukotskiy Avtonomnyy Okrug' => __('Chukotskiy Avtonomnyy Okrug'),
                                'Chuvashia' => __('Chuvashia'),
                                'Dagestan' => __('Dagestan'),
                                'Irkutskaya Oblast\'' => __('Irkutskaya Oblast\''),
                                'Ivanovskaya Oblast\'' => __('Ivanovskaya Oblast\''),
                                'Jewish Autonomous Oblast' => __('Jewish Autonomous Oblast'),
                                'Kabardino-Balkarskaya Respublika' => __('Kabardino-Balkarskaya Respublika'),
                                'Kaliningradskaya Oblast\'' => __('Kaliningradskaya Oblast\''),
                                'Kalmykiya' => __('Kalmykiya'),
                                'Kaluzhskaya Oblast\'' => __('Kaluzhskaya Oblast\''),
                                'Kamtchatski Kray' => __('Kamtchatski Kray'),
                                'Karachayevo-Cherkesiya' => __('Karachayevo-Cherkesiya'),
                                'Kemerovskaya Oblast\'' => __('Kemerovskaya Oblast\''),
                                'Khabarovsk Krai' => __('Khabarovsk Krai'),
                                'Khanty-Mansiyskiy Avtonomnyy Okrug-Yugra' => __('Khanty-Mansiyskiy Avtonomnyy Okrug-Yugra'),
                                'Kirovskaya Oblast\'' => __('Kirovskaya Oblast\''),
                                'Komi Republic' => __('Komi Republic'),
                                'Kostromskaya Oblast\'' => __('Kostromskaya Oblast\''),
                                'Krasnodarskiy Kray' => __('Krasnodarskiy Kray'),
                                'Krasnoyarskiy Kray' => __('Krasnoyarskiy Kray'),
                                'Kurganskaya Oblast\'' => __('Kurganskaya Oblast\''),
                                'Kurskaya Oblast\'' => __('Kurskaya Oblast\''),
                                'Leningradskaya Oblast\'' => __('Leningradskaya Oblast\''),
                                'Lipetskaya Oblast\'' => __('Lipetskaya Oblast\''),
                                'Magadanskaya Oblast\'' => __('Magadanskaya Oblast\''),
                                'Moscow' => __('Moscow'),
                                'Moscow Oblast' => __('Moscow Oblast'),
                                'Murmansk' => __('Murmansk'),
                                'Nenetskiy Avtonomnyy Okrug' => __('Nenetskiy Avtonomnyy Okrug'),
                                'Nizhegorodskaya Oblast\'' => __('Nizhegorodskaya Oblast\''),
                                'North Ossetia' => __('North Ossetia'),
                                'Novgorodskaya Oblast\'' => __('Novgorodskaya Oblast\''),
                                'Novosibirskaya Oblast\'' => __('Novosibirskaya Oblast\''),
                                'Omskaya Oblast\'' => __('Omskaya Oblast\''),
                                'Orenburgskaya Oblast\'' => __('Orenburgskaya Oblast\''),
                                'Orlovskaya Oblast\'' => __('Orlovskaya Oblast\''),
                                'Penzenskaya Oblast\'' => __('Penzenskaya Oblast\''),
                                'Perm Krai' => __('Perm Krai'),
                                'Primorskiy Kray' => __('Primorskiy Kray'),
                                'Pskovskaya Oblast\'' => __('Pskovskaya Oblast\''),
                                'Republic of Karelia' => __('Republic of Karelia'),
                                'Respublika Adygeya' => __('Respublika Adygeya'),
                                'Respublika Buryatiya' => __('Respublika Buryatiya'),
                                'Respublika Ingushetiya' => __('Respublika Ingushetiya'),
                                'Respublika Khakasiya' => __('Respublika Khakasiya'),
                                'Respublika Mariy-El' => __('Respublika Mariy-El'),
                                'Respublika Mordoviya' => __('Respublika Mordoviya'),
                                'Respublika Sakha (Yakutiya)' => __('Respublika Sakha (Yakutiya)'),
                                'Respublika Tyva' => __('Respublika Tyva'),
                                'Rostov Oblast' => __('Rostov Oblast'),
                                'Ryazanskaya Oblast\'' => __('Ryazanskaya Oblast\''),
                                'Sakhalinskaya Oblast\'' => __('Sakhalinskaya Oblast\''),
                                'Samarskaya Oblast\'' => __('Samarskaya Oblast\''),
                                'Saratovskaya Oblast\'' => __('Saratovskaya Oblast\''),
                                'Smolenskaya Oblast\'' => __('Smolenskaya Oblast\''),
                                'St.-Petersburg' => __('St.-Petersburg'),
                                'Stavropol\'skiy Kray' => __('Stavropol\'skiy Kray'),
                                'Sverdlovskaya Oblast\'' => __('Sverdlovskaya Oblast\''),
                                'Tambovskaya Oblast\'' => __('Tambovskaya Oblast\''),
                                'Tatarstan' => __('Tatarstan'),
                                'Tomskaya Oblast\'' => __('Tomskaya Oblast\''),
                                'Transbaikal Territory' => __('Transbaikal Territory'),
                                'Tul\'skaya Oblast\'' => __('Tul\'skaya Oblast\''),
                                'Tverskaya Oblast\'' => __('Tverskaya Oblast\''),
                                'Tyumenskaya Oblast\'' => __('Tyumenskaya Oblast\''),
                                'Udmurtskaya Respublika' => __('Udmurtskaya Respublika'),
                                'Ulyanovsk Oblast' => __('Ulyanovsk Oblast'),
                                'Vladimirskaya Oblast\'' => __('Vladimirskaya Oblast\''),
                                'Volgogradskaya Oblast\'' => __('Volgogradskaya Oblast\''),
                                'Vologodskaya Oblast\'' => __('Vologodskaya Oblast\''),
                                'Voronezhskaya Oblast\'' => __('Voronezhskaya Oblast\''),
                                'Yamalo-Nenetskiy Avtonomnyy Okrug' => __('Yamalo-Nenetskiy Avtonomnyy Okrug'),
                                'Yaroslavskaya Oblast\'' => __('Yaroslavskaya Oblast\''),
                            ],
                    ],
                'RW' =>
                    [
                        'value' => 'RW',
                        'label' => __('Rwanda'),
                        'regions' =>
                            [
                                'Kigali' => __('Kigali'),
                            ],
                    ],
                'SA' =>
                    [
                        'value' => 'SA',
                        'label' => __('Saudi Arabia'),
                        'regions' =>
                            [
                                '\'Asir' => __('\'Asir'),
                                'Al Bahah' => __('Al Bahah'),
                                'Al Madinah al Munawwarah' => __('Al Madinah al Munawwarah'),
                                'Al-Qassim' => __('Al-Qassim'),
                                'Ar Riyāḑ' => __('Ar Riyāḑ'),
                                'Eastern Province' => __('Eastern Province'),
                                'Hai\'l Region' => __('Hai\'l Region'),
                                'Jizan' => __('Jizan'),
                                'Makkah Province' => __('Makkah Province'),
                                'Najran' => __('Najran'),
                                'Tabuk' => __('Tabuk'),
                            ],
                    ],
                'SB' =>
                    [
                        'value' => 'SB',
                        'label' => __('Solomon Islands'),
                        'regions' =>
                            [
                                'Guadalcanal Province' => __('Guadalcanal Province'),
                            ],
                    ],
                'SC' =>
                    [
                        'value' => 'SC',
                        'label' => __('Seychelles'),
                        'regions' =>
                            [
                                'English River' => __('English River'),
                                'Takamaka' => __('Takamaka'),
                            ],
                    ],
                'SD' =>
                    [
                        'value' => 'SD',
                        'label' => __('Sudan'),
                        'regions' =>
                            [
                                'Khartoum' => __('Khartoum'),
                                'Southern Kordofan' => __('Southern Kordofan'),
                            ],
                    ],
                'SE' =>
                    [
                        'value' => 'SE',
                        'label' => __('Sweden'),
                        'regions' =>
                            [
                                'Blekinge' => __('Blekinge'),
                                'Dalarna' => __('Dalarna'),
                                'Gotland' => __('Gotland'),
                                'Gävleborg' => __('Gävleborg'),
                                'Halland' => __('Halland'),
                                'Jämtland' => __('Jämtland'),
                                'Jönköping' => __('Jönköping'),
                                'Kalmar' => __('Kalmar'),
                                'Kronoberg' => __('Kronoberg'),
                                'Norrbotten' => __('Norrbotten'),
                                'Skåne' => __('Skåne'),
                                'Stockholm' => __('Stockholm'),
                                'Södermanland' => __('Södermanland'),
                                'Uppsala' => __('Uppsala'),
                                'Värmland' => __('Värmland'),
                                'Västerbotten' => __('Västerbotten'),
                                'Västernorrland' => __('Västernorrland'),
                                'Västmanland' => __('Västmanland'),
                                'Västra Götaland' => __('Västra Götaland'),
                                'Örebro' => __('Örebro'),
                                'Östergötland' => __('Östergötland'),
                            ],
                    ],
                'SG' =>
                    [
                        'value' => 'SG',
                        'label' => __('Singapore'),
                        'regions' =>
                            [
                                'Central Singapore Community Development Council' => __('Central Singapore Community Development Council'),
                                'North East Community Development Region' => __('North East Community Development Region'),
                                'North West Community Development Council' => __('North West Community Development Council'),
                                'South West Community Development Council' => __('South West Community Development Council'),
                            ],
                    ],
                'SH' =>
                    [
                        'value' => 'SH',
                        'label' => __('Saint Helena'),
                        'regions' =>
                            [
                            ],
                    ],
                'SI' =>
                    [
                        'value' => 'SI',
                        'label' => __('Slovenia'),
                        'regions' =>
                            [
                                'Beltinci' => __('Beltinci'),
                                'Bohinj' => __('Bohinj'),
                                'Borovnica' => __('Borovnica'),
                                'Brda' => __('Brda'),
                                'Brezovica' => __('Brezovica'),
                                'Cankova' => __('Cankova'),
                                'Celje' => __('Celje'),
                                'Cerknica' => __('Cerknica'),
                                'Cerkno' => __('Cerkno'),
                                'Cerkvenjak' => __('Cerkvenjak'),
                                'Cirkulane' => __('Cirkulane'),
                                'Destrnik' => __('Destrnik'),
                                'Dobrova-Polhov Gradec' => __('Dobrova-Polhov Gradec'),
                                'Dol pri Ljubljani' => __('Dol pri Ljubljani'),
                                'Dolenjske Toplice' => __('Dolenjske Toplice'),
                                'Dravograd' => __('Dravograd'),
                                'Duplek' => __('Duplek'),
                                'Gorenja Vas-Poljane' => __('Gorenja Vas-Poljane'),
                                'Gornja Radgona' => __('Gornja Radgona'),
                                'Gornji Grad' => __('Gornji Grad'),
                                'Gornji Petrovci' => __('Gornji Petrovci'),
                                'Grosuplje' => __('Grosuplje'),
                                'Hajdina' => __('Hajdina'),
                                'Horjul' => __('Horjul'),
                                'Hrastnik' => __('Hrastnik'),
                                'Hrpelje-Kozina' => __('Hrpelje-Kozina'),
                                'Idrija' => __('Idrija'),
                                'Ig' => __('Ig'),
                                'Ilirska Bistrica' => __('Ilirska Bistrica'),
                                'Izola' => __('Izola'),
                                'Jesenice' => __('Jesenice'),
                                'Kamnik' => __('Kamnik'),
                                'Komen' => __('Komen'),
                                'Koper' => __('Koper'),
                                'Kostanjevica na Krki' => __('Kostanjevica na Krki'),
                                'Kostel' => __('Kostel'),
                                'Kranj' => __('Kranj'),
                                'Kranjska Gora' => __('Kranjska Gora'),
                                'Kuzma' => __('Kuzma'),
                                'Lenart' => __('Lenart'),
                                'Lendava' => __('Lendava'),
                                'Litija' => __('Litija'),
                                'Ljubljana' => __('Ljubljana'),
                                'Ljubno' => __('Ljubno'),
                                'Ljutomer' => __('Ljutomer'),
                                'Logatec' => __('Logatec'),
                                'Log–Dragomer' => __('Log–Dragomer'),
                                'Lovrenc na Pohorju' => __('Lovrenc na Pohorju'),
                                'Lukovica' => __('Lukovica'),
                                'Makole' => __('Makole'),
                                'Maribor' => __('Maribor'),
                                'Markovci' => __('Markovci'),
                                'Medvode' => __('Medvode'),
                                'Mestna Obcina Novo mesto' => __('Mestna Obcina Novo mesto'),
                                'Metlika' => __('Metlika'),
                                'Miren-Kostanjevica' => __('Miren-Kostanjevica'),
                                'Mislinja' => __('Mislinja'),
                                'Mokronog-Trebelno' => __('Mokronog-Trebelno'),
                                'Mozirje' => __('Mozirje'),
                                'Municipality of Cerklje na Gorenjskem' => __('Municipality of Cerklje na Gorenjskem'),
                                'Municipality of Dobrna' => __('Municipality of Dobrna'),
                                'Municipality of Šentjur' => __('Municipality of Šentjur'),
                                'Murska Sobota' => __('Murska Sobota'),
                                'Naklo' => __('Naklo'),
                                'Nova Gorica' => __('Nova Gorica'),
                                'Obcina Ajdovscina' => __('Obcina Ajdovscina'),
                                'Obcina Apace' => __('Obcina Apace'),
                                'Obcina Bled' => __('Obcina Bled'),
                                'Obcina Brezice' => __('Obcina Brezice'),
                                'Obcina Crna na Koroskem' => __('Obcina Crna na Koroskem'),
                                'Obcina Crnomelj' => __('Obcina Crnomelj'),
                                'Obcina Domzale' => __('Obcina Domzale'),
                                'Obcina Gorisnica' => __('Obcina Gorisnica'),
                                'Obcina Hoce-Slivnica' => __('Obcina Hoce-Slivnica'),
                                'Obcina Ivancna Gorica' => __('Obcina Ivancna Gorica'),
                                'Obcina Jursinci' => __('Obcina Jursinci'),
                                'Obcina Kidricevo' => __('Obcina Kidricevo'),
                                'Obcina Kobarid' => __('Obcina Kobarid'),
                                'Obcina Kocevje' => __('Obcina Kocevje'),
                                'Obcina Krsko' => __('Obcina Krsko'),
                                'Obcina Lasko' => __('Obcina Lasko'),
                                'Obcina Loska Dolina' => __('Obcina Loska Dolina'),
                                'Obcina Majsperk' => __('Obcina Majsperk'),
                                'Obcina Menges' => __('Obcina Menges'),
                                'Obcina Mezica' => __('Obcina Mezica'),
                                'Obcina Miklavz na Dravskem Polju' => __('Obcina Miklavz na Dravskem Polju'),
                                'Obcina Moravce' => __('Obcina Moravce'),
                                'Obcina Ormoz' => __('Obcina Ormoz'),
                                'Obcina Poljcane' => __('Obcina Poljcane'),
                                'Obcina Race-Fram' => __('Obcina Race-Fram'),
                                'Obcina Radece' => __('Obcina Radece'),
                                'Obcina Ravne na Koroskem' => __('Obcina Ravne na Koroskem'),
                                'Obcina Razkrizje' => __('Obcina Razkrizje'),
                                'Obcina Recica ob Savinji' => __('Obcina Recica ob Savinji'),
                                'Obcina Rogaska Slatina' => __('Obcina Rogaska Slatina'),
                                'Obcina Rogasovci' => __('Obcina Rogasovci'),
                                'Obcina Ruse' => __('Obcina Ruse'),
                                'Obcina Semic' => __('Obcina Semic'),
                                'Obcina Sempeter-Vrtojba' => __('Obcina Sempeter-Vrtojba'),
                                'Obcina Sencur' => __('Obcina Sencur'),
                                'Obcina Sentilj' => __('Obcina Sentilj'),
                                'Obcina Sentjernej' => __('Obcina Sentjernej'),
                                'Obcina Sezana' => __('Obcina Sezana'),
                                'Obcina Skofljica' => __('Obcina Skofljica'),
                                'Obcina Smartno ob Paki' => __('Obcina Smartno ob Paki'),
                                'Obcina Smartno pri Litiji' => __('Obcina Smartno pri Litiji'),
                                'Obcina Sostanj' => __('Obcina Sostanj'),
                                'Obcina Store' => __('Obcina Store'),
                                'Obcina Straza' => __('Obcina Straza'),
                                'Obcina Tisina' => __('Obcina Tisina'),
                                'Obcina Tolmin' => __('Obcina Tolmin'),
                                'Obcina Trzic' => __('Obcina Trzic'),
                                'Obcina Velike Lasce' => __('Obcina Velike Lasce'),
                                'Obcina Zalec' => __('Obcina Zalec'),
                                'Obcina Zelezniki' => __('Obcina Zelezniki'),
                                'Obcina Zirovnica' => __('Obcina Zirovnica'),
                                'Obcina Zrece' => __('Obcina Zrece'),
                                'Obcina Zuzemberk' => __('Obcina Zuzemberk'),
                                'Odranci' => __('Odranci'),
                                'Osilnica' => __('Osilnica'),
                                'Pesnica' => __('Pesnica'),
                                'Piran' => __('Piran'),
                                'Pivka' => __('Pivka'),
                                'Podlehnik' => __('Podlehnik'),
                                'Polzela' => __('Polzela'),
                                'Postojna' => __('Postojna'),
                                'Preddvor' => __('Preddvor'),
                                'Prevalje' => __('Prevalje'),
                                'Ptuj' => __('Ptuj'),
                                'Puconci' => __('Puconci'),
                                'Radlje ob Dravi' => __('Radlje ob Dravi'),
                                'Radovljica' => __('Radovljica'),
                                'Ribnica' => __('Ribnica'),
                                'Selnica ob Dravi' => __('Selnica ob Dravi'),
                                'Sevnica' => __('Sevnica'),
                                'Slovenj Gradec' => __('Slovenj Gradec'),
                                'Slovenska Bistrica' => __('Slovenska Bistrica'),
                                'Slovenske Konjice' => __('Slovenske Konjice'),
                                'Sveta Ana' => __('Sveta Ana'),
                                'Tabor' => __('Tabor'),
                                'Trbovlje' => __('Trbovlje'),
                                'Trebnje' => __('Trebnje'),
                                'Trzin' => __('Trzin'),
                                'Velenje' => __('Velenje'),
                                'Videm' => __('Videm'),
                                'Vipava' => __('Vipava'),
                                'Vransko' => __('Vransko'),
                                'Zagorje ob Savi' => __('Zagorje ob Savi'),
                                'Škofja Loka' => __('Škofja Loka'),
                            ],
                    ],
                'SJ' =>
                    [
                        'value' => 'SJ',
                        'label' => __('Svalbard and Jan Mayen'),
                        'regions' =>
                            [
                                'Jan Mayen' => __('Jan Mayen'),
                                'Svalbard' => __('Svalbard'),
                            ],
                    ],
                'SK' =>
                    [
                        'value' => 'SK',
                        'label' => __('Slovak Republic'),
                        'regions' =>
                            [
                                'Banskobystricky kraj' => __('Banskobystricky kraj'),
                                'Bratislavsky kraj' => __('Bratislavsky kraj'),
                                'Kosicky kraj' => __('Kosicky kraj'),
                                'Nitriansky kraj' => __('Nitriansky kraj'),
                                'Presovsky kraj' => __('Presovsky kraj'),
                                'Trenciansky kraj' => __('Trenciansky kraj'),
                                'Trnavsky kraj' => __('Trnavsky kraj'),
                                'Zilinsky kraj' => __('Zilinsky kraj'),
                            ],
                    ],
                'SL' =>
                    [
                        'value' => 'SL',
                        'label' => __('Sierra Leone'),
                        'regions' =>
                            [
                                'Western Area' => __('Western Area'),
                            ],
                    ],
                'SM' =>
                    [
                        'value' => 'SM',
                        'label' => __('San Marino'),
                        'regions' =>
                            [
                                'Castello di Borgo Maggiore' => __('Castello di Borgo Maggiore'),
                                'Castello di Faetano' => __('Castello di Faetano'),
                                'Castello di San Marino Citta' => __('Castello di San Marino Citta'),
                                'Serravalle' => __('Serravalle'),
                            ],
                    ],
                'SN' =>
                    [
                        'value' => 'SN',
                        'label' => __('Senegal'),
                        'regions' =>
                            [
                                'Dakar' => __('Dakar'),
                                'Fatick' => __('Fatick'),
                                'Kaolack' => __('Kaolack'),
                                'Kolda' => __('Kolda'),
                                'Louga' => __('Louga'),
                                'Region de Kaffrine' => __('Region de Kaffrine'),
                                'Region de Kedougou' => __('Region de Kedougou'),
                                'Region de Sedhiou' => __('Region de Sedhiou'),
                                'Saint-Louis' => __('Saint-Louis'),
                                'Tambacounda' => __('Tambacounda'),
                            ],
                    ],
                'SO' =>
                    [
                        'value' => 'SO',
                        'label' => __('Somalia'),
                        'regions' =>
                            [
                                'Banaadir' => __('Banaadir'),
                                'Gedo' => __('Gedo'),
                                'Woqooyi Galbeed' => __('Woqooyi Galbeed'),
                            ],
                    ],
                'SR' =>
                    [
                        'value' => 'SR',
                        'label' => __('Suriname'),
                        'regions' =>
                            [
                                'Distrikt Brokopondo' => __('Distrikt Brokopondo'),
                                'Distrikt Commewijne' => __('Distrikt Commewijne'),
                                'Distrikt Coronie' => __('Distrikt Coronie'),
                                'Distrikt Marowijne' => __('Distrikt Marowijne'),
                                'Distrikt Nickerie' => __('Distrikt Nickerie'),
                                'Distrikt Para' => __('Distrikt Para'),
                                'Distrikt Paramaribo' => __('Distrikt Paramaribo'),
                                'Distrikt Saramacca' => __('Distrikt Saramacca'),
                                'Distrikt Sipaliwini' => __('Distrikt Sipaliwini'),
                                'Distrikt Wanica' => __('Distrikt Wanica'),
                            ],
                    ],
                'SS' =>
                    [
                        'value' => 'SS',
                        'label' => __('South Sudan'),
                        'regions' =>
                            [
                                'Central Equatoria' => __('Central Equatoria'),
                            ],
                    ],
                'ST' =>
                    [
                        'value' => 'ST',
                        'label' => __('São Tomé and Príncipe'),
                        'regions' =>
                            [
                                'Principe' => __('Principe'),
                                'São Tomé Island' => __('São Tomé Island'),
                            ],
                    ],
                'SV' =>
                    [
                        'value' => 'SV',
                        'label' => __('El Salvador'),
                        'regions' =>
                            [
                                'Departamento de Ahuachapan' => __('Departamento de Ahuachapan'),
                                'Departamento de Cabanas' => __('Departamento de Cabanas'),
                                'Departamento de Chalatenango' => __('Departamento de Chalatenango'),
                                'Departamento de Cuscatlan' => __('Departamento de Cuscatlan'),
                                'Departamento de La Libertad' => __('Departamento de La Libertad'),
                                'Departamento de La Paz' => __('Departamento de La Paz'),
                                'Departamento de La Union' => __('Departamento de La Union'),
                                'Departamento de Morazan' => __('Departamento de Morazan'),
                                'Departamento de San Miguel' => __('Departamento de San Miguel'),
                                'Departamento de San Salvador' => __('Departamento de San Salvador'),
                                'Departamento de San Vicente' => __('Departamento de San Vicente'),
                                'Departamento de Santa Ana' => __('Departamento de Santa Ana'),
                                'Departamento de Sonsonate' => __('Departamento de Sonsonate'),
                                'Departamento de Usulutan' => __('Departamento de Usulutan'),
                            ],
                    ],
                'SX' =>
                    [
                        'value' => 'SX',
                        'label' => __('Sint Maarten'),
                        'regions' =>
                            [
                            ],
                    ],
                'SY' =>
                    [
                        'value' => 'SY',
                        'label' => __('Syria'),
                        'regions' =>
                            [
                                'Aleppo Governorate' => __('Aleppo Governorate'),
                                'As-Suwayda Governorate' => __('As-Suwayda Governorate'),
                                'Damascus Governorate' => __('Damascus Governorate'),
                                'Hama Governorate' => __('Hama Governorate'),
                                'Latakia Governorate' => __('Latakia Governorate'),
                                'Quneitra Governorate' => __('Quneitra Governorate'),
                            ],
                    ],
                'SZ' =>
                    [
                        'value' => 'SZ',
                        'label' => __('Swaziland'),
                        'regions' =>
                            [
                                'Hhohho District' => __('Hhohho District'),
                                'Lubombo District' => __('Lubombo District'),
                                'Manzini District' => __('Manzini District'),
                            ],
                    ],
                'TC' =>
                    [
                        'value' => 'TC',
                        'label' => __('Turks and Caicos Islands'),
                        'regions' =>
                            [
                            ],
                    ],
                'TD' =>
                    [
                        'value' => 'TD',
                        'label' => __('Chad'),
                        'regions' =>
                            [
                                'Chari-Baguirmi Region' => __('Chari-Baguirmi Region'),
                                'Hadjer-Lamis' => __('Hadjer-Lamis'),
                                'Logone Occidental Region' => __('Logone Occidental Region'),
                                'Ouadaï' => __('Ouadaï'),
                            ],
                    ],
                'TF' =>
                    [
                        'value' => 'TF',
                        'label' => __('French Southern Territories'),
                        'regions' =>
                            [
                            ],
                    ],
                'TG' =>
                    [
                        'value' => 'TG',
                        'label' => __('Togo'),
                        'regions' =>
                            [
                                'Maritime' => __('Maritime'),
                            ],
                    ],
                'TH' =>
                    [
                        'value' => 'TH',
                        'label' => __('Thailand'),
                        'regions' =>
                            [
                                'Bangkok' => __('Bangkok'),
                                'Changwat Amnat Charoen' => __('Changwat Amnat Charoen'),
                                'Changwat Ang Thong' => __('Changwat Ang Thong'),
                                'Changwat Bueng Kan' => __('Changwat Bueng Kan'),
                                'Changwat Buriram' => __('Changwat Buriram'),
                                'Changwat Chachoengsao' => __('Changwat Chachoengsao'),
                                'Changwat Chai Nat' => __('Changwat Chai Nat'),
                                'Changwat Chaiyaphum' => __('Changwat Chaiyaphum'),
                                'Changwat Chanthaburi' => __('Changwat Chanthaburi'),
                                'Changwat Chiang Rai' => __('Changwat Chiang Rai'),
                                'Changwat Chon Buri' => __('Changwat Chon Buri'),
                                'Changwat Chumphon' => __('Changwat Chumphon'),
                                'Changwat Kalasin' => __('Changwat Kalasin'),
                                'Changwat Kamphaeng Phet' => __('Changwat Kamphaeng Phet'),
                                'Changwat Kanchanaburi' => __('Changwat Kanchanaburi'),
                                'Changwat Khon Kaen' => __('Changwat Khon Kaen'),
                                'Changwat Krabi' => __('Changwat Krabi'),
                                'Changwat Lampang' => __('Changwat Lampang'),
                                'Changwat Lamphun' => __('Changwat Lamphun'),
                                'Changwat Loei' => __('Changwat Loei'),
                                'Changwat Lop Buri' => __('Changwat Lop Buri'),
                                'Changwat Mae Hong Son' => __('Changwat Mae Hong Son'),
                                'Changwat Maha Sarakham' => __('Changwat Maha Sarakham'),
                                'Changwat Mukdahan' => __('Changwat Mukdahan'),
                                'Changwat Nakhon Nayok' => __('Changwat Nakhon Nayok'),
                                'Changwat Nakhon Pathom' => __('Changwat Nakhon Pathom'),
                                'Changwat Nakhon Phanom' => __('Changwat Nakhon Phanom'),
                                'Changwat Nakhon Ratchasima' => __('Changwat Nakhon Ratchasima'),
                                'Changwat Nakhon Sawan' => __('Changwat Nakhon Sawan'),
                                'Changwat Nakhon Si Thammarat' => __('Changwat Nakhon Si Thammarat'),
                                'Changwat Nan' => __('Changwat Nan'),
                                'Changwat Narathiwat' => __('Changwat Narathiwat'),
                                'Changwat Nong Bua Lamphu' => __('Changwat Nong Bua Lamphu'),
                                'Changwat Nong Khai' => __('Changwat Nong Khai'),
                                'Changwat Nonthaburi' => __('Changwat Nonthaburi'),
                                'Changwat Pathum Thani' => __('Changwat Pathum Thani'),
                                'Changwat Pattani' => __('Changwat Pattani'),
                                'Changwat Phangnga' => __('Changwat Phangnga'),
                                'Changwat Phatthalung' => __('Changwat Phatthalung'),
                                'Changwat Phayao' => __('Changwat Phayao'),
                                'Changwat Phetchabun' => __('Changwat Phetchabun'),
                                'Changwat Phetchaburi' => __('Changwat Phetchaburi'),
                                'Changwat Phichit' => __('Changwat Phichit'),
                                'Changwat Phitsanulok' => __('Changwat Phitsanulok'),
                                'Changwat Phra Nakhon Si Ayutthaya' => __('Changwat Phra Nakhon Si Ayutthaya'),
                                'Changwat Phrae' => __('Changwat Phrae'),
                                'Changwat Prachin Buri' => __('Changwat Prachin Buri'),
                                'Changwat Prachuap Khiri Khan' => __('Changwat Prachuap Khiri Khan'),
                                'Changwat Ranong' => __('Changwat Ranong'),
                                'Changwat Ratchaburi' => __('Changwat Ratchaburi'),
                                'Changwat Rayong' => __('Changwat Rayong'),
                                'Changwat Roi Et' => __('Changwat Roi Et'),
                                'Changwat Sa Kaeo' => __('Changwat Sa Kaeo'),
                                'Changwat Sakon Nakhon' => __('Changwat Sakon Nakhon'),
                                'Changwat Samut Prakan' => __('Changwat Samut Prakan'),
                                'Changwat Samut Sakhon' => __('Changwat Samut Sakhon'),
                                'Changwat Samut Songkhram' => __('Changwat Samut Songkhram'),
                                'Changwat Sara Buri' => __('Changwat Sara Buri'),
                                'Changwat Satun' => __('Changwat Satun'),
                                'Changwat Sing Buri' => __('Changwat Sing Buri'),
                                'Changwat Sisaket' => __('Changwat Sisaket'),
                                'Changwat Songkhla' => __('Changwat Songkhla'),
                                'Changwat Sukhothai' => __('Changwat Sukhothai'),
                                'Changwat Suphan Buri' => __('Changwat Suphan Buri'),
                                'Changwat Surat Thani' => __('Changwat Surat Thani'),
                                'Changwat Surin' => __('Changwat Surin'),
                                'Changwat Tak' => __('Changwat Tak'),
                                'Changwat Trang' => __('Changwat Trang'),
                                'Changwat Trat' => __('Changwat Trat'),
                                'Changwat Ubon Ratchathani' => __('Changwat Ubon Ratchathani'),
                                'Changwat Udon Thani' => __('Changwat Udon Thani'),
                                'Changwat Uthai Thani' => __('Changwat Uthai Thani'),
                                'Changwat Uttaradit' => __('Changwat Uttaradit'),
                                'Changwat Yala' => __('Changwat Yala'),
                                'Changwat Yasothon' => __('Changwat Yasothon'),
                                'Chiang Mai Province' => __('Chiang Mai Province'),
                                'Phuket' => __('Phuket'),
                            ],
                    ],
                'TJ' =>
                    [
                        'value' => 'TJ',
                        'label' => __('Tajikistan'),
                        'regions' =>
                            [
                                'Gorno-Badakhshan' => __('Gorno-Badakhshan'),
                                'Viloyati Sughd' => __('Viloyati Sughd'),
                            ],
                    ],
                'TK' =>
                    [
                        'value' => 'TK',
                        'label' => __('Tokelau'),
                        'regions' =>
                            [
                                'Nukunonu' => __('Nukunonu'),
                            ],
                    ],
                'TL' =>
                    [
                        'value' => 'TL',
                        'label' => __('East Timor'),
                        'regions' =>
                            [
                                'Dili' => __('Dili'),
                            ],
                    ],
                'TM' =>
                    [
                        'value' => 'TM',
                        'label' => __('Turkmenistan'),
                        'regions' =>
                            [
                                'Ahal' => __('Ahal'),
                            ],
                    ],
                'TN' =>
                    [
                        'value' => 'TN',
                        'label' => __('Tunisia'),
                        'regions' =>
                            [
                                'Gafsa' => __('Gafsa'),
                                'Gouvernorat de Beja' => __('Gouvernorat de Beja'),
                                'Gouvernorat de Ben Arous' => __('Gouvernorat de Ben Arous'),
                                'Gouvernorat de Bizerte' => __('Gouvernorat de Bizerte'),
                                'Gouvernorat de Gabes' => __('Gouvernorat de Gabes'),
                                'Gouvernorat de Kairouan' => __('Gouvernorat de Kairouan'),
                                'Gouvernorat de Kasserine' => __('Gouvernorat de Kasserine'),
                                'Gouvernorat de Kef' => __('Gouvernorat de Kef'),
                                'Gouvernorat de Mahdia' => __('Gouvernorat de Mahdia'),
                                'Gouvernorat de Monastir' => __('Gouvernorat de Monastir'),
                                'Gouvernorat de Nabeul' => __('Gouvernorat de Nabeul'),
                                'Gouvernorat de Sfax' => __('Gouvernorat de Sfax'),
                                'Gouvernorat de Sidi Bouzid' => __('Gouvernorat de Sidi Bouzid'),
                                'Gouvernorat de Siliana' => __('Gouvernorat de Siliana'),
                                'Gouvernorat de Sousse' => __('Gouvernorat de Sousse'),
                                'Gouvernorat de Tozeur' => __('Gouvernorat de Tozeur'),
                                'Gouvernorat de Tunis' => __('Gouvernorat de Tunis'),
                                'Gouvernorat de Zaghouan' => __('Gouvernorat de Zaghouan'),
                                'Gouvernorat de l\'Ariana' => __('Gouvernorat de l\'Ariana'),
                                'Tataouine' => __('Tataouine'),
                            ],
                    ],
                'TO' =>
                    [
                        'value' => 'TO',
                        'label' => __('Tonga'),
                        'regions' =>
                            [
                                'Vava\'u' => __('Vava\'u'),
                            ],
                    ],
                'TR' =>
                    [
                        'value' => 'TR',
                        'label' => __('Turkey'),
                        'regions' =>
                            [
                                'Adana' => __('Adana'),
                                'Adiyaman' => __('Adiyaman'),
                                'Afyonkarahisar' => __('Afyonkarahisar'),
                                'Aksaray' => __('Aksaray'),
                                'Amasya' => __('Amasya'),
                                'Ankara' => __('Ankara'),
                                'Antalya' => __('Antalya'),
                                'Ardahan' => __('Ardahan'),
                                'Artvin' => __('Artvin'),
                                'Aydın' => __('Aydın'),
                                'Ağrı' => __('Ağrı'),
                                'Balıkesir' => __('Balıkesir'),
                                'Bartın' => __('Bartın'),
                                'Batman' => __('Batman'),
                                'Bayburt' => __('Bayburt'),
                                'Bilecik' => __('Bilecik'),
                                'Bingöl' => __('Bingöl'),
                                'Bitlis' => __('Bitlis'),
                                'Bolu' => __('Bolu'),
                                'Burdur' => __('Burdur'),
                                'Bursa' => __('Bursa'),
                                'Denizli' => __('Denizli'),
                                'Diyarbakir' => __('Diyarbakir'),
                                'Duezce' => __('Duezce'),
                                'Edirne' => __('Edirne'),
                                'Elazığ' => __('Elazığ'),
                                'Erzincan' => __('Erzincan'),
                                'Erzurum' => __('Erzurum'),
                                'Eskişehir' => __('Eskişehir'),
                                'Gaziantep' => __('Gaziantep'),
                                'Giresun' => __('Giresun'),
                                'Guemueshane' => __('Guemueshane'),
                                'Hakkâri' => __('Hakkâri'),
                                'Hatay' => __('Hatay'),
                                'Isparta' => __('Isparta'),
                                'Istanbul' => __('Istanbul'),
                                'Izmir' => __('Izmir'),
                                'Iğdır' => __('Iğdır'),
                                'Kahramanmaraş' => __('Kahramanmaraş'),
                                'Karabuek' => __('Karabuek'),
                                'Karaman' => __('Karaman'),
                                'Kars' => __('Kars'),
                                'Kastamonu' => __('Kastamonu'),
                                'Kayseri' => __('Kayseri'),
                                'Kilis' => __('Kilis'),
                                'Kocaeli' => __('Kocaeli'),
                                'Konya' => __('Konya'),
                                'Kütahya' => __('Kütahya'),
                                'Kırklareli' => __('Kırklareli'),
                                'Kırıkkale' => __('Kırıkkale'),
                                'Kırşehir' => __('Kırşehir'),
                                'Malatya' => __('Malatya'),
                                'Manisa' => __('Manisa'),
                                'Mardin' => __('Mardin'),
                                'Mersin' => __('Mersin'),
                                'Muğla' => __('Muğla'),
                                'Muş' => __('Muş'),
                                'Nevsehir' => __('Nevsehir'),
                                'Nigde' => __('Nigde'),
                                'Ordu' => __('Ordu'),
                                'Osmaniye' => __('Osmaniye'),
                                'Rize' => __('Rize'),
                                'Sakarya' => __('Sakarya'),
                                'Samsun' => __('Samsun'),
                                'Siirt' => __('Siirt'),
                                'Sinop' => __('Sinop'),
                                'Sivas' => __('Sivas'),
                                'Tekirdağ' => __('Tekirdağ'),
                                'Tokat' => __('Tokat'),
                                'Trabzon' => __('Trabzon'),
                                'Tunceli' => __('Tunceli'),
                                'Uşak' => __('Uşak'),
                                'Van' => __('Van'),
                                'Yalova' => __('Yalova'),
                                'Yozgat' => __('Yozgat'),
                                'Zonguldak' => __('Zonguldak'),
                                'Çanakkale' => __('Çanakkale'),
                                'Çankırı' => __('Çankırı'),
                                'Çorum' => __('Çorum'),
                                'Şanlıurfa' => __('Şanlıurfa'),
                                'Şırnak' => __('Şırnak'),
                            ],
                    ],
                'TT' =>
                    [
                        'value' => 'TT',
                        'label' => __('Trinidad and Tobago'),
                        'regions' =>
                            [
                                'Borough of Arima' => __('Borough of Arima'),
                                'Chaguanas' => __('Chaguanas'),
                                'City of Port of Spain' => __('City of Port of Spain'),
                                'City of San Fernando' => __('City of San Fernando'),
                                'Couva-Tabaquite-Talparo' => __('Couva-Tabaquite-Talparo'),
                                'Diego Martin' => __('Diego Martin'),
                                'Eastern Tobago' => __('Eastern Tobago'),
                                'Mayaro' => __('Mayaro'),
                                'Penal/Debe' => __('Penal/Debe'),
                                'Point Fortin' => __('Point Fortin'),
                                'Princes Town' => __('Princes Town'),
                                'San Juan/Laventille' => __('San Juan/Laventille'),
                                'Sangre Grande' => __('Sangre Grande'),
                                'Siparia' => __('Siparia'),
                                'Tobago' => __('Tobago'),
                                'Tunapuna/Piarco' => __('Tunapuna/Piarco'),
                            ],
                    ],
                'TV' =>
                    [
                        'value' => 'TV',
                        'label' => __('Tuvalu'),
                        'regions' =>
                            [
                                'Funafuti' => __('Funafuti'),
                                'Vaitupu' => __('Vaitupu'),
                            ],
                    ],
                'TW' =>
                    [
                        'value' => 'TW',
                        'label' => __('Taiwan'),
                        'regions' =>
                            [
                                'Changhua' => __('Changhua'),
                                'Chiayi' => __('Chiayi'),
                                'Hsinchu' => __('Hsinchu'),
                                'Hsinchu County' => __('Hsinchu County'),
                                'Hualien' => __('Hualien'),
                                'Kaohsiung' => __('Kaohsiung'),
                                'Keelung' => __('Keelung'),
                                'Miaoli' => __('Miaoli'),
                                'Nantou' => __('Nantou'),
                                'New Taipei' => __('New Taipei'),
                                'Pingtung' => __('Pingtung'),
                                'Taichung City' => __('Taichung City'),
                                'Tainan' => __('Tainan'),
                                'Taitung' => __('Taitung'),
                                'Taoyuan' => __('Taoyuan'),
                                'Yilan' => __('Yilan'),
                                'Yunlin County' => __('Yunlin County'),
                            ],
                    ],
                'TZ' =>
                    [
                        'value' => 'TZ',
                        'label' => __('Tanzania'),
                        'regions' =>
                            [
                                'Arusha' => __('Arusha'),
                                'Dar es Salaam Region' => __('Dar es Salaam Region'),
                                'Dodoma' => __('Dodoma'),
                                'Iringa' => __('Iringa'),
                                'Kagera' => __('Kagera'),
                                'Kigoma' => __('Kigoma'),
                                'Kilimanjaro' => __('Kilimanjaro'),
                                'Lindi' => __('Lindi'),
                                'Manyara' => __('Manyara'),
                                'Mara' => __('Mara'),
                                'Mbeya' => __('Mbeya'),
                                'Morogoro' => __('Morogoro'),
                                'Mtwara' => __('Mtwara'),
                                'Mwanza' => __('Mwanza'),
                                'Pemba North' => __('Pemba North'),
                                'Pemba South' => __('Pemba South'),
                                'Pwani' => __('Pwani'),
                                'Rukwa' => __('Rukwa'),
                                'Ruvuma' => __('Ruvuma'),
                                'Shinyanga' => __('Shinyanga'),
                                'Singida' => __('Singida'),
                                'Tabora' => __('Tabora'),
                                'Tanga' => __('Tanga'),
                                'Zanzibar Central/South' => __('Zanzibar Central/South'),
                                'Zanzibar North' => __('Zanzibar North'),
                                'Zanzibar Urban/West' => __('Zanzibar Urban/West'),
                            ],
                    ],
                'UA' =>
                    [
                        'value' => 'UA',
                        'label' => __('Ukraine'),
                        'regions' =>
                            [
                                'Cherkas\'ka Oblast\'' => __('Cherkas\'ka Oblast\''),
                                'Chernihiv' => __('Chernihiv'),
                                'Chernivtsi' => __('Chernivtsi'),
                                'Dnipropetrovska Oblast\'' => __('Dnipropetrovska Oblast\''),
                                'Donets\'ka Oblast\'' => __('Donets\'ka Oblast\''),
                                'Gorod Sevastopol' => __('Gorod Sevastopol'),
                                'Ivano-Frankivs\'ka Oblast\'' => __('Ivano-Frankivs\'ka Oblast\''),
                                'Kharkivs\'ka Oblast\'' => __('Kharkivs\'ka Oblast\''),
                                'Khersons\'ka Oblast\'' => __('Khersons\'ka Oblast\''),
                                'Khmel\'nyts\'ka Oblast\'' => __('Khmel\'nyts\'ka Oblast\''),
                                'Kirovohrads\'ka Oblast\'' => __('Kirovohrads\'ka Oblast\''),
                                'Kyiv City' => __('Kyiv City'),
                                'Kyiv Oblast' => __('Kyiv Oblast'),
                                'L\'vivs\'ka Oblast\'' => __('L\'vivs\'ka Oblast\''),
                                'Luhans\'ka Oblast\'' => __('Luhans\'ka Oblast\''),
                                'Mykolayivs\'ka Oblast\'' => __('Mykolayivs\'ka Oblast\''),
                                'Odessa' => __('Odessa'),
                                'Poltavs\'ka Oblast\'' => __('Poltavs\'ka Oblast\''),
                                'Republic of Crimea' => __('Republic of Crimea'),
                                'Rivnens\'ka Oblast\'' => __('Rivnens\'ka Oblast\''),
                                'Sums\'ka Oblast\'' => __('Sums\'ka Oblast\''),
                                'Ternopil\'s\'ka Oblast\'' => __('Ternopil\'s\'ka Oblast\''),
                                'Vinnyts\'ka Oblast\'' => __('Vinnyts\'ka Oblast\''),
                                'Volyns\'ka Oblast\'' => __('Volyns\'ka Oblast\''),
                                'Zakarpattia Oblast' => __('Zakarpattia Oblast'),
                                'Zaporizhia' => __('Zaporizhia'),
                                'Zhytomyrs\'ka Oblast\'' => __('Zhytomyrs\'ka Oblast\''),
                            ],
                    ],
                'UG' =>
                    [
                        'value' => 'UG',
                        'label' => __('Uganda'),
                        'regions' =>
                            [
                                'Central Region' => __('Central Region'),
                            ],
                    ],
                'UM' =>
                    [
                        'value' => 'UM',
                        'label' => __('U.S. Minor Outlying Islands'),
                        'regions' =>
                            [
                            ],
                    ],
                'US' =>
                    [
                        'value' => 'US',
                        'label' => __('United States'),
                        'regions' =>
                            [
                                'Alabama' => __('Alabama'),
                                'Alaska' => __('Alaska'),
                                'Arizona' => __('Arizona'),
                                'Arkansas' => __('Arkansas'),
                                'California' => __('California'),
                                'Colorado' => __('Colorado'),
                                'Connecticut' => __('Connecticut'),
                                'Delaware' => __('Delaware'),
                                'District of Columbia' => __('District of Columbia'),
                                'Florida' => __('Florida'),
                                'Georgia' => __('Georgia'),
                                'Hawaii' => __('Hawaii'),
                                'Idaho' => __('Idaho'),
                                'Illinois' => __('Illinois'),
                                'Indiana' => __('Indiana'),
                                'Iowa' => __('Iowa'),
                                'Kansas' => __('Kansas'),
                                'Kentucky' => __('Kentucky'),
                                'Louisiana' => __('Louisiana'),
                                'Maine' => __('Maine'),
                                'Maryland' => __('Maryland'),
                                'Massachusetts' => __('Massachusetts'),
                                'Michigan' => __('Michigan'),
                                'Minnesota' => __('Minnesota'),
                                'Mississippi' => __('Mississippi'),
                                'Missouri' => __('Missouri'),
                                'Montana' => __('Montana'),
                                'Nebraska' => __('Nebraska'),
                                'Nevada' => __('Nevada'),
                                'New Hampshire' => __('New Hampshire'),
                                'New Jersey' => __('New Jersey'),
                                'New Mexico' => __('New Mexico'),
                                'New York' => __('New York'),
                                'North Carolina' => __('North Carolina'),
                                'North Dakota' => __('North Dakota'),
                                'Ohio' => __('Ohio'),
                                'Oklahoma' => __('Oklahoma'),
                                'Oregon' => __('Oregon'),
                                'Pennsylvania' => __('Pennsylvania'),
                                'Rhode Island' => __('Rhode Island'),
                                'South Carolina' => __('South Carolina'),
                                'South Dakota' => __('South Dakota'),
                                'Tennessee' => __('Tennessee'),
                                'Texas' => __('Texas'),
                                'Utah' => __('Utah'),
                                'Vermont' => __('Vermont'),
                                'Virginia' => __('Virginia'),
                                'Washington' => __('Washington'),
                                'West Virginia' => __('West Virginia'),
                                'Wisconsin' => __('Wisconsin'),
                                'Wyoming' => __('Wyoming'),
                            ],
                    ],
                'UY' =>
                    [
                        'value' => 'UY',
                        'label' => __('Uruguay'),
                        'regions' =>
                            [
                                'Artigas' => __('Artigas'),
                                'Canelones' => __('Canelones'),
                                'Cerro Largo' => __('Cerro Largo'),
                                'Colonia' => __('Colonia'),
                                'Departamento de Montevideo' => __('Departamento de Montevideo'),
                                'Departamento de Paysandu' => __('Departamento de Paysandu'),
                                'Departamento de Rio Negro' => __('Departamento de Rio Negro'),
                                'Departamento de Rivera' => __('Departamento de Rivera'),
                                'Departamento de San Jose' => __('Departamento de San Jose'),
                                'Departamento de Tacuarembo' => __('Departamento de Tacuarembo'),
                                'Florida' => __('Florida'),
                                'Lavalleja' => __('Lavalleja'),
                                'Maldonado' => __('Maldonado'),
                                'Soriano' => __('Soriano'),
                            ],
                    ],
                'UZ' =>
                    [
                        'value' => 'UZ',
                        'label' => __('Uzbekistan'),
                        'regions' =>
                            [
                                'Qashqadaryo' => __('Qashqadaryo'),
                                'Samarqand Viloyati' => __('Samarqand Viloyati'),
                                'Toshkent Shahri' => __('Toshkent Shahri'),
                            ],
                    ],
                'VA' =>
                    [
                        'value' => 'VA',
                        'label' => __('Vatican City'),
                        'regions' =>
                            [
                            ],
                    ],
                'VC' =>
                    [
                        'value' => 'VC',
                        'label' => __('Saint Vincent and the Grenadines'),
                        'regions' =>
                            [
                                'Grenadines' => __('Grenadines'),
                                'Parish of Charlotte' => __('Parish of Charlotte'),
                                'Parish of Saint George' => __('Parish of Saint George'),
                            ],
                    ],
                'VE' =>
                    [
                        'value' => 'VE',
                        'label' => __('Venezuela'),
                        'regions' =>
                            [
                                'Amazonas' => __('Amazonas'),
                                'Anzoátegui' => __('Anzoátegui'),
                                'Apure' => __('Apure'),
                                'Aragua' => __('Aragua'),
                                'Barinas' => __('Barinas'),
                                'Bolívar' => __('Bolívar'),
                                'Capital' => __('Capital'),
                                'Carabobo' => __('Carabobo'),
                                'Cojedes' => __('Cojedes'),
                                'Delta Amacuro' => __('Delta Amacuro'),
                                'Dependencias Federales' => __('Dependencias Federales'),
                                'Estado Trujillo' => __('Estado Trujillo'),
                                'Falcón' => __('Falcón'),
                                'Guárico' => __('Guárico'),
                                'Lara' => __('Lara'),
                                'Miranda' => __('Miranda'),
                                'Monagas' => __('Monagas'),
                                'Mérida' => __('Mérida'),
                                'Nueva Esparta' => __('Nueva Esparta'),
                                'Portuguesa' => __('Portuguesa'),
                                'Sucre' => __('Sucre'),
                                'Táchira' => __('Táchira'),
                                'Vargas' => __('Vargas'),
                                'Yaracuy' => __('Yaracuy'),
                                'Zulia' => __('Zulia'),
                            ],
                    ],
                'VG' =>
                    [
                        'value' => 'VG',
                        'label' => __('British Virgin Islands'),
                        'regions' =>
                            [
                            ],
                    ],
                'VI' =>
                    [
                        'value' => 'VI',
                        'label' => __('U.S. Virgin Islands'),
                        'regions' =>
                            [
                                'Saint Croix Island' => __('Saint Croix Island'),
                                'Saint John Island' => __('Saint John Island'),
                                'Saint Thomas Island' => __('Saint Thomas Island'),
                            ],
                    ],
                'VN' =>
                    [
                        'value' => 'VN',
                        'label' => __('Vietnam'),
                        'regions' =>
                            [
                                'An Giang' => __('An Giang'),
                                'Dak Nong' => __('Dak Nong'),
                                'Gia Lai' => __('Gia Lai'),
                                'Hau Giang' => __('Hau Giang'),
                                'Ho Chi Minh City' => __('Ho Chi Minh City'),
                                'Kon Tum' => __('Kon Tum'),
                                'Long An' => __('Long An'),
                                'Thanh Pho Can Tho' => __('Thanh Pho Can Tho'),
                                'Thanh Pho GJa Nang' => __('Thanh Pho GJa Nang'),
                                'Thanh Pho Ha Noi' => __('Thanh Pho Ha Noi'),
                                'Thanh Pho Hai Phong' => __('Thanh Pho Hai Phong'),
                                'Tinh Ba Ria-Vung Tau' => __('Tinh Ba Ria-Vung Tau'),
                                'Tinh Bac Giang' => __('Tinh Bac Giang'),
                                'Tinh Bac Lieu' => __('Tinh Bac Lieu'),
                                'Tinh Bac Ninh' => __('Tinh Bac Ninh'),
                                'Tinh Ben Tre' => __('Tinh Ben Tre'),
                                'Tinh Binh Duong' => __('Tinh Binh Duong'),
                                'Tinh Binh GJinh' => __('Tinh Binh GJinh'),
                                'Tinh Binh Thuan' => __('Tinh Binh Thuan'),
                                'Tinh Ca Mau' => __('Tinh Ca Mau'),
                                'Tinh Cao Bang' => __('Tinh Cao Bang'),
                                'Tinh Dien Bien' => __('Tinh Dien Bien'),
                                'Tinh GJak Lak' => __('Tinh GJak Lak'),
                                'Tinh GJong Nai' => __('Tinh GJong Nai'),
                                'Tinh GJong Thap' => __('Tinh GJong Thap'),
                                'Tinh Ha Giang' => __('Tinh Ha Giang'),
                                'Tinh Ha Nam' => __('Tinh Ha Nam'),
                                'Tinh Ha Tinh' => __('Tinh Ha Tinh'),
                                'Tinh Hai Duong' => __('Tinh Hai Duong'),
                                'Tinh Hoa Binh' => __('Tinh Hoa Binh'),
                                'Tinh Hung Yen' => __('Tinh Hung Yen'),
                                'Tinh Khanh Hoa' => __('Tinh Khanh Hoa'),
                                'Tinh Kien Giang' => __('Tinh Kien Giang'),
                                'Tinh Lai Chau' => __('Tinh Lai Chau'),
                                'Tinh Lam GJong' => __('Tinh Lam GJong'),
                                'Tinh Lang Son' => __('Tinh Lang Son'),
                                'Tinh Lao Cai' => __('Tinh Lao Cai'),
                                'Tinh Nam GJinh' => __('Tinh Nam GJinh'),
                                'Tinh Nghe An' => __('Tinh Nghe An'),
                                'Tinh Ninh Binh' => __('Tinh Ninh Binh'),
                                'Tinh Phu Tho' => __('Tinh Phu Tho'),
                                'Tinh Phu Yen' => __('Tinh Phu Yen'),
                                'Tinh Quang Binh' => __('Tinh Quang Binh'),
                                'Tinh Quang Nam' => __('Tinh Quang Nam'),
                                'Tinh Quang Ngai' => __('Tinh Quang Ngai'),
                                'Tinh Quang Tri' => __('Tinh Quang Tri'),
                                'Tinh Soc Trang' => __('Tinh Soc Trang'),
                                'Tinh Son La' => __('Tinh Son La'),
                                'Tinh Tay Ninh' => __('Tinh Tay Ninh'),
                                'Tinh Thai Binh' => __('Tinh Thai Binh'),
                                'Tinh Thai Nguyen' => __('Tinh Thai Nguyen'),
                                'Tinh Thanh Hoa' => __('Tinh Thanh Hoa'),
                                'Tinh Thua Thien-Hue' => __('Tinh Thua Thien-Hue'),
                                'Tinh Tien Giang' => __('Tinh Tien Giang'),
                                'Tinh Tra Vinh' => __('Tinh Tra Vinh'),
                                'Tinh Tuyen Quang' => __('Tinh Tuyen Quang'),
                                'Tinh Vinh Long' => __('Tinh Vinh Long'),
                                'Tinh Vinh Phuc' => __('Tinh Vinh Phuc'),
                                'Tinh Yen Bai' => __('Tinh Yen Bai'),
                            ],
                    ],
                'VU' =>
                    [
                        'value' => 'VU',
                        'label' => __('Vanuatu'),
                        'regions' =>
                            [
                                'Penama Province' => __('Penama Province'),
                                'Shefa Province' => __('Shefa Province'),
                            ],
                    ],
                'WF' =>
                    [
                        'value' => 'WF',
                        'label' => __('Wallis and Futuna'),
                        'regions' =>
                            [
                            ],
                    ],
                'WS' =>
                    [
                        'value' => 'WS',
                        'label' => __('Samoa'),
                        'regions' =>
                            [
                                'Atua' => __('Atua'),
                                'Tuamasaga' => __('Tuamasaga'),
                            ],
                    ],
                'XK' =>
                    [
                        'value' => 'XK',
                        'label' => __('Kosovo'),
                        'regions' =>
                            [
                            ],
                    ],
                'YE' =>
                    [
                        'value' => 'YE',
                        'label' => __('Yemen'),
                        'regions' =>
                            [
                                'Aden' => __('Aden'),
                                'Dhamār' => __('Dhamār'),
                                'Muhafazat Hadramawt' => __('Muhafazat Hadramawt'),
                                'Muhafazat al Hudaydah' => __('Muhafazat al Hudaydah'),
                                'Sanaa' => __('Sanaa'),
                            ],
                    ],
                'YT' =>
                    [
                        'value' => 'YT',
                        'label' => __('Mayotte'),
                        'regions' =>
                            [
                            ],
                    ],
                'ZA' =>
                    [
                        'value' => 'ZA',
                        'label' => __('South Africa'),
                        'regions' =>
                            [
                                'Eastern Cape' => __('Eastern Cape'),
                                'Gauteng' => __('Gauteng'),
                                'KwaZulu-Natal' => __('KwaZulu-Natal'),
                                'Limpopo' => __('Limpopo'),
                                'Mpumalanga' => __('Mpumalanga'),
                                'Province of North West' => __('North West'),
                                'Northern Cape' => __('Northern Cape'),
                                'Orange Free State' => __('Orange Free State'),
                                'Province of the Western Cape' => __('Western Cape'),
                            ],
                    ],
                'ZM' =>
                    [
                        'value' => 'ZM',
                        'label' => __('Zambia'),
                        'regions' =>
                            [
                                'Copperbelt' => __('Copperbelt'),
                                'Luapula Province' => __('Luapula Province'),
                                'Lusaka Province' => __('Lusaka Province'),
                                'Muchinga Province' => __('Muchinga Province'),
                                'North-Western Province' => __('North-Western Province'),
                                'Northern Province' => __('Northern Province'),
                                'Southern Province' => __('Southern Province'),
                                'Western Province' => __('Western Province'),
                            ],
                    ],
                'ZW' =>
                    [
                        'value' => 'ZW',
                        'label' => __('Zimbabwe'),
                        'regions' =>
                            [
                                'Bulawayo' => __('Bulawayo'),
                                'Harare' => __('Harare'),
                                'Mashonaland West' => __('Mashonaland West'),
                                'Matabeleland North' => __('Matabeleland North'),
                                'Matabeleland South Province' => __('Matabeleland South Province'),
                                'Midlands Province' => __('Midlands Province'),
                            ],
                    ]
            ];

        return $data;
    }
}
