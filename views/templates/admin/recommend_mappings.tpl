<!-- This template should recommend mappings based on common XML feed structures -->
<div class="panel">
    <h3>{$module->l('Recommended Mappings')}</h3>
    <form action="{$link->getAdminLink('AdminXmlFeedManager')}" method="post" class="form-horizontal">
        {foreach from=$recommended_mappings item=mapping}
            <div class="form-group">
                <label class="control-label col-lg-3">{$mapping.xml_field}</label>
                <div class="col-lg-9">
                    <input type="text" name="{$mapping.xml_field}" value="{$mapping.prestashop_field}" class="form-control">
                </div>
            </div>
        {/foreach}
        <div class="panel-footer">
            <button type="submit" name="submitRecommendedMappings" class="btn btn-default pull-right">{$module->l('Save Recommended Mappings')}</button>
        </div>
    </form>
</div>
