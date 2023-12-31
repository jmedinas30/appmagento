<?php
/**
 * Blog
 * 
 * @author Slava Yurthev
 */
namespace Vexsoluciones\Delivery\Block\Adminhtml\Sector\Edit;
 
use Magento\Backend\Block\Widget\Tabs as WidgetTabs;
 
class Tabs extends WidgetTabs{
	protected function _construct(){
		parent::_construct();
		$this->setId('sector_edit_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle(__('Sector'));
	}
	protected function _beforeToHtml(){
		$this->addTab(
			'general_data',
			[
				'label' => __('General'),
				'title' => __('General'),
				'content' => $this->getLayout()->createBlock(
					'Vexsoluciones\Delivery\Block\Adminhtml\Sector\Edit\Tab\General'
				)->toHtml(),
				'active' => true
			]
		);
		return parent::_beforeToHtml();
	}
}