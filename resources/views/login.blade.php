<div class="row">
    <div class="col-6 offset-3">
        <x-form-card method="post" action="/login">
            @if ($errors->any() && $retries > 0)
            <x-alert type="warning">
                    Remaining {{ $retries }} attempt.
            </x-alert>
            @endif

            @if ($retries <= 0)
            <x-alert type="danger">
                Please try again after {{ $seconds }} seconds.
            </x-alert>
            @endif
