<?php

namespace App\Http\Controllers;

use App\Models\Verdict;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerdictController extends Controller
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function index()
    {
        $verdicts = Verdict::all();
        return view('verdicts.index', compact('verdicts'));
    }

    public function create()
    {
        return view('verdicts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'litigant' => 'required|string',
            'defendant' => 'required|string',
            'case_number' => 'required|string',
            'case_type' => 'required|in:Gugatan,Permohonan',
            'sub_case_type' => 'required|string',
            'verdict_date' => 'required|date',
            'url_to_valid_verdict' => 'required|url',
            'verdict_copy_file' => 'required|file|mimes:pdf|max:10240',
        ]);

        $uuid = (string) \Illuminate\Support\Str::uuid();
        Log::info("Generated uuid: {$uuid}");
        $validated['id'] = $uuid;

        if ($request->hasFile('verdict_copy_file')) {
            $file = $request->file('verdict_copy_file');
            $originalFilePath = $this->fileService->upload($file, $uuid, 'verdicts');
            $qrFilePath = $this->fileService->generateQrCode($validated['url_to_valid_verdict'], $uuid);
            $stampedFilePath = $this->fileService->stampPdfWithQrCode($originalFilePath, $qrFilePath);

            $validated['file_verdict_path'] = $originalFilePath;
            $validated['qr_path'] = $qrFilePath;
            $validated['file_verdict_stamped_path'] = $stampedFilePath;
            unset($validated['verdict_copy_file']);
        }

        // dd($validated);

        Verdict::create($validated);

        return redirect()->route('verdicts.index')->with('success', 'Data perkara berhasil ditambahkan');
    }

    public function show(Verdict $verdict)
    {
        return view('verdicts.show', compact('verdict'));
    }

    public function edit(Verdict $verdict)
    {
        return view('verdicts.edit', compact('verdict'));
    }

    public function update(Request $request, Verdict $verdict)
    {
        $validated = $request->validate([
            'litigant' => 'required|string',
            'defendant' => 'required|string',
            'case_number' => 'required|string',
            'case_type' => 'required|in:Gugatan,Permohonan',
            'sub_case_type' => 'required|string',
            'verdict_date' => 'required|date',
            'url_to_valid_verdict' => 'required|url',
            'file_verdict_path' => 'nullable|string',
            'file_verdict_stamped_path' => 'nullable|string',
        ]);

        $verdict->update($validated);

        return redirect()->route('verdicts.index')->with('success', 'Verdict updated successfully.');
    }

    public function destroy(Verdict $verdict)
    {
        $verdict->delete();
        $this->fileService->delete($verdict->file_verdict_path, $verdict->file_verdict_stamped_path);
        return redirect()->route('verdicts.index')->with('success', 'Data Perkara berhasil dihapus');
    }
}
