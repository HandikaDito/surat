<?php

namespace App\Http\Controllers;

use App\Enums\LetterType;
use App\Http\Requests\StoreLetterRequest;
use App\Http\Requests\UpdateLetterRequest;
use App\Models\Attachment;
use App\Models\Classification;
use App\Models\Config;
use App\Models\Letter;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class IncomingLetterController extends Controller
{
    public function index(Request $request): View
    {
        return view('pages.transaction.incoming.index', [
            'data' => Letter::incoming()->render($request->search),
            'search' => $request->search,
        ]);
    }

    public function agenda(Request $request): View
    {
        return view('pages.transaction.incoming.agenda', [
            'data' => Letter::incoming()
                ->agenda($request->since, $request->until, $request->filter)
                ->render($request->search),
            'search' => $request->search,
            'since' => $request->since,
            'until' => $request->until,
            'filter' => $request->filter,
            'query' => $request->getQueryString(),
        ]);
    }

    public function print(Request $request): View
    {
        $agenda = __('menu.agenda.menu');
        $letter = __('menu.agenda.incoming_letter');
        $title = App::getLocale() == 'id' ? "$agenda $letter" : "$letter $agenda";

        return view('pages.transaction.incoming.print', [
            'data' => Letter::incoming()
                ->agenda($request->since, $request->until, $request->filter)
                ->get(),
            'search' => $request->search,
            'since' => $request->since,
            'until' => $request->until,
            'filter' => $request->filter,
            'config' => Config::pluck('value', 'code')->toArray(),
            'title' => $title,
        ]);
    }

    public function create(): View
    {
        return view('pages.transaction.incoming.create', [
            'classifications' => Classification::all(),
        ]);
    }

    public function store(StoreLetterRequest $request): RedirectResponse
    {
        try {
            $user = auth()->user();

            if ($request->type != LetterType::INCOMING->type()) {
                throw new \Exception(__('menu.transaction.incoming_letter'));
            }

            $newLetter = $request->validated();
            $newLetter['user_id'] = $user->id;

            $letter = Letter::create($newLetter);

            // 🔥 FIX UPLOAD
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {

                    $extension = $file->getClientOriginalExtension();

                    if (!in_array($extension, ['png', 'jpg', 'jpeg', 'pdf'])) {
                        continue;
                    }

                    $filename = time() . '-' . $file->getClientOriginalName();
                    $filename = str_replace(' ', '-', $filename);

                    // 🔥 simpan + ambil path
                    $path = $file->storeAs('attachments', $filename, 'public');

                    Attachment::create([
                        'path' => $path, // WAJIB
                        'filename' => $filename,
                        'extension' => $extension,
                        'user_id' => $user->id,
                        'letter_id' => $letter->id,
                    ]);
                }
            }

            return redirect()
                ->route('transaction.incoming.index')
                ->with('success', __('menu.general.success'));

        } catch (\Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function show(Letter $incoming): View
    {
        return view('pages.transaction.incoming.show', [
            'data' => $incoming->load(['classification', 'user', 'attachments']),
        ]);
    }

    public function edit(Letter $incoming): View
    {
        return view('pages.transaction.incoming.edit', [
            'data' => $incoming,
            'classifications' => Classification::all(),
        ]);
    }

    public function update(UpdateLetterRequest $request, Letter $incoming): RedirectResponse
    {
        try {
            $incoming->update($request->validated());

            // 🔥 FIX UPLOAD (SAMA SEPERTI STORE)
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {

                    $extension = $file->getClientOriginalExtension();

                    if (!in_array($extension, ['png', 'jpg', 'jpeg', 'pdf'])) {
                        continue;
                    }

                    $filename = time() . '-' . $file->getClientOriginalName();
                    $filename = str_replace(' ', '-', $filename);

                    $path = $file->storeAs('attachments', $filename, 'public');

                    Attachment::create([
                        'path' => $path, // WAJIB
                        'filename' => $filename,
                        'extension' => $extension,
                        'user_id' => auth()->user()->id,
                        'letter_id' => $incoming->id,
                    ]);
                }
            }

            return back()->with('success', __('menu.general.success'));

        } catch (\Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function destroy(Letter $incoming): RedirectResponse
    {
        try {
            $incoming->delete();

            return redirect()
                ->route('transaction.incoming.index')
                ->with('success', __('menu.general.success'));

        } catch (\Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }
}
