<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDispositionRequest;
use App\Http\Requests\UpdateDispositionRequest;
use App\Models\Disposition;
use App\Models\Letter;
use App\Models\LetterStatus;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log; // ✅ FIX ERROR

class DispositionController extends Controller
{
    /**
     * Tampilkan daftar disposisi
     */
    public function index(Request $request, Letter $letter): View
    {
        return view('pages.transaction.disposition.index', [
            'data' => Disposition::render($letter, $request->search),
            'letter' => $letter,
            'search' => $request->search,
        ]);
    }

    /**
     * Form create disposisi
     */
    public function create(Letter $letter): View
    {
        return view('pages.transaction.disposition.create', [
            'letter' => $letter,
            'statuses' => LetterStatus::all(),
        ]);
    }

    /**
     * 🔥 STORE + KIRIM WA KE PIMPINAN
     */
    public function store(StoreDispositionRequest $request, Letter $letter): RedirectResponse
    {
        try {
            // simpan disposisi
            $disposition = Disposition::create([
                ...$request->validated(),
                'user_id' => auth()->id(),
                'letter_id' => $letter->id,
            ]);

            // ambil semua pimpinan
            $pimpinans = User::where('role', 'pimpinan')->get();

            foreach ($pimpinans as $p) {

                if (empty($p->phone)) continue;

                try {
                    $url = url('/transaction/' . $letter->id . '/disposition');

                    $message = "📢 *DISPOSISI BARU*\n\n"
                        . "📄 Surat ID: {$letter->id}\n"
                        . "👤 Dari: " . auth()->user()->name . "\n\n"
                        . "🔗 Buka:\n{$url}";

                    $this->sendWhatsapp($p->phone, $message);

                } catch (\Throwable $waError) {
                    // log jika WA gagal
                    Log::error('WA gagal: ' . $waError->getMessage());
                }
            }

            return redirect()
                ->route('transaction.disposition.index', $letter)
                ->with('success', 'Disposisi berhasil & notifikasi WA terkirim');

        } catch (\Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Form edit disposisi
     */
    public function edit(Letter $letter, Disposition $disposition): View
    {
        return view('pages.transaction.disposition.edit', [
            'data' => $disposition,
            'letter' => $letter,
            'statuses' => LetterStatus::all(),
        ]);
    }

    /**
     * Update disposisi
     */
    public function update(UpdateDispositionRequest $request, Letter $letter, Disposition $disposition): RedirectResponse
    {
        try {
            $disposition->update($request->validated());

            return back()->with('success', __('menu.general.success'));

        } catch (\Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Hapus disposisi
     */
    public function destroy(Letter $letter, Disposition $disposition): RedirectResponse
    {
        try {
            $disposition->delete();

            return back()->with('success', __('menu.general.success'));

        } catch (\Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    /**
     * 🔥 APPROVE OLEH PIMPINAN
     */
    public function approve(Disposition $disposition)
    {
        if (auth()->user()->role !== 'pimpinan') {
            abort(403);
        }

        $disposition->update([
            'is_approved' => true,
            'approved_by' => auth()->id(),
        ]);

        return back()->with('success', 'Disposisi disetujui pimpinan');
    }

    /**
     * 🔥 FUNCTION KIRIM WHATSAPP (FONNTE)
     */
    private function sendWhatsapp($number, $message)
    {
        $token = env('FONNTE_TOKEN'); // ✅ ambil dari .env

        Http::withHeaders([
            'Authorization' => $token
        ])->post('https://api.fonnte.com/send', [
            'target' => $number,
            'message' => $message,
        ]);
    }
}
