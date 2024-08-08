<div class="panel">
    <h3>{$module->displayName}</h3>
    <form action="{$link->getAdminLink('AdminXmlFeedManager')}" method="post" class="form-horizontal">
        <div class="form-group">
            <label class="control-label col-lg-3">Feed Name</label>
            <div class="col-lg-9">
                <input type="text" name="XMLFEEDMANAGER_FEED_NAME" value="{$fields_value.XMLFEEDMANAGER_FEED_NAME|escape:'htmlall':'UTF-8'}" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3">Feed URL</label>
            <div class="col-lg-9">
                <input type="text" name="XMLFEEDMANAGER_FEED_URL" value="{$fields_value.XMLFEEDMANAGER_FEED_URL|escape:'htmlall':'UTF-8'}" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3">Feed Type</label>
            <div class="col-lg-9">
                <select name="XMLFEEDMANAGER_FEED_TYPE" class="form-control">
                    {foreach from=$feed_types item=type}
                        <option value="{$type.id}" {if $fields_value.XMLFEEDMANAGER_FEED_TYPE == $type.id}selected{/if}>{$type.name}</option>
                    {/foreach}
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3">Mapping</label>
            <div class="col-lg-9">
                <textarea name="XMLFEEDMANAGER_MAPPING" rows="10" class="form-control">{$fields_value.XMLFEEDMANAGER_MAPPING|escape:'htmlall':'UTF-8'}</textarea>
            </div>
        </div>
        <div class="panel-footer">
            <button type="submit" name="submitXMLFeedManager" class="btn btn-default pull-right">{$module->l('Save')}</button>
        </div>
    </form>
</div>
