<?php

namespace App\Helpers;

/**
 * Utility This class provides generic utility functions for syndication.
 */
class SyndicationUtility
{

    /**
     * CDN URL for images
     *     
     * @return string
     */
    public static function getCdnUrl(): string
    {
        return getEnv('syndication.image.cdn');
    }

    /**
     * CDN URL for Kijiji images
     *
     * @return string
     */
    public static function getOptimizeCdnUrl(): string
    {
        return getEnv('syndication.image.cdn.optimize');
    }

    public static function getImageUrl($url, $cdn = ''): string
    {
        $pattern = "/^(?:ftp|https?|feed)?:?\/\/(?:(?:(?:[\w\.\-\+!$&'\(\)*\+,;=]|%[0-9a-f]{2})+:)*
        (?:[\w\.\-\+%!$&'\(\)*\+,;=]|%[0-9a-f]{2})+@)?(?:
        (?:[a-z0-9\-\.]|%[0-9a-f]{2})+|(?:\[(?:[0-9a-f]{0,4}:)*(?:[0-9a-f]{0,4})\]))(?::[0-9]+)?(?:[\/|\?]
        (?:[\w#!:\.\?\+\|=&@$'~*,;\/\(\)\[\]\-]|%[0-9a-f]{2})*)?$/xi";

        $url = trim($url);
        if ((bool) preg_match($pattern, $url)) {
            return $url;
        }
        $cdnUrl = !empty($cdn) ? $cdn : self::getOptimizeCdnUrl();
        return $cdnUrl . $url;
    }



    /**
     * parseSyncdicationOptions 
     *
     * @param  mixed $comment
     * @return string
     */
    public static function parseSyncdicationOptions($data, $seperator): string
    {
        $json_array = json_decode($data, true);
        $result_array = [];

        if (gettype($json_array) == "array") {
            array_walk_recursive($json_array, function ($item, $key) use (&$result_array) {
                if (!empty($item) && $item == 1 && (count($result_array) < 25)) {
                    if (gettype($key) == "string") {
                        array_push($result_array, $key);
                    }
                }
            }, $result_array);
        }

        return implode($seperator, $result_array);
    }

    public static function cleanTextForCSV($text) {
        // 1. Remove BOM if present
        $text = str_replace("\xEF\xBB\xBF", '', $text);
        $text = preg_replace('/\x{FEFF}/u', '', $text);

        // 2. Convert to UTF-8 and strip invalid bytes
        $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
        $text = iconv('UTF-8', 'UTF-8//IGNORE', $text);

        // 3. Remove control characters (except \n and \t if needed)
        //$text = preg_replace('/[\x00-\x1F\x7F]/u', '', $text);

        // 4. Remove emojis, icons, and symbols (unicode blocks)
        $text = preg_replace('/[\x{1F600}-\x{1F64F}]/u', '', $text); // Emoticons
        $text = preg_replace('/[\x{1F300}-\x{1F5FF}]/u', '', $text); // Misc symbols and pictographs
        $text = preg_replace('/[\x{1F680}-\x{1F6FF}]/u', '', $text); // Transport & map symbols
        $text = preg_replace('/[\x{2600}-\x{26FF}]/u', '', $text);   // Misc symbols
        $text = preg_replace('/[\x{2700}-\x{27BF}]/u', '', $text);   // Dingbats
        $text = preg_replace('/[\x{1F900}-\x{1F9FF}]/u', '', $text); // Supplemental symbols and pictographs
        $text = preg_replace('/[\x{1FA70}-\x{1FAFF}]/u', '', $text); // Extended symbols
        $text = preg_replace('/[\x{200D}\x{FE0F}]/u', '', $text);    // Zero-width joiners & variation selectors

        // 5. Remove all other non-text characters (optional stricter filtering)
        // Keeps letters, numbers, punctuation, basic symbols
        $text = preg_replace('/[^\P{C}\P{So}\P{Sk}]+/u', '', $text); // Remove other symbols (So) and modifiers (Sk)

        // 6. Normalize whitespace
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);

        return $text;
    }



    /**
     * Comment Parsing for Sydnication <br> ( Stripping HTML)
     *
     * @return string
     */
    public static function parseComment($comment): string
    {
        $desc = str_replace(["</p>", "</li>", "</u>", "<br>", "&nbsp;"], ["</p> <br/> ", "</li> <br/> ", "</u> <br/> ", "<br/> ", " "], $comment);
        $desc = strip_tags($desc, '<br>');
        $desc = preg_replace("/\r|\n/", ' ', $desc);
        //$desc = preg_replace('/[\x00-\x1F\x7F]/u', '', $desc);
        $desc = self::cleanTextForCSV($desc);

        return $desc;
    }

    public static function parseDescription($comment, $length = 800): string
    {
        $comments = str_replace(["&nbsp;"], [" "], $comment);
        $desc = strip_tags($comments);
        $desc = preg_replace("/\r|\n/", ' ', $desc);
        //$desc = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $desc);
        //$desc = preg_replace('/[\x00-\x1F\x7F]/u', '', $desc);
        $desc = self::cleanTextForCSV($desc);
        $desc = substr($desc, 0, $length) . " ...";

        return $desc;
    }

    public static function parseDescriptionWithHtml($comment, $length = 800): string
    {
        $desc = str_replace(["</p>", "</li>", "</u>", "<br>", "&nbsp;"], ["</p> <br/> ", "</li> <br/> ", "</u> <br/> ", "<br/> ", " "], $comment);
        $desc = strip_tags($desc, '<br>');
        $desc = preg_replace("/\r|\n/", ' ', $desc);
        //$desc = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $desc);
        //$desc = preg_replace('/[\x00-\x1F\x7F]/u', '', $desc);
        $desc = self::cleanTextForCSV($desc);
        $desc = substr($desc, 0, $length) . " ...";

        return $desc;
    }

    /**
     * Jsone to string (inventory)
     *     
     * @return string
     */
    public static function parseTags($tags, $seperator): string
    {
        try {
            $tags = json_decode($tags);
            return implode($seperator, $tags);
        } catch (\Exception $ex) {
            return '';
        }
    }

    /**
     * Scan for files in a folder to string (inventory)
     *     
     * @param  string $dir 
     * @param  string $fileNameCheck
     * @param string $fileNameExtension
     * @return array
     */
    public static function listFolders($dir, $fileNameCheck = "", $fileNameExtension = "csv"): array
    {
        $list = [];
        $directory = new \RecursiveDirectoryIterator($dir);
        $directory->setFlags(\RecursiveDirectoryIterator::SKIP_DOTS);

        $files = new \RecursiveIteratorIterator($directory, \RecursiveIteratorIterator::SELF_FIRST);

        foreach ($files as $file) {
            if ($file->isDir() == false && $file->getExtension() === $fileNameExtension) {
                if ($fileNameCheck) {
                    if ($file->getBasename() != $fileNameCheck) {
                        continue;
                    }
                }
                $list[] = $file->getPathname();
            }
        }
        return $list;
    }


    /**
     * Merge multiple CSV files into one master CSV file 
     * Remove header line from individual file
     *     
     * @param  array $files 
     * @param  string $masterFile
     * @param  string $seperator
     * 
     */
    public static function joinFiles(array $files, $masterFile, $seperator = ",")
    {
        if (!is_array($files)) {
            throw new \Exception('$files` must be an array');
        }

        // Open and Write Master CSV File
        $masterCSVFile = fopen($masterFile, "w+");
        $counter = 0;

        // Process each CSV file inside root directory
        foreach ($files as $file) {
            $data = [];

            // Open and Read individual CSV file
            if (($handle = fopen($file, 'r')) !== false) {
                // Collect CSV each row records
                while (($dataValue = fgetcsv($handle, 0)) !== false) {
                    $data[] = $dataValue;
                }
            }

            fclose($handle); // Close individual CSV file 

            if ($counter != 0) {
                // Remove first row of CSV, commonly tends to CSV header
                unset($data[0]);
            }

            // Check whether record present or not
            if (count($data) > 0) {
                foreach ($data as $value) {
                    try {
                        // Insert record into master CSV file
                        $val = fputcsv($masterCSVFile, $value, $seperator);
                    } catch (\Exception $e) {
                        echo $e->getMessage();
                    }
                }
            } else {
                // echo "[$file] file contains no record to process.";
            }

            $counter++;
        }

        // Close master CSV file 
        fclose($masterCSVFile);
    }


    /**
     * Encrypt data using pgp
     *     
     * @param  array $files 
     * @param  string $masterFile
     * @param  string $seperator
     * 
     */
    public static function encrypt(string $fingerprint, string $outputFile)
    {
        try {
            $gpg = gnupg_init();
            gnupg_seterrormode($gpg, GNUPG_ERROR_WARNING);
            gnupg_addencryptkey($gpg, $fingerprint);
            $plaintext = file_get_contents($outputFile);
            $ciphertext = gnupg_encrypt($gpg, $plaintext);
            file_put_contents($outputFile . '.gpg', $ciphertext);
        } catch (\Exception $e) {
            die('Error ' . $e->getMessage());
        }
    }

    /**
     * Fix utf8 char
     *     
     * @param  string $title
     * @return string $NewStr
     */
    public static function fixutf(string $title): ?string
    {
        $StrArr = str_split($title);
        $NewStr = '';
        foreach ($StrArr as $Char) {
            $CharNo = ord($Char);
            if ($CharNo == 163) {
                $NewStr .= $Char;
                continue;
            }
            if ($CharNo > 31 && $CharNo < 127) {
                $NewStr .= $Char;
            }
        }
        return $NewStr;
    }
}
