{extends file='layouts/layout.tpl'}

{block name="content"}
<div class="panel">
    <div class="panel-heading">
        <i class="icon-cogs"></i> {$module.displayName}
    </div>
    <div class="panel-body">
        {if isset($confirmation)}
        <div class="alert alert-success">
            {$confirmation}
        </div>
        {/if}

        <form action="{$module.module_url}" method="post" class="form-horizontal">
            <div class="form-group">
                <label class="control-label col-lg-3" for="feed_type">{$module.l('Feed Type')}</label>
                <div class="col-lg-9">
                    <select name="feed_type" id="feed_type" class="form-control">
                        {foreach from=$feed_types item=feed_type}
                            <option value="{$feed_type.id}" {if $feed_type.selected}selected{/if}>{$feed_type.name}</option>
                        {/foreach}
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-3" for="xml_field">{$module.l('XML Field')}</label>
                <div class="col-lg-9">
                    <input type="text" name="xml_field" id="xml_field" class="form-control" value="{$xml_field}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-3" for="prestashop_field">{$module.l('PrestaShop Field')}</label>
                <div class="col-lg-9">
                    <select name="prestashop_field" id="prestashop_field" class="form-control">
                        {foreach from=$prestashop_fields item=prestashop_field}
                            <option value="{$prestashop_field}" {if $prestashop_field.selected}selected{/if}>{$prestashop_field}</option>
                        {/foreach}
                    </select>
                </div>
            </div>

            <div class="panel-footer">
                <button type="submit" name="submit_configure" class="btn btn-primary">{$module.l('Save')}</button>
            </div>
        </form>
    </div>
</div>
{/block}
