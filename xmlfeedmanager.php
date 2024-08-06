public function getContent()
{
    $output = null;

    if (Tools::isSubmit('submit' . $this->name)) {
        $feedNames = Tools::getValue('XMLFEEDMANAGER_FEED_NAMES');
        $feedUrls = Tools::getValue('XMLFEEDMANAGER_FEED_URLS');
        Db::getInstance()->execute('TRUNCATE TABLE ' . _DB_PREFIX_ . 'xmlfeedmanager_feeds');
        foreach ($feedNames as $index => $feedName) {
            if (!empty($feedName) && !empty($feedUrls[$index])) {
                Db::getInstance()->insert('xmlfeedmanager_feeds', array(
                    'feed_name' => pSQL($feedName),
                    'feed_url' => pSQL($feedUrls[$index]),
                ));
            }
        }
        $output .= $this->displayConfirmation($this->l('Settings updated'));
    }

    return $output . $this->renderForm();
}

protected function renderForm()
{
    $feeds = Db::getInstance()->executeS('SELECT * FROM ' . _DB_PREFIX_ . 'xmlfeedmanager_feeds');

    $feedNames = array();
    $feedUrls = array();

    foreach ($feeds as $feed) {
        $feedNames[] = $feed['feed_name'];
        $feedUrls[] = $feed['feed_url'];
    }

    $fields_form = array(
        'form' => array(
            'legend' => array(
                'title' => $this->l('Settings'),
            ),
            'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => 'XMLFEEDMANAGER_FEED_NAMES[]',
                ),
                array(
                    'type' => 'hidden',
                    'name' => 'XMLFEEDMANAGER_FEED_URLS[]',
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            )
        )
    );

    $helper = new HelperForm();
    $helper->module = $this;
    $helper->name_controller = $this->name;
    $helper->token = Tools::getAdminTokenLite('AdminModules');
    $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
    $helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
    $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
    $helper->title = $this->displayName;
    $helper->submit_action = 'submit' . $this->name;
    $helper->fields_value = $this->getConfigFieldsValues($feeds);

    return $helper->generateForm(array($fields_form));
}

public function getConfigFieldsValues($feeds)
{
    $feedNames = array();
    $feedUrls = array();

    foreach ($feeds as $feed) {
        $feedNames[] = $feed['feed_name'];
        $feedUrls[] = $feed['feed_url'];
    }

    return array(
        'XMLFEEDMANAGER_FEED_NAMES[]' => $feedNames,
        'XMLFEEDMANAGER_FEED_URLS[]' => $feedUrls,
    );
}
