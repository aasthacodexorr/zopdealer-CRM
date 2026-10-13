<?php

namespace Tenant\app\Libraries\Captcha;

use App\Helpers\Utility;
use CodeIgniter\Cache\CacheFactory;
use Config\NonPersistantCache;
use Gregwar\Captcha\CaptchaBuilder;

class SimpleCaptcha
{

    public static function generate(int $seconds = 600)
    {
        $random_string = substr(str_shuffle(str_repeat("ABCDEFGHIJKLMNOPQRSTUVWXYZ", 5)), 0, 5);
        $builder = new CaptchaBuilder();
        $builder->setPhrase($random_string);
        $builder->setDistortion(false);
        $builder->setMaxBehindLines(1);
        $builder->setMaxFrontLines(1);
        $builder->build();

        $payload = [
            'expiry_time' => time() + $seconds,
            'seconds'   => $seconds,
            'phrase'   => $builder->getPhrase(),
        ];

        return [
            'phrase' => $builder->getPhrase(),
            'hash' => Utility::encryptString(json_encode($payload), getenv("password.encryption")),
            'captcha' => $builder->inline()
        ];
    }

    public static function validate(string $encrypted_string, string $entered_captcha): bool
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

            if (strtolower($entered_captcha) == strtolower($phrase)) {
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
