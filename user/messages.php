<?php

// For login system
define('MSG_ACCOUNT_DOES_NOT_EXIST', 'Account with this username or email does not exist.');
define('MSG_INVALID_USER_OR_PASSWORD', 'The username or password you typed is invalid, please correct your information.');
define('MSG_INCORRECT_CREDS', 'Your username/email or password is incorrect.');

// For registration system
define('MSG_FIRSTNMAE_TOO_SHORT', 'Your first name must contain at least 3 characters.');
define('MSG_LASTNAME_TOO_SHORT', 'Your last name must contain at least 3 characters.');
define('MSG_FIRSTNMAE_TOO_LONG', 'Your first name must be less than 20 characters.');
define('MSG_LASTNMAE_TOO_LONG', 'Your last name must be less than 20 characters.');
define('MSG_USERNAME_TAKEN', 'An account with this username already exists, please choose another one.');
define('MSG_EMAIL_TAKEN', 'An account with this email already exists, please choose another one.');
define('MSG_PASSWORD_TOO_SHORT', 'Your password must contain at least 8 characters.');
define('MSG_USERNAME_TOO_SHORT', 'Your username cannot contain less than 2 characters.');
define('MSG_USERNAME_TOO_LONG', 'Your username cannot contain more than 24 characters.');

define('MSG_INVALID_EMAIL_FORMAT', 'Email address is not in valid format.');
define('MSG_EMAIL_TOO_LONG', 'Email address is too long, please shorten it.');
define('MSG_NO_ACCOUNT_WITH_EMAIL', 'Account with this email address does not exist.');

define('MSG_SUCCESSFULLY_REGISTERED', '<div class="txt=align-center">
<h3 class="txt-gold txt-uppercase">Account created</h3>
<p class="txt-grey mt-1">Thank you for joining the <span class="txt-gold">GoldenRules</span> family!</p>
<p class="txt-grey mt-1" style="font-size:0.8rem">Your account is awaiting activation. An email has been send to confirm your email address, please check your inbox.</p>
<p class="txt-grey mt-1">Would you like to <a href="/login" class="link-gold no-underline">login</a>?
</div>');

define('MSG_PASSWORD_RESET_REQUEST_EXPIRED', 'Your password could not be changed. The request may have expired.');
define('MSG_PASSWORD_CANNOT_BE_SAME', 'Your new password cannot be the same as your old one.');
define('MSG_PASSWORD_SUCCESSFULLY_RESET', '<h3>Password changed</h3><p class="txt-grey mt-1">Your password has been successfully changed. Would you like to <a href="/login" class="link-gold">login</a>?');

define('MSG_SOMETHING_WENT_WRONG', 'Sorry, something went wrong. Please try again.');

define('MSG_PASSWORD_SUCCESSFULLY_CHANGED', 'Your password has been successfully changed.');
define('MSG_PASSWORD_SIMILAR', 'Your new password cannot be the same as your current one.');
define('MSG_PASSWORD_MISMATCH', 'Your current password is incorrect.');

define('MSG_AVATAR_UPDATED', 'Your avatar has been successfully updated.');
define('MSG_NEWS_EXISTS', 'News with this title already exists. Please type another title.');