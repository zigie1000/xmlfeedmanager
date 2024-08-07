<div class="panel">
    <div class="panel-heading">{$module_name}</div>
    <form action="{$current}" method="post" class="form-horizontal">
        <div class="panel-body" id="feed-form-group">
            <div class="form-group">
                <label class="control-label col-lg-3" for="feed_names">{$this->l('Feed Names (one per line)')}</label>
                <div class="col-lg-9">
                    <textarea id="feed_names" name="XMLFEEDMANAGER_FEED_NAMES" class="form-control">{$XMLFEEDMANAGER_FEED_NAMES}</textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3" for="feed_urls">{$this->l('Feed URLs (one per line)')}</label>
                <div class="col-lg-9">
                    <textarea id="feed_urls" name="XMLFEEDMANAGER_FEED_URLS" class="form-control">{$XMLFEEDMANAGER_FEED_URLS}</textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3" for="feed_types">{$this->l('Feed Types (one per line)')}</label>
                <div class="col-lg-9">
                    <textarea id="feed_types" name="XMLFEEDMANAGER_FEED_TYPES" class="form-control">{$XMLFEEDMANAGER_FEED_TYPES}</textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3" for="markup_percentage">{$this->l('Markup Percentage')}</label>
                <div class="col-lg-9">
                    <input type="text" id="markup_percentage" name="XMLFEEDMANAGER_MARKUP_PERCENTAGE" value="{$XMLFEEDMANAGER_MARKUP_PERCENTAGE}" class="form-control">
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <button type="submit" name="submit{$module_name}" class="btn btn-default pull-right">{$this->l('Save')}</button>
        </div>
    </form>
</div>
