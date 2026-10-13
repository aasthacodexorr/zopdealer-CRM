<?php

namespace App\Helpers;

/**
 * Utility This class provides generic utility functions for ADF Lead processing.
 */
class Adflead
{

    private static $DEFAULT_EMAIL = 'noemail@example.com'; // static value    
    private static $DEFAULT_PHONE = '0000000000'; // static value  

    public static function validate($data): string
    {
        $proData =  ($data == '') ? "" : trim($data);
        return $proData;
    }

    public static function format_phone($phone): string
    {
        $phone = ($phone != "") ? preg_replace("/[^0-9]/", "", $phone) : self::$DEFAULT_PHONE;
        return substr($phone, -10);
    }

    public static function badFilter($string): string
    {
        $search = ['<https:', '<http:', '&','utf-16'];
        $replace = ['https:', '<http:', '&amp;','utf-8'];
        $proData = str_replace($search, $replace, $string);

        return $proData;
    }

    public static function getPhone($xml): string
    {
        $phone = '';

        $DataCount = count($xml->prospect->customer->contact->phone);
        if ($DataCount == 1 && $xml->prospect->customer->contact->phone != "") {
            $phone =  Adflead::format_phone((string)$xml->prospect->customer->contact->phone);
        } elseif ($DataCount > 1) {
            $phone =  Adflead::format_phone((string)$xml->prospect->customer->contact->phone[0]);
        } else {
            $phone =  self::format_phone('');
        }

        return $phone;
    }

    public static function getEmail($xml): string
    {
        $emails = $xml->prospect->customer->contact->email;

        if (count($emails) > 0) {
            $email = trim((string) $emails[0]);

            if ($email !== '' && strtolower($email) !== 'null') {
                return $email;
            }
        }

        return self::$DEFAULT_EMAIL;
    }

    public static function str_lreplace($search, $replace, $subject)
    {
        $pos = strrpos($subject, $search);

        if ($pos !== false) {
            $subject = substr_replace($subject, $replace, $pos, strlen($search));
        }

        return $subject;
    }

    public static function getName($xml): array
    {
        $contactName = $xml->prospect->customer->contact->name;
        $dataCount   = count($contactName);

        // Helper: split a full-name string into ['firstname', 'lastname'].
        $splitFullName = function (string $fullName): array {
            $fullName = trim($fullName);
            $pieces   = explode(' ', $fullName);
            if (count($pieces) > 1) {
                $lastName  = array_pop($pieces); // dont change code up-down
                $firstName = static::str_lreplace($lastName, '', $fullName);
            } else {
                $firstName = $lastName = $fullName;
            }
            return ['firstname' => $firstName, 'lastname' => $lastName];
        };

        if ($dataCount === 1 && (string)$contactName !== '') {
            return $splitFullName((string)$contactName);
        }

        if ($dataCount === 2) {
            if ((string)$contactName[1] == '') {
                return [
                    'firstname' => (string)$contactName[0],
                    'lastname'  => (string)$contactName[0],
                ];
            }

            if ((string)$contactName[0] == '') {
                return [
                    'firstname' => (string)$contactName[1],
                    'lastname'  => (string)$contactName[1],
                ];
            }

            return [
                'firstname' => (string)$contactName[0],
                'lastname'  => (string)$contactName[1],
            ];
        }

        if ($dataCount === 3) {
            // Index 2 = last name, index 1 = first name, index 0 = full-name fallback.
            if ((string)$contactName[2] !== '') {
                return [
                    'firstname' => (string)$contactName[1],
                    'lastname'  => (string)$contactName[2],
                ];
            }
            if ((string)$contactName[1] !== '') {
                return $splitFullName((string)$contactName[1]);
            }
            if ((string)$contactName[0] !== '') {
                return $splitFullName((string)$contactName[0]);
            }
        }

        return ['firstname' => 'Unknown', 'lastname' => 'Unknown'];
    }

    /** Depreciated function not in use */
    public static function get_string_between($string, $start, $end): string
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    /** Depreciated function not in use - */
    public static function xml2array($xmlObject, $out = array())
    {
        foreach ((array) $xmlObject as $index => $node)
            $out[$index] = (is_object($node)) ? self::xml2array($node) : $node;
        return $out;
    }

    public static function cleanAdfXml(string $input): string
    {
        $input = trim($input);

        // Find XML declaration start
        $start = strpos($input, '<?xml');
//        if ($start === false) {
//            throw new Exception('Invalid ADF: XML declaration not found.');
//        }

        // Find closing ADF tag
        $end = stripos($input, '</adf>');
//        if ($end === false) {
//            throw new Exception('Invalid ADF: closing </adf> tag not found.');
//        }

        // Keep only content from <?xml ... through </adf>

        $end += strlen('</adf>');
        $xml = substr($input, $start, $end - $start);

        return trim($xml);
    }
}
