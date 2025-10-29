<?php

namespace App\Traits;

trait HasSimpleRoles
{
    public function hasRole($role)
    {
        // Simple implementation - you can extend this based on your needs
        // For example, check a 'role' column in users table
        return $this->role === $role;
    }

    public function hasAnyRole($roles)
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }

        return in_array($this->role, $roles);
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function isOperator()
    {
        return $this->hasRole('operator');
    }

    public function isPegawai()
    {
        return $this->hasRole('pegawai');
    }
}