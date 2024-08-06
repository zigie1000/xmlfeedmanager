{extends file="layout.tpl"}

{block name="content"}
<form action="{$link->getAdminLink('AdminXmlFeedManager')}" method="post" class="defaultForm form-horizontal">
    <div class="panel">
        <h3>{$module_name}</h3>
        <div class="form-group">
            <label class="control-label col-lg-3">{$markupPercentageLabel}</label>
            <div class="col-lg-9">
                <input type="text" name="XMLFEEDMANAGER_MARKUP_PERCENTAGE" value="{$markupPercentage}" class="form-control">
            </div>
        </div>
        <div id="feed-form-group">
            {foreach from=$feeds item=feed name=feeds}
            <div class="form-group">
                <label class="control-label col-lg-3">{$feedNameLabel}</label>
                <div class="col-lg-9">
                    <input type="text" name="XMLFEEDMANAGER_FEED_NAMES[]" value="{$feed.feed_name}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3">{$feedUrlLabel}</label>
                <div class="col-lg-9">
                    <input type="text" name="XMLFEEDMANAGER_FEED_URLS[]" value="{$feed.feed_url}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3">{$feedTypeLabel}</label>
                <div class="col-lg-9">
                    <select name="XMLFEEDMANAGER_FEED_TYPES[]" class="form-control">
                        <option value="full" {if $feed.feed_type == 'full'}selected{/if}>{$fullLabel}</option>
                        <option value="update" {if $feed.feed_type == 'update'}selected{/if}>{$updateLabel}</option>
                    </select>
                </div>
            </div>
            {/foreach}
        </div>
        <div class="form-group">
            <div class="col-lg-9 col-lg-offset-3">
                <button type="button" id="add-feed" class="btn btn-default">{$addFeedLabel}</button>
                <button type="button" id="remove-feed" class="btn btn-danger">{$removeFeedLabel}</button>
            </div>
        </div>
        <div class="panel-footer">
            <button type="submit" name="submit{$module_name}" class="btn btn-default pull-right">{$saveLabel}</button>
        </div>
    </div>
</form>
{/block}
