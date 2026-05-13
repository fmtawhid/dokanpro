<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <!-- resources/views/payment.blade.php -->
    <form action="/payment" method="POST">
        @csrf
        <label for="amount">Amount:</label>
        <input type="number" id="amount" name="amount" >
        <label for="currency">Currency:</label>
        <input type="text" id="currency" name="currency" >
        <label for="description">Description:</label>
        <input type="text" id="description" name="description" >
        <button type="submit">Pay</button>
    </form>

</body>
</html>
