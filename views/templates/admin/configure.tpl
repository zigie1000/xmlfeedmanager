<form action="{$link->getAdminLink('AdminXmlFeedManager')}" method="post" class="defaultForm form-horizontal" enctype="multipart/form-data">
    <div class="panel">
        <h3>{$module_name}</h3>
        <div class="form-group">
            <label class="control-label col-lg-3">{$l s='Example Setting'}</label>
            <div class="col-lg-9">
                <input type="text" name="example_setting" value="{$example_setting}" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3">{$l s='XML File'}</label>
            <div class="col-lg-9">
                <input type="file" name="xml_file" class="form-control">
            </div>
        </div>
        <div class="panel-footer">
            <button type="submit" class="btn btn-default pull-right" name="submitxmlfeedmanager">
                <i class="process-icon-save"></i> {$save}
            </button>
            <button type="submit" class="btn btn-default pull-right" name="scanXmlFeed">
                <i class="process-icon-scan"></i> {$scan}
            </button>
        </div>
    </div>
</form>

{include file='field_list.tpl'}
