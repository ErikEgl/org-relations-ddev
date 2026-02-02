<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = ['org_name'];

    public function daughters()
    {
        return $this->belongsToMany(Organization::class, 'organization_relations', 'parent_id', 'daughter_id');
    }

    public function parents()
    {
        return $this->belongsToMany(Organization::class, 'organization_relations', 'daughter_id', 'parent_id');
    }
}
