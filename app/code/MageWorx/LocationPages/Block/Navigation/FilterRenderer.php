<?php
/**
 * Location page layer filter renderer
 *
 * Copyright © Mageworx, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorx\LocationPages\Block\Navigation;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\View\Element\Template;
use MageWorx\Locations\Helper\VersionResolver;

class FilterRenderer extends \Magento\LayeredNavigation\Block\Navigation\FilterRenderer
{
   public function __construct(
       VersionResolver $versionResolver,
       Template\Context $context,
       array $data = []
   ) {
       parent::__construct($context, $data);

       if ($versionResolver->checkModuleVersion('Magento_LayeredNavigation', '100.4.1')) {
           $this->setData(
               'product_layer_view_model',
               ObjectManager::getInstance()->get(\Magento\LayeredNavigation\ViewModel\Layer\Filter::class)
           );
       }
   }
}
