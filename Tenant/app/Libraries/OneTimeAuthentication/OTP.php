<?php

namespace Tenant\app\Libraries\OneTimeAuthentication;

use App\Helpers\Utility;
use CodeIgniter\Cache\CacheFactory;
use Config\NonPersistantCache;
use Config\Services;

class OTP
{

    public static function generate(int $seconds = 300)
    {
        $otp = rand(1000, 9999);
        sleep(rand(1, 5));

        $payload = [
            'expiry_time' => time() + $seconds,
            'seconds'   => $seconds,
            'otp'   => $otp,
        ];

        return [
            'OTP' => $otp,
            'hash' => Utility::encryptString(json_encode($payload), getenv("password.encryption"))
        ];
    }

    public static function validate(string $encrypted_string, string $entered_otp): bool
    {
        $cache_factory = CacheFactory::getHandler(new NonPersistantCache());

        try {
            $decrypted_payload = Utility::decryptString($encrypted_string, getenv("password.encryption"));
            extract(json_decode($decrypted_payload, true));

            if ((int) $expiry_time < time()) {
                return false;
            }

            if (!empty($cache_factory->get($encrypted_string))) {
                return false;
            }

            if ($entered_otp == $otp) {
                return true;
            }

            return false;
        } catch (\Exception $ex) {
            return false;
        }
    }

    public static function invalidate($token): void
    {

        try {
            $decrypted_payload = Utility::decryptString($token, getenv("password.encryption"));
            extract(json_decode($decrypted_payload, true));

            $cache_factory = CacheFactory::getHandler(new NonPersistantCache());
            $cache_factory->save($token, 1, (int) $seconds * 2);
        } catch (\Exception $ex) {
        }
    }
}
