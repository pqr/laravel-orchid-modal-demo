<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Phone extends Model
{
    use AsSource;

    protected $fillable = ['number', 'description', 'contact_id'];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
