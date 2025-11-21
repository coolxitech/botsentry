<?php

namespace app\controller;

use app\lib\Botsentry;
use app\lib\Captcha;
use app\lib\crack\Yescaptcha;
use app\utils\Response;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use think\facade\Config;
use think\facade\Request;

class Api
{
    public function callback()
    {
        $type = Request::param('type');
        $data = Request::param('data');
        if (empty($data)) {
            return json(['code' => -2, 'msg' => '缺少参数']);
        }
        switch ($type) {
            case 'geetest':
                $result = $this->geetest($data);
                break;
            case 'aliyun':
                $result = $this->aliyun($data);
                break;
            default:
                return json(['code' => 0, 'msg' => '参数错误']);
        }
        if ($result) {
            $yescaptcha = new Yescaptcha();
            $token = $yescaptcha->verify();
            return Response::json(0, '通过验证', [
                'token' => $token,
            ]);
        } else {
            return Response::json(-1, '验证失败');
        }
    }

    public function test()
    {
        $yescaptcha = new Yescaptcha();
        $token = $yescaptcha->verify();
        return Response::json(0, '验证成功', ['token' => $token]);
    }

    private function geetest(array $data): bool
    {
        $client = new Client();
        $captcha_key = Config::get('captcha.geetest.key');
        $sign_token = hash_hmac('sha256', $data['lot_number'], $captcha_key);
        try {
            $response = $client->request('POST', 'http://gcaptcha4.geetest.com/validate', [
                'form_params' => [
                    'lot_number' => $data['lot_number'],
                    'captcha_output' => $data['captcha_output'],
                    'pass_token' => $data['pass_token'],
                    'gen_time' => $data['gen_time'],
                    'captcha_id' => $data['captcha_id'],
                    'sign_token' => $sign_token,
                ]
            ]);
        } catch (GuzzleException $e) {
            return false;
        }
        $result = $response->getBody()->getContents();
        $data = json_decode($result, true);
        return $data['status'] === 'success' && $data['result'] === 'success';
    }

    private function aliyun(string $data): bool
    {
        $captcha = new Captcha();
        $data = $captcha->verify($data, Config::get('captcha.aliyun.sceneId'));
        return $data['Result']['VerifyResult'];
    }
}