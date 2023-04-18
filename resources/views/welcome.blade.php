<form id="checkout-form" action="https://checkout.stripe.com/c/pay/{{ $sessionId }}" method="POST">
  <input type="hidden" name="_token" value="{{ csrf_token() }}">
</form>

<script>
  document.getElementById('checkout-form').submit();
</script>