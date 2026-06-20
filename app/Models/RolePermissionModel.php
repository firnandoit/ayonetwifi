<?php
namespace App\Models;
use CodeIgniter\Model;

class RolePermissionModel extends Model {
    protected $table = 'role_permissions';
    protected $allowedFields = ['role', 'id_menu'];

    public function getPermissionsByRole($role) {
        return $this->select('id_menu')->where('role', $role)->findAll();
    }

    public function getMenusByRole($role) {
        return $this->join('menus', 'menus.id_menu = role_permissions.id_menu')
                    ->where('role', $role)
                    ->findAll();
    }

    public function getRolesWithMenus() {
        return $this->db->query("
            SELECT 
                r.role_name as role, 
                GROUP_CONCAT(m.nama_menu SEPARATOR ', ') as menu_diizinkan
            FROM (SELECT 'owner' as role_name UNION SELECT 'admin' UNION SELECT 'customer') r
            LEFT JOIN role_permissions rp ON r.role_name = rp.role
            LEFT JOIN menus m ON rp.id_menu = m.id_menu
            GROUP BY r.role_name
        ")->getResultArray();
    }

    public function getAllMenus() {
        return $this->db->table('menus')->get()->getResultArray();
    }

    public function updateRolePermissions($role, $menuIds) {
        $this->db->transStart();

        $this->where('role', $role)->delete();

        if (!empty($menuIds)) {
            foreach ($menuIds as $id_menu) {
                $this->insert([
                    'role'    => $role,
                    'id_menu' => $id_menu
                ]);
            }
        }

        $this->db->transComplete();

        return $this->db->transStatus();
    }
}