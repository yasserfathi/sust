<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use League\Csv\Reader;
use League\Csv\Statement;

class UserFileController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240'
        ]);

        $file = $request->file('file');

        $csv = Reader::createFromPath($file->getRealPath());
        $csv->setHeaderOffset(0);

        $stmt = (new Statement())
            ->offset(0) // Starts at the beginning
            ->limit(1000); // Limits to 1000 records per chunk

        $results = [];
        $totalRecords = count($csv);
        $chunkSize = 1000;

        for ($offset = 0; $offset < $totalRecords; $offset += $chunkSize) {
            $stmt = (new Statement())
                ->offset($offset)
                ->limit($chunkSize);

            $records = $stmt->process($csv);

            foreach ($records as $record) {
                // Process each record
                $results[] = $record;

                // Or insert to database in batches
                // Model::create($record);
            }
        }

        return response()->json([
            'message' => 'CSV processed successfully',
            'total_records' => count($results),
            'sample_data' => array_slice($results, 0, 5)
        ]);
    }
}
