<?php

namespace TaskList;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Panel extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'Panel';

    protected $fillable = ['name','position'];
}
