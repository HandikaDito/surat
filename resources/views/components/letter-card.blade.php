<div class="card mb-4 shadow-sm">
    <div class="card-header pb-0">
        <div class="d-flex justify-content-between flex-column flex-sm-row">

            <!-- LEFT -->
            <div class="card-title">
                <h5 class="text-nowrap mb-0 fw-bold">
                    {{ $letter->reference_number }}
                </h5>

                <small class="text-black">
                    {{ $letter->type == 'incoming' ? $letter->from : $letter->to }} |
                    <span class="text-secondary">
                        {{ __('model.letter.agenda_number') }}:
                    </span>
                    {{ $letter->agenda_number }} |
                    {{ $letter->classification?->type }}
                </small>
            </div>

            <!-- RIGHT -->
            <div class="card-title d-flex flex-row align-items-center">

                <div class="mx-2 text-end text-black">
                    <small class="d-block text-secondary">
                        {{ __('model.letter.letter_date') }}
                    </small>
                    {{ $letter->formatted_letter_date }}
                </div>

                @if($letter->type == 'incoming')
                    <div class="mx-3">
                        <a href="{{ route('transaction.disposition.index', $letter) }}"
                           class="btn btn-primary btn-sm">
                            {{ __('model.letter.dispose') }}
                            <span>({{ $letter->dispositions->count() }})</span>
                        </a>
                    </div>
                @endif

                <!-- DROPDOWN -->
                <div class="dropdown d-inline-block">
                    <button class="btn p-0"
                            type="button"
                            id="dropdown-{{ $letter->type }}-{{ $letter->id }}"
                            data-bs-toggle="dropdown">

                        <i class="bx bx-dots-vertical-rounded"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-end">

                        @if($letter->type == 'incoming')
                            <a class="dropdown-item"
                               href="{{ route('transaction.incoming.show', $letter) }}">
                                {{ __('menu.general.view') }}
                            </a>

                            <a class="dropdown-item"
                               href="{{ route('transaction.incoming.edit', $letter) }}">
                                {{ __('menu.general.edit') }}
                            </a>

                            <form action="{{ route('transaction.incoming.destroy', $letter) }}"
                                  method="post">
                                @csrf
                                @method('DELETE')
                                <span class="dropdown-item cursor-pointer btn-delete">
                                    {{ __('menu.general.delete') }}
                                </span>
                            </form>

                        @else
                            <a class="dropdown-item"
                               href="{{ route('transaction.outgoing.show', $letter) }}">
                                {{ __('menu.general.view') }}
                            </a>

                            <a class="dropdown-item"
                               href="{{ route('transaction.outgoing.edit', $letter) }}">
                                {{ __('menu.general.edit') }}
                            </a>

                            <form action="{{ route('transaction.outgoing.destroy', $letter) }}"
                                  method="post">
                                @csrf
                                @method('DELETE')
                                <span class="dropdown-item cursor-pointer btn-delete">
                                    {{ __('menu.general.delete') }}
                                </span>
                            </form>
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- BODY -->
    <div class="card-body">
        <hr>

        <p class="mb-2">{{ $letter->description }}</p>

        <div class="d-flex justify-content-between flex-column flex-sm-row align-items-center">

            <!-- NOTE -->
            <small class="text-secondary mb-2 mb-sm-0">
                {{ $letter->note }}
            </small>

            <!-- ATTACHMENT -->
            @if(count($letter->attachments))
                <div class="d-flex gap-2">

                    @foreach($letter->attachments as $attachment)

                        <a href="{{ asset('storage/' . $attachment->path) }}"
                           target="_blank"
                           download
                           class="text-decoration-none">

                            @if($attachment->extension == 'pdf')
                                <i class="bx bxs-file-pdf display-6 text-danger"></i>

                            @elseif(in_array($attachment->extension, ['jpg', 'jpeg']))
                                <i class="bx bxs-file-jpg display-6 text-primary"></i>

                            @elseif($attachment->extension == 'png')
                                <i class="bx bxs-file-png display-6 text-info"></i>
                            @endif

                        </a>

                    @endforeach

                </div>
            @endif

        </div>

        {{ $slot }}
    </div>
</div>
