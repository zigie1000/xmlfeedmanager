<?php

class PrestaShopFeedFields
{
    public static function getFields($type)
    {
        $fields = array();

        if ($type == 'product') {
            $fields = array(
                'product_id' => 'Product ID',
                'active' => 'Active (0/1)',
                'name' => 'Name *',
                'categories' => 'Categories (x,y,z...)',
                'price_tax_excluded' => 'Price tax excluded',
                'tax_rules_id' => 'Tax rules ID',
                'wholesale_price' => 'Wholesale price',
                'on_sale' => 'On sale (0/1)',
                'discount_amount' => 'Discount amount',
                'discount_percent' => 'Discount percent',
                'discount_from' => 'Discount from (yyyy-mm-dd)',
                'discount_to' => 'Discount to (yyyy-mm-dd)',
                'reference' => 'Reference #',
                'supplier_reference' => 'Supplier reference #',
                'supplier' => 'Supplier',
                'manufacturer' => 'Manufacturer',
                'ean13' => 'EAN13',
                'upc' => 'UPC',
                'ecotax' => 'Ecotax',
                'width' => 'Width',
                'height' => 'Height',
                'depth' => 'Depth',
                'weight' => 'Weight',
                'delivery_time_in_stock' => 'Delivery time of in-stock products',
                'delivery_time_out_stock' => 'Delivery time of out-of-stock products with allowed orders',
                'quantity' => 'Quantity',
                'minimal_quantity' => 'Minimal quantity',
                'low_stock_level' => 'Low stock level',
                'receive_low_stock_alert' => 'Receive a low stock alert by email',
                'visibility' => 'Visibility',
                'additional_shipping_cost' => 'Additional shipping cost',
                'unity' => 'Unity',
                'unit_price' => 'Unit price',
                'summary' => 'Summary',
                'description' => 'Description',
                'tags' => 'Tags (x,y,z...)',
                'meta_title' => 'Meta title',
                'meta_keywords' => 'Meta keywords',
                'meta_description' => 'Meta description',
                'url_rewritten' => 'URL rewritten',
                'text_in_stock' => 'Text when in stock',
                'text_backorder' => 'Text when backorder allowed',
                'available_for_order' => 'Available for order (0 = No, 1 = Yes)',
                'product_available_date' => 'Product available date',
                'product_creation_date' => 'Product creation date',
                'show_price' => 'Show price (0 = No, 1 = Yes)',
                'image_urls' => 'Image URLs (x,y,z...)',
                'image_alt_texts' => 'Image alt texts (x,y,z...)',
                'delete_existing_images' => 'Delete existing images (0 = No, 1 = Yes)',
                'feature' => 'Feature(Name:Value:Position)',
                'available_online_only' => 'Available online only (0 = No, 1 = Yes)',
                'condition' => 'Condition',
                'customizable' => 'Customizable (0 = No, 1 = Yes)',
                'uploadable_files' => 'Uploadable files (0 = No, 1 = Yes)',
                'text_fields' => 'Text fields (0 = No, 1 = Yes)',
                'out_of_stock_action' => 'Out of stock action',
                'virtual_product' => 'Virtual product',
                'file_url' => 'File URL',
                'number_of_allowed_downloads' => 'Number of allowed downloads',
                'expiration_date' => 'Expiration date',
                'number_of_days' => 'Number of days',
                'id_shop' => 'ID / Name of shop',
                'advanced_stock_management' => 'Advanced stock management',
                'depends_on_stock' => 'Depends On Stock',
                'warehouse' => 'Warehouse',
                'accessories' => 'Accessories  (x,y,z...)'
            );
        }

        return $fields;
    }
}
