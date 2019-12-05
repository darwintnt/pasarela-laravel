@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Make a Payment</div>

                <div class="card-body">
                    <h2>Pasos adicionales</h2>
                </div>

            </div>

        </div>

    </div>
</div>

@push('scripts')

<script src="https://js.stripe.com/v3/"></script>

<script>
  // Create a Stripe client.
const stripe = Stripe('{{ config('services.stripe.public_key') }}');

stripe.handleCardAction("{{ $clientSecret }}")
.then(function(result){
    if(result.error) {
        window.location.replace("{{ route('cancelled') }}")
    } else {
        window.location.replace("{{ route('approval') }}")
    }
})

</script>

@endpush


@endsection
