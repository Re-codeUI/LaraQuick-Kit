<?php

namespace Magicbox\LaraQuickKit\Models;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $table = 'users'; // Nama tabel yang disesuaikan
}

