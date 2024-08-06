{extends file="layout.tpl"}

{block name="content"}
<form action="{$link->getAdminLink('AdminXmlFeedManager')}" method="post" class="defaultForm form-horizontal">
    <div class="panel">
        <h3>{$module_name}</h3>
        <div class="form-group">
            <label class="control-label col-lg-3" id="markupPercentageLabel">{$l s='Markup Percentage'}</label>
            <div class="col-lg-9">
                <input type="text" name="XMLFEEDMANAGER_MARKUP_PERCENTAGE" value="{$fields_value.XMLFEEDMANAGER_MARKUP_PERCENTAGE}" class="form-control" placeholder="Enter the markup percentage">
            </div>
        </div>

        <div id="feed-form-group">
            {foreach from=$feeds item=feed name=feeds}
            <div class="form-group">
                <label class="control-label col-lg-3" id="feedNameLabel">{$l s='Feed Name'}</label>
                <div class="col-lg-9">
                    <input type="text" name="XMLFEEDMANAGER_FEED_NAMES[]" value="{$feed.feed_name}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3" id="feedUrlLabel">{$l s='Feed URL'}</label>
                <div class="col-lg-9">
                    <input type="text" name="XMLFEEDMANAGER_FEED_URLS[]" value="{$feed.feed_url}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3" id="feedTypeLabel">{$l s='Feed Type'}</label>
                <div class="col-lg-9">
                    <select name="XMLFEEDMANAGER_FEED_TYPES[]" class="form-control">
                        <option value="full" {if $feed.feed_type == 'full'}selected{/if}>{$l s='Full'}</option>
                        <option value="update" {if $feed.feed_type == 'update'}selected{/if}>{$l s='Update'}</option>
                    </select>
                </div>
            </div>
            {/foreach}
        </div>

        <div class="panel-footer">
            <button type="button" id="add-feed" class="btn btn-default">{$l s='Add Feed'}</button>
            <button type="button" id="remove-feed" class="btn btn-default">{$l s='Remove Feed'}</button>
            <button type="submit" name="submit{$module_name}" class="btn btn-default pull-right">{$l s='Save'}</button>
        </div>
    </div>
</form>
{/block}
