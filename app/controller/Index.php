<?php

namespace app\controller;

use app\BaseController;
use GuzzleHttp\Client;
use think\facade\Config;
use think\facade\View;

class Index extends BaseController
{
    public function index()
    {
        $captchaType = Config::get('app.captcha');
        $serverName = Config::get('app.server_name');
        View::assign('captcha', $captchaType); // 渲染验证码类型
        View::assign('reCaptcha', [ // 渲染 reCaptcha 参数
            'params' => Config::get('captcha.google'),
        ]);
        View::assign('geetest', [
            'params' => [
                'id' => Config::get('captcha.geetest')['id'],
            ],
        ]);
        View::assign('aliyun', [
            'params' => [
                'prefix' => Config::get('captcha.aliyun')['prefix'],
                'sceneId' => Config::get('captcha.aliyun')['sceneId'],
            ],
        ]);
        View::assign('serverName', $serverName);
        View::assign('ip', $this->getIp()); // 测试环境使用
        // View::assign('ip', $this->request->ip()); // 正式环境使用
        return View::fetch();
    }

    private function getIp()
    {
        $client = new Client();
        $response = $client->request('GET', 'https://v2.xxapi.cn/api/ip');
        $result = $response->getBody()->getContents();
        $data = json_decode($result, true);
        return $data['data']['ip'];
    }
}
