<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Block\Adminhtml\Location\Edit;

use MageWorx\Locations\Api\LocationRepositoryInterface;
use MageWorx\Locations\Helper\Data;

class Conditions extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * Core registry
     *
     * @var \Magento\Backend\Block\Widget\Form\Renderer\Fieldset
     */
    protected $rendererFieldset;

    /**
     * @var \Magento\Rule\Block\Conditions
     */
    protected $conditions;

    /**
     * @var string
     */
    protected $_nameInLayout = 'mageworx_location_conditions';

    /**
     * @var LocationRepositoryInterface
     */
    private $locationRepositoryInterface;

    /**
     * @var Data
     */
    private $helper;

    /**
     * @var string
     */
    protected $formName;

    /**
     * Conditions constructor.
     *
     * @param Data $helper
     * @param LocationRepositoryInterface $locationRepositoryInterface
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Rule\Block\Conditions $conditions
     * @param \Magento\Backend\Block\Widget\Form\Renderer\Fieldset $rendererFieldset
     * @param string $formName
     * @param string[] $data
     */
    public function __construct(
        Data $helper,
        LocationRepositoryInterface $locationRepositoryInterface,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Rule\Block\Conditions $conditions,
        \Magento\Backend\Block\Widget\Form\Renderer\Fieldset $rendererFieldset,
        $formName = 'mageworx_locations_location_form',
        array $data = []
    ) {
        $this->formName                    = $formName;
        $this->helper                      = $helper;
        $this->rendererFieldset            = $rendererFieldset;
        $this->conditions                  = $conditions;
        $this->locationRepositoryInterface = $locationRepositoryInterface;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        /** @var LocationRepositoryInterface $model */
        $model = $this->helper->getCurrentLocation();
        $model = $model->getRule();

        $conditionsFieldSetId = $model->getConditionsFieldSetId($this->formName);
        $newChildUrl          = $this->getUrl(
            'mageworx_locations/location/conditionHtml/form/' . $conditionsFieldSetId,
            ['form_namespace' => $this->formName]
        );

        /** @var \Magento\Framework\Data\Form $form */
        $form     = $this->_formFactory->create();
        $renderer = $this->rendererFieldset->setTemplate(
            'Magento_CatalogRule::promo/fieldset.phtml'
        )->setNewChildUrl(
            $newChildUrl
        )->setFieldSetId(
            $conditionsFieldSetId
        )->setNameInLayout('mageworx_locations_fieldset');

        $fieldset = $form->addFieldset(
            'conditions_fieldset',
            [
                'legend' => __(
                    'Conditions (don\'t add conditions to show all products)'
                )
            ]
        )->setRenderer(
            $renderer
        );
        $fieldset->addField(
            'conditions',
            'text',
            [
                'name'           => 'conditions',
                'label'          => __('Conditions'),
                'title'          => __('Conditions'),
                'required'       => true,
                'data-form-part' => $this->formName
            ]
        )->setRule(
            $model
        )->setRenderer(
            $this->conditions
        );

        $form->setValues($model->getData());
        $this->setConditionFormName($model->getConditions(), $this->formName);

        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * @param \Magento\Rule\Model\Condition\AbstractCondition $conditions
     * @param string $formName
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function setConditionFormName(\Magento\Rule\Model\Condition\AbstractCondition $conditions, $formName)
    {
        $conditions->setFormName($formName);
        if (is_array($conditions->getConditions())) {
            foreach ($conditions->getConditions() as $condition) {
                $this->setConditionFormName($condition, $formName);
            }
        }
    }
}
