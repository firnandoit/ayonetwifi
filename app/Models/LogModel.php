<?php

namespace App\Models;

use CodeIgniter\Model;

class LogModel extends Model
{
    protected $table      = 'activity_logs';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'description', 'module', 'action', 'ip_address'];
    protected $useTimestamps = false; // Karena kita pakai DEFAULT CURRENT_TIMESTAMP

    public function getLogs()
    {
        return $this->select('activity_logs.*, users.name as user_name')
                    ->join('users', 'users.id = activity_logs.user_id')
                    ->orderBy('activity_logs.created_at', 'DESC')
                    ->findAll();
    }
}