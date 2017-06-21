<?php
/**
 * @var array $item
 */
?>
<li class="js-comment"
    data-id="<?=$item['id'];?>"
>
    <div class="content"><?=$item['content'];?></div>
    <div class="action">
        <a class="js-add-comment" href="#">Add comment</a>
        <a class="js-comment-remove" href="#">Delete</a>
        <a class="js-show-parents" href="#" data-parent-id="<?=$item['id'];?>">
            Show childs
        </a>
    </div>
    <div class="js-form-container"></div>
</li>
