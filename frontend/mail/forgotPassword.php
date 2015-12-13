<?php

use yii\helpers\Url;

/**
 * @var string $subject
 * @var \amnah\yii2\user\models\User $user
 * @var \amnah\yii2\user\models\UserKey $userKey
 */
?>

<h3><?= $subject ?></h3>

<p><?= "Please use this link to reset your password:" ?></p>

<p><?= Url::toRoute(["/netwrk/user/reset-password", "key" => $userKey->key_value], true); ?></p>
