<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="card card-flush">
    <div class="card-header pt-7">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold text-gray-800">Riwayat Aktivitas Sistem</span>
            <span class="text-gray-400 mt-1 fw-semibold fs-6">Menampilkan jejak aksi semua admin</span>
        </h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="table_logs">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th>Waktu</th>
                        <th>User</th>
                        <th>Modul</th>
                        <th>Aksi</th>
                        <th>Keterangan</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    <?php foreach($logs as $l): ?>
                    <tr>
                        <td><?= date('d M Y, H:i', strtotime($l['created_at'])) ?></td>
                        <td>
                            <span class="badge badge-light-primary"><?= $l['user_name'] ?></span>
                        </td>
                        <td><?= $l['module'] ?></td>
                        <td>
                            <?php 
                            $color = 'primary';
                            if($l['action'] == 'Rollback' || $l['action'] == 'Delete') $color = 'danger';
                            if($l['action'] == 'Create') $color = 'success';
                            ?>
                            <span class="badge badge-light-<?= $color ?>"><?= $l['action'] ?></span>
                        </td>
                        <td><?= $l['description'] ?></td>
                        <td class="text-muted fs-7"><?= $l['ip_address'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    $(document).ready(function() {
     $('#table_logs').DataTable({
            // "order" menentukan urutan default saat halaman dibuka
            // [0, "desc"] artinya kolom pertama (Waktu), diurutkan dari yang terbaru (desc)
            "order": [[0, "desc"]],
            
            // Opsional: Jika ingin mengganti teks pencarian dan bahasa ke Indonesia
            "language": {
                "search": "Cari Log:",
                "lengthMenu": "Tampilkan _MENU_ data",
                "zeroRecords": "Tidak ada log aktivitas ditemukan",
                "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Data tidak tersedia",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Berikutnya",
                    "previous": "Sebelumnya"
                }
            }
        });
    });
<?= $this->endSection() ?>