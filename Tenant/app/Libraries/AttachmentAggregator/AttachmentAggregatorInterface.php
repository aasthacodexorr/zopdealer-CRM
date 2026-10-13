<?php

namespace Tenant\app\Libraries\AttachmentAggregator;


interface AttachmentAggregatorInterface
{


    /**
     * getAllLinks
     * @param  array $namespaces
     * @param  String $current_module
     * @param  String $current_module_id
     * @param  array $selected_attachments
     * @param  array $output_attachment_list 
     * @return array
     */
    public function getAllLinks(
        array $namespaces = [],
        String $current_module,
        String $current_module_id,
        array $selected_attachments = [],
        array $output_attachment_list = []
    ): array;

    
    /**
     * process
     *
     * @param  array $attachments
     * @param  String $zip_file
     * @return void
     */
    public function process(array $attachments = [], String $zip_file = 'docs.zip'): void;
}
