<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    */

    "postmark" => [
        "key" => env("POSTMARK_API_KEY"),
    ],

    "resend" => [
        "key" => env("RESEND_API_KEY"),
    ],

    "ses" => [
        "key"    => env("AWS_ACCESS_KEY_ID"),
        "secret" => env("AWS_SECRET_ACCESS_KEY"),
        "region" => env("AWS_DEFAULT_REGION", "us-east-1"),
    ],

    "slack" => [
        "notifications" => [
            "bot_user_oauth_token" => env("SLACK_BOT_USER_OAUTH_TOKEN"),
            "channel"              => env("SLACK_BOT_USER_DEFAULT_CHANNEL"),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | NEOVAM SMS GATEWAY (https://sms.vfsl.co.tz)
    |--------------------------------------------------------------------------
    | Uses HMAC-SHA256 signing.
    | Signing material: timestamp + "+" + exact JSON body
    | Header X-Signature = HMAC-SHA256(secret, material)
    */
    "neovam_sms" => [
        "url"         => env("NEOVAM_SMS_URL", "https://sms.vfsl.co.tz"),
        "key"         => env("NEOVAM_SMS_KEY"),          // HMAC shared secret
        "client_id"   => env("NEOVAM_SMS_CLIENT_ID", "neovam-hms-prod"),
        "sender"      => env("NEOVAM_SMS_SENDER", "NEOVAM"),
        "environment" => env("NEOVAM_SMS_ENV", "production"),
        "test_mode"   => env("NEOVAM_SMS_TEST_MODE", false),
        "timeout"     => env("NEOVAM_SMS_TIMEOUT", 8),
    ],

    /*
    |--------------------------------------------------------------------------
    | EXTERNAL ACCOUNTING SERVICE (future integration)
    |--------------------------------------------------------------------------
    */
    "accounting" => [
        "url" => env("ACCOUNTING_API_URL"),
        "key" => env("ACCOUNTING_API_KEY"),
    ],

];