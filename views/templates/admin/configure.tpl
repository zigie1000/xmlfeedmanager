{extends file="field_list.tpl"}

{block name="content"}
<div class="panel">
    <div class="panel-heading">
        {$module.displayName|escape:'htmlall':'UTF-8'}
    </div>
    <div class="form-wrapper">
        <form action="{$current}&token={$token}" method="post" class="form-horizontal">
            <div class="panel-body" id="feed-form-group">
                <div class="form-group">
                    <label class="control-label col-lg-3" for="feed_names">{$module.l('Feed Names (one per line)')}</label>
                    <div class="col-lg-9">
                        <textarea id="feed_names" name="XMLFEEDMANAGER_FEED_NAMES[]" class="form-control" rows="10">{$fields_value.XMLFEEDMANAGER_FEED_NAMES}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-3" for="feed_urls">{$module.l('Feed URLs (one per line)')}</label>
                    <div class="col-lg-9">
                        <textarea id="feed_urls" name="XMLFEEDMANAGER_FEED_URLS[]" class="form-control" rows="10">{$fields_value.XMLFEEDMANAGER_FEED_URLS}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-3" for="markup_percentage">{$module.l('Markup Percentage')}</label>
                    <div class="col-lg-9">
                        <input type="text" id="markup_percentage" name="XMLFEEDMANAGER_MARKUP_PERCENTAGE" value="{$fields_value.XMLFEEDMANAGER_MARKUP_PERCENTAGE}" class="form-control">
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <button type="submit" name="submit{$module.name}" class="btn btn-default pull-right">
                    <i class="process-icon-save"></i> {$module.l('Save')}
                </button>
            </div>
        </form>
    </div>
</div>
{/block}
