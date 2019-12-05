@push('styles')

<style type="text/css">

</style>

@endpush

<div class="mt-3">
  <label>Ingrese la información del medio de pago</label>

  {{-- <fieldset>
        <ul>
            <li>
            </li>
            <li>
            </li>
            <li>
            </li>
            <li>
            </li>
            <li>
            </li>
            <li>
            </li>
            <li>
            </li>
            <li>
            </li>
            <li>
               <label for="installments">Installments:</label>
               <select id="installments" class="form-control" name="installments"></select>
          </li>
        </ul>
        <input type="hidden" name="amount" id="amount"/>
        <input type="hidden" name="paymentMethodId" />
    </fieldset> --}}

  <div class="form-row">
    <div class="form-group col-md-6">
      <small for="cardNumber" class="form-text text-muted">Credit card number:</small>
      <input type="text" class="form-control" id="cardNumber" data-checkout="cardNumber" placeholder="Number Card"
        onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false"
        onDrag="return false" onDrop="return false" autocomplete=off />
    </div>
    <div class="form-group col-md-2">
      <small for="securityCode" class="form-text text-muted">Security code:</small>
      <input type="text" class="form-control" id="securityCode" data-checkout="securityCode" placeholder="CVC"
        onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false"
        onDrag="return false" onDrop="return false" autocomplete=off />
    </div>
    <div class="form-group col-md-2">
      <small for="cardExpirationMonth" class="form-text text-muted">Expiration month:</small>
      <input type="text" class="form-control" id="cardExpirationMonth" data-checkout="cardExpirationMonth"
        placeholder="Month" onselectstart="return false" onpaste="return false" onCopy="return false"
        onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off />
    </div>
    <div class="form-group col-md-2" class="form-text text-muted">
      <small for="cardExpirationYear" class="form-text text-muted">Expiration year:</small>
      <input type="text" class="form-control" id="cardExpirationYear" data-checkout="cardExpirationYear"
        placeholder="Year" onselectstart="return false" onpaste="return false" onCopy="return false"
        onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off />
    </div>
  </div>

  <div class="form-row">
    <div class="form-group col-md-6">
      <small for="cardholderName" class="form-text text-muted">Card holder name:</small>
      <input type="text" class="form-control" id="cardholderName" data-checkout="cardholderName"
        placeholder="your name" />
    </div>
    <div class="form-group col-md-6">
      <small for="email" class="form-text text-muted">Email</small>
      <input type="email" class="form-control" id="email" name="email" placeholder="your email" />
    </div>
    <div class="form-group col-md-6">
      <small for="docType" class="form-text text-muted">Document type:</small>
      <select id="docType" class="form-control" data-checkout="docType"></select>
    </div>
    <div class="form-group col-md-6">
      <small for="docNumber" class="form-text text-muted">Document number:</small>
      <input type="text" class="form-control" id="docNumber" data-checkout="docNumber" placeholder="12345678" />
    </div>
    <input type="hidden" name="paymentMethodId" />
  </div>

  <div class="form-group form-row">
    <div class="col">
      <small for="cardholderName" class="form-text text-muted" role="alert">El pago sera convertido a {{ strtoupper(config('services.mercadopago.base_currency'))}}</small>
    </div>
  </div>

  <div class="form-group form-row">
      <div class="col">
        <small id="paymentErrors" class="form-text text-danger" role="alert"></small>
      </div>
  </div>


</div>


@push('scripts')

<script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>

<script>
  const mercadoPagoForm = document.getElementById('paymentForm');
  const $mercadoPago = window.Mercadopago
  const $mercadoPagoKey = $mercadoPago.setPublishableKey("{{ config('services.mercadopago.public_key') }}");
  const types = $mercadoPago.getIdentificationTypes();
  const cardnumber = document.getElementById("cardNumber");
  let paymentErrors = document.getElementById("paymentErrors");

  mercadoPagoForm.addEventListener('submit', (e)=> {
    if(mercadoPagoForm.elements.payment_platform.value === "{{ $platform->id }}") {
      e.preventDefault();
      $mercadoPago.createToken(mercadoPagoForm, sdkResponseHandler);
    }
  })

  function sdkResponseHandler(status, response) {
    if (status != 200 && status != 201) {
      paymentErrors.textContent = response.cause[0].description;
    }else{
        var card = document.createElement('input');
        card.setAttribute('name', 'token');
        card.setAttribute('type', 'hidden');
        card.setAttribute('value', response.id);
        mercadoPagoForm.appendChild(card);
        doSubmit=true;
        mercadoPagoForm.submit();
    }
  };

  document.querySelector('#cardNumber').addEventListener('keyup', guessingPaymentMethod);
  document.querySelector('#cardNumber').addEventListener('change', guessingPaymentMethod);


  function guessingPaymentMethod(event) {

      if (event.type == "keyup") {
          $mercadoPago.getPaymentMethod({
              "bin": cardnumber.value.substring(0, 6)
          }, setPaymentMethodInfo);

      } else {
          setTimeout(function () {
              $mercadoPago.getPaymentMethod({
                  "bin": cardnumber.value.substring(0, 6)
              }, setPaymentMethodInfo);
          }, 100);
      }
  };

  function setPaymentMethodInfo(status, response) {
      if (status == 200) {
        paymentErrors.innerText = '';
          const paymentMethodElement = document.querySelector('input[name=paymentMethodId]');

          if (paymentMethodElement) {
              paymentMethodElement.value = response[0].id;
          } else {
              const input = document.createElement('input');
              input.setAttribute('name', 'paymentMethodId');
              input.setAttribute('type', 'hidden');
              input.setAttribute('value', response[0].id);
              form.appendChild(input);
          }
      } else {
          paymentErrors.textContent = `payment method info error: ${response.error}`;
      }
  };

</script>

@endpush