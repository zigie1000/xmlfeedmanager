<form action="{$currentIndex}&token={$token}" method="post" class="defaultForm form-horizontal" enctype="multipart/form-data">
    <div class="panel">
        <div class="panel-heading">
            {$title}
        </div>
        <div class="form-wrapper" id="feed-form-group">
            {foreach $fields_value.XMLFEEDMANAGER_FEED_NAMES as $index => $feed_name}
                <div class="form-group">
                    <label class="control-label col-lg-3" for="feed_name_{$index}">{$module->l('Feed Name')}</label>
                    <div class="col-lg-9">
                        <input type="text" id="feed_name_{$index}" name="XMLFEEDMANAGER_FEED_NAMES[]" class="form-control" value="{$feed_name|escape:'htmlall':'UTF-8'}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-3" for="feed_url_{$index}">{$module->l('Feed URL')}</label>
                    <div class="col-lg-9">
                        <input type="text" id="feed_url_{$index}" name="XMLFEEDMANAGER_FEED_URLS[]" class="form-control" value="{$fields_value.XMLFEEDMANAGER_FEED_URLS[$index]|escape:'htmlall':'UTF-8'}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-3" for="feed_type_{$index}">{$module->l('Feed Type')}</label>
                    <div class="col-lg-9">
                        <select id="feed_type_{$index}" name="XMLFEEDMANAGER_FEED_TYPES[]" class="form-control">
                            <option value="full" {if $fields_value.XMLFEEDMANAGER_FEED_TYPES[$index] == 'full'}selected="selected"{/if}>{$module->l('Full')}</option>
                            <option value="update" {if $fields_value.XMLFEEDMANAGER_FEED_TYPES[$index] == 'update'}selected="selected"{/if}>{$module->l('Update')}</option>
                        </select>
                    </div>
                </div>
            {/foreach}
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3" for="XMLFEEDMANAGER_MARKUP_PERCENTAGE">{$module->l('Markup Percentage')}</label>
            <div class="col-lg-9">
                <input type="text" id="XMLFEEDMANAGER_MARKUP_PERCENTAGE" name="XMLFEEDMANAGER_MARKUP_PERCENTAGE" class="form-control" value="{$fields_value.XMLFEEDMANAGER_MARKUP_PERCENTAGE|escape:'htmlall':'UTF-8'}">
            </div>
        </div>
        <div class="panel-footer">
            <button type="submit" value="1" id="submitAddconfiguration" name="submitAddconfiguration" class="btn btn-default pull-right">
                <i class="process-icon-save"></i> {$module->l('Save')}
            </button>
        </div>
    </div>
</form>
