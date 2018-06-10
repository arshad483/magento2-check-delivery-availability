<?php

namespace MagePrashant\CheckDelivery\Block\Product\View;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use MagePrashant\CheckDelivery\Helper\Data as DataHelper;

/**
 * Class CheckDelivery
 * @package MagePrashant\CheckDelivery\Block\Product\View
 */
class CheckDelivery extends Template
{
    /**
     * @var Registry
     */
    protected $_registry;

    /**
     * CheckDelivery constructor.
     * @param Context $context
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        DataHelper $dataHelper,
        $data = []
    ) {
        parent::__construct($context, $data);
        $this->_registry = $registry;
        $this->dataHelper = $dataHelper;
    }

    /**
     * @return mixed
     */
    public function getCurrentProduct()
    {
        return $this->_registry->registry('current_product');
    }
	
	public function getIsActive()
	{
		return $this->dataHelper->getIsActive();
	}
}
