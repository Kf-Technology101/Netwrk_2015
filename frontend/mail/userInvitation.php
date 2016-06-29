<?php

use yii\helpers\Url;

/**
 * @var string $subject
 * @var \amnah\yii2\user\models\User $user
 * @var \amnah\yii2\user\models\UserKey $userKey
 */
?>
<table width="500px" align="center" cellpadding="0" cellspacing="0" style="background-color:#ffffff;">
	<tr>
		<td style="height: 480px;text-align: center;">
			<a href="<?= Url::toRoute(["/netwrk/user/join", "key" => $userKey->key_value], true); ?>">
				<img src="<?= Url::toRoute(['/img/background/invitation_email_bg.png'], true); ?>" width="100%" max-height="480px" style="margin:0 auto;" alt="Come on in" />
			</a>
		</td>
	</tr>
</table>