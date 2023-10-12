<?php
/**
 * Blog
 * 
 * @author Slava Yurthev
 */
namespace Vexsoluciones\Delivery\Block\Adminhtml\Sector\Edit\Tab;
 
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Cms\Model\Wysiwyg\Config;
use Magento\Framework\Filesystem\DirectoryList;
 
class General extends Generic implements TabInterface {
	protected $_wysiwygConfig;
	protected $_directoryList;
	public function __construct(
		Context $context,
		Registry $registry,
		FormFactory $formFactory,
		Config $wysiwygConfig,
		DirectoryList $_directoryList,
		array $data = []
	) {
		$this->_directoryList = $_directoryList;
		$this->_wysiwygConfig = $wysiwygConfig;

		parent::__construct($context, $registry, $formFactory, $data);
	}
	protected function _prepareForm(){


		$model = $this->_coreRegistry->registry('sector');
		$form = $this->_formFactory->create();

		
 
		$fieldset = $form->addFieldset(
			'base_fieldset',
			['legend' => __('General')]
		);

 
		if ($model->getId()) {
			$fieldset->addField(
				'id',
				'hidden',
				['name' => 'id']
			);
		}



		$fieldset->addField(
			'status',
			'select',
			[
				'name' => 'status',
				'label'	=> __('Estado'),
				'required' => true,
				'values' => [
					['value'=>"1",'label'=>__('Habilitado')],
					['value'=>"0",'label'=>__('Deshabilitado')]
				]
			]
		);

		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$tipos_envio = $objectManager->get('Vexsoluciones\Delivery\Helper\Data')->tiposenvio();

		$listaenvios = array();
		foreach ($tipos_envio as $key => $value) {
			$listaenvios[] = array(
				"value" => $key,
				"label"=> $value
			);
		}

		$fieldset->addField(
			'tipo_envio',
			'select',
			[
				'name' => 'tipo_envio',
				'label'	=> __('Tipo envio'),
				'required' => true,
				'values' => $listaenvios
			]
		);

		

		$fieldset->addType(
            'filedsectores',
            '\Vexsoluciones\Delivery\Block\Adminhtml\Sector\FieldSector\Edit\Renderer\CustomRenderer'
        );
		$fieldset->addField('sectores', 'filedsectores',
            [
                'name' => 'sectores',
                'label' => __(''),
                'title' => __('')
            ]
        );

		$data = $model->getData();
		$form->setValues($data);
		$this->setForm($form);
 
		return parent::_prepareForm();
	}
	public function getTabLabel(){
		return __('Sector');
	}
	public function getTabTitle(){
		return __('Sector');
	}
	public function canShowTab(){
		return true;
	}
	public function isHidden(){
		return false;
	}
}