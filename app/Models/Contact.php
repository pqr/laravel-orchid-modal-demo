<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Contact extends Model
{
    use AsSource;

    protected $fillable = ['name'];

    public function phones()
    {
        return $this->hasMany(Phone::class);
    }
}
