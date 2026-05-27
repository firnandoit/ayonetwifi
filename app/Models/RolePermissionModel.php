<?php
namespace App\Models;
use CodeIgniter\Model;

class RolePermissionModel extends Model {
    protected $table = 'role_permissions';
    protected $allowedFields = ['role', 'id_menu'];

    public function getPermissionsByRole($role) {
        return $this->select('id_menu')->where('role', $role)->findAll();
    }
}