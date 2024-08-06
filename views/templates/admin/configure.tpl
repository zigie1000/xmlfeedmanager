<form action="{$link->getAdminLink('AdminXmlFeedManager')}" method="post" class="defaultForm form-horizontal" enctype="multipart/form-data">
    <div class="panel">
        <h3>{$module_name}</h3>
        <div class="form-group">
            <label class="control-label col-lg-3">{$l s='XML Feed URL'}</label>
            <div class="col-lg-9">
                <input type="text" name="XMLFEEDMANAGER_XML_FEED_URL" value="{$XMLFEEDMANAGER_XML_FEED_URL}" class="form-control">
            </div>
        </div>
        <div class="panel-footer">
            <button type="submit" class="btn btn-default pull-right" name="submitxmlfeedmanager">
                <i class="process-icon-save"></i> {$save}
            </button>
        </div>
    </div>
</form>

{include file='field_list.tpl'}
