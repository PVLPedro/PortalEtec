<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Course extends Model
{
    protected $table = 'course';
 
    protected $primaryKey = 'id_course';
 
    public $timestamps = false;
 
    protected $fillable = [
        'course_initialism',
        'course_name',
    ];
}