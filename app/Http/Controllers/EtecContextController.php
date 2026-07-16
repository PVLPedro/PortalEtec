<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EtecContextController extends Controller
{
    public function switch(Request $request): JsonResponse
    {
        $validated = $request->validate(['etec_id' => 'required|exists:etecs,id']);

        abort_unless($request->user()->etecs->contains('id', $validated['etec_id']), 403);
        session(['etec_ativa' => $validated['etec_id']]);

        return response()->json(['success' => true]);
    }
}
