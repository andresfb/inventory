<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait ApiMetadata
{
    public function with(Request $request): array
    {
        return $this->getApiInfo();
    }

    public function getApiInfo(): array
    {
        return [
            'version' => "1.0.0",
            'author' => config('app.author_name'),
            'author_email' => config('app.author_email'),
            'copyright' => sprintf("©%s %s. All Rights Reserved", date('Y'), config('app.author_name'))
        ];
    }
}
