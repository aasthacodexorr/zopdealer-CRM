<?php

namespace App\Libraries\Mailbox;

use Carbon\Carbon;
use Exception;
use PhpImap\Exceptions\ConnectionException;
use PhpImap\Mailbox;

/**
 * IMAP This class is used for operations of IMAP such as listing, reading and downloading attachments
 * in the mail.
 */
class IMAP
{


    /**
     * mailbox This initializes the mailbox with settings.
     *
     * @var mixed
     */
    private $mailbox;

    private $host_name;

    private $email;

    private $password;

    private $port;

    private $secure;

    private $attachment_folder;


    /**
     * __construct
     *
     * @param  mixed $host_name
     * @param  mixed $email
     * @param  mixed $password
     * @param  mixed $port
     * @param  mixed $secure
     * @param  mixed $attachment_folder
     * @return void
     */
    public function __construct(String $host_name, String $email, String $password, $port, $secure, $attachment_folder = null)
    {
        $this->host_name = $host_name;
        $this->email = $email;
        $this->password = $password;
        $this->port = $port;
        $this->secure = $secure;
        $this->attachment_folder = $attachment_folder;
    }


    /**
     * initialize This initializes the connection for the Mailbox
     *
     * @return void
     */
    protected function initialize()
    {
        $connection_string = '{' . $this->host_name . ':' . $this->port . '/imap/' . $this->secure . getenv('email.validate.url') .'}';
        
        $server_encoding = strstr($this->host_name, "outlook") ? "US-ASCII" : "UTF-8";
        $this->mailbox = new Mailbox($connection_string, $this->email, $this->password, $this->attachment_folder, $server_encoding);
        $this->mailbox->setConnectionArgs(CL_EXPUNGE |  OP_SILENT);
    }

    /**
     * getEmailConversation This method 
     *
     * @param  string $receiver This is the email id of the other person with whom we are communicating.
     * @param  int $days This parameter fetchs the conversation for following number of days.
     * @return array
     */
    public function getEmailConversation(string $receiver, int $days = 7): array
    {
        try {
            $this->initialize();

            $past_date = Carbon::now()->subDays($days);
            $past_date = $past_date->isoFormat("D MMMM YYYY");

            $this->mailbox->setAttachmentsIgnore(true);

            $folders = $this->mailbox->getMailboxes('*');

            $from_mail_ids = [];
            $to_mail_ids = [];
            $response = [];
            foreach ($folders as $folder) {
                if (preg_match('/(Sent|Inbox)/mi', $folder['fullpath'])) {

                    $this->mailbox->switchMailbox($folder['fullpath']);
                    $from_mail_ids  = $this->mailbox->searchMailbox('FROM "' . $receiver . '" SINCE "' . $past_date . '"');
                    $to_mail_ids  = $this->mailbox->searchMailbox('TO "' . $receiver . '" SINCE "' . $past_date . '"');

                    $mail_ids = [];

                    if (gettype($from_mail_ids) == 'array' && gettype($to_mail_ids) == 'array') {
                        $mail_ids = array_merge($from_mail_ids, $to_mail_ids);
                        //$mail_ids = array_unique($mail_ids);
                    } else if (gettype($from_mail_ids) == 'array') {
                        $mail_ids = $from_mail_ids;
                    } else if (gettype($to_mail_ids) == 'array') {
                        $mail_ids = $to_mail_ids;
                    }

                    if (count($mail_ids) > 0) {
                        $emails = $this->mailbox->getMailsInfo($mail_ids);
                        foreach ($emails as $email) {
                            $sender = preg_replace('/[^<]*</', '', $email->from);
                            $sender = preg_replace('/>/', '', $sender);

                            $recipient = preg_replace('/[^<]*</', '', $email->to);
                            $recipient = preg_replace('/>/', '', $recipient);
                            array_push($response, [
                                'id' => $email->uid,
                                'sender' => $sender,
                                'recipient' => $recipient,
                                'subject' => !empty($email->subject) ? $email->subject : "",
                                'udate' => $email->udate,
                                'date' => $email->date,
                                'seen' => $email->seen,
                                'shortpath' => $folder['shortpath']
                            ]);
                        }
                    }
                }
            }

            usort($response, function ($a, $b) {
                return $b['udate'] - $a['udate'];
            });

            return $response;
        } catch (\PhpImap\Exceptions\ConnectionException $ex) {
            return ['errors' => $ex->getMessage()];
        } catch (Exception $ex) {
            return ['errors' => $ex->getMessage()];
        }
    }


    /**
     * getSingleEmail
     *
     * @param  string $mailbox
     * @param  int $id
     * @return void
     */
    public function getSingleEmail(string $mailbox, int $id): ?array
    {
        $response = [];
        try {

            $this->initialize();
            $folders = $this->mailbox->getMailboxes('*');
            foreach ($folders as $folder) {
                if ($mailbox === $folder['shortpath']) {
                    $this->mailbox->switchMailbox($folder['fullpath']);
                    $email = $this->mailbox->getMail($id);
                    $response['body'] = $email->textHtml ? $email->textHtml : $email->textPlain;
                    $response['from'] = $email->fromAddress;
                    $response['subject'] = !empty($email->subject) ? $email->subject : "";
                    $response['to'] = !empty($email->to) ? implode(", ", array_keys($email->to)) : "";
                    $response['cc'] = !empty($email->cc) ? implode(", ", array_keys($email->cc)) : "";
                    $response['bcc'] = !empty($email->bcc) ? implode(", ", array_keys($email->bcc)) : "";
                    $response['replyTo'] = !empty($email->replyTo) ? implode(", ", array_keys($email->replyTo)) : "";
                    return $response;
                }
            }

            return ['errors' => "No Email Found."];
        } catch (\Exception $ex) {
            return ['errors' => $ex->getMessage()];
        }
    }


    /**
     * testConnection
     *
     * @return bool
     */
    public function testConnection()
    {
        try {

            $this->initialize();

            $this->mailbox->setAttachmentsIgnore(true);
            $folders = $this->mailbox->getMailboxes('*');
            return true;
        } catch (ConnectionException $ex) {
            return false;
        } catch (Exception $ex) {
            return false;
        }

        return true;
    }
    
    /**
     * downloadAttachmentOfEmail
     *
     * @param  mixed $mailbox
     * @param  mixed $id
     * @return void
     */
    public function downloadAttachmentOfEmail(string $mailbox, int $id)
    {
        try {

            $this->initialize();
            $folders = $this->mailbox->getMailboxes('*');
            foreach ($folders as $folder) {
                if ($mailbox === $folder['shortpath']) {
                    $this->mailbox->switchMailbox($folder['fullpath']);
                    $email = $this->mailbox->getMail($id);
                    
                    // Save attachments one by one
                    if (!$this->mailbox->getAttachmentsIgnore()) {
                        $attachments = $email->getAttachments();
                        return $attachments;
                    }
                    else {
                        return ['errors' => "No Attachments Found."];
                    }
                }
            }

            return ['errors' => "No Email Found."];
        } catch (\Exception $ex) {
            return ['errors' => $ex->getMessage()];
        }
    }
}
