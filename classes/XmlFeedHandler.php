<?php
class XmlFeedHandler
{
    public function importFeeds($markup)
    {
        $feeds = Db::getInstance()->executeS('SELECT * FROM ' . _DB_PREFIX_ . 'xmlfeedmanager_feeds');
        foreach ($feeds as $feed) {
            if ($feed['feed_type'] == 'full') {
                $this->importFullFeed($feed['feed_name'], $feed['feed_url'], $markup);
            } else {
                $this->importUpdateFeed($feed['feed_name'], $feed['feed_url'], $markup);
            }
        }
    }

    public function importFullFeed($feedName, $feedUrl, $markup)
    {
        if (!$feedUrl || !filter_var($feedUrl, FILTER_VALIDATE_URL)) {
            throw new Exception("Invalid URL: $feedUrl");
        }

        $xmlData = file_get_contents($feedUrl);
        if (!$xmlData) {
            throw new Exception("Error fetching XML from URL: $feedUrl");
        }

        $xml = simplexml_load_string($xmlData);
        if (!$xml) {
            throw new Exception("Error parsing XML from URL: $feedUrl");
        }

        $fieldMapping = json_decode(Configuration::get('XMLFEEDMANAGER_FIELD_MAPPING'), true);

        foreach ($xml->product as $product) {
            $mappedProduct = $this->mapFields($product, $fieldMapping, $markup);
            $existingProductId = $this->getProductIdByReferenceAndFeed($mappedProduct['reference'], $feedName);
            if ($existingProductId) {
                $this->updateProduct($existingProductId, $mappedProduct);
            } else {
                $this->addNewProduct($mappedProduct, $feedName);
            }
        }
    }

    public function importUpdateFeed($feedName, $feedUrl, $markup)
    {
        if (!$feedUrl || !filter_var($feedUrl, FILTER_VALIDATE_URL)) {
            throw new Exception("Invalid URL: $feedUrl");
        }

        $xmlData = file_get_contents($feedUrl);
        if (!$xmlData) {
            throw new Exception("Error fetching XML from URL: $feedUrl");
        }

        $xml = simplexml_load_string($xmlData);
        if (!$xml) {
            throw new Exception("Error parsing XML from URL: $feedUrl");
        }

        $fieldMapping = json_decode(Configuration::get('XMLFEEDMANAGER_FIELD_MAPPING'), true);

        foreach ($xml->product as $product) {
            $mappedProduct = $this->mapFields($product, $fieldMapping, $markup);
            $existingProductId = $this->getProductIdByReferenceAndFeed($mappedProduct['reference'], $feedName);
            if ($existingProductId) {
                $this->updateProduct($existingProductId, $mappedProduct);
            }
        }
    }

    protected function mapFields($product, $fieldMapping, $markup)
    {
        $mappedProduct = [];
        foreach ($fieldMapping as $xmlField => $prestashopField) {
            $mappedProduct[$prestashopField] = (string) $product->$xmlField;
        }

        // Apply markup to the price field if it exists
        if (isset($mappedProduct['price'])) {
            $mappedProduct['price'] *= (1 + $markup / 100);
        }

        return $mappedProduct;
    }

    protected function getProductIdByReferenceAndFeed($reference, $feedName)
    {
        $sql = 'SELECT p.id_product FROM ' . _DB_PREFIX_ . 'product p
                JOIN ' . _DB_PREFIX_ . 'xmlfeedmanager_product_feed f
                ON p.id_product = f.id_product
                WHERE p.reference = "' . pSQL($reference) . '" AND f.feed_name = "' . pSQL($feedName) . '"';
        return Db::getInstance()->getValue($sql);
    }

    protected function updateProduct($productId, $mappedProduct)
    {
        $product = new Product($productId);
        foreach ($mappedProduct as $field => $value) {
            $product->$field = $value;
        }
        $product->update();
    }

    protected function addNewProduct($mappedProduct, $feedName)
    {
        $product = new Product();
        foreach ($mappedProduct as $field => $value) {
            $product->$field = $value;
        }
        $product->add();

        Db::getInstance()->insert('xmlfeedmanager_product_feed', [
            'id_product' => (int)$product->id,
            'feed_name' => pSQL($feedName),
        ]);
    }
}
