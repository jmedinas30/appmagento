<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\LocationPages\Plugin;

class AddViewActionPlugin extends \Magento\Ui\Component\Listing\Columns\Column
{

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;
    /**
     * Url path  to view
     *
     * @var string
     */
    const URL_PATH_VIEW = 'mageworx_locationpages/page/view';

    /**
     * Constructor
     *
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param string[] $components
     * @param string[] $data
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
     * @param \MageWorx\Locations\Ui\Component\Listing\Column\LocationActions $subject
     * @param string[] $dataSource
     * @return string[]
     */
    public function afterPrepareDataSource(
        \MageWorx\Locations\Ui\Component\Listing\Column\LocationActions $subject,
        array $dataSource
    ) {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['entity_id'])) {
                    $item[$subject->getData('name')]['view'] = [
                        'href'   => $this->urlBuilder->getUrl(
                            static::URL_PATH_VIEW,
                            [
                                'entity_id' => $item['entity_id']
                            ]
                        ),
                        'label'  => __('View'),
                        'target' => '_blank'
                    ];
                }
            }
        }

        return $dataSource;
    }
}
