<?php

defined('IN_LitePhp') or exit('Access Denied');

return [
    'ENVIRONMENT'   => 'DEV',  // 'DEV', 'PRO'
    'APP_DEBUG'     => true,  //生产环境：ENVIRONMENT 设为 PRO, APP_DEBUG 设为 false
    'name'          => 'LiteApp',
    'authkey'       => 'LitePhp_185622a8f4e2c72a9f75f8f5b8099259',
    'cookie_pre'    => 'Lite',
    'cookie_path'   => '/',
    'cookie_domain' => '',
    'template'      => 'default',
    'skin'          => 'default',
    'rsaKey'        => [
        'priv' => 'MIICeQIBADANBgkqhkiG9w0BAQEFAASCAmMwggJfAgEAAoGBALTPgvxsLgIyXZMoTvB5TZ+eXIuwLC1abKj/JDMhXYFDZMmT6iu24KOspSN3qETwuj0vdWtaGT2VpfNUBaLhrEdwn9T+4f0niuX49pMM5DmbLSj80x2m7m3T1JQNjjXZIDvGZ7SeYmREeW1bd89hyLka26WsNvxUtQHrlD5rtOKfAgMBAAECgYEAj/OaVH+ITcVtjKiN2JSrAUbiZXBYa69PTWj8mBybRjuytW4nBLCvvn/IZilw9Zo2nFn5gQOVjtti6QxfBGPpdQv9ZpGrGzZNniIAF4ZquQwXDwIp1pfBEnMZwDISQSw7Ijp2TY3vli5DwP2x/Qg1jT1PLuic0qBGs5tuh/SpDTkCQQDkUfOIw5ABgdT37RoZKbVieIUxBSZT7HWt2KZJeY0GQmI3Z+yhjtO56eOSbdmDeHM5URAugvjF4yDdXEsayetNAkEAyrsTA/V5MlYnPJlsSAOVunbLYnNgP8V8yBb8aY+NnY6sYzeJhLj4sw7pUe/NC+BP6k1Tp0Lx/hmJjfhY4c2XmwJBALR2cd8vc6s9S2+K4I3zAYMLb3sHbvv2ci0uiICaqnTAE5FnewlXMtJHo7AhU0Mu+SPchsW8j5UZqOhOAq7x2iECQQC1SMwcQin6ZSf4/IjjbLE7aHc+tkVfQU3v7v4Ptxq/xZFJt6P1VyKtm6vwZStKb2+G6VbgvvB+dH+YwHdak+n5AkEAsjCgQPwIH8Dwdjtv36sp9twJgktWCUvzn6o4EAOanQFNT6NugzfgBbFCt4uhR/4javyQlbTteE5tXEO+kV9kbg==',

        'pub' => 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC0z4L8bC4CMl2TKE7weU2fnlyLsCwtWmyo/yQzIV2BQ2TJk+ortuCjrKUjd6hE8Lo9L3VrWhk9laXzVAWi4axHcJ/U/uH9J4rl+PaTDOQ5my0o/NMdpu5t09SUDY412SA7xme0nmJkRHltW3fPYci5GtulrDb8VLUB65Q+a7TinwIDAQAB',

        'thirdPub' => 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC0z4L8bC4CMl2TKE7weU2fnlyLsCwtWmyo/yQzIV2BQ2TJk+ortuCjrKUjd6hE8Lo9L3VrWhk9laXzVAWi4axHcJ/U/uH9J4rl+PaTDOQ5my0o/NMdpu5t09SUDY412SA7xme0nmJkRHltW3fPYci5GtulrDb8VLUB65Q+a7TinwIDAQAB',

        'private_key_bits'=>1024
    ],
];