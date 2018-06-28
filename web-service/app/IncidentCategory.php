<?php

namespace TaskList;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class IncidentCategory extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'IncidentCategory';

    protected $fillable = ['name'];
}
