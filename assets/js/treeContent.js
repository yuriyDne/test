window.treeContent = {
    loadUrl: '',
    addUrl: '',
    deleteUrl: '',
    containerTag: 'ul',
    load: function($selector, parentId)
    {
        window.loader.showLoader();
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: this.loadUrl + 'parentId=' + parentId,
            success: function (data) {
                if (data.content.length > 0) {
                    $selector.append($(data.content));
                }
                window.loader.hideLoader();
            },
            error: function (data) {
                console.log('Error getting content '+data);
                window.loader.hideLoader();
            }
        });
    },

    add: function($selector, content, parentId)
    {
        if (!content.length) {
            alert('Content cannot be empty');
            return false;
        }
        var that = this;
        window.loader.showLoader();
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: this.addUrl,
            data: {
                parentId: parentId,
                content: content
            },
            success: function (data) {
                if (data.content.length > 0) {
                    if (!$selector.find(that.containerTag).length) {
                        var $tag = $("<"+that.containerTag+">");
                        $selector.append($tag);
                    }
                    $selector.find(that.containerTag).append($(data.content));
                }
                window.loader.hideLoader();
            },
            error: function (data) {
                console.log('Error adding item '+data);
                window.loader.hideLoader();
            }
        });

    },

    delete: function($selector, id)
    {
        window.loader.showLoader();
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: this.deleteUrl,
            data: {
                id: id
            },
            success: function (data) {
                $selector.remove();
                window.loader.hideLoader();
            },
            error: function (data) {
                console.log('Error deleting item '+id);
                window.loader.hideLoader();
            }
        });
    },

    init: function(loadUrl, addUrl, deleteUrl) {
        this.loadUrl = loadUrl;
        this.addUrl = addUrl;
        this.deleteUrl = deleteUrl;
    }
}