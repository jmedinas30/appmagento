<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_LayeredNavigation
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\LayeredNavigation\Plugin\PageCache;

use Magento\Framework\App\PageCache\Kernel;
use Magento\Framework\App\Request\Http;
use Mageplaza\LayeredNavigation\Helper\Data;

/**
 * Class ProcessRequest
 * @package Mageplaza\LayeredNavigation\Plugin\PageCache
 */
class ProcessRequest
{
    /**
     * @var Http
     */
    protected $request;

    /**
     * @var Data
     */
    protected $helperData;

    /**
     * ProcessRequest constructor.
     *
     * @param Http $request
     * @param Data $helperData
     */
    public function __construct(
        Http $request,
        Data $helperData
    ) {
        $this->request = $request;
        $this->helperData = $helperData;
    }

    /**
     * @param Kernel $subject
     */
    public function beforeLoad(Kernel $subject)
    {
        if ($this->helperData->isEnabled() &&
            ($this->request->getFullActionName() === 'catalog_category_view') || $this->request->getFullActionName() === 'catalogsearch_result_index') {
            $this->request->setMethod('GET');
        }
    }
}
