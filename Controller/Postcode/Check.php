<?php

namespace MagePrashant\CheckDelivery\Controller\Postcode;

use MagePrashant\CheckDelivery\Helper\Data as DataHelper;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;

/**
 * Class Check
 * @package MagePrashant\CheckDelivery\Controller\Postcode
 */
class Check extends Action
{
    /**
     * @var ProductModel
     */
    protected $_productModel;
    /**
     * @var DataHelper
     */
    protected $_helper;

    /**
     * Check constructor.
     * @param Context $context
     * @param ProductFactory $productFactory
     * @param DataHelper $dataHelper
     */
    public function __construct(
        Context $context,
        ProductFactory $productFactory,
        DataHelper $dataHelper
    ) {
        parent::__construct($context);
        $this->productFactory = $productFactory;
        $this->dataHelper = $dataHelper;
    }

    /**
     *
     */
    public function execute()
    {
        $response = [];
        try {
            if (!$this->getRequest()->isAjax()) {
                throw new \Exception('Invalid request.');
            }
            if (!$postcode = $this->getRequest()->getParam('postcode')) {
                throw new \Exception('Please enter postcode.');
            }

            $productId = $this->getRequest()->getParam('id', 0);
            $product = $this->productFactory->create()->load($productId);

            if (!$product->getId()) {
                throw new \Exception('Product not found.');
            }
            $postcodes = trim($product->getMageCheckDeliveryPostcodes());
            if (!$postcodes) {
                $postcodes = $this->dataHelper->getPostcodes();
            }
            $postcodes = array_map('trim', explode(',', $postcodes));
            if (in_array($postcode, $postcodes)) {
                $response['type'] = 'success';
                $response['message'] = __($this->dataHelper->getSuccessMessage(), $postcode);
            } else {
                $response['type'] = 'error';
                $response['message'] = __($this->dataHelper->getErrorMessage(), $postcode);
            }
        } catch (\Exception $e) {
            $response['type'] = 'error';
            $response['message'] = $e->getMessage();
        }
        $this->getResponse()->setContent(json_encode($response));
    }
}
