<form action="{$link->getAdminLink('AdminXmlFeedManager')}" method="post" class="defaultForm form-horizontal">
    <div class="panel">
        <h3>{$module_name}</h3>
        <div id="feed-container">
            {foreach from=$XMLFEEDMANAGER_FEED_NAMES item=feedName key=index}
            <div class="form-group feed-group">
                <label class="control-label col-lg-3">{$l s='Feed Name'}</label>
                <div class="col-lg-3">
                    <input type="text" name="XMLFEEDMANAGER_FEED_NAMES[]" value="{$feedName}" class="form-control">
                </div>
                <label class="control-label col-lg-3">{$l s='Feed URL'}</label>
                <div class="col-lg-3">
                    <input type="text" name="XMLFEEDMANAGER_FEED_URLS[]" value="{$XMLFEEDMANAGER_FEED_URLS[$index]}" class="form-control">
                </div>
            </div>
            {/foreach}
        </div>
        <div class="panel-footer">
            <button type="button" id="add-feed" class="btn btn-default">{$l s='Add Feed'}</button>
            <button type="submit" class="btn btn-default pull-right" name="submitxmlfeedmanager">
                <i class="process-icon-save"></i> {$l s='Save'}
            </button>
        </div>
    </div>
</form>

{include file='field_list.tpl'}

<script type="text/javascript">
document.getElementById('add-feed').addEventListener('click', function() {
    var container = document.getElementById('feed-container');
    var feedGroup = document.createElement('div');
    feedGroup.className = 'form-group feed-group';

    var feedNameLabel = document.createElement('label');
    feedNameLabel.className = 'control-label col-lg-3';
    feedNameLabel.textContent = '{$l s='Feed Name'}';
    feedGroup.appendChild(feedNameLabel);

    var feedNameInputDiv = document.createElement('div');
    feedNameInputDiv.className = 'col-lg-3';
    var feedNameInput = document.createElement('input');
    feedNameInput.type = 'text';
    feedNameInput.name = 'XMLFEEDMANAGER_FEED_NAMES[]';
    feedNameInput.className = 'form-control';
    feedNameInputDiv.appendChild(feedNameInput);
    feedGroup.appendChild(feedNameInputDiv);

    var feedUrlLabel = document.createElement('label');
    feedUrlLabel.className = 'control-label col-lg-3';
    feedUrlLabel.textContent = '{$l s='Feed URL'}';
    feedGroup.appendChild(feedUrlLabel);

    var feedUrlInputDiv = document.createElement('div');
    feedUrlInputDiv.className = 'col-lg-3';
    var feedUrlInput = document.createElement('input');
    feedUrlInput.type = 'text';
    feedUrlInput.name = 'XMLFEEDMANAGER_FEED_URLS[]';
    feedUrlInput.className = 'form-control';
    feedUrlInputDiv.appendChild(feedUrlInput);
    feedGroup.appendChild(feedUrlInputDiv);

    container.appendChild(feedGroup);
});
</script>
