<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\StoreLocator\Setup\Patch\Data;

use Magento\Cms\Model\Page;
use Magento\Cms\Model\PageRepository;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Store\Model\Store;

class CreateDefaultPage implements DataPatchInterface, PatchVersionInterface
{
    /**
     * @var Page
     */
    protected $pageModel;

    /**
     * @var \Magento\Cms\Model\PageFactory
     */
    protected $pageFactory;

    /**
     * @var ModuleDataSetupInterface
     */
    protected $moduleDataSetup;

    /**
     * @var PageRepository
     */
    protected $pageRepository;

    public function __construct(
        \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup,
        \Magento\Cms\Model\PageFactory $pageFactory,
        \Magento\Cms\Model\PageRepository $pageRepository,
        Page $pageModel
    ) {
        $this->pageFactory     = $pageFactory;
        $this->moduleDataSetup = $moduleDataSetup;
        $this->pageRepository  = $pageRepository;
        $this->pageModel       = $pageModel;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        if (!$this->pageModel->checkIdentifier('mw-store-locator', Store::DEFAULT_STORE_ID)) {
            $cmsPageData = [
                'title'           => __('Store locator'),
                'page_layout'     => '1column',
                'identifier'      => 'mw-store-locator',
                'content_heading' => 'Store locator',
                'content'         => '{{block class="MageWorx\StoreLocator\Block\MainPage"}}',
                'is_active'       => 1,
                'stores'          => [0],
                'sort_order'      => 0
            ];

            $page = $this->pageFactory->create()->setData($cmsPageData);

            $this->pageRepository->save($page);
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public static function getVersion()
    {
        return '1.0.0';
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }
}
