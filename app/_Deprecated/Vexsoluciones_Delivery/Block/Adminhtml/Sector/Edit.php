<?php
/**
 * Blog
 * 
 * @author Slava Yurthev
 */
namespace Vexsoluciones\Delivery\Block\Adminhtml\Sector;

class Edit extends \Magento\Backend\Block\Widget\Form\Container {
	protected $_coreRegistry = null;
	public function __construct(
		\Magento\Backend\Block\Widget\Context $context,
		\Magento\Framework\Registry $registry,
		array $data = []
	) {
		$this->_coreRegistry = $registry;
		parent::__construct($context, $data);
	}
	protected function _construct(){

		
		$this->_objectId = 'id';
		$this->_blockGroup = 'Vexsoluciones_Delivery';
		$this->_controller = 'adminhtml_sector';
		parent::_construct();
		if ($this->_isAllowedAction('Vexsoluciones_Delivery::sector')) {
			$this->buttonList->remove('reset');
			$this->buttonList->update('save', 'label', __('Guardar sector'));
			$this->buttonList->add(
				'saveandcontinue',
				[
					'label' => __('Guardar y continuar editando'),
					'class' => 'save',
					'data_attribute' => [
						'mage-init' => [
							'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
						],
					]
				],
				-100
			);
		} else {
			$this->buttonList->remove('save');
		}
		if ($this->_isAllowedAction('Vexsoluciones_Delivery::sector')) {
			$this->buttonList->update('delete', 'label', __('Eliminar sector'));
		} else {
			$this->buttonList->remove('delete');
		}
	}
	public function getHeaderText(){
		if ($this->_coreRegistry->registry('sector')->getId()) {
			return __("Editar sector '%1'", $this->escapeHtml($this->_coreRegistry->registry('sector')->getId()));
		} else {
			return __('Nuevo sector');
		}
	}
	protected function _isAllowedAction($resourceId){
		return $this->_authorization->isAllowed($resourceId);
	}
	protected function _getSaveAndContinueUrl(){
		return $this->getUrl('*/*/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '']);
	}
	protected function _prepareLayout(){
		$this->_formScripts[] = "
			function toggleEditor() {
				if (tinyMCE.getInstanceById('general_content') == null) {
					tinyMCE.execCommand('mceAddControl', false, 'general_content');
				} else {
					tinyMCE.execCommand('mceRemoveControl', false, 'general_content');
				}
			};
		";
		return parent::_prepareLayout();
	}
}