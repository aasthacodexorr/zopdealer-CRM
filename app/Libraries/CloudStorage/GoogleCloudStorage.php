<?php

namespace App\Libraries\CloudStorage;

use Google\Cloud\Storage\Bucket;
use Google\Cloud\Storage\StorageClient;
use Override;

class GoogleCloudStorage implements CloudStorageInterface
{

    private StorageClient $storageClient;

    private Bucket  $bucket;

    /**
     * permitted_folders_for_deletion This stores the folder names which are permitted to be deleted.
     *
     * @var array
     */
    private $permitted_folders_for_deletion = [UPLOAD_FOLDER, ROOT_FILE];

    public function __construct()
    {
        $this->storageClient = new StorageClient([
            'keyFilePath' => ROOTPATH . 'google_cloud_storage.json'
        ]);

        $this->bucket = $this->storageClient->bucket(env('google.storage.zone'));
    }
    public function save($file, String $path): void
    {
        $this->bucket->upload(
            fopen($file, 'r'),
            [
                'name' => $path
            ]
        );
    }

    public function has(String $file_path): bool
    {
        try {
            if ($this->isAbsoluteUrl($file_path)) {
                return false;
            }

            $file_path = ltrim($file_path, '/');
            $object =  $this->bucket->object($file_path);
            $info = $object->info();

            if (isset($info['name'])) {
                return true;
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function get(String $file_path): void
    {
        $file_path = ltrim($file_path, '/');

        if ($this->has($file_path)) {
            $object = $this->bucket->object($file_path);
            $object->downloadToFile(ROOTPATH . '/public/' . $file_path);
        }
    }

    public function delete(String $file_path): void
    {
        $file_path = ltrim($file_path, '/');

        if ($this->has($file_path)) {
            $object = $this->bucket->object($file_path);
            $object->delete();
        }
    }

    public function emptyFolder(String $folder_path): void
    {
        try {
            foreach ($this->permitted_folders_for_deletion as $permitted_folder) {
                if (strpos($folder_path, $permitted_folder) !== false) {
                    $folder_path = ltrim($folder_path, '/');
                    $options = ['prefix' => $folder_path];
                    foreach ($this->bucket->objects($options) as $object) {
                        $object->delete();
                    }
                }
            }
        } catch (\Exception $ex) {
            log_message('warning', $ex->getMessage());
        }
    }

    #[Override]
    public function getFileUrl(string $file_path): string
    {
        if ($this->isAbsoluteUrl($file_path)) {
            return $file_path;
        }

        $file_path = ltrim($file_path, '/');
        $object =  $this->bucket->object($file_path);
        $info = $object->info();

        if (isset($info['name'])) {
            $url = $object->signedUrl(
                new \DateTime('+15 minutes')
            );
            return $url;
        }

        throw new \Exception("Signed URL couldn't be found", 404);
    }

    private function isAbsoluteUrl(String $url)
    {
        $pattern = "/^(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9]\.[^\s]{2,})|^([a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,7})$/im";
        return (bool) preg_match($pattern, $url);
    }
}
