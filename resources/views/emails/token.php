<p>You are seeing this email because we received a password reset request for your account. Click the link below to reset your password:</p>
<p><a href="<?= absolute_url('password/reset?email=' . $email . '&token=' . $token) ?>"> <?= absolute_url('password/reset?email=' . $email . '&token=' . $token) ?></a></p>
<p>If you did not requested a password reset, no further action is required.</p>