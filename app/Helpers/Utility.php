<?php

namespace App\Helpers;

use Carbon\Carbon;
use DateTime;
use Exception;
use Tenant\app\Repositories\InventoryRepository;

/**
 * Utility This class provides generic utility functions.
 */
class Utility
{
    /**
     * randomString
     *
     * @param  int $length_of_string Length of the string.
     * @return string
     */
    public static function randomString($length_of_string): string
    {
        return substr(md5(time()), 0, $length_of_string);
    }

    /**
     * uniqueNumberBasedString
     *
     * @param  mixed $prefix This is the Prefix that is to be added.
     * @param  mixed $seek_head This reduces the length of return string.
     * @return string
     */
    public static function uniqueNumberBasedString(string $prefix = '', int $seek_head = 4): string
    {
        return $prefix . substr(round(microtime(true)), $seek_head);
    }

    /**
     * CDN URL for images (DEPRICIATED)
     * 
     * @return string
     */
    public static function getCdnUrl(): string
    {
        return getEnv('image.cdn');
    }

    /**
     * format_phone_us This function is for formatting the phone
     *
     * @param  mixed $phone
     */
    public static function format_phone_us($phone)
    {
        // note: making sure we have something
        if (!isset($phone)) {
            return '';
        }
        // note: strip out everything but numbers 
        $phone = preg_replace("/[^0-9]/", "", $phone);
        $length = strlen($phone);
        switch ($length) {
            case 7:
                return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
                break;
            case 10:
                return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phone);
                break;
            case 11:
                return preg_replace("/([0-9]{1})([0-9]{3})([0-9]{3})([0-9]{4})/", "$1($2) $3-$4", $phone);
                break;
            default:
                return $phone;
                break;
        }
    }

    /**
     * featureExtractFromJSONString 
     *
     * @param  mixed $feature_JSON_string
     * @return string
     */
    public static function featureExtractFromJSONString($feature_JSON_string): string
    {
        $json_array =  json_decode($feature_JSON_string, true);
        $result_array = [];

        if (gettype($json_array) == "array") {
            array_walk_recursive($json_array, function ($item, $key) use (&$result_array) {
                if (gettype($key) == "string") {
                    array_push($result_array, $key);
                }
            }, $result_array);
        }

        return implode(" , ", $result_array);
    }

    /**
     * validateDate Date Validator.
     *
     * @param  mixed $date
     * @param  mixed $format
     * @return void
     */
    public static function validateDate($date, $format = 'MM/DD/YYYY')
    {
        try {
            Carbon::createFromIsoFormat($format, $date);
            return true;
        } catch (\Exception $e) {
            throw new Exception("Invalid date format. Expected format : " . $format);
        }
    }


    /**
     * urlCreation
     *
     * @param  String $base_url Business URL with protocol (Example https://)
     * @param  String $slug Slug to be added to the URL
     * @param  array $inventory Inventory Type array
     * @param  mixed $wordpress_type
     * @return String
     */
    public static function urlCreation(String $base_url, String $slug = "inventory", array $inventory, $wordpress_type = '1'): String
    {
        try {

            $inventory = (new InventoryRepository())->replacePlaceholders($inventory);

            switch ($wordpress_type) {
                case '0':
                    $url_part = $inventory['year'] . '-' . $inventory['make'] . '-' . $inventory['model'] . '-' . $inventory['trim'];
                    $url_part = strtolower(str_replace(array('  ', ' '), '-', preg_replace('/[^a-zA-Z0-9 s]/', '-', trim($url_part))));
                    return $base_url . '/' . $slug . '/' . $url_part . '/' . $inventory['inventory_id'] . '/';

                    break;
                case '1':
                    $inventory_title = strip_tags($inventory['title']);
                    $inventory_slug = $inventory_title . '-' . $inventory['inventory_id'];
                    $inventory_slug = self::slugify($inventory_slug);
                    return $base_url . '/' . $slug . '/' . $inventory_slug;
                    break;

                case '2':
                    $url_part = strtolower($inventory['year'] . '-' . $inventory['make'] . '-' . $inventory['model']);
                    $url_part = preg_replace('/[^a-zA-Z0-9]/', '-', $url_part);
                    $url_part = $inventory['inventory_id'] . '-' . $url_part;
                    return $base_url . '/' . $slug . '/' . $url_part;

                default:
                    $inventory_title = strip_tags($inventory['title']);
                    $inventory_slug = $inventory_title . '-' . $inventory['inventory_id'];
                    $inventory_slug = self::slugify($inventory_slug);
                    return $base_url . '/' . $slug . '/' . $inventory_slug;
                    break;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * slugify
     *
     * @param  mixed $string This is the string that is supposed to be replaced
     * @param  mixed $replacer This is the string that is supposed to be replaced
     * @return 
     */
    public static function slugify($string, $replacer = '-')
    {
        $string = str_replace(array('[\', \']'), '', $string);
        $string = preg_replace('/\[.*\]/U', '', $string);
        $string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', $replacer, $string);
        $string = htmlentities($string, ENT_COMPAT, 'utf-8');
        $string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string);
        $string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/'), $replacer, $string);
        return strtolower(trim($string, $replacer));
    }

    /**
     * encryptString
     *
     * @param  mixed $plaintext
     * @param  mixed $password
     * @param  mixed $encoding
     * @return
     */
    public static function encryptString($plaintext, $password, $encoding = "hex")
    {
        $iv = openssl_random_pseudo_bytes(16);
        $ciphertext = openssl_encrypt($plaintext, "AES-256-CBC", hash('sha256', $password, true), OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext . $iv, hash('sha256', $password, true), true);
        return $encoding == "hex" ? bin2hex($iv . $hmac . $ciphertext) : ($encoding == "base64" ? base64_encode($iv . $hmac . $ciphertext) : $iv . $hmac . $ciphertext);
    }

    /**
     * decryptString
     *
     * @param  mixed $ciphertext
     * @param  mixed $password
     * @param  mixed $encoding
     * @return
     */
    public static function decryptString($ciphertext, $password, $encoding = "hex")
    {
        $ciphertext = $encoding == "hex" ? hex2bin($ciphertext) : ($encoding == "base64" ? base64_decode($ciphertext) : $ciphertext);
        if (!hash_equals(hash_hmac('sha256', substr($ciphertext, 48) . substr($ciphertext, 0, 16), hash('sha256', $password, true), true), substr($ciphertext, 16, 32))) return null;
        return openssl_decrypt(substr($ciphertext, 48), "AES-256-CBC", hash('sha256', $password, true), OPENSSL_RAW_DATA, substr($ciphertext, 0, 16));
    }

    /**
     * shortEncryptString
     *
     * @param  mixed $plaintext
     * @param  mixed $password
     * @return string
     */
    public static function shortEncryptString($plaintext, $password): string
    {
        $encrypted_base_64_string = openssl_encrypt(
            $plaintext,
            "AES-128-CBC",
            $password,
            0,
            "1234567891011121"
        );

        return bin2hex($encrypted_base_64_string);
    }

    /**
     * shortDecryptString
     *
     * @param  mixed $plaintext
     * @param  mixed $password
     * @return string
     */
    public static function shortDecryptString($plaintext, $password): string
    {
        $convert_to_base_64 = hex2bin($plaintext);
        return openssl_decrypt(
            $convert_to_base_64,
            "AES-128-CBC",
            $password,
            0,
            "1234567891011121"
        );
    }

    /**
     * getCompleteFinancingDetails
     *
     * @param  mixed $inventory
     * @param  mixed $other_amount
     * @param  mixed $tax
     * @return 
     */
    public static function getCompleteFinancingDetails($inventory, $other_amount = 0, $tax = 0)
    {
        try {
            if (!$inventory['financing_on']) {
                return [];
            }

            $price = (int) $inventory["price"];
            $special_price =  (int) $inventory["special_price"];
            $amount = $selling_price = $inventory["special_price_on"] == "1" ? $special_price : $price;
            $amount += $other_amount;
            $payment_frequency = $inventory["payment_frequency"] ?? "Bi-Weekly";
            $term_placeholder = $payment_frequency;

            $months = $term =   (int) $inventory["term"];
            $annInterest =    (float) $inventory["interest_rate"];
            $monInt = $annInterest / 1200;

            if ($payment_frequency == "Weekly") {
                $term_placeholder = "Week";
                $months = ($months / 12) * 52;
                $monInt = $annInterest / 5200;
            } else if ($payment_frequency == "Bi-Weekly") {
                $term_placeholder = "Bi-Week";
                $months = ($months / 12) * 26;
                $monInt = $annInterest / 2600;
            }

            $down = (float) $inventory["downpayment"];
            $down = ($down * $amount) / 100;
            $diff = $amount - $down;

            $cal = $diff * $monInt * (pow(1 + $monInt, $months) / (pow(1 + $monInt, $months) - 1));
            $cal = $cal + ($cal * $tax * 0.01);

            return [
                'emi_amount' => round($cal, 2),
                'downpayment' => round($down, 2),
                'selling_price' => $selling_price,
                'payment_frequency' => $payment_frequency,
                'interest_rate' => $annInterest,
                'term' => $term,
            ];
        } catch (\Exception $ex) {
            return [
                'emi_amount' => '',
                'downpayment' => '',
                'selling_price' => '',
                'payment_frequency' => '',
                'interest_rate' => '',
                'term' => '',
            ];
        }
    }


    /**
     * getFinancingCalculationAmount
     *
     * @param  mixed $inventory
     * @param  mixed $other_amount
     * @param  mixed $tax
     * @return 
     */
    public static function getFinancingAmount($inventory, $other_amount = 0, $tax = 0)
    {

        try {
            $price = is_numeric($inventory["price"]) ? (int) $inventory["price"] : 0;
            $special_price = is_numeric($inventory["special_price"]) ? (int) $inventory["special_price"] : 0;
            $amount = $inventory["special_price_on"] == "1" ? $special_price : $price;
            $amount += $other_amount;
            $payment_frequency = !empty($inventory["payment_frequency"]) ? $inventory["payment_frequency"] : "Bi-Weekly";
            $term_placeholder = $payment_frequency;

            $months =    is_numeric($inventory["term"]) ? (int) $inventory["term"] : 0;
            $annInterest =    is_numeric($inventory["interest_rate"]) ? (float) $inventory["interest_rate"] : 0;
            $monInt = $annInterest / 1200;

            if ($payment_frequency == "Weekly") {
                $term_placeholder = "Week";
                $months = ($months / 12) * 52;
                $monInt = $annInterest / 5200;
            } else if ($payment_frequency == "Bi-Weekly") {
                $term_placeholder = "Bi-Week";
                $months = ($months / 12) * 26;
                $monInt = $annInterest / 2600;
            }

            $down = is_numeric($inventory["downpayment"]) ? (float) $inventory["downpayment"] : 0;
            $down = ($down * $amount) / 100;
            $diff = $amount - $down;

            //P x R x (1+R)^N]/[(1+R)^ (N-1)

            $cal = $diff * $monInt * (pow(1 + $monInt, $months) / (pow(1 + $monInt, $months) - 1));
            $cal = $cal + ($cal * $tax * 0.01);
            return $cal;
        } catch (\Exception $ex) {
            return 0;
        }

        // return 
        //     ($monInt + $monInt / (pow(1 + $monInt, $months) - 1)) * $diff;

    }

    /**
     * getImageUrl This method gets absolute url for the images.
     *
     * @param  mixed $url
     * @return string
     */
    public static function getImageUrl($url): string
    {
        $pattern = "/^(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9]\.[^\s]{2,})|^([a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,7})$/im";

        $url = trim($url);
        if ((bool) preg_match($pattern, $url)) {
            return $url;
        } else {
            return self::getCdnUrl() . $url;
        }
    }


    /**
     * permutations Please refer https://stackoverflow.com/questions/5506888/permutations-all-possible-sets-of-numbers
     *
     * @param  mixed $pool
     * @param  mixed $r
     * @return
     */
    public static function permutations($pool, $r = null)
    {
        $n = count($pool);

        if ($r == null) {
            $r = $n;
        }

        if ($r > $n) {
            return;
        }

        $indices = range(0, $n - 1);
        $cycles = range($n, $n - $r + 1, -1); // count down

        yield array_slice($pool, 0, $r);

        if ($n <= 0) {
            return;
        }

        while (true) {
            $exit_early = false;
            for ($i = $r; $i--; $i >= 0) {
                $cycles[$i] -= 1;
                if ($cycles[$i] == 0) {
                    // Push whatever is at index $i to the end, move everything back
                    if ($i < count($indices)) {
                        $removed = array_splice($indices, $i, 1);
                        array_push($indices, $removed[0]);
                    }
                    $cycles[$i] = $n - $i;
                } else {
                    $j = $cycles[$i];
                    // Swap indices $i & -$j.
                    $i_val = $indices[$i];
                    $neg_j_val = $indices[count($indices) - $j];
                    $indices[$i] = $neg_j_val;
                    $indices[count($indices) - $j] = $i_val;
                    $result = [];
                    $counter = 0;
                    foreach ($indices as $indx) {
                        array_push($result, $pool[$indx]);
                        $counter++;
                        if ($counter == $r) break;
                    }
                    yield $result;
                    $exit_early = true;
                    break;
                }
            }
            if (!$exit_early) {
                break; // Outer while loop
            }
        }
    }


    /**
     * stripHTMLTags This Method Strips The HTML Tags as well as the BOM Characters.
     *
     * @param  mixed $html
     * @return string
     */
    public static function stripHTMLTags($html): string
    {
        $desc = strip_tags($html);
        $desc = preg_replace("/\r|\n/", ' ', $desc);
        $desc = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $desc);
        return $desc;
    }

    /**
     * isAbsoluteUrl This Regex is used to check for Absolute Url.
     *
     * @param  mixed $url
     * @return bool
     */
    public static function isAbsoluteUrl($url)
    {
        $pattern = "/^(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9]\.[^\s]{2,})|^([a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,7})$/im";

        return (bool) preg_match($pattern, $url);
    }

    /**
     * formatPhoneNumber This function is for formatting the phone
     * eg: +1 xxx xxx xxx  to xxxxxxxxxxx
     * 
     * @param  mixed $phone
     * @param  string $format (plain or us)
     * 
     * @return void
     */
    public static function formatPhoneNumber($phoneNumber, $format = 'plain')
    {
        if (!isset($phoneNumber)) {
            return '';
        }

        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        if (strlen($phoneNumber) > 10) {
            $countryCode = substr($phoneNumber, 0, strlen($phoneNumber) - 10);
            $areaCode = substr($phoneNumber, -10, 3);
            $nextThree = substr($phoneNumber, -7, 3);
            $lastFour = substr($phoneNumber, -4, 4);
            //$phoneNumber = '+'.$countryCode.' ('.$areaCode.') '.$nextThree.'-'.$lastFour :

            $phoneNumber = ($format == 'us') ?
                '(' . $areaCode . ') ' . $nextThree . '-' . $lastFour :
                $areaCode . $nextThree . $lastFour;
        } else if (strlen($phoneNumber) == 10) {
            $areaCode = substr($phoneNumber, 0, 3);
            $nextThree = substr($phoneNumber, 3, 3);
            $lastFour = substr($phoneNumber, 6, 4);

            $phoneNumber = ($format == 'us') ?
                '(' . $areaCode . ') ' . $nextThree . '-' . $lastFour :
                $areaCode . $nextThree . $lastFour;
        } else if (strlen($phoneNumber) == 7) {
            $nextThree = substr($phoneNumber, 0, 3);
            $lastFour = substr($phoneNumber, 3, 4);

            $phoneNumber = ($format == 'us') ?
                $nextThree . '-' . $lastFour :
                $nextThree . '-' . $lastFour;
        }

        return $phoneNumber;
    }

    /**
     * trimString
     *
     * @param  mixed $description
     * @param  mixed $len
     * @param  mixed $replace
     * @return string
     */
    public static function trimString(string $description, int $len = 0, string $replace = "..."): string
    {
        return (strlen($description) > $len) ? substr($description, 0, $len) . $replace : $description;
    }
}
