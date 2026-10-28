<?php
function getExchangeRate($fromCurrency, $toCurrency) {
    $apiKey = '528f5571d2425e98a8654510'; 
    $url = "https://v6.exchangerate-api.com/v6/$apiKey/latest/$fromCurrency"; 

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);

    if (isset($data['conversion_rates'][$toCurrency])) {
        return $data['conversion_rates'][$toCurrency];
    } else {
        return null; 
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $amount = $_POST['amount'];
    $fromCurrency = $_POST['currency-from'];
    $toCurrency = $_POST['currency-to'];

    if (is_numeric($amount) && $amount > 0) {
        $exchangeRate = getExchangeRate($fromCurrency, $toCurrency);

        if ($exchangeRate !== null) {
            $convertedAmount = $amount * $exchangeRate;
            $result = number_format($convertedAmount, 2);
        } else {
            $result = "Error: Could not fetch exchange rates.";
        }
    } else {
        $result = "Please enter a valid amount.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Currency Converter</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="converter-container">
        <h2>Currency Converter</h2>
        <form action="convert.php" method="POST">
            <label for="amount">Amount</label>
            <input type="number" id="amount" name="amount" placeholder="Enter amount" required>

            <label for="currency-from">From</label>
            <select id="currency-from" name="currency-from">
                <option value="GBP">Pounds Sterling (GBP)</option>
                <option value="USD">American Dollars (USD)</option>
                <option value="EUR">Euro (EUR)</option>
                <option value="BRL">Brazilian Real (BRL)</option>
                <option value="JPY">Japanese Yen (JPY)</option>
                <option value="TRY">Turkish Lira (TRY)</option>
            </select>

            <label for="currency-to">To</label>
            <select id="currency-to" name="currency-to">
                <option value="GBP">Pounds Sterling (GBP)</option>
                <option value="USD">American Dollars (USD)</option>
                <option value="EUR">Euro (EUR)</option>
                <option value="BRL">Brazilian Real (BRL)</option>
                <option value="JPY">Japanese Yen (JPY)</option>
                <option value="TRY">Turkish Lira (TRY)</option>
            </select>

            <button type="submit">Convert</button>
        </form>

        <?php if (isset($result)) { ?>
            <p class="result">Converted Amount: <?php echo $result . " " . $toCurrency; ?></p>
        <?php } ?>
    </div>
</body>
</html>
