<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'projects';
    
    protected $fillable = [
        'title',
        'tematic',
        'subtematic',
        'mode',
        'N.estudents',
        'description',
        'requirements',
        'pre-requirements',
        'keys',
        'alumno_id', // reference alumno
        'editor_id'  // reference edit
    ];
    
    public function profesor(){
        return $this->belongsTo(User::class, 'profesor_id');
    }
    public function alumno(){
        return $this->belongsTo(User::class, 'alumno_id');
    }
    public function knowledgeArea()
    {
        return $this->belongsTo(KnowledgeArea::class);
    }
    public function articleStatus()
    {
        return $this->belongsTo(ArticleStatus::class);
    }

    public function call()
    {
        return $this->belongsTo(Call::class);
    }

    public function authors()
    {
        return $this->hasMany(Author::class);
    }

    public function file()
    {
        return $this->morphOne(File::class, 'fileable');
    }

    public function articleReviews()
    {
        return $this->hasMany(ArticleReview::class);
    }
}
