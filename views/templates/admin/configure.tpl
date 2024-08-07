<div class="panel">
    <div class="panel-heading">
        {$module.displayName}
    </div>
    <div class="form-horizontal">
        <form action="{$current}&token={$token}" method="post">
            <div id="feed-form-group">
                <div class="form-group">
                    <label class="control-label col-lg-3" for="feed_name_0">Feed Name</label>
                    <div class="col-lg-9">
                        <input type="text" id="feed_name_0" name="XMLFEEDMANAGER_FEED_NAMES[]" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-3" for="feed_url_0">Feed URL</label>
                    <div class="col-lg-9">
                        <input type="text" id="feed_url_0" name="XMLFEEDMANAGER_FEED_URLS[]" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-3" for="feed_type_0">Feed Type</label>
                    <div class="col-lg-9">
                        <select id="feed_type_0" name="XMLFEEDMANAGER_FEED_TYPES[]" class="form-control">
                            <option value="full">Full</option>
                            <option value="update">Update</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3" for="XMLFEEDMANAGER_MARKUP_PERCENTAGE">Markup Percentage</label>
                <div class="col-lg-9">
                    <input type="text" id="XMLFEEDMANAGER_MARKUP_PERCENTAGE" name="XMLFEEDMANAGER_MARKUP_PERCENTAGE" class="form-control">
                </div>
            </div>
            <div class="panel-footer">
                <button type="submit" class="btn btn-default pull-right">{$this->l('Save')}</button>
            </div>
        </form>
    </div>
</div>
