<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Simbeez\PurchasedOn\Ui\Component\Listing\Column\Product;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Framework\App\ResourceConnection;

class OnOrders extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * @var ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param ResourceConnection $resourceConnection
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        ResourceConnection $resourceConnection,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {     

        if (isset($dataSource['data']['items'])) {

            $fieldName = $this->getData('name');
            
            foreach ($dataSource['data']['items'] as & $item) {
                $item[$fieldName] = (int) $this->getOnorderCount($item['entity_id']);
            }
        }
        return $dataSource;
    }

    private function getOnorderCount($product_id) {
        $sales_order_item = $this->resourceConnection->getTableName('sales_order_item');
        $sales_order = $this->resourceConnection->getTableName('sales_order');
        $connection = $this->resourceConnection->getConnection();
        return $connection->fetchOne("SELECT SUM(soi.qty_ordered - soi.qty_refunded) as on_order from $sales_order_item as soi LEFT JOIN $sales_order as so on (so.entity_id = soi.order_id) WHERE so.status like 'processing' and soi.product_id = $product_id");
    }
}