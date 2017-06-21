<?php
use Enum\ActionEnum;
use Factory\ActionFactory;
require_once(__DIR__.'/autoloader.php');

define('STATIC_PATH', getenv('STATIC_PATH'));

$actionName = !empty($_REQUEST['controllerAction']) ? urldecode($_REQUEST['controllerAction']) : ActionEnum::INDEX;
$actionFactory = new ActionFactory();

$actionFactory->buildAction(new ActionEnum($actionName))
    ->run();
?>
