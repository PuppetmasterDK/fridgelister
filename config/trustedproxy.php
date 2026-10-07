<?php

// Which proxies may tell the app its real address (X-Forwarded-* headers).
// Unset on your own computer. A codespace sets TRUSTED_PROXIES=* in .env,
// because the app sits behind GitHub's proxy there.

return [
    'proxies' => env('TRUSTED_PROXIES'),
];
