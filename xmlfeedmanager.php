<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class XmlFeedManager extends Module
{
    public function __construct()
    {
        $this->name = 'xmlfeedmanager';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Marco Zagato';
        $this->author_uri = 'https://dealbrut.com';
        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('XML Feed Manager');
        $this->description = $this->l('Manage multiple XML feeds for importing and updating product data without overwriting existing products.');
    }

    public function install()
    {
        return parent::install() &&
               $this->registerHook('actionAdminControllerSetMedia') &&
               $this->installDb();
    }

    public function uninstall()
    {
        return parent::uninstall() && $this->uninstallDb();
    }

    private function installDb()
    {
        $sql1 = "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."xmlfeedmanager_feeds` (
            `id_feed` INT(11) NOT NULL AUTO_INCREMENT,
            `feed_name` VARCHAR(255) NOT NULL,
            `feed_url` VARCHAR(255) NOT NULL,
            `feed_type` ENUM('full', 'update') NOT NULL DEFAULT 'full',
            `last_imported` DATETIME DEFAULT NULL,
            PRIMARY KEY (`id_feed`)
        ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;";

        $sql2 = "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."xmlfeedmanager_product_feed` (
            `id_product` INT(11) NOT NULL,
            `feed_name` VARCHAR(255) NOT NULL,
            PRIMARY KEY (`id_product`, `feed_name`)
        ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;";

        return Db::getInstance()->execute($sql1) && Db::getInstance()->execute($sql2);
    }

    private function uninstallDb()
    {
        $sql1 = "DROP TABLE IF EXISTS `"._DB_PREFIX_."xmlfeedmanager_feeds`;";
        $sql2 = "DROP TABLE IF EXISTS `"._DB_PREFIX_."xmlfeedmanager_product_feed`;";

        return Db::getInstance()->execute($sql1) && Db::getInstance()->execute($sql2);
    }

    public function getContent()
    {
        $output = null;

        if (Tools::isSubmit('submit' . $this->name)) {
            $feedNames = Tools::getValue('XMLFEEDMANAGER_FEED_NAMES');
            $feedUrls = Tools::getValue('XMLFEEDMANAGER_FEED_URLS');
            $feedTypes = Tools::getValue('XMLFEEDMANAGER_FEED_TYPES');
            $markup = (float)Tools::getValue('XMLFEEDMANAGER_MARKUP');

            Db::getInstance()->execute('TRUNCATE TABLE ' . _DB_PREFIX_ . 'xmlfeedmanager_feeds');
            foreach ($feedNames as $index => $feedName) {
                if (!empty($feedName) && !empty($feedUrls[$index])) {
                    Db::getInstance()->insert('xmlfeedmanager_feeds', array(
                        'feed_name' => pSQL($feedName),
                        'feed_url' => pSQL($feedUrls[$index]),
                        'feed_type' => pSQL($feedTypes[$index]),
                        'last_imported' => null,
                    ));
                }
            }

            Configuration::updateValue('XMLFEEDMANAGER_MARKUP', $markup);

            $fieldMapping = Tools::getValue('XMLFEEDMANAGER_FIELD_MAPPING');
            Configuration::updateValue('XMLFEEDMANAGER_FIELD_MAPPING', json_encode($fieldMapping));

            $output .= $this->displayConfirmation($this->l('Settings updated'));
        }

        if (Tools::isSubmit('importFeeds')) {
            try {
                $feedHandler = new XmlFeedHandler();
                $feedHandler->importFeeds((float)Configuration::get('XMLFEEDMANAGER_MARKUP'));
                $output .= $this->displayConfirmation($this->l('Feeds imported successfully'));
            } catch (Exception $e) {
                $output .= $this->displayError($this->l('Error importing feeds: ') . $e->getMessage());
            }
        }

        return $output . $this->renderForm();
    }

    protected function renderForm()
    {
        $feeds = Db::getInstance()->executeS('SELECT * FROM ' . _DB_PREFIX_ . 'xmlfeedmanager_feeds');
        $fieldMapping = json_decode(Configuration::get('XMLFEEDMANAGER_FIELD_MAPPING'), true) ?: [];
        $markup = (float)Configuration::get('XMLFEEDMANAGER_MARKUP');

        $feedNames = array();
        $feedUrls = array();
        $feedTypes = array();

        foreach ($feeds as $feed) {
            $feedNames[] = $feed['feed_name'];
            $feedUrls[] = $feed['feed_url'];
            $feedTypes[] = $feed['feed_type'];
        }

        $xmlFields = !empty($feeds) ? $this->getXmlFields($feeds[0]['feed_url']) : [];
        $prestashopFields = $this->getPrestashopFields();

        $this->context->smarty->assign([
            'module_name' => $this->displayName,
            'feeds' => $feeds,
            'XMLFEEDMANAGER_FIELD_MAPPING' => $fieldMapping,
            'PRESTASHOP_FIELDS' => $prestashopFields,
            'XMLFEEDMANAGER_MARKUP' => $markup,
            'link' => $this->context->link,
        ]);

        return $this->display(__FILE__, 'views/templates/admin/configure.tpl');
    }

    protected function getXmlFields($feedUrl)
    {
        // Fetch the first XML feed to extract field names
        $xmlData = file_get_contents($feedUrl);
        $xml = simplexml_load_string($xmlData);
        $fields = [];

        if ($xml && isset($xml->product[0])) {
            foreach ($xml->product[0] as $key => $value) {
                $fields[] = $key;
            }
        }

        return $fields;
    }

    protected function getPrestashopFields()
    {
        return [
            ['id' => 'name', 'name' => $this->l('Name')],
            ['id' => 'reference', 'name' => $this->l('Reference')],
            ['id' => 'ean13', 'name' => $this->l('EAN13')],
            ['id' => 'upc', 'name' => $this->l('UPC')],
            ['id' => 'price', 'name' => $this->l('Price')],
            ['id' => 'wholesale_price', 'name' => $this->l('Wholesale Price')],
            ['id' => 'description_short', 'name' => $this->l('Short Description')],
            ['id' => 'description', 'name' => $this->l('Description')],
            ['id' => 'id_category_default', 'name' => $this->l('Default Category')],
            ['id' => 'quantity', 'name' => $this->l('Quantity')],
            ['id' => 'active', 'name' => $this->l('Active')],
            ['id' => 'weight', 'name' => $this->l('Weight')],
            ['id' => 'width', 'name' => $this->l('Width')],
            ['id' => 'height', 'name' => $this->l('Height')],
            ['id' => 'depth', 'name' => $this->l('Depth')],
            ['id' => 'id_manufacturer', 'name' => $this->l('Manufacturer')],
            ['id' => 'id_supplier', 'name' => $this->l('Supplier')],
        ];
    }

    public function getConfigFieldsValues($feeds, $fieldMapping, $markup)
    {
        $feedNames = array();
        $feedUrls = array();
        $feedTypes = array();

        foreach ($feeds as $feed) {
            $feedNames[] = $feed['feed_name'];
            $feedUrls[] = $feed['feed_url'];
            $feedTypes[] = $feed['feed_type'];
        }

        $fields_values = [
            'XMLFEEDMANAGER_FEED_NAMES' => implode("\n", $feedNames),
            'XMLFEEDMANAGER_FEED_URLS' => implode("\n", $feedUrls),
            'XMLFEEDMANAGER_FEED_TYPES' => implode("\n", $feedTypes),
            'XMLFEEDMANAGER_MARKUP' => $markup,
        ];

        foreach ($fieldMapping as $xmlField => $prestashopField) {
            $fields_values['XMLFEEDMANAGER_FIELD_MAPPING[' . $xmlField . ']'] = $prestashopField;
        }

        return $fields_values;
    }
}
`views/templates/admin/configure.tpl`

```tpl
<form action="{$link->getAdminLink('AdminXmlFeedManager')}" method="post" class="defaultForm form-horizontal">
    <div class="panel">
        <h3>{$module_name}</h3>
        <div class="form-group">
            <label class="control-label col-lg-3">Feed Names and URLs</label>
            <div class="col-lg-9">
                {foreach from=$feeds item=feed name=feeds}
                    <div class="input-group">
                        <input type="text" name="XMLFEEDMANAGER_FEED_NAMES[]" value="{$feed.feed_name}" class="form-control" placeholder="Feed Name" />
                        <input type="text" name="XMLFEEDMANAGER_FEED_URLS[]" value="{$feed.feed_url}" class="form-control" placeholder="Feed URL" />
                        <select name="XMLFEEDMANAGER_FEED_TYPES[]" class="form-control">
                            <option value="full" {if $feed.feed_type == 'full'}selected{/if}>{$l s='Full'}</option>
                            <option value="update" {if $feed.feed_type == 'update'}selected{/if}>{$l s='Update'}</option>
                        </select>
                        <button type="button" class="btn btn-danger remove-feed">{$l s='Remove'}</button>
                    </div>
                    <br/>
                {/foreach}
                <button type="button" class="btn btn-primary" id="add-feed">{$l s='Add Feed'}</button>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3">Markup Percentage</label>
            <div class="col-lg-9">
                <input type="text" name="XMLFEEDMANAGER_MARKUP" value="{$XMLFEEDMANAGER_MARKUP}" class="form-control" />
                <p class="help-block">{$l s='Enter the markup percentage to be applied to product prices.'}</p>
            </div>
        </div>
        {foreach from=$XMLFEEDMANAGER_FIELD_MAPPING key=xmlField item=prestashopField}
        <div class="form-group">
            <label class="control-label col-lg-3">{$l s='Map '}{$xmlField}</label>
            <div class="col-lg-9">
                <select name="XMLFEEDMANAGER_FIELD_MAPPING[{$xmlField}]" class="form-control">
                    {foreach from=$PRESTASHOP_FIELDS item=field}
                    <option value="{$field.id}" {if $prestashopField == $field.id}selected="selected"{/if}>{$field.name}</option>
                    {/foreach}
                </select>
                <p class="help-block">{$l s='Select the corresponding PrestaShop field for the XML field '}{$xmlField}</p>
            </div>
        </div>
        {/foreach}
        <div class="panel-footer">
            <button type="submit" class="btn btn-default pull-right" name="submitxmlfeedmanager">
                <i class="process-icon-save"></i> {$l s='Save'}
            </button>
            <button type="submit" class="btn btn-primary pull-right" name="importFeeds" style="margin-right: 10px;">
                <i class="process-icon-refresh"></i> {$l s='Import Feeds'}
            </button>
        </div>
    </div>
</form>

<!-- Feed History Section -->
<div class="panel">
    <h3>{$l s='Feed History'}</h3>
    <table class="table">
        <thead>
            <tr>
                <th>{$l s='Feed Name'}</th>
                <th>{$l s='URL'}</th>
                <th>{$l s='Type'}</th>
                <th>{$l s='Last Imported'}</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$feeds item=feed}
            <tr>
                <td>{$feed.feed_name}</td>
                <td>{$feed.feed_url}</td>
                <td>{ucfirst($feed.feed_type)}</td>
                <td>{if $feed.last_imported}{$feed.last_imported}{else}{$l s='Never'}{/if}</td>
            </tr>
            {/foreach}
        </tbody>
    </table>
</div>

<script>
document.getElementById('add-feed').addEventListener('click', function() {
    var feedContainer = document.createElement('div');
    feedContainer.className = 'input-group';
    feedContainer.innerHTML = `
        <input type="text" name="XMLFEEDMANAGER_FEED_NAMES[]" class="form-control" placeholder="Feed Name" />
        <input type="text" name="XMLFEEDMANAGER_FEED_URLS[]" class="form-control" placeholder="Feed URL" />
        <select name="XMLFEEDMANAGER_FEED_TYPES[]" class="form-control">
            <option value="full">Full</option>
            <option value="update">Update</option>
        </select>
        <button type="button" class="btn btn-danger remove-feed">Remove</button>
        <br/>
    `;
    document.querySelector('.form-group .col-lg-9').appendChild(feedContainer);
});

document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('remove-feed')) {
        e.target.closest('.input-group').remove();
    }
});
</script>
