<?php

namespace App\Jobs;

use App\Jobs\Job;
use Archive7z\Archive7z;
use Exception;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Support\Facades\Log;

class ProcessReniecSignedFiles extends Job implements SelfHandling
{
  protected $fileName;

  /**
   * @param string $zipPath Ruta completa al .7z devuelto por RENIEC
   *  
   * @return void
   */
  public function __construct($fileName)
  {
    $this->fileName = $fileName;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    try {
      Log::info("******* ProcessReniecSignedFiles - handle *******");
      // Directorios
      $tmpDir = storage_path('app/tmp/zip/firmados/');
      $zipDir = public_path('tmp/zip/firmados/');
      $pdfDir = public_path('tmp/pdf/firmados/');

      foreach ([$zipDir, $pdfDir] as $dir) {
        if (!is_dir($dir)) mkdir($dir, 0775, true);
      }

      $tmpPath = $tmpDir . $this->fileName;
      $finalZipPath = $zipDir . $this->fileName;

      // Verificar archivo
      if (!file_exists($tmpPath)) {
        Log::error("Archivo no encontrado en tmp: {$tmpPath}");
        return;
      }

      // Mover archivo a public
      if (!rename($tmpPath, $finalZipPath)) {
        Log::error("No se pudo mover archivo a {$finalZipPath}");
        return;
      }

      // Descomprimir y renombrar
      $archive = new Archive7z($finalZipPath);

      foreach ($archive->getEntries() as $entry) {
        $newName = 'cf_' . str_replace('[R]', '', $entry->getPath());
        $archive->renameEntry($entry->getPath(), $newName);
      }

      $archive->setOutputDirectory($pdfDir)->extract();

      Log::info("Archivo procesado correctamente: {$this->fileName}");

    } catch (Exception $e) {
      Log::error("Error procesando {$this->fileName}: " . $e->getMessage());
      throw $e;
    }
  }
}
