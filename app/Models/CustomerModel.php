<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model
{
    protected $table            = 'customers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id', 'package_id', 'name', 'address', 'phone', 'installation_date', 'status'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Mengambil data pelanggan dengan join ke tabel packages
     * dan mendukung filter pencarian.
     */
    public function getDatatables($searchTerm = null)
    {
        // Gunakan $this->builder() agar otomatis menggunakan tabel yang didefinisikan di protected $table
        $builder = $this->builder();

        // Pilih kolom, sertakan 'speed' seperti di controller lama Anda
        $builder->select($this->table . '.*, packages.name as package_name, packages.speed');
        $builder->join('packages', 'packages.id = ' . $this->table . '.package_id', 'left');

        // Logika Pencarian
        if ($searchTerm) {
            $builder->groupStart()
                ->like($this->table . '.name', $searchTerm)
                ->orLike($this->table . '.phone', $searchTerm)
                ->orLike($this->table . '.address', $searchTerm)
                ->groupEnd();
        }

        // Gunakan updated_at agar data yang baru diubah muncul di paling atas (seperti controller lama)
        $builder->orderBy($this->table . '.updated_at', 'DESC');

        return $builder->get()->getResultArray();
    }
}
