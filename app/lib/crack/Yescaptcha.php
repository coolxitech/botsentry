<?php

namespace app\lib\crack;

use app\interface\Crack;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use think\facade\Log;

class Yescaptcha implements Crack
{
    protected string $endpoint = 'https://cn.yescaptcha.com';
    protected Client $client;

    protected string $siteKey = '6Lflj6cZAAAAACI8uLiYrRgH6OlZRNuIkGzkPzSp';
    protected string $siteUrl = 'https://cyberdevelopment.es/BotSentry/verify/';

    protected string $clientKey = '';
    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => $this->endpoint,
            'timeout' => 0,
        ]);
        $this->clientKey = config('crack.yescaptcha.ClientKey');
    }

    public function verify(): string
    {
        // 创建打码任务
        try {
            $response = $this->client->request('POST', '/createTask', [
                'json' => [
                    'clientKey' => $this->clientKey,
                    'task' => [
                        'type' => 'NoCaptchaTaskProxyless',
                        'websiteURL' => $this->siteUrl,
                        'websiteKey' => $this->siteKey,
                    ],
                ],
            ]);
        } catch (GuzzleException $e) {
            Log::error('创建打码任务失败:' . $e->getMessage());
            return false;
        }
        $result = $response->getBody()->getContents();
        $data = json_decode($result, true);
        if ($data['errorId'] != 0) {
            Log::error('创建打码任务失败:' . $data['errorDescription']);
            return false;
        }
        $taskId = $data['taskId'];
        return $this->getTaskResult($taskId);
    }

    private function getTaskResult($taskId): ?string
    {
        while (true) {
            try {
                $response = $this->client->request('POST', '/getTaskResult', [
                    'json' => [
                        'clientKey' => $this->clientKey,
                        'taskId' => $taskId,
                    ],
                ]);
            } catch (GuzzleException $e) {
                return '';
            }
            $result = $response->getBody()->getContents();
            $data = json_decode($result, true);
            if ($data['errorId'] != 0) {
                Log::error('打码失败:' . $data['errorDescription']);
                return '';
            }
            if ($data['status'] == 'ready') {
                return $data['solution']['gRecaptchaResponse'];
            }
            sleep(1);
        }
    }
}