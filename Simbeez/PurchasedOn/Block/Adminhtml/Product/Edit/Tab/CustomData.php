<?php

namespace Simbeez\PurchasedOn\Block\Adminhtml\Product\Edit\Tab;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Sales\Block\Adminhtml\Order\View\Info;


class CustomData extends \Magento\Framework\View\Element\Template
{
    
    protected $_template = 'purchasedon.phtml';

    protected $_coreRegistry;

    /**
     * Column name
     */
    protected $orderCollectionFactory;

    protected $viewUrl;

    public function __construct(
        Context $context,
        Registry $registry,
        CollectionFactory $orderCollectionFactory,
        Info $viewUrl,
        array $data = []
    )
    {
        $this->_coreRegistry = $registry;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->viewUrl=$viewUrl;
        parent::__construct($context, $data);
        
    }

    public function getProductOrders(){
        $orderCollection = $this->orderCollectionFactory->create();
        // $orderCollection->addFieldToFilter('status','processing');
        $orderCollection->getSelect()
                 ->join(
                    'mgsales_order_item',
                    'main_table.entity_id = mgsales_order_item.order_id'
                 )->where('product_id = ?',$this->getProduct()->getId());

        $orderCollection->getSelect()->group('main_table.entity_id');
        return $orderCollection;
    }

    public function getProductOrdersUrl(){
        return $this->viewUrl;
    }

    public function getProduct()
    {
        return $this->_coreRegistry->registry('current_product');
    }
}