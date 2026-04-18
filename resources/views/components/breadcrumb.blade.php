<div class="mb-3">

    @if(!empty($values))

    <div class="d-flex justify-content-between align-items-center flex-wrap">

        <h5 class="mb-2">

            @foreach($values as $value)

                @if($loop->last)
                    <span class="fw-bold">{{ $value }}</span>
                @else
                    <span class="text-muted">{{ $value }}</span>
                    <span class="mx-1 text-muted">/</span>
                @endif

            @endforeach

        </h5>

        <div>
            {{ $slot }}
        </div>

    </div>

    @endif

</div>