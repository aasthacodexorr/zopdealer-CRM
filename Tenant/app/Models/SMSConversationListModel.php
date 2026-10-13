<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class SMSConversationListModel extends Model
{
    protected $table      = 'sms_conversation';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'from','Incoming'
    ];

    protected $useSoftDeletes = true;
    protected $returnType    = 'CodeIgniter\Entity';
    protected $useTimestamps = true;

    /**
     * findAll Overidding findAll method and returning the objects with Images Array.
     *
     * @param  mixed $limit
     * @param  mixed $offset
     * @return void
     */
    public function findAll(int $limit = 0, int $offset = 0)
    {
        $builder = $this->builder();

        if ($this->tempUseSoftDeletes === true) {
            $builder->where($this->table . '.' . $this->deletedField, null);
        }

        $row = $builder
            ->select("
            (SELECT IF(STRCMP(sms_conversation.direction,'Incoming') = 0, 
            sms_conversation.from, sms_conversation.to )) as customer_number
            ,
            (SELECT IF(STRCMP(sms_conversation.direction,'Incoming') = 0, 
            sms_conversation.to, sms_conversation.from )) as salesperson_number
            ,
            SUM(sms_conversation.read=0) as unread,
            GROUP_CONCAT( sms_conversation.body ORDER BY sms_conversation.created_at DESC SEPARATOR '||' ) as body, MAX(created_at) as timestamp", FALSE)
            ->groupBy(['customer_number','salesperson_number'])
            ->limit($limit, $offset)
            ->get();

        $row = $row->getResult($this->tempReturnType);

        $eventData = $this->trigger('afterFind', ['data' => $row, 'limit' => $limit, 'offset' => $offset]);

        $this->tempReturnType     = $this->returnType;
        $this->tempUseSoftDeletes = $this->useSoftDeletes;

        return $eventData['data'];
    }
}
