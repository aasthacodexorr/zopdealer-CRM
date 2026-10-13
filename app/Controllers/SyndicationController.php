<?php

namespace App\Controllers;

use Carbon\Carbon;
use App\Models\SyndicationModel;
use App\Entities\Syndication;
use App\Helpers\SyndicationUtility;
use App\Repositories\SyndicationRepository;
use App\Repositories\SyndicationQueueRepository;
use App\Libraries\Syndication\SyndicationFactory;
use App\Libraries\FtpClient\FtpClient;
use Hug\Sftp\Sftp as Sftp;

use Tenant\app\Repositories\SyndicationRepository as TenantSyndication;  // @TODO : verify if this is required

class SyndicationController extends BaseController
{
  public $data = [];
  private $_errors = [
    "NO_RECORDS" => "No Record Found",
    "NO_ACTIVE_SYNDICATION" => "No Active Syndication",
    "ERROR" => "Something went wrong. Please contact Admin",
    "SUCCESS" => "Data has been loaded",
    "NO_FILE" => "No Syndication files found",
    "FILE_SUCCESS" => "File has been uploaded successfully",
    "WRONG_OPERATION" => "Invalid Operation"
  ];

  /**
   * Add new syndication record
   *
   * @return void
   */
  public function create()
  {
    $data = $this->request->getPost();
    $syndication = new Syndication($data);
    $syndicationModel = new SyndicationModel();

    if ($syndicationModel->save($syndication) === false) {
      $this->response->setStatusCode('400');
      $this->data['status'] = 'ERROR';
      $this->data['msg'] = $syndicationModel->errors();
    } else {
      $syndication_id = $syndicationModel->insertID;
      $this->data['status'] = 'OK';
      $this->data['msg'] = "New Syndication Created with ID:" . $syndication_id;
    }

    $this->response->setContentType('application/json')->setJSON($this->data)->send();
  }

  /**
   * Get all existing syndication Queue listing
   * 
   * @return void
   */
  public function list()
  {
    $items = null;
    $data = new SyndicationRepository();

    if ($this->request->getGet('status')) {
      // @TODO : Santize input
      $status = $this->request->getGet('status');
      $data->filterByStatus($status);
    }

    if ($this->request->getGet('items')) {
      $items = $this->request->getGet('items');
    }

    $this->data['data'] = $data->getSyndicationList($items);

    if (count($this->data['data']) < 1) {
      $this->data['msg'] = $this->_errors['NO_RECORDS'];
    }

    $this->response->setContentType('application/json')->setJSON($this->data)->send();
  }

  /**
   * Add all active syndication to queue
   *
   * @return void
   */
  public function generateQueue($source = null)
  {
    if ($source) {
      $feeds = (new SyndicationRepository())->getRecordToQueue($source);
    } else {
      $feeds = (new SyndicationRepository())->getRecordToQueue();
    }


    if (!$feeds) :
      $this->data['msg'] = $this->_errors['NO_ACTIVE_SYNDICATION'];
    else :
      $records = (new SyndicationQueueRepository)->addtoQueue($feeds);
      $this->data['msg'] = "New Queue Added With Total records :" . $records;
    endif;

    $this->response->setContentType('application/json')->setJSON($this->data)->send();
  }


  public function generateit($source = null)
  {
    $this->generateQueue($source);
  }

  public function processit()
  {
    $this->processQueue();
  }

  /**
   * Skip queue record
   *
   * @return void
   */
  public function skipQueue(int $rid)
  {
    $msg = "Queue Record has been skipped";
    $skip = (new SyndicationQueueRepository())->skipQueueRecordById($rid);
    if (!$skip) {
      $msg = $this->_errors['ERROR'];
    }
    $this->data['msg'] = $msg;
    $this->response->setContentType('application/json')->setJSON($this->data)->send();
  }

  /**
   * Add all offline syndication to queue
   *
   * @return void
   */
  public function generateSocialQueue()
  {
    $feeds = (new SyndicationRepository())->getSocialRecordToQueue();

    if (!$feeds) :
      $this->data['msg'] = $this->_errors['NO_ACTIVE_SYNDICATION'];
    else :
      $records = (new SyndicationQueueRepository)->addtoQueue($feeds);
      $this->data['msg'] = "New Queue Added With Total records :" . $records;
    endif;

    $this->response->setContentType('application/json')->setJSON($this->data)->send();
  }

  /**
   * Get all syndication queue list
   *
   * @return void
   */
  public function getQueue()
  {
    $this->data['data'] = $feeds = (new SyndicationQueueRepository())->getQueueList()->getResult();

    if (count($feeds) < 1) {
      $this->data['msg'] = $this->_errors['NO_RECORDS'];
    }

    $this->response->setContentType('application/json')->setJSON($this->data)->send();
  }



  /**
   * Get social syndication operations
   *
   * @return void
   */
  public function getCommand($op, $source = null)
  {
    if (method_exists($this, $op)) {
      $this->{$op}();
      exit;
    }


    $this->data['msg'] = $this->_errors['WRONG_OPERATION'];
    $this->response->setStatusCode('400')->setContentType('application/json')->setJSON($this->data)->send();
  }

  /**
   * Delete all syndication queue records
   *
   * @return void
   */
  public function cleanQueue()
  {
    $msg = "Queue has been cleared";
    $truncate = (new SyndicationQueueRepository())->clearQueueList();
    if (!$truncate) {
      $msg = $this->_errors['ERROR'];
    }
    $this->data['msg'] = $msg;
    $this->response->setContentType('application/json')->setJSON($this->data)->send();
  }

  /**
   * Process the syndication queue
   *
   * @return void
   */
  public function processQueue($id = false)
  {
    $records = null;
    // get single record to process
    $config = $id ?
      (new SyndicationQueueRepository())->getQueueList()->getSingleRecordById($id)->getRow()
      : (new SyndicationQueueRepository())->getQueueList()->getSingleRecord()->getRow();

    $inprocess = (new SyndicationQueueRepository())->updateLogList($config->syndication_queue_id, ['status' => 'inprocess']);

    $this->data['msg'] = $this->_errors['NO_RECORDS'];

    if ($config !== null) {
      try {
        // Loads object of Syndication class
        $syndication = SyndicationFactory::getSyndicationInstance($config);

        if ($syndication == null) {
          $this->data['msg'] = 'Class Not Found';
          $this->response->setContentType('application/json')->setStatusCode(400)->setJSON($this->data)->send();
          exit;
        }

        $syndication->generateFile();
        if ($status = $syndication->uploadToFtp()) {
          $archiveFileName = $syndication->archive();

          $log['message'] = " Inventories has been sent to ";
          $log['source'] = $config->source;
          $log['invcount'] = $config->icount;
          $log['feed_id'] = $config->syndication_queue_id;
          $log['file_name'] = $archiveFileName;

          $updateLog = (new TenantSyndication())->updateLogList($log);

          $logAdmin['status'] = 'completed';
          $logAdmin['oldfile'] = $archiveFileName;
          $logAdmin['message'] = $config->icount . " Inventories has been sent to " . ucfirst($config->source);

          $updateAdminLog = (new SyndicationQueueRepository())->updateLogList($config->syndication_queue_id, $logAdmin);
          $this->data['msg'] = "Job Completed";
        }
      } catch (\Exception $e) {
        $this->data['msg'] = $e->getMessage();
      }
    }

    $this->response->setContentType('application/json')->setJSON($this->data)->send();
  }

  /**
   * Get all Syndication listing records
   *
   * @return void
   */
  public function feedList()
  {
    $data = $this->request->getPost();

    $valid_password = false;

    $validated = $this->validate([
      'password' => [
        'label' => 'Password',
        'rules' => 'required|min_length[6]',
      ]
    ]);

    if ($validated) {
      $valid_password = password_verify($data['password'], getEnv('clients.show'));
      if (!$valid_password) {
        $this->validator->setError('password', 'Wrong Password');
      }
    }
    if ($validated && $valid_password) {
      $data = new SyndicationModel();
      $this->data['data'] = $data->asArray()->findAll();
      $this->data['msg'] =  $this->_errors['SUCCESS'];

      if (count($this->data['data']) < 1) {
        $this->data['msg'] = $this->_errors['NO_RECORDS'];
      }

      $this->response->setContentType('application/json')->setJSON($this->data)->send();
    } else {
      $errors = $this->validator->getErrors();
      $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($errors)->send();
    }
  }


  /**
   * Push AutotraderGlobal
   *
   * @return void
   */
  public function processAutotraderGlobal()
  {
    $syncData = new \stdClass();
    $syncData->host = getenv('autotrader.host');
    $syncData->user  = getenv('autotrader.user');
    $syncData->pass = getenv('autotrader.pass');
    $syncData->file = getenv('autotrader.file');

    $csvfile = SYNDICATION_EXPORT . 'global/' . $syncData->file;
    $directory = SYNDICATION_EXPORT . 'global/';

    $data1 = SyndicationUtility::listFolders(SYNDICATION_EXPORT, $syncData->file, 'csv');
    $data2 = SyndicationUtility::listFolders(SYNDICATION_EXPORT, str_replace('.csv', '_01.csv', $syncData->file), 'csv');
    $data = array_merge($data1, $data2);

    if (count($data) > 0) {
      $status = 0;
      $merge = SyndicationUtility::joinFiles($data, $csvfile, ',');

        // uploading file
        $uploadResult = 0;
        $login =  Sftp::test($syncData->host, $syncData->user, $syncData->pass, 22, $timeout = 14);
        if ($login) {
            $uploadResult =  Sftp::upload($syncData->host, $syncData->user, $syncData->pass,  $csvfile , $syncData->file , 22, $timeout = 14);
        }

      if (file_exists($csvfile)) {
        $file = new \CodeIgniter\Files\File($csvfile);
        $newFilename =  Carbon::now()->format('Y_m_d_His') . "_" . $syncData->file;
        $newFile = $file->move($directory, $newFilename);
      }

       $this->data['msg'] =   $uploadResult ? $this->_errors['FILE_SUCCESS']   : $this->_errors['ERROR'] ;
    } else {
      $this->data['msg'] =  $this->_errors['NO_FILE'];
    }

    $this->response->setContentType('application/json')->setJSON($this->data)->send();
  }

    /**
     * Push AutotraderGlobal NPV
     *
     * @return void
     */
    public function processAutotraderGlobalNpv()
    {
        $syncData = new \stdClass();
        $syncData->host = getenv('autotrader.npv.host');
        $syncData->user  = getenv('autotrader.npv.user');
        $syncData->pass = getenv('autotrader.npv.pass');
        $syncData->file = getenv('autotrader.npv.file');

        //due to same filename
        $syncData->tempfile = getenv('autotrader.npv.tempfile');

        $csvfile = SYNDICATION_EXPORT . 'globalnpv/' . $syncData->file;
        $directory = SYNDICATION_EXPORT . 'globalnpv/';

        $data = SyndicationUtility::listFolders(SYNDICATION_EXPORT, $syncData->tempfile, 'csv');

        if (count($data) > 0) {
            $status = 0;
            $merge = SyndicationUtility::joinFiles($data, $csvfile, ',');

            // uploading file
            $uploadResult = 0;
            $login =  Sftp::test($syncData->host, $syncData->user, $syncData->pass, 22, $timeout = 14);
            if ($login) {
                $uploadResult =  Sftp::upload($syncData->host, $syncData->user, $syncData->pass,  $csvfile , $syncData->file , 22, $timeout = 14);
            }

            if (file_exists($csvfile)) {
                $file = new \CodeIgniter\Files\File($csvfile);
                $newFilename =  Carbon::now()->format('Y_m_d_His') . "_" . $syncData->file;
                $newFile = $file->move($directory, $newFilename);
            }

            $this->data['msg'] =   $uploadResult ? $this->_errors['FILE_SUCCESS']   : $this->_errors['ERROR'] ;
        } else {
            $this->data['msg'] =  $this->_errors['NO_FILE'];
        }

        $this->response->setContentType('application/json')->setJSON($this->data)->send();
    }


  /**
   * Push SiriusXM
   *
   * @return void
   */
  public function processSiriusxm()
  {
    $directory = SYNDICATION_EXPORT . 'siriusxm';      //static folder
    (is_dir($directory)) ?: mkdir($directory, 0777);   // directory creation

    $syncData = new \stdClass();
    $syncData->host = getenv('siriusxm.host');
    $syncData->user  = getenv('siriusxm.user');
    $syncData->pass = getenv('siriusxm.pass');
    $syncData->file = getenv('siriusxm.file');
    $syncData->folder = getenv('siriusxm.folder');
    $syncData->cid = getenv('siriusxm.cid');
    $syncData->fingerprint = getenv('siriusxm.fingerprint');

    $invTypes = ['inv_', 'sold_', 'cancel_'];

    // loop all type of inventory tpe
    foreach ($invTypes  as $key => $invType) {
      $csvfile = SYNDICATION_EXPORT . '/siriusxm/' . $syncData->cid . '_' . $invType . Carbon::now()->format('Ymd') . '_01.txt';
      $remoteFile =  $syncData->cid . '_' . $invType . Carbon::now()->format('Ymd') . '_01.txt.gpg';

      $data = SyndicationUtility::listFolders(SYNDICATION_EXPORT, $invType . $syncData->file, 'csv');

      if (count($data) > 0) {

        // creation of file/folder and merging to one
        (file_exists($csvfile)) ?: touch($csvfile);
        SyndicationUtility::joinFiles($data, $csvfile, '|');
        SyndicationUtility::encrypt($syncData->fingerprint, $csvfile); // @todo handle correctly

        // uploading file
        $uploadResult = 0;
        $login =  Sftp::test($syncData->host, $syncData->user, $syncData->pass, 22, $timeout = 14);
        if ($login) {
          $uploadResult =  Sftp::upload($syncData->host, $syncData->user, $syncData->pass,  $csvfile . ".gpg", $remoteFile, 22, $timeout = 14);
        }

        $this->data['msg'][] =  $uploadResult ? $this->_errors['FILE_SUCCESS'] . ' - ' . $csvfile  : $this->_errors['ERROR'] . ' - ' . $csvfile;
      } else {
        $this->data['msg'][] =  $this->_errors['NO_FILE'] . ' - ' . $csvfile;
      }
    }

    $this->response->setContentType('application/json')->setJSON($this->data)->send();
  }

  /**
   * Push PlaGloabl
   *
   * @return void
   */
  public function processPlaGlobal()
  {
    $syncData = new \stdClass();
    $syncData->host = getenv('pla.host');
    $syncData->user  = getenv('pla.user');
    $syncData->pass = getenv('pla.pass');
    $syncData->file = getenv('pla.file');

    $csvfile = SYNDICATION_EXPORT . 'pla/' . $syncData->file;
    $directory = SYNDICATION_EXPORT . 'pla/';

    $data = SyndicationUtility::listFolders(SYNDICATION_EXPORT, $syncData->file, 'csv');

    if (count($data) > 0) {
      $status = 0;
      $merge = SyndicationUtility::joinFiles($data, $csvfile, ',');

      $ftp = new FtpClient();
      $ftp->connect($syncData->host);
      $ftp->login($syncData->user, $syncData->pass);
      $ftp->pasv(1);
      $status = $ftp->put($syncData->file, $csvfile, FTP_BINARY);
      $ftp->close();

      if (file_exists($csvfile)) {
        $file = new \CodeIgniter\Files\File($csvfile);
        $newFilename =  Carbon::now()->format('Y_m_d_His') . "_" . $syncData->file;
        $newFile = $file->move($directory, $newFilename);
      }

      $this->data['msg'] =  $this->_errors['FILE_SUCCESS'];
    } else {
      $this->data['msg'] =  $this->_errors['NO_FILE'];
    }

    $this->response->setContentType('application/json')->setJSON($this->data)->send();
  }
}