<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MageWorx\LocationPages\Block\Customer;

use Magento\Catalog\Model\Product\Image\UrlBuilder;
use Magento\Framework\View\ConfigInterface;

class Sidebar extends \Magento\Wishlist\Block\Customer\Sidebar
{
    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager = null;

    /**
     * Sidebar constructor.
     *
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param array $data
     * @param ConfigInterface|null $config
     * @param UrlBuilder|null $urlBuilder
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        array $data = [],
        ConfigInterface $config = null,
        UrlBuilder $urlBuilder = null
    ) {
        $this->objectManager = $objectManager;

        if (class_exists('\Magento\Wishlist\ViewModel\WishlistData')) {
            $data['wishlistDataViewModel'] = $this->objectManager->create('\Magento\Wishlist\ViewModel\WishlistData');
        }
        parent::__construct($context, $httpContext, $data, $config, $urlBuilder);
    }
}
