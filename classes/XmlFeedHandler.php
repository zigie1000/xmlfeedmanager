<?php

class XmlFeedHandler
{
    public function importFeeds($markup)
    {
        $feeds = Db::getInstance()->executeS('SELECT * FROM ' . _DB_PREFIX_ . 'xmlfeedmanager_feeds');
        foreach ($feeds as $feed) {
            $this->importFeed($feed['feed_url'], $markup, $feed['feed_name']);
            Db::getInstance()->update('xmlfeedmanager_feeds', ['last_imported' => date('Y-m-d H:i:s')], 'id_feed = ' . (int)$feed['id_feed']);
        }
    }

    private function importFeed($feedUrl, $markup, $feedName)
    {
        $xmlData = file_get_contents($feedUrl);
        $xml = simplexml_load_string($xmlData);
        $fieldMapping = json_decode(Configuration::get('XMLFEEDMANAGER_FIELD_MAPPING'), true);

        foreach ($xml->product as $productData) {
            $product = new Product();
            foreach ($fieldMapping as $xmlField => $prestashopField) {
                if (isset($productData->$xmlField)) {
                    $value = (string)$productData->$xmlField;
                    if ($prestashopField == 'price' || $prestashopField == 'wholesale_price') {
                        $value = $value * (1 + ($markup / 100));
                    }
                    $product->$prestashopField = $value;
                }
            }

            if ($product->add()) {
                Db::getInstance()->insert('xmlfeedmanager_product_feed', array(
                    'id_product' => (int)$product->id,
                    'feed_name' => pSQL($feedName),
                ));
            }
        }
    }
}
