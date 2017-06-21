$(function() {
    window.treeContent.init(
        '/comment?action=show&',
        '/comment?action=add',
        '/comment?action=delete'
    );

    var selector = $('#js-comments');
    window.treeContent.load(selector, 0)

    $('body').on('click', '.js-comment-submit', function(){
        var parentId = $(this).parent().find('.js-parent-id').val();
        var content = $(this).parent().find('.js-comment-text').val();
        var $container = $(this).closest('.js-comment').length
            ? $(this).closest('.js-comment')
            : $('#js-comments').parent();
        window.treeContent.add($container, content, parentId);
        if ($(this).closest('.js-comment').length) {
            $(this).parent().remove();
        }
    });

    $('body').on('click', '.js-add-comment', function() {
        var $commentForm = $('.js-comment-form').first().clone();
        var parentId = $(this).closest('.js-comment').data('id');
        $commentForm.find('.js-parent-id').val(parentId);
        $commentForm.find('.js-comment-text').val('')
        var $comment = $(this).closest('.js-comment');
        if (!$comment.find('.js-comment-form').first().length) {
            $comment.find('.js-form-container').first().append($commentForm);
        }
    });

    $('body').on('click', '.js-comment-remove', function(){
        var $selector = $(this).closest('.js-comment');
        var commentId = $selector.data('id');
        window.treeContent.delete($selector, commentId);
    });

    $('body').on('click', '.js-show-parents', function() {
        var $comment = $(this).closest('.js-comment');
        var parentId = $(this).data('parent-id');
        window.treeContent.load($comment, parentId);
    })

});