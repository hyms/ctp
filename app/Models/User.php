<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use function PHPUnit\Framework\isNull;

class User extends Model implements AuthenticatableContract
{
    use Authenticatable;

    protected $table = 'users';
    public static $tables = 'users';
    protected $guarded = [];

    public function getNameAttribute()
    {
        return $this->nombre . ' ' . $this->apellido;
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::needsRehash($password) ? Hash::make($password) : $password;
    }

    public static function getAll()
    {
        $users = DB::table(self::$tables);
        $users = $users->whereNull('deleted_at');
        return $users->get();
    }

    public static function getRole($int = null)
    {
        $roles = array(
            '0' => 'sadmin',
            '1' => 'admin',
            '2' => 'venta',
            '3' => 'operario',
            '4' => 'diseño',
            '5' => 'auxVenta'
        );
        if (isNull($int)) {
            return array(
                ['value' => '0', 'text' => 'sadmin'],
                ['value' => '1', 'text' => 'admin'],
                ['value' => '2', 'text' => 'venta'],
                ['value' => '3', 'text' => 'operario'],
                ['value' => '4', 'text' => 'diseño'],
                ['value' => '5', 'text' => 'auxVenta'],
            );
        }
        return $roles[$int];
    }
}
