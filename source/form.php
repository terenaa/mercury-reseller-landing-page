<?php

//[DLP_MAIL_SETTINGS]

$valid = true;
$status = false;
$offerForm = filter_input_array(INPUT_POST, [
    'full_name' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
    'email' => FILTER_SANITIZE_EMAIL,
    'phone' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
    'price' => FILTER_SANITIZE_NUMBER_INT,
    'currency' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
    'comments' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
    'domain' => FILTER_SANITIZE_URL,
    'g-recaptcha-response' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
]);

if ($settings['captcha']['secret']) {
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => 'https://www.google.com/recaptcha/api/siteverify',
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => sprintf('secret=%s&response=%s', $settings['captcha']['secret'], $offerForm['g-recaptcha-response'])
    ]);
    $response = json_decode(curl_exec($ch), true);

    if (!$response['success']) {
        $valid = false;
    }
}

if ($valid) {
    $message = "There is a new offer for {$offerForm['domain']}\n\n"
        . "Name: {$offerForm['full_name']}\n"
        . "Email: {$offerForm['email']}\n"
        . "Phone: {$offerForm['phone']}\n"
        . "Price: {$offerForm['price']} {$offerForm['currency']}\n"
        . "Comments: {$offerForm['comments']}";

    $status = mail($settings['recipient']['email'], sprintf($settings['subject'], $offerForm['domain']), $message, implode("\r\n", [
        "To: {$settings['recipient']['name']} <{$settings['recipient']['email']}>",
        "From: domain.reseller@{$offerForm['domain']}",
        "Reply-To: {$offerForm['email']}"
    ]));
}

header('Location: ' . ($status ? 'sent.html' : 'error.html'));
exit;
