<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class ParseRequest extends Model
{
    protected $fillable = ['is_saved', 'csv_file_link'];

    public function articles()
    {
        return $this->hasMany('App\Article');
    }
    
    
    
}
