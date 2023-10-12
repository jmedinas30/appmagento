<?php

/*
    Constantes de la extensión
*/

define( '_VEXFE_MODULE_DIR_' , __DIR__);
define( '_VEXFE_MODO_TEST_', true );
define( '_VEXFE_DB_PREFIX_' , 'mg_');
define( '_VEXFE_DIR_DOCUMENTOS_', _VEXFE_MODULE_DIR_.DIRECTORY_SEPARATOR.'docs'.DIRECTORY_SEPARATOR.'documentos' );
define( '_VEXFE_DIR_CONFIG_FILES_', _VEXFE_MODULE_DIR_.DIRECTORY_SEPARATOR.'docs'.DIRECTORY_SEPARATOR.'config' );



\Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::MODULE,
    'Vexsoluciones_Facturacionelectronica',
    __DIR__
);
