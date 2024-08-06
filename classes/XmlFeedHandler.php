<?php
class XmlFeedHandler
{
    public function importFeeds()
    {
        $feeds = Db::getInstance()->executeS('SELECT * FROM ' . _DB_PREFIX_ . 'xmlfeedmanager_feeds');
        foreach ($feeds as $feed) {
            $this->import($feed['feed_name'], $feed['feed_url']);
        }
    }

    public function import($feedName, $feedUrl)
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
            $mappedProduct = $this->mapFields($product, $fieldMapping);
            $existingProductId = $this->getProductIdByReferenceAndFeed($mappedProduct['reference'], $feedName);
            if ($existingProductId) {
                $this->updateProduct($existingProductId, $mappedProduct);
            } else {
                $this->addNewProduct($mappedProduct, $feedName);
            }
        }
    }

    protected function mapFields($product, $fieldMapping)
    {
        $mappedProduct = [];
        foreach ($fieldMapping as $xmlField => $prestashopField) {
            $mappedProduct[$prestashopField] = (string) $product->$xmlField;
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
