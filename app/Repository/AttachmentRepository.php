<?php

namespace App\Repository;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use App\Repository\BaseRepository;
use Illuminate\Support\Facades\Response;
use League\Flysystem\FileExistsException;
use App\Models\Attachment;

class AttachmentRepository extends BaseRepository
{
    protected $basePath;

    public function __construct(Attachment $anexo)
    {
        parent::__construct($anexo);

        // Usa o driver default para armazenamento
        $driver = Storage::disk()->getDriver();
        $prefix = $driver->getAdapter()->getPathPrefix();

        $this->basePath = $prefix . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;
    }

    /**
     * Array com o nome dos diretorios baseados no hash passado
     * @param $hash
     * @return array
     */
    private function hashDirectories($hash)
    {
        return array(substr($hash, 0, 2), substr($hash, 2, 2));
    }

    /**
     * Trata uploads guardando o arquivo no servidor e registrando na
     * base de dados
     * @param UploadedFile $uploadedFile
     * @return \Illuminate\Http\RedirectResponse|static
     * @throws FileExistsException
     * @throws \Exception
     */
    public function uploadAttachment(UploadedFile $uploadedFile)
    {
        $hash = sha1_file($uploadedFile);
        list($firstDir, $secondDir) = $this->hashDirectories($hash);

        $caminhoArquivo = $this->basePath . $firstDir . DIRECTORY_SEPARATOR . $secondDir;


        try {
            $anexo = [
                'name' => $uploadedFile->getClientOriginalName(),
                'mime' => $uploadedFile->getClientMimeType(),
                'extension' => $uploadedFile->getClientOriginalExtension(),
                'location' => $hash
            ];

            if (!file_exists($caminhoArquivo . DIRECTORY_SEPARATOR . $hash)) {
                $uploadedFile->move($caminhoArquivo, $hash);
            }
            return $this->create($anexo);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }
        }
    }

    /**
     * @param $anexoId
     * @return null
     */
    public function downloadAttachment($anexoId)
    {
        $anexo = $this->find($anexoId);

        if (!$anexo) {
            $anexo = 'error_non_existent';
            return $anexo;
        }

        list($firstDir, $secondDir) = $this->hashDirectories($anexo->location);

        $caminhoArquivo = $this->basePath . $firstDir . DIRECTORY_SEPARATOR . $secondDir . DIRECTORY_SEPARATOR . $anexo->location;

        $headers = array('Content-Type: ' . $anexo->mime);

        return Response::download($caminhoArquivo, $anexo->name, $headers);
    }

    /**
     * Atualiza o registro de um anexo
     * @param $anexoId
     * @param UploadedFile $uploadedFile
     * @return \Illuminate\Http\RedirectResponse|string
     * @throws FileExistsException
     * @throws \Exception
     */
    public function atualizarAnexo($anexoId, UploadedFile $uploadedFile)
    {
        $anexo = $this->find($anexoId);

        if (!$anexo) {
            return array(
                'type' => 'error_non_existent',
                'message' => 'Arquivo não existe!'
            );
        }

        $hash = sha1_file($uploadedFile);
        list($firstDir, $secondDir) = $this->hashDirectories($hash);

        $caminhoArquivo = $this->basePath . $firstDir . DIRECTORY_SEPARATOR . $secondDir;

        if (file_exists($caminhoArquivo . DIRECTORY_SEPARATOR . $hash)) {
            if (config('app.debug')) {
                throw new FileExistsException($caminhoArquivo . DIRECTORY_SEPARATOR . $hash);
            }

            return array(
                'type' => 'error_exists',
                'message' => 'Arquivo enviado já existe'
            );
        }

        try {
            list($firstOldDir, $secondOldDir) = $this->hashDirectories($anexo->location);
            // Exclui antigo arquivo
            array_map('unlink', glob($this->basePath . $firstOldDir . DIRECTORY_SEPARATOR . $secondOldDir . DIRECTORY_SEPARATOR . $anexo->location));

            // Atualiza registro com o novo arquivo
            $data = [
                'name' => $uploadedFile->getClientOriginalName(),
                'mime' => $uploadedFile->getClientMimeType(),
                'extension' => $uploadedFile->getClientOriginalExtension(),
                'location' => $hash
            ];

            $uploadedFile->move($caminhoArquivo, $hash);
            return $this->update($data, $anexoId, 'id');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }
        }
    }

    /**
     * Deleta um anexo do servidor e seu registro no banco
     * @param $anexoId
     * @return int|string|array
     * @throws \Exception
     */
    public function deletarAnexo($anexoId)
    {
        $anexo = $this->find($anexoId);

        if (!$anexo) {
            return array(
                'type' => 'error_non_existent',
                'message' => 'Arquivo não existe!'
            );
        }

        try {
            list($firstOldDir, $secondOldDir) = $this->hashDirectories($anexo->location);
            // Exclui antigo arquivo
            array_map('unlink', glob($this->basePath . $firstOldDir . DIRECTORY_SEPARATOR . $secondOldDir . DIRECTORY_SEPARATOR . $anexo->location));
            return $this->delete($anexoId);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }
        }
    }
}
