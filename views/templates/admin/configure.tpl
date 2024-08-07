<div class="panel">
    <div class="panel-heading">{$module->displayName}</div>
    <div class="panel-body">
        <form action="{$currentIndex}&token={$token}&configure={$module->name}" method="post" class="form-horizontal">
            <div id="feed-form-group">
                <div class="form-group">
                    <label class="control-label col-lg-3" for="XMLFEEDMANAGER_FEED_NAMES">{$module->l('Feed Names (one per line)')}</label>
                    <div class="col-lg-9">
                        <textarea id="XMLFEEDMANAGER_FEED_NAMES" name="XMLFEEDMANAGER_FEED_NAMES[]" class="form-control">{$XMLFEEDMANAGER_FEED_NAMES}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-3" for="XMLFEEDMANAGER_FEED_URLS">{$module->l('Feed URLs (one per line)')}</label>
                    <div class="col-lg-9">
                        <textarea id="XMLFEEDMANAGER_FEED_URLS" name="XMLFEEDMANAGER_FEED_URLS[]" class="form-control">{$XMLFEEDMANAGER_FEED_URLS}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-3" for="XMLFEEDMANAGER_FEED_TYPES">{$module->l('Feed Types (one per line)')}</label>
                    <div class="col-lg-9">
                        <textarea id="XMLFEEDMANAGER_FEED_TYPES" name="XMLFEEDMANAGER_FEED_TYPES[]" class="form-control">{$XMLFEEDMANAGER_FEED_TYPES}</textarea>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3" for="XMLFEEDMANAGER_MARKUP_PERCENTAGE">{$module->l('Markup Percentage')}</label>
                <div class="col-lg-9">
                    <input type="text" id="XMLFEEDMANAGER_MARKUP_PERCENTAGE" name="XMLFEEDMANAGER_MARKUP_PERCENTAGE" class="form-control" value="{$XMLFEEDMANAGER_MARKUP_PERCENTAGE}">
                </div>
            </div>
            <div class="panel-footer">
                <button type="submit" name="submit{$module->name}" class="btn btn-default pull-right">{$module->l('Save')}</button>
            </div>
        </form>
    </div>
</div>
