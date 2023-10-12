<?php

namespace Meetanshi\GA4\Block\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Class Button
 * @package Meetanshi\GA4\Block\System\Config
 */
class Button extends Field
{
    /**
     * @var string
     */
    protected $_template = 'Meetanshi_GA4::system/config/button.phtml';

    /**
     * Button constructor.
     * @param Context $context
     * @param array $data
     */
    public function __construct(Context $context, array $data = [])
    {
        parent::__construct($context, $data);
    }

    /**
     * @param AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }

    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        return $this->_toHtml();
    }

    /**
     * @return string
     */
    public function getCustomUrl()
    {
        return $this->getUrl('ga4/configjson/generatejson');
    }

    /**
     * @return string
     */
    public function getJsonDownload()
    {
        return $this->getUrl('ga4/configjson/downloadjson');
    }

    /**
     * @return string
     */
    public function getCheckButton()
    {
        return $this->getUrl('ga4/configjson/checkbutton');
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getButtonHtml()
    {
        $button = $this->getLayout()->createBlock('Magento\Backend\Block\Widget\Button')->setData([
            'id' => 'generate_json',
            'class' => 'primary',
            'label' => __('Generate Json for GTM - Tags, Triggers and Variables')
        ]);
        return $button->toHtml();
    }
}
