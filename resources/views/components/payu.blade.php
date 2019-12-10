@push('styles')


@endpush

<div class="mt-3">
  <label>Ingrese la información del medio de pago</label>

  <div class="form-row">
    <div class="form-group col-md-5">
      <small class="form-text text-muted">Número de la tarjeta de crédito</small>
      <input type="text" class="form-control" size="30" payu-content="number" id="number">
    </div>
    <div class="col-md-1 mt-2" id="mylistID" style=""></div>
    <div class="col-md-2">
      <small class="form-text text-muted">CVC</small>
      <input type="text" class="form-control" size="8" payu-content="cvc">
    </div>
    <div class="col-md-2">
      <small class="form-text text-muted">Month (MM)</small>
      <input type="text" class="form-control" size="2" payu-content="exp_month">
    </div>
    <div class="col-md-2">
      <small class="form-text text-muted">Year (AAAA)</small>
      <input type="text" class="form-control" size="4" payu-content="exp_year">
      <span></span>
    </div>
    <div id="mylistID" style=""></div>
  </div>

  <div class="form-row">
    <div class="col-md-6">
      <small class="form-text text-muted">Documento</small>
      <input type="text" class="form-control" size="25" payu-content="document">
    </div>
    <div class="col-md-6">
      <small class="form-text text-muted">Nombre</small>
      <input type="text" class="form-control" size="30" payu-content="name_card">
    </div>
    <input payu-content="payer_id" value="MI PAYER ID" type="hidden">
    <input name="payu_token" id="payuToken" value="" type="hidden">
  </div>

  <div class="form-group form-row">
    <div class="col">
      <small for="cardholderName" class="form-text text-muted" role="alert">El pago sera convertido a
        {{ strtoupper(config('services.payu.base_currency'))}}</small>
    </div>
  </div>

  <div class="form-group form-row">
    <div class="col">
      <small class="form-text text-muted create-errors" role="alert"></small>
    </div>
  </div>

</div>


@push('scripts')

<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<script src="https://gateway.payulatam.com/ppp-web-gateway/javascript/PayU.js"></script>

<script>

  payU.setURL('https://sandbox.api.payulatam.com/payments-api/4.0/service.cgi');
  payU.setPublicKey("4Vj8eK4rloUd272L48hsrarnUA");
  payU.setAccountID("512321");
  payU.setListBoxID("mylistID");
  payU.setLanguage("es"); // optional
  // payU.getPaymentMethods();

  document.getElementById('number').addEventListener('keyup', function() {
    console.log(payU.validateCard(this.value));
    payU.setCardDetails();
  });


</script>

@endpush