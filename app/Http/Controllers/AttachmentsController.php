<?php

namespace App\Http\Controllers;

use App\Repository\AttachmentRepository;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use DB;

class AttachmentsController extends Controller
{

    protected $attachmentRepository;

    public function __construct(AttachmentRepository $attachment)
    {
        $this->attachmentRepository = $attachment;

    }

    /**
     * Recupera um arquivo
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        return $this->attachmentRepository->downloadAttachment($id);
    }

}
