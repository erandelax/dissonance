<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Project;
use App\Models\Upload;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

final class UploadRepository
{
    public function upload(UploadedFile $file, User|null $user = null, Project|null $project = null, string|null $disk = null): Upload
    {
        if (null === $user) {
            $user = Auth::user();
        }
        if (null === $project) {
            $project = request()->input('project')?->getModel();
        }
        if (null === $disk) {
            $disk = 'public';
        }
        $dir = 'u/' . md5($project?->getKey() ?? '') .'/'. md5(date('Y-m-W'));
        $path = Storage::disk($disk)
            ->putFileAs($dir, $file, Uuid::uuid4()->toString() . '.' . $file->clientExtension());
        $data = [
            'user_id' => $user?->getKey(),
            'project_id' => $project?->getKey(),
            'disk' => $disk,
            'mime' => $file->getClientMimeType(),
            'name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'path' => $path,
        ];
        return Upload::create($data);
    }
}
