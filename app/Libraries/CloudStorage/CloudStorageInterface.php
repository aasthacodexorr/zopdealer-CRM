<?php

namespace App\Libraries\CloudStorage;

/***
 * CloudStorageInterface defines the structure of the CloudStorages.
 */
interface CloudStorageInterface
{

    /**
     * save This is to save the File in the Storage.
     *
     * @param  mixed $file
     * @param  mixed $path
     * @return void
     */
    function save($file, String $path): void;

    /**
     * has This method checks whether the file is present or not.
     *
     * @param  mixed $file_path
     * @return bool
     */
    function has(String $file_path): bool;


    /**
     * get This method gets the file on the local storage.
     *
     * @param  mixed $file_path
     * @return void
     */
    function get(String $file_path): void;

    /**
     * delete This method deletes the file if present.
     *
     * @param  mixed $file_path
     * @return void
     */
    function delete(String $file_path): void;

    /**
     * emptyFolder This method deletes all the files in given folder.
     *
     * @param  mixed $folder_path
     * @return void
     */
    function emptyFolder(String $folder_path): void;
    
    /**
     * getFileUrl
     *
     * @param  String $file_path
     * @return String
     */
    function getFileUrl(String $file_path): String;
}
