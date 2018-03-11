<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticlesTag extends Model
{
    public $timestamps = false;

    protected $fillable = ['name'];
}
