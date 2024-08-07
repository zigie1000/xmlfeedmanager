<div class="panel">
    <div class="panel-heading">
        {$module_name}
    </div>
    <div class="panel-body">
        <form action="{$current}&token={$token}" method="post" class="form-horizontal">
            <div id="feed-form-group">
                <!-- Existing form fields for feed names, URLs, and types will be added here dynamically -->
                {foreach from=$feeds item=feed name=feeds}
                    <div class="form-group">
                        <label class="control-label col-lg-3" for="feed_name_{$smarty.foreach.feeds.index}">Feed Name</label>
                        <div class="col-lg-9">
                            <input type="text" id="feed_name_{$smarty.foreach.feeds.index}" name="XMLFEEDMANAGER_FEED_NAMES[]" class="form-control" value="{$feed.feed_name}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-3" for="feed_url_{$smarty.foreach.feeds.index}">Feed URL</label>
                        <div class="col-lg-9">
                            <input type="text" id="feed_url_{$smarty.foreach.feeds.index}" name="XMLFEEDMANAGER_FEED_URLS[]" class="form-control" value="{$feed.feed_url}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-3" for="feed_type_{$smarty.foreach.feeds.index}">Feed Type</label>
                        <div class="col-lg-9">
                            <select id="feed_type_{$smarty.foreach.feeds.index}" name="XMLFEEDMANAGER_FEED_TYPES[]" class="form-control">
                                <option value="full" {if $feed.feed_type == 'full'}selected{/if}>Full</option>
                                <option value="update" {if $feed.feed_type == 'update'}selected{/if}>Update</option>
                            </select>
                        </div>
                    </div>
                {/foreach}
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3" for="XMLFEEDMANAGER_MARKUP_PERCENTAGE">{$markup_percentage_label}</label>
                <div class="col-lg-9">
                    <input type="text" id="XMLFEEDMANAGER_MARKUP_PERCENTAGE" name="XMLFEEDMANAGER_MARKUP_PERCENTAGE" value="{$markup_percentage_value}" class="form-control">
                </div>
            </div>
            <div class="panel-footer">
                <button type="submit" name="submit{$module_name}" class="btn btn-default pull-right">{$save_button_label}</button>
            </div>
        </form>
    </div>
</div>
