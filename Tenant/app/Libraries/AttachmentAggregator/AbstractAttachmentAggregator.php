<?php

namespace Tenant\app\Libraries\AttachmentAggregator;

use App\Helpers\Utility;
use Tenant\app\Libraries\AttachmentAggregator\AttachmentAggregatorInterface;

abstract class AbstractAttachmentAggregator implements AttachmentAggregatorInterface
{
    /**
     * module
     *
     * @var string
     */
    protected $module = '';

    protected function hasRequiredInformation(string $current_module, string $current_module_id, array $selected_attachments = [], String $requested_attachment = ''): bool
    {
        if (
            empty($current_module) ||
            empty($current_module_id) ||
            empty($selected_attachments) ||
            empty($this->module)
        ) {
            return false;
        }

        if (strtoupper($current_module) === strtoupper($this->module) && in_array($requested_attachment, $selected_attachments)) {
            return true;
        }

        return false;
    }

    public function process(array $attachments = [], String $zip_file = 'docs.zip'): void
    {

        if (empty($attachments)) {
            return;
        }

        $arrContextOptions = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );


        $options = new \ZipStream\Option\Archive();
        $options->setSendHttpHeaders(true);

        $zip = new \ZipStream\ZipStream($zip_file, $options);

        try {
            foreach ($attachments as $attachment_key => $attachment_url) {

                $fp = tmpfile();
                $attachment_url = Utility::getImageUrl($attachment_url);
                fwrite($fp, file_get_contents($attachment_url, false, stream_context_create($arrContextOptions)));
                rewind($fp);
                $zip->addFileFromStream($attachment_key, $fp);
                fclose($fp);
            }
            $zip->finish();
        } catch (\Exception $ex) {
            var_dump($ex->getMessage());
        }
    }
}
