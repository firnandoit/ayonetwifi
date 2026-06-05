<?php

namespace App\Controllers;

use App\Models\BillModel;
use CodeIgniter\API\ResponseTrait;
use Dompdf\Dompdf;
use Dompdf\Options;

class LaporanController extends BaseController
{
    use ResponseTrait;

    protected $billModel;

    public function __construct()
    {
        $this->billModel = new BillModel();
    }

    public function pemasukan()
    {
        $tglMulai   = $this->request->getGet('tgl_mulai') ?? date('Y-m-01');
        $tglSelesai = $this->request->getGet('tgl_selesai') ?? date('Y-m-d');
        
        $adminId = session()->get('role') !== 'owner' ? session()->get('id') : null;
        $laporan = $this->billModel->getLaporanPemasukan($tglMulai, $tglSelesai, $adminId);

        $totalPemasukan = array_sum(array_column($laporan, 'paid_amount'));

        return view('laporan/pemasukan', [
            'title'          => 'Laporan Pemasukan',
            'tglMulai'       => $tglMulai,
            'tglSelesai'     => $tglSelesai,
            'laporan'        => $laporan,
            'totalPemasukan' => $totalPemasukan
        ]);
    }

    public function cetakPemasukan()
    {
        $tglMulai   = $this->request->getGet('tgl_mulai') ?? date('Y-m-01');
        $tglSelesai = $this->request->getGet('tgl_selesai') ?? date('Y-m-d');
        
        $adminId = session()->get('role') !== 'owner' ? session()->get('id') : null;
        $laporan = $this->billModel->getLaporanPemasukan($tglMulai, $tglSelesai, $adminId);
        $totalPemasukan = array_sum(array_column($laporan, 'paid_amount'));

        $html = view('laporan/pdf_pemasukan', [
            'tglMulai'       => $tglMulai,
            'tglSelesai'     => $tglSelesai,
            'laporan'        => $laporan,
            'totalPemasukan' => $totalPemasukan,
            'adminName'      => session()->get('name')
        ]);

        $this->generatePdf($html, 'Laporan_Pemasukan_' . $tglMulai . '_' . $tglSelesai);
    }

    public function tunggakan()
    {
        $bulan = $this->request->getGet('bulan') ?? date('n');
        $tahun = $this->request->getGet('tahun') ?? date('Y');

        $laporan = $this->billModel->getLaporanTunggakan($bulan, $tahun);
        
        // Sisa tagihan = amount - paid_amount
        $totalTunggakan = 0;
        foreach ($laporan as $row) {
            $totalTunggakan += ($row['amount'] - $row['paid_amount']);
        }

        return view('laporan/tunggakan', [
            'title'          => 'Laporan Tunggakan',
            'bulan'          => $bulan,
            'tahun'          => $tahun,
            'laporan'        => $laporan,
            'totalTunggakan' => $totalTunggakan
        ]);
    }

    public function cetakTunggakan()
    {
        $bulan = $this->request->getGet('bulan') ?? date('n');
        $tahun = $this->request->getGet('tahun') ?? date('Y');

        $laporan = $this->billModel->getLaporanTunggakan($bulan, $tahun);
        
        $totalTunggakan = 0;
        foreach ($laporan as $row) {
            $totalTunggakan += ($row['amount'] - $row['paid_amount']);
        }

        $html = view('laporan/pdf_tunggakan', [
            'bulan'          => $bulan,
            'tahun'          => $tahun,
            'laporan'        => $laporan,
            'totalTunggakan' => $totalTunggakan
        ]);

        $this->generatePdf($html, 'Laporan_Tunggakan_' . $bulan . '_' . $tahun);
    }

    private function generatePdf($html, $filename)
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        $dompdf->stream($filename . ".pdf", ["Attachment" => true]);
    }
}
