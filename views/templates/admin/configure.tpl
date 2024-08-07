<div class="panel">
    <div class="panel-heading">
        {$module->displayName|escape:'html':'UTF-8'}
    </div>
    <div class="form-wrapper">
        <form action="{$currentIndex}&token={$token}" method="post" class="form-horizontal">
            <div id="feed-form-group">
                {foreach from=$feeds item=feed}
                    <div class="form-group">
                        <label class="control-label col-lg-3" for="feed_name_{$feed.id_feed}">{$module->l('Feed Name')}</label>
                        <div class="col-lg-9">
                            <input type="text" id="feed_name_{$feed.id_feed}" name="XMLFEEDMANAGER_FEED_NAMES[]" class="form-control" value="{$feed.feed_name|escape:'html':'UTF-8'}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-3" for="feed_url_{$feed.id_feed}">{$module->l('Feed URL')}</label>
                        <div class="col-lg-9">
                            <input type="text" id="feed_url_{$feed.id_feed}" name="XMLFEEDMANAGER_FEED_URLS[]" class="form-control" value="{$feed.feed_url|escape:'html':'UTF-8'}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-3" for="feed_type_{$feed.id_feed}">{$module->l('Feed Type')}</label>
                        <div class="col-lg-9">
                            <select id="feed_type_{$feed.id_feed}" name="XMLFEEDMANAGER_FEED_TYPES[]" class="form-control">
                                <option value="full" {if $feed.feed_type == 'full'}selected{/if}>{$module->l('Full')}</option>
                                <option value="update" {if $feed.feed_type == 'update'}selected{/if}>{$module->l('Update')}</option>
                            </select>
                        </div>
                    </div>
                {/foreach}
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3" for="markup_percentage">{$module->l('Markup Percentage')}</label>
                <div class="col-lg-9">
                    <input type="text" id="markup_percentage" name="XMLFEEDMANAGER_MARKUP_PERCENTAGE" class="form-control" value="{$markup_percentage|escape:'html':'UTF-8'}">
                </div>
            </div>
            <div class="panel-footer">
                <button type="submit" name="submit{$module->name}" class="btn btn-default pull-right">{$module->l('Save')}</button>
            </div>
        </form>
    </div>
</div>
