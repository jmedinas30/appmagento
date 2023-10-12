<?php
namespace Vexsoluciones\Facturacionelectronica\Setup;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    /**
     * install tables
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     * @return void
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();



        /* campos

        const TIPO_DE_COMPROBANTE = 'vexfe_tipo_de_comprobante';
        const TIPO_DE_DOCUMENTO_CLIENTE = 'vexfe_tipo_de_documento';
        const NUMERO_DE_DOCUMENTO = 'vexfe_numero_de_documento';
        const DENOMINACION_CLIENTE = 'vexfe_denominacion_cliente';
        const DIRECCION_FISCAL = 'vexfe_direccion_fiscal';
        const COMENTARIO = 'vexfe_comentario';

        */


        $columns = [
            'vexfe_tipo_de_comprobante' => [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_NUMERIC,
                'length' => 3,
                'nullable' => false,
                'comment' => '.',
            ],
            'vexfe_tipo_de_documento_cliente' => [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_NUMERIC,
                'length' => 3,
                'nullable' => false,
                'comment' => '.',
            ],
            'vexfe_numero_de_documento' => [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 30,
                'nullable' => false,
                'comment' => '.',
            ],
            'vexfe_denominacion_cliente' => [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 100,
                'nullable' => false,
                'comment' => '.',
            ],
            'vexfe_direccion_fiscal' => [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 100,
                'nullable' => false,
                'comment' => '.',
            ],
            'vexfe_comentario' => [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 100,
                'nullable' => false,
                'comment' => '.',
            ]
        ];

        $connection = $installer->getConnection();
 

        foreach ($columns as $name => $definition) {
            $connection->addColumn( $installer->getTable('quote'), $name, $definition);
        }

        foreach ($columns as $name => $definition) {
            $connection->addColumn( $installer->getTable('sales_order'), $name, $definition);
        }



        if (!$installer->tableExists('mg_vexfe_comprobantes')) {

            $table = $installer->getConnection()->newTable(
                $installer->getTable('mg_vexfe_comprobantes')
            )
            ->addColumn(
                'comprobante_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary'  => true,
                    'unsigned' => true,
                ],
                'ID del comprobante'
            )
            ->addColumn(
                'tipo',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '20',
                [],
                'Por defecto 1, es orden'
            )
            ->addColumn(
                'parent_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                [],
                'El ID de la orden'
            )
            ->addColumn(
                'tipo_de_comprobante',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2',
                [],
                '1 factura, 2 boleta'
            )
            ->addColumn(
                'serie',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                10,
                [],
                'Serie del comprobante'
            )
            ->addColumn(
                'numero',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                [],
                'numero del comprobante'
            )
            ->addColumn(
                'cliente_tipo_de_documento',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '6',
                [],
                'DNI, Carnet, Passaporte'
            )
            ->addColumn(
                'cliente_numero_de_documento',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '30',
                [],
                '.'
            )
            ->addColumn(
                'cliente_denominacion',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '100',
                [],
                'Nombre completo o razon social'
            )
            ->addColumn(
                'cliente_direccion',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '100',
                [],
                'direccion fiscal'
            )
            ->addColumn(
                'cliente_email',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '100',
                [],
                '.'
            )
            ->addColumn(
                'cliente_fono',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '30',
                [],
                '.'
            )
            ->addColumn(
                'fecha_de_emision',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                null,
                [],
                'fecha de emision del comprobante'
            )
            ->addColumn(
                'fecha_de_vencimiento',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                null,
                [],
                'fecha de vencimiento del comprobante'
            )
            ->addColumn(
                'moneda',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '1',
                [],
                '.'
            )
            ->addColumn(
                'moneda',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '5',
                [],
                'codigo de la moneda'
            )
            ->addColumn(
                'porcentaje_de_igv',
                \Magento\Framework\DB\Ddl\Table::TYPE_NUMERIC,
                null,
                ['precision' => 4],
                '.'
            )
            ->addColumn(
                'total_gravada',
                \Magento\Framework\DB\Ddl\Table::TYPE_NUMERIC,
                null,
                ['precision' => 4],
                '.'
            )
            ->addColumn(
                'total_igv',
                \Magento\Framework\DB\Ddl\Table::TYPE_NUMERIC,
                null,
                ['precision' => 4],
                '.'
            )
            ->addColumn(
                'total',
                \Magento\Framework\DB\Ddl\Table::TYPE_NUMERIC,
                null,
                ['precision' => 4],
                '.'
            )
            ->addColumn(
                'data',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '255',
                [],
                'data adicional'
            )
            ->addColumn(
                'sunat_estado',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '1',
                [],
                'probablemente este campo no se use'
            )
            ->addColumn(
                'sys_estado',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '1',
                [],
                '1 si es un registro activo'
            )
            ->addColumn(
                'fecha_creacion',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                [],
                'Created At'
            )
            ->addColumn(
                'fecha_actualizacion',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                [],
                'Updated At'
            )
            ->addColumn(
                'user_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                [],
                'Id del usuario que ha generado el comprobante, deberia ser el mismo de la orden'
            )
            ->setComment('Tabla principal de comprobantes');

            $installer->getConnection()->createTable($table);

            /*
            $installer->getConnection()->addIndex(
                $installer->getTable('mageplaza_helloworld_post'),
                $setup->getIdxName(
                    $installer->getTable('mageplaza_helloworld_post'),
                    ['name','url_key','post_content','tags','featured_image','sample_upload_file'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                ),
                ['name','url_key','post_content','tags','featured_image','sample_upload_file'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
            ); */
        }


        if (!$installer->tableExists('mg_vexfe_comprobante_detalle')) {

            $table = $installer->getConnection()->newTable(
                $installer->getTable('mg_vexfe_comprobante_detalle')
            )
            ->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary'  => true,
                    'unsigned' => true,
                ],
                '.'
            )
            ->addColumn(
                'comprobante_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                [],
                'El ID del comprobante'
            )

            ->addColumn(
                'detalle',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '100',
                [],
                'Nombre o detalle del producto'
            )
            ->addColumn(
                'product_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                [],
                'Id del producto'
            )
            ->addColumn(
                'cantidad',
                \Magento\Framework\DB\Ddl\Table::TYPE_NUMERIC,
                null,
                ['precision' => 4],
                '.'
            )
            ->addColumn(
                'precio_unitario',
                \Magento\Framework\DB\Ddl\Table::TYPE_NUMERIC,
                null,
                ['precision' => 4],
                '.'
            )
            ->addColumn(
                'subtotal',
                \Magento\Framework\DB\Ddl\Table::TYPE_NUMERIC,
                null,
                ['precision' => 4],
                '.'
            )
            ->addColumn(
                'sys_estado',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '1',
                [],
                '1 si es un registro activo'
            )
            ->addColumn(
                'fecha_creacion',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                [],
                'Created At'
            )
            ->addColumn(
                'fecha_actualizacion',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                [],
                'Updated At'
            )
            ->addColumn(
                'user_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                [],
                'Id del usuario que ha generado el registro'
            )
            ->setComment('Tabla que guarda el detalle del comprobante');

            $installer->getConnection()->createTable($table);

        }



        if (!$installer->tableExists('mg_vexfe_envios_facturas')) {

            $table = $installer->getConnection()->newTable(
                $installer->getTable('mg_vexfe_envios_facturas')
            )
            ->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary'  => true,
                    'unsigned' => true,
                ],
                '.'
            )
            ->addColumn(
                'comprobante_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                [],
                'El ID del comprobante'
            )
            ->addColumn(
                'fecha_hora',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                [],
                '.'
            )
            ->addColumn(
                'codigo_sunat',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '100',
                [],
                '.'
            )
            ->addColumn(
                'estado_sunat',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '5',
                [],
                '.'
            )
            ->addColumn(
                'codigo_sunat',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '5',
                [],
                '.'
            )
            ->addColumn(
                'sys_estado',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '1',
                [],
                '1 si es un registro activo'
            )
            ->addColumn(
                'fecha_actualizacion',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                [],
                'Updated At'
            )
            ->addColumn(
                'user_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                [],
                'Id del usuario que ha generado el registro'
            )
            ->setComment('Tabla que guarda el detalle del envio de una factura');

            $installer->getConnection()->createTable($table);

        }




        if (!$installer->tableExists('mg_vexfe_series')) {

            $table = $installer->getConnection()->newTable(
                $installer->getTable('mg_vexfe_series')
            )
            ->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary'  => true,
                    'unsigned' => true,
                ],
                '.'
            )
            ->addColumn(
                'tipo_de_comprobante',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                [],
                'El ID del comprobante'
            )
            ->addColumn(
                'serie_tipo',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '3',
                [],
                '.'
            )
            ->addColumn(
                'serie_numero',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                [],
                '.'
            )
            ->addColumn(
                'num_desde',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                [],
                '.'
            )
            ->addColumn(
                'num_hasta',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                [],
                '.'
            )
            ->addColumn(
                'correlativo',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                [],
                '.'
            )
            ->addColumn(
                'sys_estado',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '1',
                [],
                '1 si es un registro activo'
            )
            ->addColumn(
                'fecha_actualizacion',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                [],
                'Updated At'
            )
            ->addColumn(
                'user_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                [],
                'Id del usuario que ha generado el registro'
            )
            ->setComment('Tabla que guarda las series de comprobantes');

            $installer->getConnection()->createTable($table);

        }

      $installer->endSetup();


    }
}
