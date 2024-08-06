<form action="{$link->getAdminLink('AdminXmlFeedManager')}" method="post" class="defaultForm form-horizontal">
    <div class="panel">
        <h3>{$module_name}</h3>
        <div class="form-group">
            <label class="control-label col-lg-3">{$l s='Feed Names (one per line)'}</label>
            <div class="col-lg-9">
                <textarea name="XMLFEEDMANAGER_FEED_NAMES" cols="60" rows="10" class="form-control">{$XMLFEEDMANAGER_FEED_NAMES}</textarea>
                <p class="help-block">{$l s='Enter the names of the feeds, one per line.'}</p>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3">{$l s='Feed URLs (one per line)'}</label>
            <div class="col-lg-9">
                <textarea name="XMLFEEDMANAGER_FEED_URLS" cols="60" rows="10" class="form-control">{$XMLFEEDMANAGER_FEED_URLS}</textarea>
                <p class="help-block">{$l s='Enter the URLs of the feeds, one per line.'}</p>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3">{$l s='Feed Types (one per line)'}</label>
            <div class="col-lg-9">
                <select name="XMLFEEDMANAGER_FEED_TYPES" class="form-control">
                    <option value="full" {$XMLFEEDMANAGER_FEED_TYPES == 'full' ? 'selected' : ''}>{$l s='Full'}</option>
                    <option value="update" {$XMLFEEDMANAGER_FEED_TYPES == 'update' ? 'selected' : ''}>{$l s='Update'}</option>
                </select>
                <p class="help-block">{$l s='Select the type for each feed.'}</p>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3">{$l s='Markup Percentage'}</label>
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
