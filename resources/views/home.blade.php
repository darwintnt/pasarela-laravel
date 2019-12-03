@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Make a Payment</div>

                <div class="card-body">
                    <form action="{{ route('pay') }}" method="POST" id="paymentForm">
                        @csrf
                        <div class="row">
                            <div class="col-auto">
                                <label for="">Cuanto deseas pagar</label>
                                <input type="number" class="form-control" name="value" min="5" step="0.01"
                                    value="{{ mt_rand(500, 100000) / 100 }}">
                                <small class="form-text text-muted">
                                    Usar valores decimales usando "."
                                </small>
                            </div>
                            <div class="col-auto">
                                <label for="">Moneda</label>
                                <select name="currency" id="" class="custom-select" required>
                                    @foreach ($currencies as $currency)
                                    <option value="{{ $currency->iso }}">{{ strtoupper($currency->iso) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="">Seleccione una pasarela de pago</label>
                                <div class="form-group" id="toggler">
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        @foreach ($platforms as $platform)
                                        <label class="btn btn-outline-secondary rounded m-2 p-1"
                                            data-target="#{{ $platform->name }}" data-toggle="collapse">
                                            <input type="radio" name="payment_platform" value="{{ $platform->id }}"
                                                required>
                                            <img class="img-thumbnail" src="{{ asset($platform->image) }}">
                                        </label>
                                        @endforeach
                                    </div>

                                    @foreach ($platforms as $platform)
                                    <div id="{{ $platform->name }}" class="collapse" data-parent="#toggler">
                                        @includeIf('components.'. $platform->name)
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-auto">
                                <button type="submit" id="payButton" class="buttonbtn btn-primary btn-lg">Pay</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection