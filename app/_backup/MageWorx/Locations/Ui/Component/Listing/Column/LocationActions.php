<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Ui\Component\Listing\Column;

class LocationActions extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * Url path  to delete
     *
     * @var string
     */
    const URL_PATH_DELETE = 'mageworx_locations/location/delete';

    /**
     * Url path  to edit
     *
     * @var string
     */
    const URL_PATH_EDIT = 'mageworx_locations/location/edit';

    /**
     * Url path  to set active
     *
     * @var string
     */
    const URL_PATH_ENABLE = 'mageworx_locations/location/enable';

    /**
     * Url path  to set inactive
     *
     * @var string
     */
    const URL_PATH_DISABLE = 'mageworx_locations/location/disable';

    /**
     * Constructor
     *
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {

        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['entity_id'])) {
                    $item[$this->getData('name')] = [
                        'edit'    => [
                            'href'  => $this->urlBuilder->getUrl(
                                static::URL_PATH_EDIT,
                                [
                                    'entity_id' => $item['entity_id']
                                ]
                            ),
                            'label' => __('Edit')
                        ],
                        'enable'  => [
                            'href'  => $this->urlBuilder->getUrl(
                                static::URL_PATH_ENABLE,
                                [
                                    'entity_id' => $item['entity_id']
                                ]
                            ),
                            'label' => __('Enable')
                        ],
                        'disable' => [
                            'href'  => $this->urlBuilder->getUrl(
                                static::URL_PATH_DISABLE,
                                [
                                    'entity_id' => $item['entity_id']
                                ]
                            ),
                            'label' => __('Disable')
                        ],
                        'delete'  => [
                            'href'    => $this->urlBuilder->getUrl(
                                static::URL_PATH_DELETE,
                                [
                                    'entity_id' => $item['entity_id']
                                ]
                            ),
                            'label'   => __('Delete'),
                            'confirm' => [
                                'title'   => __('Delete "${ $.$data.name}"'),
                                'message' => $this->getDeleteMessage()
                            ]
                        ]
                    ];
                }
            }
        }

        return $dataSource;
    }

    /**
     * @return \Magento\Framework\Phrase|string
     */
    protected function getDeleteMessage()
    {
        return __('Are you sure you want to delete store?');
    }
}
