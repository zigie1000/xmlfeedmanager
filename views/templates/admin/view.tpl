{extends file='layouts/layout.tpl'}

{block name="content"}
<div class="panel">
    <div class="panel-heading">
        <i class="icon-cogs"></i> {$module.displayName|escape:'html':'UTF-8'}
    </div>
    <div class="form-wrapper">
        <form id="xml_feed_manager_form" class="defaultForm form-horizontal" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label class="control-label col-lg-3">
                    {l s='Feed Type' mod='xmlfeedmanager'}
                </label>
                <div class="col-lg-6">
                    <select name="XMLFEEDMANAGER_FEED_TYPE" id="XMLFEEDMANAGER_FEED_TYPE" class="form-control">
                        <option value="product_feed" {if $feed_type == 'product_feed'}selected="selected"{/if}>{l s='Product Feed' mod='xmlfeedmanager'}</option>
                        <option value="category_feed" {if $feed_type == 'category_feed'}selected="selected"{/if}>{l s='Category Feed' mod='xmlfeedmanager'}</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-offset-3 col-lg-6">
                    <button type="submit" name="submitXMLFeedManager" class="btn btn-default pull-right">
                        <i class="process-icon-save"></i> {l s='Save' mod='xmlfeedmanager'}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
{/block}
