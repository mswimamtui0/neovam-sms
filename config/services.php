<?php

return [

 "postmark" => [
 "key" => env("POSTMARK_API_KEY"),
 ],

 "resend" => [
 "key" => env("RESEND_API_KEY"),
 ],

 "ses" => [
 "key" => env("AWS_ACCESS_KEY_ID"),
 "secret" => env("AWS_SECRET_ACCESS_KEY"),
 "region" => env("AWS_DEFAULT_REGION", "us-east-1"),
 ],

 "slack" => [
 "notifications" => [
 "bot_user_oauth_token" => env("SLACK_BOT_USER_OAUTH_TOKEN"),
 "channel" => env("SLACK_BOT_USER_DEFAULT_CHANNEL"),
 ],
 ],

 /* ============ NEOVAM SMS GATEWAY ============ */
 "neovam_sms" => [
 "url" => env("NEOVAM_SMS_URL"),
 "key" => env("NEOVAM_SMS_KEY"),
 "sender" => env("NEOVAM_SMS_SENDER", "NEOVAM"),
 "secret" => env("NEOVAM_SMS_SECRET"), // for HMAC signing if your gateway supports it
 "environment" => env("NEOVAM_SMS_ENV", "sandbox"), // sandbox | production
 "test_mode" => env("NEOVAM_SMS_TEST_MODE", true),
 ],

 /* ============ ACCOUNTING (future) ============ */
 "accounting" => [
 "url" => env("ACCOUNTING_API_URL"),
 "key" => env("ACCOUNTING_API_KEY"),
 ],
];