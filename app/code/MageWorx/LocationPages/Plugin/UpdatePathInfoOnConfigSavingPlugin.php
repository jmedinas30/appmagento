<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\LocationPages\Plugin;

use MageWorx\LocationPages\Api\LocationListRepositoryInterface;
use MageWorx\LocationPages\Helper\Data as Helper;
use MageWorx\Locations\Api\LocationRepositoryInterface;

/**
 * Class ConfigPlugin
 */
class UpdatePathInfoOnConfigSavingPlugin
{
    /**
     * @var LocationListRepositoryInterface
     */
    protected $locationListRepository;

    /**
     * @var LocationRepositoryInterface
     */
    protected $locationRepository;

    /**
     * @var Helper
     */
    protected $helper;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var bool
     */
    protected $needUpdate = false;

    /**
     * ConfigPlugin constructor.
     *
     * @param Helper $helper
     * @param \Magento\Framework\App\RequestInterface $request
     * @param LocationListRepositoryInterface $locationListRepository
     * @param LocationRepositoryInterface $locationRepository
     */
    public function __construct(
        Helper $helper,
        \Magento\Framework\App\RequestInterface $request,
        LocationListRepositoryInterface $locationListRepository,
        LocationRepositoryInterface $locationRepository
    ) {
        $this->helper                 = $helper;
        $this->request                = $request;
        $this->locationListRepository = $locationListRepository;
        $this->locationRepository     = $locationRepository;
    }

    /**
     * @param \Magento\Config\Model\Config $subject
     * @return string[]
     */
    public function beforeSave(\Magento\Config\Model\Config $subject)
    {
        if ($this->request->getParam('section') !== 'mageworx_locations') {
            return [];
        }

        $groups = $this->request->getParam('groups');

        if (!isset($groups['pages']) || !isset($groups['pages']) || !isset($groups['pages'])) {
            return [];
        }

        if (!isset($groups['pages']['fields']['base_path']['value']) ||
            (!isset($groups['pages']['fields']['url_path']['value']) &&
                !isset($groups['pages']['fields']['url_key']['value']))
        ) {
            return [];
        }

        $newBasePath = $groups['pages']['fields']['base_path']['value'];
        $newUrlPath  = $groups['pages']['fields']['url_path']['value'] ?? [];
        $newUrlKey   = $groups['pages']['fields']['url_key']['value'];
        $oldBasePath = $this->helper->getBasePath($this->request->getParam('store'));
        $oldUrlPath  = $this->helper->getUrlPathParts($this->request->getParam('store'));
        $oldUrlKey   = $this->helper->getUrlKeyParts($this->request->getParam('store'));

        if ($newBasePath == $oldBasePath && $newUrlPath == $oldUrlPath && $newUrlKey == $oldUrlKey) {
            return [];
        }

        $this->needUpdate = true;

        return [];
    }

    /**
     * @param \Magento\Config\Model\Config $subject
     * @param \Magento\Config\Model\Config $result
     * @return \Magento\Config\Model\Config
     */
    public function afterSave(\Magento\Config\Model\Config $subject, $result)
    {
        if (!$this->needUpdate) {
            return $result;
        }

        $pathInfo = $this->locationListRepository->updateLocationListCollection();

        if (!empty($pathInfo)) {
            $this->locationRepository->updatePathInfo($pathInfo);
        }

        return $result;
    }
}
