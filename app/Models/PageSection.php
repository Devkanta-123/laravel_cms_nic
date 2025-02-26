<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageSection extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table='page_section';

    protected $fillable = ['menu_id','page_section_name','order','status','hindi_name', 'khasi_name','other_name','title', 'page_section_id'];

    public function pageSectionMaster()
    {
        return $this->belongsTo(PageSectionMaster::class, 'page_section_id', 'id');
    }


}
