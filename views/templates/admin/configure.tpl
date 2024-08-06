<form action="{$link->getAdminLink('AdminXmlFeedManager')}" method="post" class="defaultForm form-horizontal">
    <div class="panel">
        <h3>{$module_name}</h3>
        <div class="form-group">
            <label class="control-label col-lg-3">Feed Names and URLs</label>
            <div class="col-lg-9">
                {foreach from=$feeds item=feed name=feeds}
                    <div class="input-group">
                        <input type="text" name="XMLFEEDMANAGER_FEED_NAMES[]" value="{$feed.feed_name}" class="form-control" placeholder="Feed Name" />
                        <input type="text" name="XMLFEEDMANAGER_FEED_URLS[]" value="{$feed.feed_url}" class="form-control" placeholder="Feed URL" />
                        <select name="XMLFEEDMANAGER_FEED_TYPES[]" class="form-control">
                            <option value="full" {if $feed.feed_type == 'full'}selected{/if}>{$l s='Full'}</option>
                            <option value="update" {if $feed.feed_type == 'update'}selected{/if}>{$l s='Update'}</option>
                        </select>
                        <button type="button" class="btn btn-danger remove-feed">{$l s='Remove'}</button>
                    </div>
                    <br/>
                {/foreach}
                <button type="button" class="btn btn-primary" id="add-feed">{$l s='Add Feed'}</button>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3">Markup Percentage</label>
            <div class="col-lg-9">
                <input type="text" name="XMLFEEDMANAGER_MARKUP" value="{$XMLFEEDMANAGER_MARKUP}" class="form-control" />
                <p class="help-block">{$l s='Enter the markup percentage to be applied to product prices.'}</p>
            </div>
        </div>
        {foreach from=$XMLFEEDMANAGER_FIELD_MAPPING key=xmlField item=prestashopField}
        <div class="form-group">
            <label class="control-label col-lg-3">{$l s='Map '}{$xmlField}</label>
            <div class="col-lg-9">
                <select name="XMLFEEDMANAGER_FIELD_MAPPING[{$xmlField}]" class="form-control">
                    {foreach from=$PRESTASHOP_FIELDS item=field}
                    <option value="{$field.id}" {if $prestashopField == $field.id}selected="selected"{/if}>{$field.name}</option>
                    {/foreach}
                </select>
                <p class="help-block">{$l s='Select the corresponding PrestaShop field for the XML field '}{$xmlField}</p>
            </div>
        </div>
        {/foreach}
        <div class="panel-footer">
            <button type="submit" class="btn btn-default pull-right" name="submitxmlfeedmanager">
                <i class="process-icon-save"></i> {$l s='Save'}
            </button>
            <button type="submit" class="btn btn-primary pull-right" name="importFeeds" style="margin-right: 10px;">
                <i class="process-icon-refresh"></i> {$l s='Import Feeds'}
            </button>
        </div>
    </div>
</form>

<!-- Feed History Section -->
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
                <td>{ucfirst($feed.feed_type)}</td>
                <td>{if $feed.last_imported}{$feed.last_imported}{else}{$l s='Never'}{/if}</td>
            </tr>
            {/foreach}
        </tbody>
    </table>
</div>

<script>
document.getElementById('add-feed').addEventListener('click', function() {
    var feedContainer = document.createElement('div');
    feedContainer.className = 'input-group';
    feedContainer.innerHTML = `
        <input type="text" name="XMLFEEDMANAGER_FEED_NAMES[]" class="form-control" placeholder="Feed Name" />
        <input type="text" name="XMLFEEDMANAGER_FEED_URLS[]" class="form-control" placeholder="Feed URL" />
        <select name="XMLFEEDMANAGER_FEED_TYPES[]" class="form-control">
            <option value="full">Full</option>
            <option value="update">Update</option>
        </select>
        <button type="button" class="btn btn-danger remove-feed">Remove</button>
        <br/>
    `;
    document.querySelector('.form-group .col-lg-9').appendChild(feedContainer);
});

document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('remove-feed')) {
        e.target.closest('.input-group').remove();
    }
});
</script>
