<div class="panel">
    <div class="panel-heading">
        {$module.displayName}
    </div>
    <div class="form-wrapper">
        <form action="{$current}&token={$token}" method="post" class="form-horizontal">
            <fieldset>
                <legend>{$module.displayName}</legend>
                <div id="feed-form-group">
                    {foreach from=$fields_value.XMLFEEDMANAGER_FEED_NAMES item=feedName name=feed}
                        <div class="form-group">
                            <label class="control-label col-lg-3" for="feed_name_{$smarty.foreach.feed.index}">{$module->l('Feed Name')}</label>
                            <div class="col-lg-9">
                                <input type="text" id="feed_name_{$smarty.foreach.feed.index}" name="XMLFEEDMANAGER_FEED_NAMES[]" value="{$feedName}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-lg-3" for="feed_url_{$smarty.foreach.feed.index}">{$module->l('Feed URL')}</label>
                            <div class="col-lg-9">
                                <input type="text" id="feed_url_{$smarty.foreach.feed.index}" name="XMLFEEDMANAGER_FEED_URLS[]" value="{$fields_value.XMLFEEDMANAGER_FEED_URLS[$smarty.foreach.feed.index]}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-lg-3" for="feed_type_{$smarty.foreach.feed.index}">{$module->l('Feed Type')}</label>
                            <div class="col-lg-9">
                                <select id="feed_type_{$smarty.foreach.feed.index}" name="XMLFEEDMANAGER_FEED_TYPES[]" class="form-control">
                                    <option value="full" {if $fields_value.XMLFEEDMANAGER_FEED_TYPES[$smarty.foreach.feed.index] == 'full'}selected{/if}>{$module->l('Full')}</option>
                                    <option value="update" {if $fields_value.XMLFEEDMANAGER_FEED_TYPES[$smarty.foreach.feed.index] == 'update'}selected{/if}>{$module->l('Update')}</option>
                                </select>
                            </div>
                        </div>
                    {/foreach}
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-3" for="XMLFEEDMANAGER_MARKUP_PERCENTAGE">{$module->l('Markup Percentage')}</label>
                    <div class="col-lg-9">
                        <input type="text" id="XMLFEEDMANAGER_MARKUP_PERCENTAGE" name="XMLFEEDMANAGER_MARKUP_PERCENTAGE" value="{$fields_value.XMLFEEDMANAGER_MARKUP_PERCENTAGE}" class="form-control">
                    </div>
                </div>
            </fieldset>
            <div class="panel-footer">
                <button type="submit" value="1" id="submit_{$module.name}" name="submit{$module.name}" class="btn btn-default pull-right">{$module->l('Save')}</button>
            </div>
        </form>
    </div>
</div>
