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
            '<div class="col-lg-
