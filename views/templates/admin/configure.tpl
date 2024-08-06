<form action="{$link->getAdminLink('AdminXmlFeedManager')}" method="post" class="defaultForm form-horizontal">
    <div class="panel">
        <h3>{$module_name}</h3>
        <div class="form-group">
            <label class="control-label col-lg-3">{$l s='Feed Names (one per line)'}</label>
            <div class="col-lg-9">
                <textarea name="XMLFEEDMANAGER_FEED_NAMES" cols="60" rows="10" class="form-control">{$XMLFEEDMANAGER_FEED_NAMES}</textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3">{$l s='Feed URLs (one per line)'}</label>
            <div class="col-lg-9">
                <textarea name="XMLFEEDMANAGER_FEED_URLS" cols="60" rows="10" class="form-control">{$XMLFEEDMANAGER_FEED_URLS}</textarea>
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
            </div>
        </div>
        {/foreach}
        <div class="panel-footer">
            <button type="submit" class="btn btn-default pull-right" name="submitxmlfeedmanager">
                <i class="process-icon-save"></i> {$l s='Save'}
            </button>
        </div>
    </div>
</form>
