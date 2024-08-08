<?php

class PrestaShopFeedTypes
{
    public static function getTypes()
    {
        return array(
            'product' => 'Product',
            'category' => 'Category',
            'manufacturer' => 'Manufacturer',
            'supplier' => 'Supplier',
            'combination' => 'Combination',
            'attribute' => 'Attribute',
            'attribute_group' => 'Attribute Group',
            'feature' => 'Feature',
            'feature_value' => 'Feature Value',
            'customer' => 'Customer',
            'address' => 'Address',
            'order' => 'Order',
            'order_detail' => 'Order Detail',
            'order_state' => 'Order State',
            'tax_rules' => 'Tax Rules',
            'zone' => 'Zone',
            'currency' => 'Currency',
            'country' => 'Country',
            'state' => 'State',
            'warehouse' => 'Warehouse',
            'stock_movement' => 'Stock Movement',
            'supply_order' => 'Supply Order',
            'carrier' => 'Carrier',
            'shop' => 'Shop',
            'shop_group' => 'Shop Group',
            'cms' => 'CMS',
            'cms_category' => 'CMS Category',
            'tag' => 'Tag',
            'attachment' => 'Attachment',
        );
    }
}
?>
