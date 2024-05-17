<?php

namespace App\Models\Issue;

use App\Models\BaseModel;

class IssueHeader extends BaseModel
{
    protected $table = 'issue_headers';

    public function details()
    {
        return $this->hasMany(IssueDetail::class, 'header_id');
    }
}
