<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\LocationPages\Plugin;

use Magento\Framework\Search\RequestInterface;
use Magento\Framework\Search\Adapter\Mysql\AdapterFactory;
use MageWorx\Locations\Helper\VersionResolver;

class UseMysqlForLocationPagePlugin
{
    /**
     * Request object
     *
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;

    /**
     * @var VersionResolver
     */
    private $versionResolver;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * UseMysqlForLocationPagePlugin constructor.
     *
     * @param VersionResolver $versionResolver
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(
        VersionResolver $versionResolver,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->versionResolver = $versionResolver;
        $this->objectManager   = $objectManager;
        $this->request         = $request;
    }

    /**
     * @param \Magento\Search\Model\SearchEngine $subject
     * @param callable $proceed
     * @param RequestInterface $request
     * @return mixed
     */
    public function aroundSearch(
        \Magento\Search\Model\SearchEngine $subject,
        callable $proceed,
        RequestInterface $request
    ) {
        if ($this->request->getFullActionName() !== 'mageworx_locationpages_location_view') {
            return $proceed($request);
        }

        if ($this->versionResolver->checkModuleVersion('Mirasvit_SearchMysql', '1.0.39', '2.0.0')) {
            $adapter = $this->objectManager->create(\Mirasvit\SearchMysql\Adapter\AdapterFactory::class)
                                           ->create();

            return $adapter->query($request);

        }
        if ($this->versionResolver->checkModuleVersion('Mirasvit_SearchMysql', '2.0.26')) {
            $adapter = $this->objectManager->create(\Mirasvit\SearchMysql\SearchAdapter\AdapterFactory::class)
                                           ->create();

            return $adapter->query($request);

        }
        //Check Magento CE > 2.3.3
        if ($this->versionResolver->checkModuleVersion('Magento_Elasticsearch', '100.3.2')) {
            return $proceed($request);
        }

        $adapter = $this->objectManager->create(AdapterFactory::class)
                                       ->create();

        return $adapter->query($request);
    }
}
