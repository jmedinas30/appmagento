<?PHP

namespace Vexsoluciones\Facturacionelectronica\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements  UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup,
                            ModuleContextInterface $context){

        $setup->startSetup();

       if (version_compare( $context->getVersion(), '1.4.0' ) < 0) { // Si es anterior a la version 1.3 entonces actualiza

            $columns = [
                'vexfe_tipo_de_comprobante' => [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_NUMERIC,
                    'length' => 3,
                    'nullable' => false,
                    'comment' => '1 Factura, 2 Boleta',
                ],
                'vexfe_tipo_de_documento_cliente' => [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_NUMERIC,
                    'length' => 3,
                    'nullable' => false,
                    'comment' => 'DNI, Pasaporte, Carnet de extranjeria, etc.. ',
                ],
                'vexfe_numero_de_documento' => [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 30,
                    'nullable' => false,
                    'comment' => 'Modulo de facturacion Vex Soluciones',
                ],
                'vexfe_denominacion_cliente' => [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 100,
                    'nullable' => false,
                    'comment' => 'Razon social o nombre completo',
                ],
                'vexfe_direccion_fiscal' => [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 100,
                    'nullable' => false,
                    'comment' => 'Modulo de facturacion Vex Soluciones',
                ],
                'vexfe_comentario' => [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 100,
                    'nullable' => false,
                    'comment' => 'Modulo de facturacion Vex Soluciones',
                ],
                'Id_facturacion' => [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 100,
                    'nullable' => false,
                    'comment' => 'PedNumero de facturacion Vex Soluciones',
                ]
            ];

            $connection = $setup->getConnection();

            foreach ($columns as $name => $definition) {
                $connection->addColumn( $setup->getTable('quote'), $name, $definition);
            }


            foreach ($columns as $name => $definition) {
                $connection->addColumn( $setup->getTable('sales_order'), $name, $definition);
            }

       }

        $setup->endSetup();
    }
}
