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
    'rsakey'        => [
        'privkey' => 'MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDcPVqzKjTeSKOXR4ExNrYfATiEjk4s/6tAEnH+5AKP6g+nOUt0cf7ZDB/AzY97cfFbxRI4ZXMsOlDgO39n1qrhyDUIPg+XjoIJrDiZRcQqB5pwOngJyfkA1yBgVvDN6A8fU8BndxfuBtaqZmzRukg+Xm69QlVEH697yNwsmHYYlvPe4NKK7hTofVsTOrdMlQ6y+1DIAfBioCQji+CqKnMYch5LiLRBF9B1Rwa1aw1jm9kzGlwqU6gxm+uNtV0lgxlR5K0sBX4hnCR7tXuVS0pHd0KqI+ajvafQEp7+/kOQvBB4j0XwKdD3J5fa2QMJH3T4Ynsr1XureUxFCCIuOQhHAgMBAAECggEALxkTz2LEQZDpIw3Qi/S2R7UcIATKpQbb2WzYYfjir2IPjORMxY8nP8U++R08hNqeEdD4D4VqdtfVuc4fddZLXtNQu/2BmhiIqIbi56wqwg40MbHfP8CodkyLCO8uuenZagqgB8BJJsbhmzkiJkue7W+GYQTRdSsNfPw8UXEdOC6SIq5ZxAs4ntwljnAUkTouJqu6B/aofGdau057/2CJHaq+C+VBM6Q7RB87mHbpcDSCoqzh69yfDkVT57SEyuvBoAC0jOqqa/BuyBkUcXXq9yCFH0b0nX70QbR7ibAZ1twE/J+7F45K6IWO0MGwDcnB5HUfiuaLZXbqjxFQURnX8QKBgQD8fTonjX6lcBlPjxik2jdOmyajTn1WXYZF3e/PBStT9cqXhzlZrCTRmyhvl+9buErM8UeVc9VD9q7bW17TcEXhhTyR8kDUahz8dd0XdnkMDntgt9+gRI9eW6CzdvWAhKJb3Ft3eLOWHyO+lj+6IoE1Vkon3cf/5ZPn78IXbTnLPwKBgQDfTVSJAfvaeMwCHd4aiHRgDWvaivxwBmHxqJJYhQoSgn/bi+9mZuRwZ/vHuhZ7rB8JwFM/1yAaay2c07rdYIwZdroImeF2ZWlL/1hV5kM/dsBLPHGvva3vbWF54FbgZtS3k2c7G8I+vOZJtWa6QHkHKynzxcRzNL9hMQ3d2R+o+QKBgCbD2z/jW4Ru95Pddn0o+8DX1VDRZjDyXwEvF/iC5KoZXKdzHX0FDwoXdlfbzeYZH3LjwIfTpvSuVR5coux3mmMLn8cQmJ+EQzsHpZBKoj8Voh+xW2Yx4IkuLDghlYPL2VmdNXXcVEZCNay3SMV9MrVLsEr6pBBH4TOE/hm2rV7lAoGAOHm3Wptc1ilc92AUb0N5o11hnLkwFSkYFmVa9fZX0Myeh6lBl/WH6wHxhNcEnqyugOnaZDgFT4kx1QsbpKepZztG26wHgMkvM2BWOAtvkJp5Ec4stpTTa82PLKLXdhOr0u392s4vd2yRFa1CWPK7aVu6VYP8JmL90Bf2+NfUEQkCgYEA508uvAht48FW5+naJH2mTKLkZ3SLdTG98FD7yS+RNuV+YU98ATKo1kKL+LeJXXFiDaG4pL9GRsBemGdboAnI1J2/H5uVDbioSVmE9lN8eOfJ9Wp3wl/hpfWERappVuUNvtXXb+ns8tV2uBd6nJ3j+yURGg8LVK9kPHjuwSHo6aM=',

        'pubkey' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA3D1asyo03kijl0eBMTa2HwE4hI5OLP+rQBJx/uQCj+oPpzlLdHH+2QwfwM2Pe3HxW8USOGVzLDpQ4Dt/Z9aq4cg1CD4Pl46CCaw4mUXEKgeacDp4Ccn5ANcgYFbwzegPH1PAZ3cX7gbWqmZs0bpIPl5uvUJVRB+ve8jcLJh2GJbz3uDSiu4U6H1bEzq3TJUOsvtQyAHwYqAkI4vgqipzGHIeS4i0QRfQdUcGtWsNY5vZMxpcKlOoMZvrjbVdJYMZUeStLAV+IZwke7V7lUtKR3dCqiPmo72n0BKe/v5DkLwQeI9F8CnQ9yeX2tkDCR90+GJ7K9V7q3lMRQgiLjkIRwIDAQAB',

        'thirdPubkey' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA3D1asyo03kijl0eBMTa2HwE4hI5OLP+rQBJx/uQCj+oPpzlLdHH+2QwfwM2Pe3HxW8USOGVzLDpQ4Dt/Z9aq4cg1CD4Pl46CCaw4mUXEKgeacDp4Ccn5ANcgYFbwzegPH1PAZ3cX7gbWqmZs0bpIPl5uvUJVRB+ve8jcLJh2GJbz3uDSiu4U6H1bEzq3TJUOsvtQyAHwYqAkI4vgqipzGHIeS4i0QRfQdUcGtWsNY5vZMxpcKlOoMZvrjbVdJYMZUeStLAV+IZwke7V7lUtKR3dCqiPmo72n0BKe/v5DkLwQeI9F8CnQ9yeX2tkDCR90+GJ7K9V7q3lMRQgiLjkIRwIDAQAB'
    ]
];