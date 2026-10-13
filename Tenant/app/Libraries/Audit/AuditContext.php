<?php

namespace Tenant\app\Libraries\Audit;
class AuditContext
{
    private string $dealership_uid;
    private string $user_uid = '';
    private string $ip_address;
    private string $source;
    private string $user_agent;
    private string $request_id;
    private string $user_email = '';
    private string $actor_type;

    public function setDealershipUid(string $dealership_uid): self
    {
        $this->dealership_uid = $dealership_uid;
        return $this;
    }

    public function setActor(string $actor_type, string $user_uid, string $user_email = ''): self
    {
        $this->actor_type = $actor_type;

        if ($this->actor_type === AuditConstant::USER) {
            $this->user_uid = $user_uid;
            $this->user_email = $user_email;
        }
        return $this;
    }

    public function setIpAddress(string $ip_address): self
    {
        $this->ip_address = $ip_address;
        return $this;
    }

    public function setSource(string $source = AuditConstant::WEB): self
    {
        $this->source = $source;
        return $this;
    }

    public function setUserAgent(string $user_agent): self
    {
        $this->user_agent = $user_agent;
        return $this;
    }

    public function setRequestId(string $request_id): self
    {
        $this->request_id = $request_id;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'dealership_uid' => $this->dealership_uid,
            'user_uid' => $this->user_uid,
            'ip_address' => $this->ip_address,
            'source' => $this->source,
            'user_agent' => $this->user_agent,
            'request_id' => $this->request_id,
            'user_email' => $this->user_email,
            'actor_type' => $this->actor_type,
        ];
    }
}