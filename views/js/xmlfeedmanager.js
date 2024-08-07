document.addEventListener('DOMContentLoaded', function () {
    var addButton = document.createElement('button');
    addButton.innerHTML = 'Add Feed';
    addButton.className = 'btn btn-default';
    addButton.onclick = function () {
        var feedFormGroup = document.getElementById('feed-form-group');
        var feedCount = feedFormGroup.children.length / 3;
        var newFeedNameDiv = document.createElement('div');
        newFeedNameDiv.className = 'form-group';
        newFeedNameDiv.innerHTML = '<label class="control-label col-lg-3" for="feed_name_' + feedCount + '">Feed Name</label>' +
            '<div class="col-lg-9">' +
            '<input type="text" id="feed_name_' + feedCount + '" name="XMLFEEDMANAGER_FEED_NAMES[]" class="form-control">' +
            '</div>';
        var newFeedUrlDiv = document.createElement('div');
        newFeedUrlDiv.className = 'form-group';
        newFeedUrlDiv.innerHTML = '<label class="control-label col-lg-3" for="feed_url_' + feedCount + '">Feed URL</label>' +
            '<div class="col-lg-9">' +
            '<input type="text" id="feed_url_' + feedCount + '" name="XMLFEEDMANAGER_FEED_URLS[]" class="form-control">' +
            '</div>';
        var newFeedTypeDiv = document.createElement('div');
        newFeedTypeDiv.className = 'form-group';
        newFeedTypeDiv.innerHTML = '<label class="control-label col-lg-3" for="feed_type_' + feedCount + '">Feed Type</label>' +
            '<div class="col-lg-9">' + '<select id="feed_type_' + feedCount + '" name="XMLFEEDMANAGER_FEED_TYPES[]" class="form-control">' +
            '<option value="full">Full</option>' +
            '<option value="update">Update</option>' +
            '</select>' +
            '</div>';
        feedFormGroup.appendChild(newFeedNameDiv);
        feedFormGroup.appendChild(newFeedUrlDiv);
        feedFormGroup.appendChild(newFeedTypeDiv);
    };
    document.querySelector('.panel-footer').insertBefore(addButton, document.querySelector('.btn.btn-default.pull-right'));
});  
