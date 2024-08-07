<div class="panel">
    <div class="panel-heading">
        {$title}
    </div>
    <div class="panel-body">
        <form action="{$current}&token={$token}" method="post" class="form-horizontal">
            <div id="feed-form-group">
                {foreach from=$feeds item=feed name=feeds}
                    <div class="form-group">
                        <label class="control-label col-lg-3" for="feed_name_{$smarty.foreach.feeds.index}">{$smarty.l s='Feed Name' mod='xmlfeedmanager'}</label>
                        <div class="col-lg-9">
                            <input type="text" id="feed_name_{$smarty.foreach.feeds.index}" name="XMLFEEDMANAGER_FEED_NAMES[]" class="form-control" value="{$feed.feed_name|escape:'htmlall':'UTF-8'}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-3" for="feed_url_{$smarty.foreach.feeds.index}">{$smarty.l s='Feed URL' mod='xmlfeedmanager'}</label>
                        <div class="col-lg-9">
                            <input type="text" id="feed_url_{$smarty.foreach.feeds.index}" name="XMLFEEDMANAGER_FEED_URLS[]" class="form-control" value="{$feed.feed_url|escape:'htmlall':'UTF-8'}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-3" for="feed_type_{$smarty.foreach.feeds.index}">{$smarty.l s='Feed Type' mod='xmlfeedmanager'}</label>
                        <div class="col-lg-9">
                            <select id="feed_type_{$smarty.foreach.feeds.index}" name="XMLFEEDMANAGER_FEED_TYPES[]" class="form-control">
                                <option value="full" {if $feed.feed_type == 'full'}selected{/if}>{$smarty.l s='Full' mod='xmlfeedmanager'}</option>
                                <option value="update" {if $feed.feed_type == 'update'}selected{/if}>{$smarty.l s='Update' mod='xmlfeedmanager'}</option>
                            </select>
                        </div>
                    </div>
                {/foreach}
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3" for="markup_percentage">{$smarty.l s='Markup Percentage' mod='xmlfeedmanager'}</label>
                <div class="col-lg-9">
                    <input type="text" id="markup_percentage" name="XMLFEEDMANAGER_MARKUP_PERCENTAGE" class="form-control" value="{$markup_percentage|escape:'htmlall':'UTF-8'}">
                </div>
            </div>
            <div class="panel-footer">
                <button type="submit" name="submit{$module_name}" class="btn btn-default pull-right">
                    <i class="process-icon-save"></i> {$smarty.l s='Save' mod='xmlfeedmanager'}
                </button>
            </div>
        </form>
    </div>
</div>
