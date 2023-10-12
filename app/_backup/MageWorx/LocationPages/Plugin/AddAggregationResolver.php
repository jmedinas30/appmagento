<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\LocationPages\Plugin;

use Magento\Framework\Search\RequestInterface;

class AddAggregationResolver
{
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $httpRequest;

    /**
     * AddAggregationResolverPlugin constructor.
     *
     * @param \Magento\Framework\App\Request\Http $httpRequest
     */
    public function __construct(
        \Magento\Framework\App\Request\Http $httpRequest
    ) {
        $this->httpRequest = $httpRequest;
    }

    /**
     * @param \Magento\Framework\Search\Adapter\Aggregation\AggregationResolver $subject
     * @param callable $proceed
     * @param RequestInterface $request
     * @param string[] $documentIds
     * @return mixed
     */
    public function aroundResolve(
        \Magento\Framework\Search\Adapter\Aggregation\AggregationResolver $subject,
        callable $proceed,
        RequestInterface $request,
        array $documentIds
    ) {
        if ($this->httpRequest->getFullActionName() == 'mageworx_locationpages_location_view') {
            return $request->getAggregation();
        }

        return $proceed($request, $documentIds);
    }
}
