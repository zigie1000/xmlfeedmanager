<form action="{$link->getAdminLink('AdminXmlFeedManager')}" method="post" class="defaultForm form-horizontal">
    <div class="panel">
        <h3>{$l s='Recommended Mappings'}</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>{$l s='XML Field'}</th>
                    <th>{$l s='Suggested PrestaShop Field'}</th>
                    <th>{$l s='Confirm'}</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$mappings item=mapping}
                <tr>
                    <td>{$mapping.xml_field}</td>
                    <td>
                        <input type="text" name="mappings[{$mapping.xml_field}]" value="{$mapping.prestashop_field}" class="form-control">
                    </td>
                    <td>
                        <input type="checkbox" name="confirm_mappings[]" value="{$mapping.xml_field}">
                    </td>
                </tr>
                {/foreach}
            </tbody>
        </table>
        <div class="panel-footer">
            <button type="submit" class="btn btn-default pull-right" name="confirmMappings">
                <i class="process-icon-validate"></i> {$l s='Confirm Mappings'}
            </button>
        </div>
    </div>
</form>
