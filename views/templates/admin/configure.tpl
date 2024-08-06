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

<hr>

<form action="{$link->getAdminLink('AdminXmlFeedManager')}" method="post" class="defaultForm form-horizontal">
    <div class="panel">
        <h3>{$l s='Custom Fields'}</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>{$l s='Field Name'}</th>
                    <th>{$l s='PrestaShop Field'}</th>
                    <th>{$l s='Actions'}</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$fields item=field}
                <tr>
                    <td>{$field.field_name}</td>
                    <td>{$field.prestashop_field}</td>
                    <td>
                        <button type="submit" name="deleteXmlField" value="{$field.id_field}" class="btn btn-danger">{$l s='Delete'}</button>
                    </td>
                </tr>
                {/foreach}
            </tbody>
        </table>
        <div class="form-group">
            <label class="control-label col-lg-3">{$l s='Field Name'}</label>
            <div class="col-lg-9">
                <input type="text" name="field_name" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3">{$l s='PrestaShop Field'}</label>
            <div class="col-lg-9">
                <input type="text" name="prestashop_field" class="form-control">
            </div>
        </div>
        <div class="panel-footer">
            <button type="submit" class="btn btn-default pull-right" name="addXmlField">
                <i class="process-icon-plus"></i> {$l s='Add Field'}
            </button>
        </div>
    </div>
</form>
