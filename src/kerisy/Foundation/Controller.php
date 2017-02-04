<?php
/**
 * Kerisy Framework
 *
 * PHP Version 7
 *
 * @author          kaihui.wang <hpuwang@gmail.com>
 * @copyright      (c) 2015 putao.com, Inc.
 * @package         kerisy/framework
 * @version         3.0.0
 */

namespace Kerisy\Foundation;

use Kerisy\Config\Config;
use Kerisy\Http\Request;
use Kerisy\Http\Response;
use Kerisy\Mvc\AssignData;
use Kerisy\Mvc\Template;
use Kerisy\Support\Arr;
use Kerisy\Support\Dir;
use Kerisy\Support\ElapsedTime;

class Controller
{

    const RESPONSE_CODE = 200;
    const RESPONSE_NORMAL_ERROR_CODE = 500;

    /**
     * @var \Kerisy\Http\View;
     */
    public $view;

    /**
     * @var \Kerisy\Http\Request
     */
    protected $request = null;

    /**
     * @var \Kerisy\Http\Response
     */
    protected $response = null;


    public function __construct(Request $request = null, Response $response = null)
    {
        $this->request = $request;
        $this->response = $response;
        $this->view = new AssignData();
    }

    /**
     * 模板render
     *
     * @param $viewPath
     * @param array $assign
     * @return mixed
     * @throws \Kerisy\Mvc\Exception\InvalidArgumentException
     */
    public function render($viewPath, $assign = [])
    {

        $fisPath = Config::get("_release.path");
        if ($fisPath) {
            $fis = Config::get("app.view.fis.view_path");
            $viewRoot = Dir::formatPath($fisPath) . $fis;
        } else {
            $viewRoot = Config::get("app.view.path");
        }

        $theme = Config::get("app.view.theme");
        $realViewRoot = Dir::formatPath($viewRoot) . $theme;
        Template::setViewRoot($realViewRoot);

        $viewCachePath = Config::get("app.view.compile_path");

        Template::setViewCacheRoot($viewCachePath);
        Template::setEngine(Config::get("app.view.engine"));
        $config = Config::get("app.view.blade_ex");
        Template::setConfig($config);

        $assign = Arr::merge($assign, $this->view->getAssignData());
        $assign = Arr::merge($assign, $this->response->view->getAssignData());
        $content = Template::render($viewPath, $assign);
        return $content;
    }

    /**
     * 显示模板
     * @param $viewPath
     * @param array $assign
     */
    public function display($viewPath, $assign = [])
    {
        $content = $this->render($viewPath, $assign);
        $this->response->end($content);
    }


    /**
     * @param $data
     * @param int $errorCode
     * @param string $errodMsg
     */
    public function response($data, $errorCode = self::RESPONSE_CODE, $errorMsg = '')
    {
        $elapsedTime = ElapsedTime::runtime("sys_elapsed_time");
        $result = [];
        $result['result'] = $data;
        $result['errorCode'] = $errorCode;
        $result['errorMsg'] = $errorMsg;
        $result['elapsedTime'] = $elapsedTime;
        $this->response->header("Content-type", "application/json");
        $content = json_encode($result, JSON_UNESCAPED_UNICODE);
        $this->response->end($content);
    }

}