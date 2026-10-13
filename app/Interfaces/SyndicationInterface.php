<?php namespace App\Interfaces;

interface SyndicationInterface {

  function setHeader();

  function generateFile();

  function uploadToFtp();
  
  function formatImages($images);
  
  function formatLineRecord($line);

  function validate($data);
  
  function archive(): string; // @TODO: can pass filename for futre use
}
