<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\DocumentDataTable;
use App\Helpers\GenerateStringNumberHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Document\DocumentStoreRequest;
use App\Http\Requests\Document\DocumentUpdateRequest;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function index(DocumentDataTable $dataTable)
    {
        return $dataTable->render('backend.document.index');
    }

    public function store(DocumentStoreRequest $request)
    {
        try {
            $document = new Document;
            $document->user_id = Auth::id();
            $document->name = $request->name;

            if ($request->file('document')) {
                $file = $request->file('document');
                $fileName = GenerateStringNumberHelper::generateTimeRandomString() . '_' . $file->getClientOriginalName();
                $file->move(public_path('document'), $fileName);
                $document->document = $fileName;
            }

            $document->save();

            return response()->json([
                'status'  => true,
                'message' => 'Document store successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function edit(Request $request)
    {
        try {
            $document = Document::find($request->id);
            return response()->json([
                'status'  => true,
                'data'    => $document,
                'message' => 'Document fetched successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(DocumentUpdateRequest $request, Document $document)
    {
        try {
            $document->user_id = Auth::id();
            $document->name = $request->name;

            if ($request->file('document')) {
                $file = $request->file('document');
                $fileName = GenerateStringNumberHelper::generateTimeRandomString() . '_' . $file->getClientOriginalName();
                $file->move(public_path('document'), $fileName);
                $document->document = $fileName;
            }

            $document->save();

            return response()->json([
                'status'  => true,
                'message' => 'Document update successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function delete(Document $document)
    {
        try {
            if ($document->delete()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Document deleted successfully'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => "Document not found!"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
