<?php

namespace TaskList;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Incident extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'Incident';

    protected $fillable = ['title','description','category','solution'];
}
