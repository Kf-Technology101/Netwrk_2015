<?php

use yii\helpers\Url;

/**
 * @var string $subject
 * @var \amnah\yii2\user\models\User $user
 * @var \amnah\yii2\user\models\UserKey $userKey
 */
?>
<p> Hi <?= $user->profile->first_name .' '. $user->profile->last_name ?>,</p>
<p><?= "We received a forgot password request for your Netwrk account. To reset your password, click on the link below:" ?></p>
<br>
<a href="<?= Url::toRoute(["/netwrk/user/reset-password", "key" => $userKey->key_value], true); ?>">
	<?= Url::toRoute(["/netwrk/user/reset-password", "key" => $userKey->key_value], true); ?>
</a>
<p>Thanks,<br>
The Netwrk Team
</p>