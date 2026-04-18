<div class="card mb-3">

    <div class="card-body">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between">

            <div>
                <h6 class="mb-1">
                    {{ $disposition->surat->perihal ?? '-' }}
                </h6>

                <small class="text-muted">
                    {{ $disposition->fromUser->name ?? 'System' }}
                    →
                    {{ $disposition->toUser->name ?? '-' }}
                </small>
            </div>

            {{-- STATUS --}}
            <span class="badge bg-label-{{ $disposition->status_color }}">
                {{ $disposition->status_label }}
            </span>

        </div>

        <hr>

        {{-- ISI --}}
        <p class="mb-2">
            {{ $disposition->catatan ?? '-' }}
        </p>

        {{-- DEADLINE --}}
        @if($disposition->deadline)
            <small class="text-danger">
                Deadline: {{ $disposition->deadline }}
            </small>
        @endif

        {{-- ACTION --}}
        @if(auth()->id() == $disposition->to_user_id)

            {{-- 🔽 FORWARD (LEVEL < 5) --}}
            @if(auth()->user()->role_level < 5)

                <form action="{{ route('disposition.forward', $disposition->id) }}" method="POST" class="mt-2">
                    @csrf

                    <select name="to_user_id" class="form-control mb-2" required>
                        @foreach($users as $u)
                            @if($u->role_level == auth()->user()->role_level + 1)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endif
                        @endforeach
                    </select>

                    <input type="text"
                           name="catatan"
                           class="form-control mb-2"
                           placeholder="Instruksi"
                           required>

                    <button class="btn btn-warning btn-sm w-100">
                        Teruskan
                    </button>

                </form>

            @endif


            {{-- 🔼 DONE (LEVEL 5) --}}
            @if(auth()->user()->role_level == 5)

                <form action="{{ route('disposition.done', $disposition->id) }}"
                      method="POST"
                      enctype="multipart/form-data"
                      class="mt-2">
                    @csrf

                    <textarea name="catatan"
                              class="form-control mb-2"
                              placeholder="Laporan hasil"
                              required></textarea>

                    <input type="file"
                           name="file_laporan"
                           class="form-control mb-2">

                    <button class="btn btn-success btn-sm w-100">
                        Selesai
                    </button>

                </form>

            @endif

        @endif

        {{-- TRACKING --}}
        <a href="{{ route('disposition.tracking', $disposition->id) }}"
           class="btn btn-info btn-sm w-100 mt-2">
            Lihat Tracking
        </a>

    </div>

</div>