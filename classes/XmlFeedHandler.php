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

        foreach ($xml->product as $product) {
            $existingProductId = $this->getProductIdByReferenceAndFeed((string) $product->reference, $feedName);
            if ($existingProductId) {
                // Update existing product
                $existingProduct = new Product($existingProductId);
                $existingProduct->price = (float) $product->price;
                $existingProduct->description = (string) $product->description;
                $existingProduct->update();
            } else {
                // Add new product
                $newProduct = new Product();
                $newProduct->name = (string) $product->name;
                $newProduct->price = (float) $product->price;
                $newProduct->id_category_default = $this->getCategoryId((string) $product->category);
                $newProduct->reference = (string) $product->reference;
                $newProduct->description = (string) $product->description;
                $newProduct->add();

                // Track which feed the product came from
                Db::getInstance()->insert('xmlfeedmanager_product_feed', array(
                    'id_product' => (int)$newProduct->id,
                    'feed_name' => pSQL($feedName),
                ));
            }
        }
    }

    protected function getProductIdByReferenceAndFeed($reference, $feedName)
    {
        $sql = 'SELECT p.id_product FROM ' . _DB_PREFIX_ . 'product p
                JOIN ' . _DB_PREFIX_ . 'xmlfeedmanager_product_feed f
                ON p.id_product = f.id_product
                WHERE p.reference = "' . pSQL($reference) . '" AND f.feed_name = "' . pSQL($feedName) . '"';
        return Db::getInstance()->getValue($sql);
    }

    protected function recommendPrestaShopField($xmlField)
    {
        $recommendedField = '';
        switch ($xmlField) {
            case 'name':
                $recommendedField = 'name';
                break;
            case 'price':
                $recommendedField = 'price';
                break;
            case 'category':
                $recommendedField = 'id_category_default';
                break;
            case 'reference':
                $recommendedField = 'reference';
                break;
            case 'description':
                $recommendedField = 'description';
                break;
            default:
                $recommendedField = 'custom_field';
                break;
        }
        return $recommendedField;
    }

    private function getCategoryId($categoryName)
    {
        $category = Category::searchByName($this->context->language->id, $categoryName);
        if ($category) {
            return $category[0]['id_category'];
        }
        return 0;
    }

    private function getCategoryName($categoryId)
    {
        $category = new Category($categoryId, $this->context->language->id);
        return $category->name;
    }
}
