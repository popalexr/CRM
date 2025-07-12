<?php

namespace App\Http\Controllers\FileUploader;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImageUploadRequest;
use App\Models\TemporaryFiles;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class ImageUploaderController extends Controller
{
    private int $chunkIndex;
    private int $totalChunks;

    private string $fileName;
    private string $filePath;

    private UploadedFile $file;

    public function __construct(private ImageUploadRequest $request)
    {
        $this->chunkIndex = (int) $request->input('dzchunkindex', 0);
        $this->totalChunks = (int) $request->input('dztotalchunkcount', 1);
        
        $this->file = $request->file('file');

        $this->fileName = md5($request->input('dzuuid', '')) . '.' . $this->file->getClientOriginalExtension();
        $this->filePath = storage_path('app/private/temp/' . $this->fileName);
    }

    public function __invoke()
    {
        if ($this->isSingleChunk()) {
            $this->addSingleChunk();

            return $this->generateLastChunkResponse();
        }

        if ($this->isFirstChunk()) {
            $this->addFirstChunk();
            
            return $this->generateGeneralResponse();
        }

        if ($this->isLastChunk()) {
            $this->addLastChunk();

            return $this->generateLastChunkResponse();
        }

        $this->addChunk();

        return $this->generateGeneralResponse();
    }

    /**
     * Check if the file has only one chunk.
     */
    private function isSingleChunk(): bool
    {
        return $this->totalChunks === 1;
    }

    /**
     * Check if the file is the first chunk.
     */
    private function isFirstChunk(): bool
    {
        return $this->chunkIndex === 0 && $this->totalChunks > 1;
    }

    /**
     * Check if the file is the last chunk.
     */
    private function isLastChunk(): bool
    {
        return $this->chunkIndex === $this->totalChunks - 1;
    }

    /**
     * Generate the response for the last chunk.
     */
    private function generateLastChunkResponse(): array
    {
        return [
            'status' => 'success',
            'file_id' => $this->getTemporaryFileId(),
        ];
    }

    /**
     * Generate general response.
     */
    private function generateGeneralResponse(): array
    {
        return [
            'status' => 'success',
        ];
    }

    /**
     * Add the first chunk to the file and in database.
     */
    private function addFirstChunk(): void
    {
        file_put_contents($this->filePath, $this->file->get(), FILE_IGNORE_NEW_LINES);

        TemporaryFiles::create([
            'file_name' => $this->fileName,
            'file_path' => 'app/private/temp/' . $this->fileName,
            'created_at' => now(),
        ]);
    }

    /**
     * Add the first and last chunk to the file and in database.
     */
    private function addSingleChunk(): void
    {
        file_put_contents($this->filePath, $this->file->get(), FILE_IGNORE_NEW_LINES);

        TemporaryFiles::create([
            'file_name' => $this->fileName,
            'file_path' => 'app/private/temp/' . $this->fileName,
            'created_at' => now(),
            'uploaded_at' => now(),
        ]);
    }

    /**
     * Append chunk to the file
     */
    private function addChunk(): void
    {
        file_put_contents($this->filePath, $this->file->get(), FILE_APPEND | FILE_IGNORE_NEW_LINES);
    }

    /**
     * Add the last chunk to the file and update the database.
     */
    private function addLastChunk(): void
    {
        file_put_contents($this->filePath, $this->file->get(), FILE_APPEND | FILE_IGNORE_NEW_LINES);

        TemporaryFiles::where('file_name', $this->fileName)
            ->update(['uploaded_at' => now()]);
    }

    /**
     * Get the temporary file id from database.
     */
    private function getTemporaryFileId(): int
    {
        $temporaryFile = TemporaryFiles::where('file_name', $this->fileName)->first();

        if (!$temporaryFile) {
            return -1;
        }

        return $temporaryFile->id;
    }
}
