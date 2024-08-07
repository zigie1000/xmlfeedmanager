class AdminXmlFeedManagerController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        parent::__construct();
    }

    public function renderForm()
    {
        $feeds = Db::getInstance()->executeS('SELECT * FROM '._DB_PREFIX_.'xmlfeedmanager_feeds');
        $feedNames = array();
        $feedUrls = array();
        $feedTypes = array();

        foreach ($feeds as $feed) {
            $feedNames[] = $feed['feed_name'];
            $feedUrls[] = $feed['feed_url'];
            $feedTypes[] = $feed['feed_type'];
        }

        $predefinedFeedTypes = PrestaShopFeedTypes::getTypes();

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                ),
                'input' => array(
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('Feed Names (one per line)'),
                        'name' => 'XMLFEEDMANAGER_FEED_NAMES',
                        'cols' => 60,
                        'rows' => 10,
                        'value' => implode("\n", $feedNames),
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('Feed URLs (one per line)'),
                        'name' => 'XMLFEEDMANAGER_FEED_URLS',
                        'cols' => 60,
                        'rows' => 10,
                        'value' => implode("\n", $feedUrls),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Predefined Feed Types'),
                        'name' => 'XMLFEEDMANAGER_PREDEFINED_FEED_TYPES[]',
                        'multiple' => true,
                        'options' => array(
                            'query' => array_map(function($key, $value) {
                                return array('id' => $key, 'name' => $value);
                            }, array_keys($predefinedFeedTypes), $predefinedFeedTypes),
                            'id' => 'id',
                            'name' => 'name'
                        ),
                        'value' => $feedTypes,
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('Feed Types (one per line)'),
                        'name' => 'XMLFEEDMANAGER_FEED_TYPES',
                        'cols' => 60,
                        'rows' => 10,
                        'value' => implode("\n", $feedTypes),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Markup Percentage'),
                        'name' => 'XMLFEEDMANAGER_MARKUP_PERCENTAGE',
                        'value' => Configuration::get('XMLFEEDMANAGER_MARKUP_PERCENTAGE', 0),
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
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->title = $this->displayName;
        $helper->submit_action = 'submit'.$this->name;
        $helper->fields_value = $this->getConfigFieldsValues($feeds);

        return $helper->generateForm(array($fields_form));
    }

    public function getConfigFieldsValues($feeds)
    {
        $feedNames = array();
        $feedUrls = array();
        $feedTypes = array();
        foreach ($feeds as $feed) {
            $feedNames[] = $feed['feed_name'];
            $feedUrls[] = $feed['feed_url'];
            $feedTypes[] = $feed['feed_type'];
        }
        return array(
            'XMLFEEDMANAGER_FEED_NAMES' => implode("\n", $feedNames),
            'XMLFEEDMANAGER_FEED_URLS' => implode("\n", $feedUrls),
            'XMLFEEDMANAGER_FEED_TYPES' => implode("\n", $feedTypes),
            'XMLFEEDMANAGER_MARKUP_PERCENTAGE' => Configuration::get('XMLFEEDMANAGER_MARKUP_PERCENTAGE', 0),
            'XMLFEEDMANAGER_PREDEFINED_FEED_TYPES' => $feedTypes,
        );
    }
}
