<?php
/**
 * @var \Action\AbstractAction $this
 */
?>
<?php if (!empty($comments)): ?>
    <ul>
        <?php $firstLevel = $currentLevel = reset($comments)['level']; ?>
        <?php
            foreach ($comments as $comment) {
                if ($comment['level'] > $currentLevel) {
                    echo "<ul>";
                    $currentLevel ++;
                }
                $this->render('commentItem', ['item' => $comment]);
                if ($comment['level'] < $currentLevel) {
                    echo "</ul>";
                    $currentLevel --;
                }
            }
            for ($i=$currentLevel; $i<$firstLevel; $i++) {
                echo "</ul>";
            }
        ?>
    </ul>
<?php endif; ?>