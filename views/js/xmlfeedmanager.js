$(document).ready(function() {
    $('#add-feed').click(function() {
        var feedGroup = `
            <div class="form-group">
                <label class="control-label col-lg-3">${$('#feedNameLabel').val()}</label>
                <div class="col-lg-9">
                    <input type="text" name="XMLFEEDMANAGER_FEED_NAMES[]" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3">${$('#feedUrlLabel').val()}</label>
                <div class="col-lg-9">
                    <input type="text" name="XMLFEEDMANAGER_FEED_URLS[]" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3">${$('#feedTypeLabel').val()}</label>
                <div class="col-lg-9">
                    <select name="XMLFEEDMANAGER_FEED_TYPES[]" class="form-control">
                        <option value="full">${$('#fullLabel').val()}</option>
                        <option value="update">${$('#updateLabel').val()}</option>
                    </select>
                </div>
            </div>`;
        $('#feed-form-group').append(feedGroup);
    });

    $('#remove-feed').click(function() {
        $('#feed-form-group .form-group').slice(-3).remove();
    });
});
