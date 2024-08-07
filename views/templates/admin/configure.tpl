<form action="{$current}&token={$token}" method="post" class="form-horizontal">
    <div id="feed-form-group">
        {foreach from=$feeds item=feed name=feeds}
        <div class="form-group">
            <label class="control-label col-lg-3" for="feed_name_{$smarty.foreach.feeds.index}">{$smarty.block.parent}Feed Name</label>
            <div class="col-lg-9">
                <input type="text" id="feed_name_{$smarty.foreach.feeds.index}" name="XMLFEEDMANAGER_FEED_NAMES[]" value="{$feed.feed_name|escape:'html':'UTF-8'}" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3" for="feed_url_{$smarty.foreach.feeds.index}">{$smarty.block.parent}Feed URL</label>
            <div class="col-lg-9">
                <input type="text" id="feed_url_{$smarty.foreach.feeds.index}" name="XMLFEEDMANAGER_FEED_URLS[]" value="{$feed.feed_url|escape:'html':'UTF-8'}" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3" for="feed_type_{$smarty.foreach.feeds.index}">{$smarty.block.parent}Feed Type</label>
            <div class="col-lg-9">
                <select id="feed_type_{$smarty.foreach.feeds.index}" name="XMLFEEDMANAGER_FEED_TYPES[]" class="form-control">
                    <option value="full" {if $feed.feed_type == 'full'}selected{/if}>{$smarty.block.parent}Full</option>
                    <option value="update" {if $feed.feed_type == 'update'}selected{/if}>{$smarty.block.parent}Update</option>
                </select>
            </div>
        </div>
        {/foreach}
    </div>
    <div class="form-group">
        <label class="control-label col-lg-3" for="markup_percentage">{$smarty.block.parent}Markup Percentage</label>
        <div class="col-lg-9">
            <input type="text" id="markup_percentage" name="XMLFEEDMANAGER_MARKUP_PERCENTAGE" value="{$markup_percentage}" class="form-control">
        </div>
    </div>
    <div class="panel-footer">
        <button type="submit" name="submit{$name}" class="btn btn-default pull-right">{$smarty.block.parent}Save</button>
    </div>
</form>
