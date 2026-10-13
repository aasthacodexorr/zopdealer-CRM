<?php

namespace App\Libraries\CloudStorage;


class BunnyEdgeCloudStorage implements CloudStorageInterface
{
    /**
     * bunnyCDNStorage This stores the instance of BunnyCDNStorage
     *
     * @var \BunnyCDN\Storage\BunnyCDNStorage
     */
    private $bunnyCDNStorage;

    /**
     * permitted_folders_for_deletion This stores the folder names which are permitted to be deleted.
     *
     * @var array
     */
    private $permitted_folders_for_deletion = [UPLOAD_FOLDER, ROOT_FILE];

    public function __construct()
    {
        $this->bunnyCDNStorage = new \BunnyCDN\Storage\BunnyCDNStorage(getenv('bunny.storage.zone'), getenv('bunny.storage.key'), getenv('bunny.storage.region'));
    }
    public function save($file, String $path): void
    {
        $this->bunnyCDNStorage->uploadFile($file, "/" . getenv('bunny.storage.zone') . "/" . $path);
    }

    public function has(String $file_path): bool
    {
        try {
            if ($this->isAbsoluteUrl($file_path)) {
                return false;
            }


            $file_info = pathinfo($file_path);

            $file_list =  $this->bunnyCDNStorage->getStorageObjects("/" . getenv('bunny.storage.zone') . $file_info['dirname']);
            foreach ($file_list as $file_object) {
                if ($file_object->ObjectName == $file_info['basename']) {
                    return true;
                }
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function get(String $file_path): void
    {
        if ($this->has($file_path)) {
            $this->bunnyCDNStorage->downloadFile("/" . getenv('bunny.storage.zone') . $file_path, ROOTPATH . '/public/' . $file_path);
        }
    }

    public function delete(String $file_path): void
    {
        try {
            if ($this->has($file_path)) {
                $this->bunnyCDNStorage->deleteObject("/" . getenv('bunny.storage.zone') . $file_path);
            }
        } catch (\Exception $ex) {
            log_message('error', $ex->getMessage());
        }
    }

    public function emptyFolder(String $folder_path): void
    {

        try {
            foreach ($this->permitted_folders_for_deletion as $permitted_folder) {
                if (strpos($folder_path, $permitted_folder) !== false) {
                    $this->bunnyCDNStorage->deleteObject("/" . getenv('bunny.storage.zone') . $folder_path);
                }
            }
        } catch (\Exception $ex) {
            log_message('warning', $ex->getMessage());
        }
    }

    public function getFileUrl(string $file_path): string
    {
        if ($this->isAbsoluteUrl($file_path)) {
            return $file_path;
        }

        return getEnv('image.cdn') . $file_path;
    }

    /**
     * isAbsoluteUrl This Regex is used to check for Absolute Url.
     *
     * @param  mixed $url
     * @return void
     */
    private function isAbsoluteUrl($url)
    {
        $pattern = "/^(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9]\.[^\s]{2,})|^([a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,7})$/im";

        return (bool) preg_match($pattern, $url);
    }
}
