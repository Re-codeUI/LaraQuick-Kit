<?php

namespace Magicbox\LaraQuickKit\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'laraquick_users'; // Nama tabel yang disesuaikan
}

