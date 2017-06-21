<?php
namespace Factory;

use Enum\ActionEnum;

class ActionFactory
{
    /**
     * @param ActionEnum $action
     *
     * @return \Action\IndexAction
     * @throws \RuntimeException
     */
    public function buildAction(ActionEnum $action)
    {
        switch($action->getValue()) {
            case $action::INDEX:
                return new \Action\IndexAction();
            case $action::COMMENTS:
                return new \Action\CommentAction();
        }

        throw new \RuntimeException('Cannot specify action for: '.$action->getValue());
    }
}
