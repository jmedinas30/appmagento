<?php
namespace Vexsoluciones\Delivery\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements  UpgradeSchemaInterface{

    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context){

        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.0.1') < 0) {

            $table  = $setup->getConnection()
                    ->newTable($setup->getTable('vexsoluciones_tiendas'))
                    ->addColumn(
                        'id',
                        Table::TYPE_INTEGER,
                        null,
                        ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                        'Id'
                    )
                    ->addColumn(
                        'nombre',
                        Table::TYPE_TEXT,
                        null,
                        ['default' => ""],
                        'nombre'
                    )
                    ->addColumn(
                        'direccion',
                        Table::TYPE_TEXT,
                        null,
                        ['default' => ""],
                        'direccion'
                    )
                    ->addColumn(
                        'latitud',
                        Table::TYPE_TEXT,
                        null,
                        ['default' => ""],
                        'latitud'
                    )->addColumn(
                        'longitud',
                        Table::TYPE_TEXT,
                        null,
                        ['default' => ""],
                        'longitud'
                    )->addColumn(
                        'distrito',
                        Table::TYPE_INTEGER,
                        null,
                        ['default' => 0],
                        'distrito'
                    )->addColumn(
                        'status',
                        Table::TYPE_INTEGER,
                        null,
                        ['default' => null],
                        'status'
                    )->addColumn(
                        'fecha',
                        Table::TYPE_TIMESTAMP,
                        null,
                        ['default' => null],
                        'fecha'
                    );
            
            $setup->getConnection()->createTable($table);



            $table  = $setup->getConnection()
                    ->newTable($setup->getTable('vexsoluciones_tienda_stock'))
                    ->addColumn(
                        'id',
                        Table::TYPE_INTEGER,
                        null,
                        ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                        'Id'
                    )
                    ->addColumn(
                        'tienda_id',
                        Table::TYPE_INTEGER,
                        null,
                        ['default' => 0],
                        'tienda_id'
                    )
                    ->addColumn(
                        'producto_id',
                        Table::TYPE_INTEGER,
                        null,
                        ['default' => 0],
                        'producto_id'
                    )
                    ->addColumn(
                        'stock',
                        Table::TYPE_INTEGER,
                        null,
                        ['default' => 0],
                        'stock'
                    )
                    ->addColumn(
                        'status',
                        Table::TYPE_INTEGER,
                        null,
                        ['default' => null],
                        'status'
                    );
            
            $setup->getConnection()->createTable($table);


        }


        if (version_compare($context->getVersion(), '1.0.3') < 0) {

            $table  = $setup->getConnection()
                    ->newTable($setup->getTable('vexsoluciones_directorio_provincia'))
                    ->addColumn(
                        'id',
                        Table::TYPE_INTEGER,
                        null,
                        ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                        'Id'
                    )
                    ->addColumn(
                        'nombre_provincia',
                        Table::TYPE_TEXT,
                        null,
                        ['default' => ""],
                        'nombre_provincia'
                    )
                    ->addColumn(
                        'region_id',
                        Table::TYPE_INTEGER,
                        null,
                        ['default' => 0],
                        'region_id'
                    );
            
            $setup->getConnection()->createTable($table);


            $table  = $setup->getConnection()
                    ->newTable($setup->getTable('vexsoluciones_directorio_distrito'))
                    ->addColumn(
                        'id',
                        Table::TYPE_INTEGER,
                        null,
                        ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                        'Id'
                    )
                    ->addColumn(
                        'nombre_distrito',
                        Table::TYPE_TEXT,
                        null,
                        ['default' => ""],
                        'nombre_distrito'
                    )
                    ->addColumn(
                        'provincia_id',
                        Table::TYPE_INTEGER,
                        null,
                        ['default' => 0],
                        'provincia_id'
                    );
            
            $setup->getConnection()->createTable($table);

        }

        if (version_compare($context->getVersion(), '1.0.5') < 0) {

            $table  = $setup->getConnection()
                    ->newTable($setup->getTable('vexsoluciones_reglas_sector'))
                    ->addColumn(
                        'id',
                        Table::TYPE_INTEGER,
                        null,
                        ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                        'Id'
                    )
                    ->addColumn(
                        'country_id',
                        Table::TYPE_TEXT,
                        null,
                        ['default' => ""],
                        'country_id'
                    )->addColumn(
                        'departamento_id',
                        Table::TYPE_INTEGER,
                        null,
                        ['default' => 0],
                        'departamento_id'
                    )
                    ->addColumn(
                        'provincia_id',
                        Table::TYPE_INTEGER,
                        null,
                        ['default' => 0],
                        'provincia_id'
                    )
                    ->addColumn(
                        'distrito_id',
                        Table::TYPE_INTEGER,
                        null,
                        ['default' => 0],
                        'distrito_id'
                    )
                    ->addColumn(
                        'tipo_envio',
                        Table::TYPE_INTEGER,
                        null,
                        ['default' => 0],
                        'tipo_envio'
                    )
                    ->addColumn(
                        'status',
                        Table::TYPE_INTEGER,
                        null,
                        ['default' => null],
                        'status'
                    );
            
            $setup->getConnection()->createTable($table);



            $table  = $setup->getConnection()
                    ->newTable($setup->getTable('vexsoluciones_reglas_horario'))
                    ->addColumn(
                        'id',
                        Table::TYPE_INTEGER,
                        null,
                        ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                        'Id'
                    )->addColumn(
                        'dia',
                        Table::TYPE_INTEGER,
                        null,
                        ['default' => 0],
                        'dia'
                    )
                    ->addColumn(
                        'hora_inicio',
                        Table::TYPE_INTEGER,
                        null,
                        ['default' => 0],
                        'hora_inicio'
                    )
                    ->addColumn(
                        'hora_fin',
                        Table::TYPE_INTEGER,
                        null,
                        ['default' => 0],
                        'hora_fin'
                    )
                    ->addColumn(
                        'sector_id',
                        Table::TYPE_INTEGER,
                        null,
                        ['default' => 0],
                        'sector_id'
                    )
                    ->addColumn(
                        'status',
                        Table::TYPE_INTEGER,
                        null,
                        ['default' => null],
                        'status'
                    );
            
            $setup->getConnection()->createTable($table);



            $table  = $setup->getConnection()
                    ->newTable($setup->getTable('vexsoluciones_reglas_precio'))
                    ->addColumn(
                        'id',
                        Table::TYPE_INTEGER,
                        null,
                        ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                        'Id'
                    )->addColumn(
                        'peso_inicio',
                        Table::TYPE_FLOAT,
                        null,
                        ['default' => 0],
                        'peso_inicio'
                    )
                    ->addColumn(
                        'peso_fin',
                        Table::TYPE_FLOAT,
                        null,
                        ['default' => 0],
                        'peso_fin'
                    )
                    ->addColumn(
                        'precio',
                        Table::TYPE_FLOAT,
                        null,
                        ['default' => 0],
                        'precio'
                    )
                    ->addColumn(
                        'sector_id',
                        Table::TYPE_INTEGER,
                        null,
                        ['default' => 0],
                        'sector_id'
                    )
                    ->addColumn(
                        'status',
                        Table::TYPE_INTEGER,
                        null,
                        ['default' => null],
                        'status'
                    );
            
            $setup->getConnection()->createTable($table);


        }



        if (version_compare($context->getVersion(), '1.0.6') < 0) {

            $setup->getConnection()->addColumn(
                $setup->getTable('quote_address'),
                'vexcoordenadas',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => true,
                    'default' => '',
                    'comment' => 'vexcoordenadas'
                ]
            );

            $setup->getConnection()->addColumn(
                $setup->getTable('quote_address'),
                'vexdiaprogramado',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => true,
                    'default' => '',
                    'comment' => 'vexdiaprogramado'
                ]
            );

            $setup->getConnection()->addColumn(
                $setup->getTable('quote_address'),
                'vexhoraprogramado',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => true,
                    'default' => '',
                    'comment' => 'vexhoraprogramado'
                ]
            );



            $setup->getConnection()->addColumn(
                $setup->getTable('sales_order_address'),
                'vexcoordenadas',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => true,
                    'default' => '',
                    'comment' => 'vexcoordenadas'
                ]
            );

            $setup->getConnection()->addColumn(
                $setup->getTable('sales_order_address'),
                'vexdiaprogramado',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => true,
                    'default' => '',
                    'comment' => 'vexdiaprogramado'
                ]
            );

            $setup->getConnection()->addColumn(
                $setup->getTable('sales_order_address'),
                'vexhoraprogramado',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => true,
                    'default' => '',
                    'comment' => 'vexhoraprogramado'
                ]
            );
            
        }

        $setup->endSetup();

    }

}