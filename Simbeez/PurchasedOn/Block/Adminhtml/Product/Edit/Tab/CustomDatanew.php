<?php

namespace Simbeez\PurchasedOn\Block\Adminhtml\Product\Edit\Tab;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Registry;
use Magento\Framework\App\ResourceConnection;

class CustomDatanew extends \Magento\Backend\Block\Widget\Container
{
    
    protected $_template = 'purchasedon.phtml';

    protected $_coreRegistry;

    protected $_resourcecollection;

    public function __construct(
        Context $context,
        Registry $registry,
        ResourceConnection $resourcecollection,
        array $data = []
    )
    {
        $this->_coreRegistry = $registry;
        $this->_resourcecollection = $resourcecollection;
        parent::__construct($context, $data);
        
    }

    /**
     * @return string
     */
    public function getGridHtml()
    {
        return $this->getLayout()->createBlock('Simbeez\PurchasedOn\Block\Adminhtml\Product\Edit\Tab\Grid')->toHtml();
    }
    public function getConnection(){

        return $this->_resourcecollection->getConnection();

    }
    public function getTableName(String $tablename){

        return $this->_resourcecollection->getTableName($tablename);

    }
    public function getSum(){

        $sum = $this->getSumOrdered() - $this->getSumRefunded() - $this->getSumShipped();

        return (int)$sum;

    }
    public function getSumOrdered(){

        $productId = $this->getProductId();
        if(!$productId) {
            return 0;
        }
        $mgsales_order_item = $this->getTableName('sales_order_item');
        $mgsales_order = $this->getTableName('sales_order');

        $sum_ordered_sql = "select SUM(qty_ordered) FROM ".$mgsales_order_item." JOIN ".$mgsales_order." ON ".$mgsales_order_item.".order_id=".$mgsales_order.".entity_id WHERE ".$mgsales_order.".status = 'processing' and product_id = ".$productId;

        $sum_ordered = $this->getConnection()->fetchOne($sum_ordered_sql);

        return (int)$sum_ordered;

    }
    public function getSumRefunded(){

        $productId = $this->getProductId();
        
        if(!$productId) {
            return 0;
        }

        $mgsales_order_item = $this->getTableName('sales_order_item');
        $mgsales_order = $this->getTableName('sales_order');

        $sum_refunded_sql = "select SUM(qty_refunded) FROM ".$mgsales_order_item." JOIN ".$mgsales_order." ON ".$mgsales_order_item.".order_id=".$mgsales_order.".entity_id WHERE ".$mgsales_order.".status = 'processing' and product_id = ".$productId;


        $sum_refunded = $this->getConnection()->fetchOne($sum_refunded_sql);

        return (int)$sum_refunded;

    }
    public function getSumShipped(){

        $productId = $this->getProductId();

        if(!$productId) {
            return 0;
        }

        $mgsales_order_item = $this->getTableName('sales_order_item');
        $mgsales_order = $this->getTableName('sales_order');

        $sum_shipped_sql = "select SUM(qty_shipped) FROM ".$mgsales_order_item." JOIN ".$mgsales_order." ON ".$mgsales_order_item.".order_id=".$mgsales_order.".entity_id WHERE ".$mgsales_order.".status = 'processing' and product_id = ".$productId;

        $sum_shipped = $this->getConnection()->fetchOne($sum_shipped_sql);

        return (int)$sum_shipped;

    }
    public function getCompleteOrderTotalItemQty(){

        $productId = $this->getProductId();

        if(!$productId) {
            return 0;
        }

        $mgsales_order_item = $this->getTableName('sales_order_item');
        $mgsales_order = $this->getTableName('sales_order');

        $complete_order_total_item_qty_sql = "select SUM(qty_shipped) FROM ".$mgsales_order_item." JOIN ".$mgsales_order." ON ".$mgsales_order_item.".order_id=".$mgsales_order.".entity_id WHERE ".$mgsales_order.".status = 'complete' and product_id = ".$productId;

        $complete_order_total_item_qty = $this->getConnection()->fetchOne($complete_order_total_item_qty_sql);

        return (int)$complete_order_total_item_qty;

    }
    public function getCloseOrderTotalItemQty(){

        $productId = $this->getProductId();

        if(!$productId) {
            return 0;
        }

        $mgsales_order_item = $this->getTableName('sales_order_item');
        $mgsales_order = $this->getTableName('sales_order');

        $getQtyOrdered_sql = "select SUM(qty_ordered) FROM ".$mgsales_order_item." JOIN ".$mgsales_order." ON ".$mgsales_order_item.".order_id=".$mgsales_order.".entity_id WHERE ".$mgsales_order.".status = 'closed' and product_id = ".$productId;

        $getQtyShipped_sql = "select SUM(qty_shipped) FROM ".$mgsales_order_item." JOIN ".$mgsales_order." ON ".$mgsales_order_item.".order_id=".$mgsales_order.".entity_id WHERE ".$mgsales_order.".status = 'closed' and product_id = ".$productId;

        $getQtyOrdered = $this->getConnection()->fetchOne($getQtyOrdered_sql);

        $getQtyShipped = $this->getConnection()->fetchOne($getQtyShipped_sql);

        $close_order_total_item_qty = (int)$getQtyOrdered - (int)$getQtyShipped;

        return (int)$close_order_total_item_qty;

    }

    public function getQtyBasedOnOrderStatus() {
        $productId = $this->getProductId();

        if(!$productId) {
            return 0;
        }

        $sales_order_item = $this->getTableName('sales_order_item');
        $sales_order = $this->getTableName('sales_order');
        
        $sql = "SELECT so.status, count(so.entity_id) AS total_orders, SUM(soi.qty_ordered) AS total_qty FROM $sales_order_item AS soi JOIN $sales_order AS so ON soi.order_id = so.entity_id WHERE soi.product_id = $productId GROUP BY so.status order by so.status desc";

        return $this->getConnection()->fetchAll($sql);
    }

    public function getProductId() {
        return $this->_coreRegistry->registry('current_product')->getId();
    }
}