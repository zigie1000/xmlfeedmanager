<form action="{$link->getAdminLink('AdminXmlFeedManager')}" method="post" class="defaultForm form-horizontal">
    <div class="panel">
        <h3>{$module_name}</h3>
        <div class="form-group">
            <label class="control-label col-lg-3" for="XMLFEEDMANAGER_FEED_NAMES">Feed Names (one per line)</label>
            <div class="col-lg-9">
                <textarea name="XMLFEEDMANAGER_FEED_NAMES" id="XMLFEEDMANAGER_FEED_NAMES" cols="60" rows="10">{$fields_value.XMLFEEDMANAGER_FEED_NAMES}</textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3" for="XMLFEEDMANAGER_FEED_URLS">Feed URLs (one per line)</label>
            <div class="col-lg-9">
                <textarea name="XMLFEEDMANAGER_FEED_URLS" id="XMLFEEDMANAGER_FEED_URLS" cols="60" rows="10">{$fields_value.XMLFEEDMANAGER_FEED_URLS}</textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3" for="XMLFEEDMANAGER_FEED_TYPES">Feed Types (one per line, 'full' or 'update')</label>
            <div class="col-lg-9">
                <textarea name="XMLFEEDMANAGER_FEED_TYPES" id="XMLFEEDMANAGER_FEED_TYPES" cols="60" rows="10">{$fields_value.XMLFEEDMANAGER_FEED_TYPES}</textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3" for="XMLFEEDMANAGER_MARKUP">Markup Percentage</label>
            <div class="col-lg-9">
                <input type="text" name="XMLFEEDMANAGER_MARKUP" id="XMLFEEDMANAGER_MARKUP" value="{$fields_value.XMLFEEDMANAGER_MARKUP}">
            </div>
        </div>
        {foreach from=$feeds item=feed name=feeds}
            <div class="input-group">
                <input type="text" name="XMLFEEDMANAGER_FEED_NAMES[]" value="{$feed.feed_name}">
                <input type="text" name="XMLFEEDMANAGER_FEED_URLS[]" value="{$feed.feed_url}">
                <select name="XMLFEEDMANAGER_FEED_TYPES[]">
                    <option value="full" {if $feed.feed_type == 'full'}selected{/if}>{$l s='Full'}</option>
                    <option value="update" {if $feed.feed_type == 'update'}selected{/if}>{$l s='Update'}</option>
                </select>
                <button type="button" class="btn btn-danger remove-feed">Remove</button>
            </div>
            <br>
        {/foreach}
        <div class="form-group">
            <label class="control-label col-lg-3" for="XMLFEEDMANAGER_FIELD_MAPPING">Field Mapping</label>
            <div class="col-lg-9">
                {foreach from=$XMLFEEDMANAGER_FIELD_MAPPING key=xmlField item=prestashopField}
                    <div class="input-group">
                        <span class="input-group-addon">{$xmlField}</span>
                        <select name="XMLFEEDMANAGER_FIELD_MAPPING[{$xmlField}]">
                            {foreach from=$PRESTASHOP_FIELDS item=field}
                                <option value="{$field.id}" {if $prestashopField == $field.id}selected{/if}>{$field.name}</option>
                            {/foreach}
                        </select>
                    </div>
                    <br>
                {/foreach}
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-9 col-lg-offset-3">
                <button type="submit" name="submit{$module_name}" class="btn btn-default pull-right">{$l s='Save'}</button>
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
                {foreach from=$feeds item=feed name=feeds}
                    <tr>
                        <td>{$feed.feed_name}</td>
                        <td>{$feed.feed_url}</td>
                        <td>{$feed.feed_type}</td>
                        <td>{$feed.last_imported}</td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
</form>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.remove-feed').forEach(function(button) {
            button.addEventListener('click', function() {
                this.closest('.input-group').remove();
            });
        });
    });
</script>
