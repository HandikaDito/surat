<div class="card card-modern">

    <div class="card-body">

        <div class="d-flex justify-content-between align-items-start">

            <div>
                <div class="mb-2">
                    <span class="badge bg-{{ $color }} p-2">
                        <i class="bx {{ $icon }}"></i>
                    </span>
                </div>

                <small class="text-muted">{{ $label }}</small>
                <h4 class="mb-0">{{ $value }}</h4>
            </div>

            {{-- DROPDOWN --}}
            @if(!empty($route))
            <div class="dropdown">
                <button class="btn btn-sm" data-bs-toggle="dropdown">
                    <i class="bx bx-dots-vertical-rounded"></i>
                </button>

                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="{{ $route }}">
                        Lihat Detail
                    </a>
                </div>
            </div>
            @endif

        </div>

        {{-- PERCENTAGE --}}
        @if(isset($percentage))
            @if($percentage > 0)
                <small class="text-success">
                    ↑ {{ $percentage }}%
                </small>
            @elseif($percentage < 0)
                <small class="text-danger">
                    ↓ {{ $percentage }}%
                </small>
            @endif
        @endif

    </div>

</div>