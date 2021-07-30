<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCategory extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['name','image','category_id'];
    
    //protected $table = 'sub_categories';

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function products(){
        return $this->hasMany(Product::class);
    }

    public function getImagenAttribute(){
        if ($this->image == null) return 'unavailable.png';
        if (file_exists('storage/subcategories/' .$this->image)) {
            return $this->image;
        }else{
            return 'unavailable.png';
        }
    }
}
