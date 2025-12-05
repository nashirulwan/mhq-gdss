<?php

namespace App\Http\Controllers;

use App\Services\ExportService;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    protected $exportService;

    public function __construct()
    {
        $this->exportService = new ExportService();
    }

    /**
     * Export hasil SMART ke CSV
     */
    public function exportSMARTToCSV()
    {
        return $this->exportService->exportSMARTToCSV();
    }

    /**
     * Export hasil Borda ke CSV
     */
    public function exportBordaToCSV()
    {
        return $this->exportService->exportBordaToCSV();
    }

    /**
     * Export hasil gabungan ke CSV
     */
    public function exportCombinedToCSV()
    {
        return $this->exportService->exportCombinedToCSV();
    }

    /**
     * Export matrix SMART ke CSV
     */
    public function exportMatrixSMARTToCSV()
    {
        return $this->exportService->exportMatrixSMARTToCSV();
    }

    /**
     * Export voting detail Borda ke CSV
     */
    public function exportVotingBordaToCSV()
    {
        return $this->exportService->exportVotingBordaToCSV();
    }

    /**
     * Export PDF (placeholder)
     */
    public function exportPDF($type)
    {
        return $this->exportService->generatePDFPlaceholder($type);
    }

    /**
     * Export juri statistics ke CSV
     */
    public function exportJuriStatisticsToCSV()
    {
        $juriId = auth()->user()->juri->id;
        return $this->exportService->exportJuriStatisticsToCSV($juriId);
    }

    /**
     * Export Excel (placeholder)
     */
    public function exportExcel($type)
    {
        return $this->exportService->generateExcelPlaceholder($type);
    }
}