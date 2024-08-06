{extends file='module:ps_specialslist/views/templates/admin/layout.tpl'}

{block name='title'}{$module_name}{/block}

{block name='content'}
<form action="{$link->getAdminLink('AdminXmlFeedManager')}" method="post" class="defaultForm form-horizontal">
    <div class="panel">
        <h3>{$module_name}</h3>
        <div class="form-group">
            <label class="control-label col-lg-3">Markup Percentage</label>
            <div class="col-lg-9">
                <input type="text" name="XMLFEEDMANAGER_MARKUP_PERCENTAGE" value="{$fields_value.XMLFEEDMANAGER_MARKUP_PERCENTAGE}" class="form-control" />
                <p class="help-block">Enter the markup percentage to be applied to product




                prices.</p>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3">Feed Names (one per line)</label>
            <div class="col-lg-9">
                <textarea name="XMLFEEDMANAGER_FEED_NAMES" cols="60" rows="10" class="form-control">{$fields_value.XMLFEEDMANAGER_FEED_NAMES}</textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3">Feed URLs (one per line)</label>
            <div class="col-lg-9">
                <textarea name="XMLFEEDMANAGER_FEED_URLS" cols="60" rows="10" class="form-control">{$fields_value.XMLFEEDMANAGER_FEED_URLS}</textarea>
            </div>
        </div>

        {foreach from=$feeds item=feed name=feeds}
        <div class="input-group">
            <input type="text" name="XMLFEEDMANAGER_FEED_NAMES[]" value="{$feed.feed_name}" class="form-control" placeholder="Feed Name" />
            <input type="text" name="XMLFEEDMANAGER_FEED_URLS[]" value="{$feed.feed_url}" class="form-control" placeholder="Feed URL" />
            <select name="XMLFEEDMANAGER_FEED_TYPES[]" class="form-control">
                <option value="full" {if $feed.feed_type == 'full'}selected{/if}>{$l s='Full'}</option>
                <option value="update" {if $feed.feed_type == 'update'}selected{/if}>{$l s='Update'}</option>
            </select>
            <button type="button" class="btn btn-danger btn-sm" onclick="$(this).closest('.input-group').remove();">Remove</button>
        </div>
        <br>
        {/foreach}

        <div class="form-group">
            <div class="col-lg-9 col-lg-offset-3">
                <button type="button" class="btn btn-default" onclick="addFeedInput();">Add Feed</button>
            </div>
        </div>
    </div>

    <div class="panel">
        <h3>{$l s='Feed History'}</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>{$l s='Feed Name'}</th>
                    <th>{$l s='URL'}</th>
                    <th>{$l s='Type'}</th>
                    <th>{$l s='Last Imported'}</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$feeds item=feed}
                <tr>
                    <td>{$feed.feed_name}</td>
                    <td>{$feed.feed_url}</td>
                    <td>{if $feed.feed_type == 'full'}{$l s='Full'}{else}{$l s='Update'}{/if}</td>
                    <td>{$feed.last_imported}</td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    </div>

    <div class="panel-footer">
        <button type="submit" class="btn btn-default pull-right">{$l s='Save'}</button>
    </div>
</form>

<script type="text/javascript">
function addFeedInput() {
    var html = '<div class="input-group">';
    html += '<input type="text" name="XMLFEEDMANAGER_FEED_NAMES[]" class="form-control" placeholder="Feed Name" />';
    html += '<input type="text" name="XMLFEEDMANAGER_FEED_URLS[]" class="form-control" placeholder="Feed URL" />';
    html += '<select name="XMLFEEDMANAGER_FEED_TYPES[]" class="form-control">';
    html += '<option value="full">Full</option>';
    html += '<option value="update">Update</option>';
    html += '</select>';
    html += '<button type="button" class="btn btn-danger btn-sm" onclick="$(this).closest(\'.input-group\').remove();">Remove</button>';
    html += '</div><br>';
    $('.panel').first().append(html);
}
</script>
{/block}
