<form action="{{ route('make.payment') }}" method="POST">>
    @csrf
    <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
        data-key="pk_test_51Mid6SFJT6jSrbF0PIDM7DBdW4PrTKzBj8CLZ8YZJfQVE8VXlYYY2jnW7x6Sg4m4g1ZZdbT1pHFhKAEMKcZIOZe600ZDRzrGGH"
        data-name="Custom t-shirt" data-description="Your custom designed t-shirt" data-amount="500" data-currency="usd">
    </script>
</form>

<form action="/stripe/create-customer" method="POST">
    @csrf
    <input type="text" name="name" placeholder="Name">
    <input type="email" name="email" placeholder="Email">
    <input type="text" name="card_number" placeholder="Card Number">
    <input type="text" name="exp_month" placeholder="Exp Month">
    <input type="text" name="exp_year" placeholder="Exp Year">
    <input type="text" name="cvc" placeholder="CVC">
    <button type="submit">Submit</button>
</form>

