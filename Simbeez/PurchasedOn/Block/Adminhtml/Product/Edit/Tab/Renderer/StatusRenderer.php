<?php
 
namespace Simbeez\PurchasedOn\Block\Adminhtml\Product\Edit\Tab\Renderer;
 
use Magento\Backend\Block\Context;
use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Framework\Registry;
 
class StatusRenderer extends AbstractRenderer
{
    /**
     * Manufacturer constructor.
     * @param AttributeFactory $attributeFactory
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Context $context,
        array $data = array()
    )
    {
        parent::__construct($context, $data);
    }
 
    /**
     * Renders grid column
     *
     * @param \Magento\Framework\DataObject $row
     * @return mixed
     */
    public function _getValue(\Magento\Framework\DataObject $row)
    {
        $style = 'display: block;padding: 5px;text-align: center;';
        $value = parent::_getValue($row);
        if($value == 'complete'): 
            return '<span style="background-color: green; color: white;'.$style.'">'.$value.'</span>';
        elseif($value == 'processing'): 
            return '<span style="background-color: orange; color: white;'.$style.'">'.$value.'</span>';
        elseif($value == 'canceled' || 'closed'): 
            return '<span style="background-color: red; color: white;'.$style.'">'.$value.'</span>';
        endif;

    }
}