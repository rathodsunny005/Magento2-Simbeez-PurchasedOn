<?php

namespace Simbeez\PurchasedOn\Block\Adminhtml\Product\Edit\Tab;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Sales\Block\Adminhtml\Order\View\Info;


class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    
    protected $_status;
    /**
     * @var \Rh\Blog\Model\BlogFactory
     */
    protected $_blogFactory;
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

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
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Framework\Module\Manager $moduleManager,
        array $data = []
    )
    {
        $this->_coreRegistry = $registry;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->viewUrl=$viewUrl;
        $this->moduleManager = $moduleManager;
        parent::__construct($context,$backendHelper, $data);
        
    }

    protected function _construct()
    {
        parent::_construct();
    }

    public function _prepareCollection(){

        $orderCollection = $this->getProductOrders();
        $this->setCollection($orderCollection);
        parent::_prepareCollection();
        return $this;

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
    protected function _prepareColumns()
    {
        $this->addColumn(
            'id',
            [
                'header' => __('ID'),
                'type' => 'text',
                'index' => 'increment_id'
            ]
        );
        $this->addColumn(
            'purchase_date',
            [
                'header' => __('Purchase Date'),
                'type' => 'date',
                'index' => 'created_at'
            ]
        );
        $this->addColumn(
            'bill_to_name',
            [
                'header' => __('Bill-to Name'),
                'type' => 'concat',
                'separator' => ' ',
                'index' => array('customer_firstname','customer_lastname'),
                'filter' => false
            ]
        );
        $this->addColumn(
            'ship_to_name',
            [
                'header' => __('Ship-to Name'),
                'type' => 'concat',
                'separator' => ' ',
                'index' => array('customer_firstname','customer_lastname'),
                'filter' => false
            ]
        );
        $this->addColumn(
            'base_grand_total',
            [
                'header' => __('Grand Total (Base)'),
                'type' => 'currency',
                'index' => 'base_grand_total'
            ]
        );
        $this->addColumn(
            'grand_total',
            [
                'header' => __('Grand Total (Purchased)'),
                'type' => 'currency',
                'index' => 'grand_total'
            ]
        );
        $this->addColumn(
            'qty_refunded',
            [
                'header' => __('Refunded Qty'),
                'type' => 'number',
                'index' => 'qty_refunded'
            ]
        );
        $this->addColumn(
            'qty_shipped',
            [
                'header' => __('Shipped Qty'),
                'type' => 'number',
                'index' => 'qty_shipped'
            ]
        );
        $this->addColumn(
            'qty_ordered',
            [
                'header' => __('On Order'),
                'type' => 'number',
                'index' => 'qty_ordered'
            ]
        );
        $this->addColumn(
            'status',
            [
                'header' => __('Status'),
                'type' => 'text',
                'index' => 'status',
                'renderer' => 'Simbeez\PurchasedOn\Block\Adminhtml\Product\Edit\Tab\Renderer\StatusRenderer'
            ]
        );
        $this->addColumn(
            'view',
            [
                'header' => __('View'),
                'type' => 'action',
                'getter' => 'getEntityId',
                'actions' => [
                    [
                        'caption' => __('View'),
                        'url' => [
                            'base' => 'sales/order/view',
                        ],
                        'field' => 'order_id',
                    ],
                ],
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action',
            ]
        );
        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }
        return parent::_prepareColumns();
    }

    public function getProductOrdersUrl(){
        return $this->viewUrl;
    }

    public function getProduct()
    {
        return $this->_coreRegistry->registry('current_product');
    }
}