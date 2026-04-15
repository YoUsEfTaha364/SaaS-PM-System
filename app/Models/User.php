<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable ,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

     public function getJWTIdentifier()
    {
        return $this->getKey();
    }

     public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function ownedWorkspaces(){
        return $this->hasMany(Workspace::class,"owner_id");
    }

        public function workspaces()
    {
        return $this->belongsToMany(Workspace::class)
                    ->withPivot('role');
                  
    }
      public function tasks()
    {
        return $this->belongsToMany(Task::class)->withTimestamps();
    }
      public function comments()
    {
        return $this->hasMany(User::class);;
    }
       public function attachments(){
        return $this->hasmany(Attachment::class,"uploaded_by");
    }

    public function activities(){
        return $this->hasMany(TaskActivity::class);
    }


}
