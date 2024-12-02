<?php

namespace App\Http\Controllers;

use App\Models\Verdict;
use App\Services\FileService;
use App\Services\VerdictsExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class VerdictController extends Controller
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function export(Request $request)
    {
        // You can apply the same filters as the search query to fetch the currently displayed data
        $query = Verdict::query();

        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where('litigant', 'like', "%{$searchTerm}%")
                ->orWhere('defendant', 'like', "%{$searchTerm}%");
        }

        $verdicts = $query->orderBy('created_at', 'desc')->get(); // Get the data as per the current filters

        $filename = 'displayed_data_perkara_' . Carbon::now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new VerdictsExport($verdicts), $filename);
    }

    public function exportAll()
    {
        $verdicts = Verdict::orderBy('created_at', 'desc')->get();

        $filename = 'all_data_perkara_' . Carbon::now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new VerdictsExport($verdicts), $filename);
    }


    public function index(Request $request)
    {
        $search = $request->input('search');

        // Query the database based on the search term in multiple columns
        $verdicts = Verdict::when($search, function ($query, $search) {
            return $query->where(function ($query) use ($search) {
                $query->where('litigant', 'LIKE', "%{$search}%")
                    ->orWhere('defendant', 'LIKE', "%{$search}%");
            });
        })
        ->orderBy('created_at', 'desc') 
        ->paginate(10);

        // Return the paginated data to the view
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
