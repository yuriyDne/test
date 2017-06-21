<?php

namespace Action;

abstract class AbstractAction
{
    abstract public function run();

    const VIEW_PATS = '../views/';

    /**
     * @param string $viewName
     * @param null|array $params
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function render($viewName, array $params = null)
    {
        if (!is_string($viewName)) {
            throw new \InvalidArgumentException('param viewName must be string');
        }

        $viewPath = __DIR__.'/'.self::VIEW_PATS.$viewName.'.php';
        if (!is_file($viewPath)) {
            throw new \RuntimeException(
                sprintf('view file %s not found', $viewPath)
            );
        }

        if (!is_null($params)) {
            extract($params, EXTR_SKIP);
        }

        require $viewPath;
    }

    /**
     * @param $viewName
     * @param array|null $params
     * @return string
     */
    public function getContent($viewName, array $params = null)
    {
        ob_start();
        ob_implicit_flush(false);
        $this->render($viewName, $params);

        return ob_get_clean();
    }

    /**
     * @param $data
     */
    public function renderJson($data)
    {
        header('Content-type:application/json;charset=utf-8');
        echo json_encode($data);
        die();
    }
}
