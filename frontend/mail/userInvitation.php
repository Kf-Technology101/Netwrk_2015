<?php

use yii\helpers\Url;

/**
 * @var string $subject
 * @var \amnah\yii2\user\models\User $user
 * @var \amnah\yii2\user\models\UserKey $userKey
 */
?>
<p> Hi <?= $user->email ?>,</p>
<p><?= "To join netwrk, click on the link below:" ?></p>
<br>
<a href="<?= Url::toRoute(["/netwrk/user/join", "key" => $userKey->key_value], true); ?>">
	<b>Come on in</b>
</a>
<p>Thanks,<br>
The Netwrk Team
</p>