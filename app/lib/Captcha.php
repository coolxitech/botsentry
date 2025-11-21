<?php

namespace app\lib;

use AlibabaCloud\SDK\Captcha\V20230305\Captcha as AliyunCaptcha;
use AlibabaCloud\Credentials\Credential;
use AlibabaCloud\Credentials\Credential\Config as CredentialConfig;
use Exception;
use AlibabaCloud\Tea\Exception\TeaError;
use AlibabaCloud\Tea\Utils\Utils;
use Darabonba\OpenApi\Models\Config;
use AlibabaCloud\SDK\Captcha\V20230305\Models\VerifyIntelligentCaptchaRequest;
use AlibabaCloud\Dara\Models\RuntimeOptions;

class Captcha
{
    /**
     * 使用凭据初始化账号 Client
     * @return AliyunCaptcha Client
     */
    public static function createClient()
    {
        $credConfig = new CredentialConfig([
            'type' => 'access_key',
            'accessKeyId' => \think\facade\Config::get('captcha.aliyun.accessKeyId'),
            'accessKeySecret' => \think\facade\Config::get('captcha.aliyun.accessKeySecret'),
        ]);
        $credential = new Credential($credConfig);
        $config = new Config([
            "credential" => $credential,
        ]);
        // Endpoint 请参考 https://api.aliyun.com/product/captcha
        $config->endpoint = "captcha.cn-shanghai.aliyuncs.com";
        return new AliyunCaptcha($config);
    }

    public static function verify(string $captchaVerifyParam, string $sceneId)
    {
        $client = self::createClient();
        $verifyIntelligentCaptchaRequest  = new VerifyIntelligentCaptchaRequest([
            'captchaVerifyParam' => $captchaVerifyParam,
            'sceneId' => $sceneId,
        ]);

        try {
            $result = $client->verifyIntelligentCaptchaWithOptions($verifyIntelligentCaptchaRequest, new RuntimeOptions());
        } catch (Exception $error) {
            if ($error instanceof TeaError) {
                // 获取服务端返回的错误信息
                halt($error->message);
            } else {
                halt($error);
            }
        }
        return $result->body->toArray();
    }

}
