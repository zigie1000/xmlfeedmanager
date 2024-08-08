<!-- This template should display the list of fields that can be mapped -->
<div class="panel">
    <h3>{$module->l('Field Mappings')}</h3>
    <form action="{$link->getAdminLink('AdminXmlFeedManager')}" method="post" class="form-horizontal">
        <div class="form-group">
            <label class="control-label col-lg-3">{$module->l('XML Field')}</label>
            <div class="col-lg-9">
                <select name="xml_field" class="form-control">
                    {foreach from=$xml_fields item=field}
                        <option value="{$field}">{$field}</option>
                    {/foreach}
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3">{$module->l('PrestaShop Field')}</label>
            <div class="col-lg-9">
                <select name="prestashop_field" class="form-control">
                    {foreach from=$prestashop_fields item=field}
                        <option value="{$field}">{$field}</option>
                    {/foreach}
                </select>
            </div>
        </div>
        <div class="panel-footer">
            <button type="submit" name="submitMapping" class="btn btn-default pull-right">{$module->l('Save Mapping')}</button>
        </div>
    </form>
</div>
