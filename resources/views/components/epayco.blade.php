@push('styles')


@endpush



<!-- div donde se imprimiran los errores (opcional) -->
<div class="card-errors"></div>
<!-- datos necesarios para tokenizar -->
<div class="form-row">
  <div class="form-group col-md-6">
    <small class="form-text text-muted">Nombre del usuario de tarjeta</small>
    <input type="text" class="form-control" data-epayco="card[name]">
  </div>
  <div class="form-group col-md-6">
    <small class="form-text text-muted">Email</small>
    <input type="text" class="form-control" data-epayco="card[email]">
  </div>
</div>

<div class="form-row">
  <div class="form-group col-md-6">
    <small class="form-text text-muted">Número de tarjeta de crédito</small>
    <input type="text" class="form-control" data-epayco="card[number]">
  </div>
  <div class="form-group col-md-2">
    <small class="form-text text-muted">CVC</small>
    <input type="text" class="form-control" size="4" data-epayco="card[cvc]">
  </div>
  <div class="form-group col-md-2">
    <small class="form-text text-muted">Mes(MM)</small>
    <input type="text" class="form-control" data-epayco="card[exp_month]">
  </div>
  <div class="form-group col-md-2">
    <small class="form-text text-muted">Año (AAAA)</small>
    <input type="text" class="form-control" data-epayco="card[exp_year]">
  </div>
  <input type="hidden" name="epaycoToken" id="epaycoToken">
</div>

<div class="form-group form-row">
  <div class="col">
    <small for="cardholderName" class="form-text text-muted" role="alert">El pago sera convertido a
      {{ strtoupper(config('services.epayco.base_currency'))}}</small>
  </div>
</div>

<div class="form-group form-row">
  <div class="col">
    <small class="form-text text-danger customer-errors" role="alert"></small>
  </div>
</div>

@push('scripts')

<script src="https://s3-us-west-2.amazonaws.com/epayco/v1.0/epayco.min.js"></script>

<script>
// Autenticación con tu public_key (Requerido)
ePayco.setPublicKey('{{ config("services.epayco.public_key") }}');

$('#paymentForm').submit(function(event) {
    //detiene el evento automático del formulario
    event.preventDefault();
    //captura el contenido del formulario
    var $form = $(this);

    //deshabilita el botón para no acumular peticiones
    $form.find("button").prop("disabled", true);
    //hace el llamado al servicio de tokenización
    ePayco.token.create($form, function(error, token) {
        //habilita el botón al obtener una respuesta
        $form.find("button").prop("disabled", false);
        if(!error) {
            //si la petición es correcta agrega un input "hidden" con el token como valor
            // $form.append($("<input type="hidden" name="epaycoToken">").val(token));
            $('#epaycoToken').val(token);
            //envia el formulario para que sea procesado
            $form.get(0).submit();
        } else {
            //muestra errores que hayan sucedido en la transacción
            $('.customer-errors').text(error.description);
        }
    });
});

</script>


@endpush