<?php

declare(strict_types=1);

namespace App\Http\Actions\Web\Uploads;

use App\Entities\Locale;
use App\Entities\ProjectReference;
use App\Repositories\UploadRepository;
use Illuminate\Http\Request;

final class PushUpload
{
    public function __construct (
        private UploadRepository $uploadRepository
    ) {}

    public function __invoke(ProjectReference $project, Locale $locale, Request $request)
    {
        $uploads = $request->file('uploads');
        foreach ($uploads as $upload) {
            $this->uploadRepository->upload($upload);
        }
        return redirect()->refresh();
    }
}
