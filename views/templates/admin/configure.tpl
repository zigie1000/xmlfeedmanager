<form action="{$link->getAdminLink('AdminXmlFeedManager')}" method="post" class="defaultForm form-horizontal">
    <div class="panel">
        <h3>{$module_name}</h3>
        <div class="form-group">
            <label class="control-label col-lg-3" for="markup_percentage">{$l s='Markup Percentage'}</label>
            <div class="col-lg-9">
                <input type="text" id="markup_percentage" name="XMLFEEDMANAGER_MARKUP_PERCENTAGE" value="{$markup_percentage}" class="form-control">
            </div>
        </div>
        <div id="feed-form-group">
            {foreach from=$feeds item=feed name=feeds}
                <div class="form-group">
                    <label class="control-label col-lg-3" for="feed_name_{$smarty.foreach.feeds.iteration}">{$l s='Feed Name'}</label>
                    <div class="col-lg-9">
                        <input type="text" id="feed_name_{$smarty.foreach.feeds.iteration}" name="XMLFEEDMANAGER_FEED_NAMES[]" value="{$feed.feed_name}" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-3" for="feed_url_{$smarty.foreach.feeds.iteration}">{$l s='Feed URL'}</label>
                    <div class="col-lg-9">
                        <input type="text" id="feed_url_{$smarty.foreach.feeds.iteration}" name="XMLFEEDMANAGER_FEED_URLS[]" value="{$feed.feed_url}" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-3" for="feed_type_{$smarty.foreach.feeds.iteration}">{$l s='Feed Type'}</label>
                    <div class="col-lg-9">
                        <select id="feed_type_{$smarty.foreach.feeds.iteration}" name="XMLFEEDMANAGER_FEED_TYPES[]" class="form-control">
                            <option value="full" {if $feed.feed_type == 'full'}selected{/if}>{$l s='Full'}</option>
                            <option value="update" {if $feed.feed_type == 'update'}selected{/if}>{$l s='Update'}</option>
                        </select>
                    </div>
                </div>
            {/foreach}
        </div>
        <div class="panel-footer">
            <button type="submit" class="btn btn-default pull-right">{$l s='Save'}</button>
        </div>
    </div>
</form>
