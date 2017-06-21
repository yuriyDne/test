<?php

namespace Action;

use Enum\TreeActionEnum;
use Repository\CommentRepository;

class CommentAction extends AbstractAction
{
    public function run()
    {
        $action = new TreeActionEnum($_GET['action']);
        $repository = CommentRepository::getInstance();
        switch ($action->getValue()) {
            case TreeActionEnum::DELETE:
                $id = (int) $_POST['id'];
                $repository->delete($id);
                $this->renderJson('OK');
                break;
            case TreeActionEnum::SHOW:
                $parentId = (int) $_GET['parentId'];
                $comments = ($parentId === 0)
                    ? $repository->getLevelItems(1)
                    : $repository->getAll($parentId);
                $content = $this->getContent(
                    'commentList',
                    ['comments' => $comments]
                );
                $this->renderJson(['content' => $content]);
                break;
            case TreeActionEnum::ADD:
                $parentId = (int) (isset($_POST['parentId']) ? $_POST['parentId'] : 0);
                $content = $_POST['content'];
                $itemId = $repository->add($content, $parentId);
                $item = $repository->getById($itemId);
                $content = $this->getContent('commentItem', ['item' => $item]);
                $this->renderJson(['content' => $content]);
                break;
        }
    }

}