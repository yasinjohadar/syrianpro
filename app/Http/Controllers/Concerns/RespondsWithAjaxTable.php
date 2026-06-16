<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait RespondsWithAjaxTable
{
    /**
     * @param  array<string, mixed>  $data
     */
    protected function ajaxTableResponse(
        Request $request,
        array $data,
        string $listView,
        ?string $modalsView = null,
        string $filteredKey = 'filtered'
    ): ?JsonResponse {
        if (! $request->ajax()) {
            return null;
        }

        $payload = [
            'success' => true,
            'html' => view($listView, $data)->render(),
            'filtered' => data_get($data, 'stats.'.$filteredKey) ?? data_get($data, $filteredKey),
        ];

        if ($modalsView) {
            $payload['modals'] = view($modalsView, $data)->render();
        }

        return response()->json($payload);
    }
}
