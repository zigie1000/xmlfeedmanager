<?php
class XmlFeedHandler
{
    public function import($xmlFilePath)
    {
        if (!file_exists($xmlFilePath)) {
            throw new Exception("File not found: $xmlFilePath");
        }

        $xml = simplexml_load_file($xmlFilePath);
        if (!$xml) {
            throw new Exception("Error parsing XML file: $xmlFilePath");
        }

        foreach ($xml->product as $product) {
            $newProduct = new Product();
            $newProduct->name = (string) $product->name;
            $newProduct->price = (float) $product->price;
            $newProduct->id_category_default = $this->getCategoryId((string) $product->category);
            $newProduct->reference = (string) $product->reference;
            $newProduct->description = (string) $product->description;
            $newProduct->save();
        }
    }

    public function export($xmlFilePath)
    {
        $products = Product::getProducts($this->context->language->id, 0, 0, 'id_product', 'ASC');
        $xml = new SimpleXMLElement('<products/>');

        foreach ($products as $product) {
            $xmlProduct = $xml->addChild('product');
            $xmlProduct->addChild('id', $product['id_product']);
            $xmlProduct->addChild('name', $product['name']);
            $xmlProduct->addChild('price', $product['price']);
            $xmlProduct->addChild('category', $this->getCategoryName($product['id_category_default']));
            $xmlProduct->addChild('reference', $product['reference']);
            $xmlProduct->addChild('description', $product['description']);
        }

        $xml->asXML($xmlFilePath);
    }

    public function scanFeed($xmlFilePath)
    {
        if (!file_exists($xmlFilePath)) {
            throw new Exception("File not found: $xmlFilePath");
        }

        $xml = simplexml_load_file($xmlFilePath);
        if (!$xml) {
            throw new Exception("Error parsing XML file: $xmlFilePath");
        }

        $mappings = [];
        foreach ($xml->product[0] as $field => $value) {
            $mappings[] = [
                'xml_field' => $field,
                'prestashop_field' => $this->recommendPrestaShopField($field)
            ];
        }
        return $mappings;
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
        return 0; // Default category or create a new category
    }

    private function getCategoryName($categoryId)
    {
        $category = new Category($categoryId, $this->context->language->id);
        return $category->name;
    }
}
