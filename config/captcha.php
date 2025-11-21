<?php

return [
    'google' => [ // 谷歌人机验证，官方的人机验证, 目前能被付费通杀(此方式不支持设置自己的验证码)
        'site_key' => '6Lflj6cZAAAAACI8uLiYrRgH6OlZRNuIkGzkPzSp',
    ],
    'geetest' => [ // 极验.免费人机验证，目前能被付费通杀，
        'id' => '',
        'key' => '',
    ],
    'aliyun' => [ // 阿里云验证码2.0,付费人机验证,被破解风险极低
        'prefix' => '', // 身份标
        'sceneId' => '', // 场景ID
        'accessKeyId' => '', // 注意:不要设置阿里云的主账户AK,分配拥有验证码权限的RAM子用户AK使用验证码
        'accessKeySecret' => '',
    ],
];