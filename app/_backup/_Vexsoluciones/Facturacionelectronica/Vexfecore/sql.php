<?PHP

namespace Vexsoluciones\Facturacionelectronica\Vexfecore;


class sql{

    private $tables = array(
        'vexfe_bajas',
        'vexfe_comprobantes',
        'Fvexfe_comprobante_detalle'
    );

    public function installSQL(){

        $sql ="


          CREATE TABLE `mg_vexfe_comprobantes` (
            `comprobante_id` int(11) NOT NULL,
            `ref_tipo` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `ref_id` int(11) DEFAULT NULL,
            `tipo_de_comprobante` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `serie` char(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `numero` int(11) DEFAULT NULL,
            `cliente_tipo_de_documento` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `cliente_numero_de_documento` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `cliente_denominacion` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `cliente_direccion` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `cliente_email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `cliente_fono` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `fecha_de_emision` date DEFAULT NULL,
            `fecha_de_vencimiento` date DEFAULT NULL,
            `moneda` char(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `porcentaje_de_igv` double DEFAULT NULL,
            `total_gravada` double DEFAULT NULL,
            `total_igv` double DEFAULT NULL,
            `total` double DEFAULT NULL,
            `wp_data` text COLLATE utf8mb4_unicode_ci,
            `sunat_estado` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
            `sunat_respuesta_envio` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
            `sunat_fecha_envio` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `sys_estado` smallint(6) DEFAULT '1'
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

          -- --------------------------------------------------------

          --
          -- Estructura de tabla para la tabla `mg_vexfe_comprobante_detalle`
          --

          CREATE TABLE `mg_vexfe_comprobante_detalle` (
            `id` int(11) NOT NULL,
            `comprobante_id` int(11) NOT NULL,
            `detalle` varchar(100) DEFAULT NULL,
            `unidad_medida` varchar(30) NOT NULL,
            `cantidad` double DEFAULT '0',
            `precio_unitario` double DEFAULT '0',
            `precio_referencial` float NOT NULL,
            `subtotal` double DEFAULT NULL,
            `impuesto` float NOT NULL,
            `sys_estado` smallint(6) DEFAULT '1',
            `product_id` int(11) DEFAULT '0'
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

          -- --------------------------------------------------------

          --
          -- Estructura de tabla para la tabla `mg_vexfe_config`
          --

          CREATE TABLE `mg_vexfe_config` (
            `id` int(11) NOT NULL,
            `keyactivacion` varchar(128) NOT NULL,
            `usuariosol` varchar(30) DEFAULT NULL,
            `clavesol` varchar(30) DEFAULT NULL,
            `certificado` varchar(255) DEFAULT NULL,
            `clavecertificado` varchar(30) DEFAULT NULL,
            `ruc` varchar(20) DEFAULT NULL,
            `razonsocial` varchar(100) DEFAULT NULL,
            `direccion` varchar(80) NOT NULL,
            `fecha_hora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `estado` smallint(6) DEFAULT NULL,
            `logo` varchar(50) DEFAULT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

          -- --------------------------------------------------------

          --
          -- Estructura de tabla para la tabla `mg_vexfe_config_parametros`
          --

          CREATE TABLE `mg_vexfe_config_parametros` (
            `id` int(11) NOT NULL,
            `moneda` varchar(4) NOT NULL,
            `unidad_de_medida` varchar(30) NOT NULL,
            `igv` float NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

          -- --------------------------------------------------------

          --
          -- Estructura de tabla para la tabla `mg_vexfe_envios_boletas`
          --

          CREATE TABLE `mg_vexfe_envios_boletas` (
            `id` int(11) NOT NULL,
            `fecha_hora` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `serie_id` int(11) DEFAULT NULL,
            `numero_inicio` int(11) DEFAULT NULL,
            `numero_fin` int(11) DEFAULT NULL,
            `boleta_inicio_id` int(11) DEFAULT NULL,
            `boleta_fin_id` int(11) DEFAULT NULL,
            `fecha` date DEFAULT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

          -- --------------------------------------------------------

          --
          -- Estructura de tabla para la tabla `mg_vexfe_envios_facturas`
          --

          CREATE TABLE `mg_vexfe_envios_facturas` (
            `id` int(11) NOT NULL,
            `comprobante_id` int(11) NOT NULL,
            `fecha_hora` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `codigo_sunat` varchar(100) DEFAULT NULL,
            `estado_sunat` smallint(6) DEFAULT '0',
            `resumen_respuesta` varchar(250) DEFAULT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

          -- --------------------------------------------------------

          --
          -- Estructura de tabla para la tabla `mg_vexfe_log`
          --

          CREATE TABLE `mg_vexfe_log` (
            `id` int(11) NOT NULL,
            `tipo_objeto` varchar(30) NOT NULL,
            `mensaje_path` varchar(80) NOT NULL,
            `objeto_id` int(11) NOT NULL,
            `descripcion_operacion` varchar(100) NOT NULL,
            `descripcion_respuesta` varchar(250) NOT NULL,
            `data_envio` text NOT NULL,
            `data_respuesta` text NOT NULL,
            `tipo` varchar(20) NOT NULL,
            `fecha_hora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

          -- --------------------------------------------------------

          --
          -- Estructura de tabla para la tabla `mg_vexfe_serie`
          --

          CREATE TABLE `mg_vexfe_serie` (
            `id` int(11) NOT NULL,
            `tipo_de_comprobante` int(11) DEFAULT NULL,
            `serie_tipo` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `serie_numero` int(11) DEFAULT NULL,
            `num_desde` int(11) DEFAULT NULL,
            `num_hasta` int(11) DEFAULT NULL,
            `correlativo` int(11) DEFAULT NULL,
            `estado` int(11) DEFAULT '1'
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

          --
          -- Índices para tablas volcadas
          --

          --
          -- Indices de la tabla `mg_vexfe_comprobantes`
          --
          ALTER TABLE `mg_vexfe_comprobantes`
            ADD PRIMARY KEY (`comprobante_id`,`sunat_estado`);

          --
          -- Indices de la tabla `mg_vexfe_comprobante_detalle`
          --
          ALTER TABLE `mg_vexfe_comprobante_detalle`
            ADD PRIMARY KEY (`id`);

          --
          -- Indices de la tabla `mg_vexfe_config`
          --
          ALTER TABLE `mg_vexfe_config`
            ADD PRIMARY KEY (`id`);

          --
          -- Indices de la tabla `mg_vexfe_config_parametros`
          --
          ALTER TABLE `mg_vexfe_config_parametros`
            ADD PRIMARY KEY (`id`);

          --
          -- Indices de la tabla `mg_vexfe_envios_boletas`
          --
          ALTER TABLE `mg_vexfe_envios_boletas`
            ADD PRIMARY KEY (`id`);

          --
          -- Indices de la tabla `mg_vexfe_envios_facturas`
          --
          ALTER TABLE `mg_vexfe_envios_facturas`
            ADD PRIMARY KEY (`id`);

          --
          -- Indices de la tabla `mg_vexfe_log`
          --
          ALTER TABLE `mg_vexfe_log`
            ADD PRIMARY KEY (`id`);

          --
          -- Indices de la tabla `mg_vexfe_serie`
          --
          ALTER TABLE `mg_vexfe_serie`
            ADD PRIMARY KEY (`id`);

          --
          -- AUTO_INCREMENT de las tablas volcadas
          --

          --
          -- AUTO_INCREMENT de la tabla `mg_vexfe_comprobantes`
          --
          ALTER TABLE `mg_vexfe_comprobantes`
            MODIFY `comprobante_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=202;

          --
          -- AUTO_INCREMENT de la tabla `mg_vexfe_comprobante_detalle`
          --
          ALTER TABLE `mg_vexfe_comprobante_detalle`
            MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=224;

          --
          -- AUTO_INCREMENT de la tabla `mg_vexfe_config`
          --
          ALTER TABLE `mg_vexfe_config`
            MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

          --
          -- AUTO_INCREMENT de la tabla `mg_vexfe_config_parametros`
          --
          ALTER TABLE `mg_vexfe_config_parametros`
            MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

          --
          -- AUTO_INCREMENT de la tabla `mg_vexfe_envios_boletas`
          --
          ALTER TABLE `mg_vexfe_envios_boletas`
            MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

          --
          -- AUTO_INCREMENT de la tabla `mg_vexfe_envios_facturas`
          --
          ALTER TABLE `mg_vexfe_envios_facturas`
            MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

          --
          -- AUTO_INCREMENT de la tabla `mg_vexfe_log`
          --
          ALTER TABLE `mg_vexfe_log`
            MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

          --
          -- AUTO_INCREMENT de la tabla `mg_vexfe_serie`
          --
          ALTER TABLE `mg_vexfe_serie`
            MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
          COMMIT;


        ";

    }

    public function reset(){

        $sql = "DELETE FROM ps_vexfe_comprobantes;
                DELETE FROM ps_vexfe_comprobante_detalle;
                DELETE FROM ps_vexfe_log; ";

    }

    public function dropEstructure(){



    }
}
