<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageSectionMaster extends Model
{
    use HasFactory;

    protected $table = "page_section_master";

    protected $fillable = ['page_section_name', 'icon'];

    // Disable the default timestamps
    public $timestamps = false;

    public function pageSections()
    {
        return $this->hasMany(PageSection::class, 'page_section_id', 'id');
    }
}


