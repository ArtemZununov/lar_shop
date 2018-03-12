<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    public $timestamps = false;

    protected $fillable = ['title', 'content', 'is_bold', 'link', 'publish_date'];

    public function tags()
    {
        return $this->hasMany('App\ArticlesTag');
    }
}
