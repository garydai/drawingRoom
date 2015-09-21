<?php

// change the following paths if necessary
$yiic=dirname(__FILE__).'/../../../framework/yiic.php';
$config=dirname(__FILE__).'/config/console.php';

require_once($yiic);


require_once '/home/admin/vendor/autoload.php';

    // 引入鉴权类
    use Qiniu\Auth;

    // 引入上传类
    use Qiniu\Storage\UploadManager;



    // 需要填写你的 Access Key 和 Secret Key
    $accessKey = 'IfQYsH_aGxIgmmwS5zumpuTwzOV39FhA4VTC8K57';
    $secretKey = 'zY4kEBSxJfMhgDFFJiPBQRN2xgOjgKvHmke8ewJC';

    // 构建鉴权对象
    $auth = new Auth($accessKey, $secretKey);



