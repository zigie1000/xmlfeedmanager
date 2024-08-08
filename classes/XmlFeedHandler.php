<?php

class XmlFeedHandler
{
    public static function importProducts($xmlFilePath, $ignoreFields = array())
    {
        if (!file_exists($xmlFilePath)) {
            throw new Exception("XML file does not exist: " . $xmlFilePath);
        }

        libxml_use_internal_errors(true);                
        $xml = simplexml_load_file($xmlFilePath);

        if ($xml === false) {
            $errors = libxml_get_errors();
            foreach ($errors as $error) {
                error_log($error->message);
            }
            throw new Exception("Failed to parse XML file.");
        }

        foreach ($xml->product as $product) {
            self::importProduct($product, $ignoreFields);
        }
    }

    private static function importProduct($product, $ignoreFields)
    {
        // Map XML fields to Prestashop fields
        $productData = array(
            'id_product' => self::getFieldValue($product, 'id', $ignoreFields),
            'active' => self::getFieldValue($product, 'active', $ignoreFields),
            'name' => self::getFieldValue($product, 'name', $ignoreFields),
            'id_category_default' => self::getFieldValue($product, 'category_default', $ignoreFields),
            'price' => self::getFieldValue($product, 'price', $ignoreFields),
            'id_tax_rules_group' => self::getFieldValue($product, 'tax_rules_group', $ignoreFields),
            'wholesale_price' => self::getFieldValue($product, 'wholesale_price', $ignoreFields),
            'on_sale' => self::getFieldValue($product, 'on_sale', $ignoreFields),
            'discount_amount' => self::getFieldValue($product, 'discount_amount', $ignoreFields),
            'discount_percent' => self::getFieldValue($product, 'discount_percent', $ignoreFields),
            'discount_from' => self::getFieldValue($product, 'discount_from', $ignoreFields),
            'discount_to' => self::getFieldValue($product, 'discount_to', $ignoreFields),
            'reference' => self::getFieldValue($product, 'reference', $ignoreFields),
            'supplier_reference' => self::getFieldValue($product, 'supplier_reference', $ignoreFields),
            'supplier' => self::getFieldValue($product, 'supplier', $ignoreFields),
            'manufacturer' => self::getFieldValue($product, 'manufacturer', $ignoreFields),
            'ean13' => self::getFieldValue($product, 'ean13', $ignoreFields),
            'upc' => self::getFieldValue($product, 'upc', $ignoreFields),
            'ecotax' => self::getFieldValue($product, 'ecotax', $ignoreFields),
            'width' => self::getFieldValue($product, 'width', $ignoreFields),
            'height' => self::getFieldValue($product, 'height', $ignoreFields),
            'depth' => self::getFieldValue($product, 'depth', $ignoreFields),
            'weight' => self::getFieldValue($product, 'weight', $ignoreFields),
            'quantity' => self::getFieldValue($product, 'quantity', $ignoreFields),
            'minimal_quantity' => self::getFieldValue($product, 'minimal_quantity', $ignoreFields),
            'low_stock_level' => self::getFieldValue($product, 'low_stock_level', $ignoreFields),
            'visibility' => self::getFieldValue($product, 'visibility', $ignoreFields),
            'additional_shipping_cost' => self::getFieldValue($product, 'additional_shipping_cost', $ignoreFields),
            'unity' => self::getFieldValue($product, 'unity', $ignoreFields),
            'unit_price' => self::getFieldValue($product, 'unit_price', $ignoreFields),
            'summary' => self::getFieldValue($product, 'summary', $ignoreFields),
            'description' => self::getFieldValue($product, 'description', $ignoreFields),
            'tags' => self::getFieldValue($product, 'tags', $ignoreFields),
            'meta_title' => self::getFieldValue($product, 'meta_title', $ignoreFields),
            'meta_keywords' => self::getFieldValue($product, 'meta_keywords', $ignoreFields),
            'meta_description' => self::getFieldValue($product, 'meta_description', $ignoreFields),
            'url_rewritten' => self::getFieldValue($product, 'url_rewritten', $ignoreFields),
            'text_when_in_stock' => self::getFieldValue($product, 'text_when_in_stock', $ignoreFields),
            'text_when_backorder_allowed' => self::getFieldValue($product, 'text_when_backorder_allowed', $ignoreFields),
            'available_for_order' => self::getFieldValue($product, 'available_for_order', $ignoreFields),
            'product_available_date' => self::getFieldValue($product, 'product_available_date', $ignoreFields),
            'product_creation_date' => self::getFieldValue($product, 'product_creation_date', $ignoreFields),
            'show_price' => self::getFieldValue($product, 'show_price', $ignoreFields),
            'image_urls' => self::getFieldValue($product, 'image_urls', $ignoreFields),
            'image_alt_texts' => self::getFieldValue($product, 'image_alt_texts', $ignoreFields),
            'delete_existing_images' => self::getFieldValue($product, 'delete_existing_images', $ignoreFields),
            'feature' => self::getFieldValue($product, 'feature', $ignoreFields),
            'available_online_only' => self::getFieldValue($product, 'available_online_only', $ignoreFields),
            'condition' => self::getFieldValue($product, 'condition', $ignoreFields),
            'customizable' => self::getFieldValue($product, 'customizable', $ignoreFields),
            'uploadable_files' => self::getFieldValue($product, 'uploadable_files', $ignoreFields),
            'text_fields' => self::getFieldValue($product, 'text_fields', $ignoreFields),
            'out_of_stock_action' => self::getFieldValue($product, 'out_of_stock_action', $ignoreFields),
            'virtual_product' => self::getFieldValue($product, 'virtual_product', $ignoreFields),
            'file_url' => self::getFieldValue($product, 'file_url', $ignoreFields),
            'number_of_allowed_downloads' => self::getFieldValue($product, 'number_of_allowed_downloads', $ignoreFields),
            'expiration_date' => self::getFieldValue($product, 'expiration_date', $ignoreFields),
            'number_of_days' => self::getFieldValue($product, 'number_of_days', $ignoreFields),
            'id_shop' => self::getFieldValue($product, 'id_shop', $ignoreFields),
            'advanced_stock_management' => self::getFieldValue($product, 'advanced_stock_management', $ignoreFields),
            'depends_on_stock' => self::getFieldValue($product, 'depends_on_stock', $ignoreFields),
            'warehouse' => self::getFieldValue($product, 'warehouse', $ignoreFields),
            'accessories' => self::getFieldValue($product, 'accessories', $ignoreFields)
        );

        $existingProduct = new Product((int)$productData['id_product']);
        if ($existingProduct->id) {
            // Update existing product
            foreach ($productData as $key => $value) {
                if ($value !== null) {
                    $existingProduct->{$key} = $value;
                }
            }
            $existingProduct->save();
        } else {
            // Create new product
            $newProduct = new Product();
            foreach ($productData as $key => $value) {
                if ($value !== null) {
                    $newProduct->{$key} = $value;
                }
            }
            $newProduct->add();
        }
    }

    private static function getFieldValue($product, $field, $ignoreFields)
    {
        if (in_array($field, $ignoreFields)) {
            return null;
        }
        return isset($product->{$field}) ? (string)$product->{$field} : null;
    }

    public static function exportProducts($outputFilePath)
    {
        $products = Product::getProducts(
            (int)Configuration::get('PS_LANG_DEFAULT'),
            0,
            0,
            'id_product',
            'ASC'
        );

        $xml = new SimpleXMLElement('<products/>');

        foreach ($products as $product) {
            $xmlProduct = $xml->addChild('product');
            foreach ($product as $key => $value) {
                $xmlProduct->addChild($key, htmlspecialchars($value));
            }
        }

        $xml->asXML($outputFilePath);
    }
}
