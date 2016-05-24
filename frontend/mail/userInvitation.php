<?php

use yii\helpers\Url;

/**
 * @var string $subject
 * @var \amnah\yii2\user\models\User $user
 * @var \amnah\yii2\user\models\UserKey $userKey
 */
?>
<a href="<?= Url::toRoute(["/netwrk/user/join", "key" => $userKey->key_value], true); ?>" style="background:#5888ac; cursor:pointer; padding: 20px 0; border:none;  -moz-border-radius: 10px; -webkit-border-radius: 10px; -khtml-border-radius: 10px; border-radius: 10px; font-size:28px; font-weight:bold; text-decoration:none; color:#fff; display: block; max-width: 300px;text-align:center;">
	<b>Come on in</b>
</a>