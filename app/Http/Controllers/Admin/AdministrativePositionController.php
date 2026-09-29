<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdministrativePosition;
use App\Http\Requests\AdministrativePositionRequest;
use Illuminate\Http\Request;

class AdministrativePositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->has('itemsPerPage') ? $request->get('itemsPerPage') : 10;
        $search = $request->get('search');

        $query = AdministrativePosition::query();

        if ($search) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('title_en', 'like', "%{$search}%");
        }

        $items = $query->orderByDesc('id')->paginate($limit == -1 ? 1000 : $limit);

        return response()->json([
            'result' => $items,
        ]);
    }

    public function list()
    {
        $items = AdministrativePosition::where('active', 1)
            ->select('id', 'title', 'title_en')
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'administrative_positions' => $items,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdministrativePositionRequest $request)
    {
        $item = AdministrativePosition::create($request->validated());

        return response()->json([
            'message' => 'تم الحفظ بنجاح',
            'data' => $item
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = AdministrativePosition::findOrFail($id);
        return response()->json($item);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdministrativePositionRequest $request, string $id)
    {
        $item = AdministrativePosition::findOrFail($id);
        $item->update($request->validated());

        return response()->json([
            'message' => 'تم التعديل بنجاح',
            'data' => $item
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = AdministrativePosition::findOrFail($id);
        $item->delete();

        return response()->json([
            'message' => 'تم الحذف بنجاح'
        ]);
    }
}
