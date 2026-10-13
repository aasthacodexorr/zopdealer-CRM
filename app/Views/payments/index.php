<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Accept a payment</title>
    <meta name="description" content="Pay online" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <script src="https://js.stripe.com/v3/"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Roboto', sans-serif;
            letter-spacing: 1px;
        }
    </style>
    <style>
        /* Variables */
        * {
            box-sizing: border-box;
        }

        body {
            font-size: 16px;
            -webkit-font-smoothing: antialiased;
            display: flex;
            justify-content: center;
            align-content: center;
            height: 100vh;
            width: 100vw;
        }

        form {
            width: 30vw;
            min-width: 500px;
            align-self: center;
            box-shadow: 0px 0px 0px 0.5px rgba(50, 50, 93, 0.1),
                0px 2px 5px 0px rgba(50, 50, 93, 0.1), 0px 1px 1.5px 0px rgba(0, 0, 0, 0.07);
            border-radius: 7px;
            padding: 40px;
        }

        .hidden {
            display: none;
        }

        #payment-message {
            color: rgb(105, 115, 134);
            font-size: 16px;
            line-height: 20px;
            padding-top: 12px;
            text-align: center;
        }

        #payment-element {
            margin-bottom: 24px;
        }

        /* Buttons and links */
        button {
            background: #5469d4;
            font-family: Arial, sans-serif;
            color: #ffffff;
            border-radius: 4px;
            border: 0;
            padding: 12px 16px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            display: block;
            transition: all 0.2s ease;
            box-shadow: 0px 4px 5.5px 0px rgba(0, 0, 0, 0.07);
            width: 100%;
        }

        button:hover {
            filter: contrast(115%);
        }

        button:disabled {
            opacity: 0.5;
            cursor: default;
        }

        /* spinner/processing state, errors */
        .spinner,
        .spinner:before,
        .spinner:after {
            border-radius: 50%;
        }

        .spinner {
            color: #ffffff;
            font-size: 22px;
            text-indent: -99999px;
            margin: 0px auto;
            position: relative;
            width: 20px;
            height: 20px;
            box-shadow: inset 0 0 0 2px;
            -webkit-transform: translateZ(0);
            -ms-transform: translateZ(0);
            transform: translateZ(0);
        }

        .spinner:before,
        .spinner:after {
            position: absolute;
            content: "";
        }

        .spinner:before {
            width: 10.4px;
            height: 20.4px;
            background: #5469d4;
            border-radius: 20.4px 0 0 20.4px;
            top: -0.2px;
            left: -0.2px;
            -webkit-transform-origin: 10.4px 10.2px;
            transform-origin: 10.4px 10.2px;
            -webkit-animation: loading 2s infinite ease 1.5s;
            animation: loading 2s infinite ease 1.5s;
        }

        .spinner:after {
            width: 10.4px;
            height: 10.2px;
            background: #5469d4;
            border-radius: 0 10.2px 10.2px 0;
            top: -0.1px;
            left: 10.2px;
            -webkit-transform-origin: 0px 10.2px;
            transform-origin: 0px 10.2px;
            -webkit-animation: loading 2s infinite ease;
            animation: loading 2s infinite ease;
        }

        @-webkit-keyframes loading {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @keyframes loading {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @media only screen and (max-width: 600px) {
            form {
                width: 80vw;
                min-width: initial;
            }
        }
    </style>
</head>

<body style="background: #e7e7e7;">
    <section class="container" style="max-width:600px;margin:auto;">
        <form class="row" id="payment-form" style="background:white;">
            <div class="col-xs-12">
                <img src="https://zopsoftware-asset.b-cdn.net/secure_checkout.png" alt="Secure Checkout" class="img-responsive">
            </div>
            <?php if (!empty($business_logo)) { ?>
                <div class="col-xs-12">
                    <img class="img-responsive" style="max-height: 75px;margin:auto;" src="<?php echo $business_logo; ?>" alt="Business Logo">
                </div>
            <?php } ?>
            <?php if (!empty($vehicle_details)) { ?>
                <p class="col-xs-12" style="margin-top:1em;"><span style="font-style: italic;"><?php echo $vehicle_details . '.'; ?></span></p>
            <?php } ?>
            <p class="col-xs-12" style="margin-bottom:1em;">Payment Amount:
                <b> <?php $fmt = numfmt_create('en_US', NumberFormatter::CURRENCY);
                    echo numfmt_format_currency($fmt, $amount, "CAD") . '.'; ?> </b>
            </p>
            <div id="payment-element" class="col-xs-12">
                <!--Stripe.js injects the Payment Element-->
            </div>
            <button id="submit" class="btn btn-primary">
                <div class="spinner hidden" id="spinner"></div>
                <span id="button-text">Pay now</span>
            </button>
            <div id="payment-message" class="hidden"></div>

            <?php if (!empty($is_refundable)) { ?>
                <p class="col-xs-12" style="margin-top:1em;font-size:0.8em;color:#777;">Secure your vehicle with a <?php echo numfmt_format_currency($fmt, $amount, "CAD") . '.'; ?> deposit.
                    Once you have submitted your online deposit you will be contacted by a Sales Associate to confirm your vehicle hold.
                    There are no obligations to purchase; you may cancel your reservation anytime to receive a 100% full refund.
                    Please note that while all efforts are taken to reserve the vehicle, the dealer reserves the right to cancel any online reservation for a full refund.
                </p>
            <?php } ?>
            <div class="col-xs-12 text-center text-danger" id="timer" style="font-weight: 800;letter-spacing:2px;margin-top:1em;"></div>
        </form>

    </section>
    <script>
        // This is your test publishable API key.
        const stripe = Stripe(
            "<?php echo getenv('STRIPE.PUBLIC_KEY') ?>", {
                stripeAccount: '<?php echo $stripe_account; ?>'
            });

        let elements;

        initialize();
        checkStatus();

        document
            .querySelector("#payment-form")
            .addEventListener("submit", handleSubmit);

        // Fetches a payment intent and captures the client secret
        async function initialize() {
            const clientSecret = '<?php echo $clientSecret; ?>';

            elements = stripe.elements({
                clientSecret
            });

            const paymentElement = elements.create("payment");
            paymentElement.mount("#payment-element");
        }

        async function handleSubmit(e) {
            e.preventDefault();
            setLoading(true);

            const {
                error
            } = await stripe.confirmPayment({
                elements,
                confirmParams: {
                    // Make sure to change this to your payment completion page
                    return_url: `${window.location.origin}/thankyou.html`,
                },
            });

            // This point will only be reached if there is an immediate error when
            // confirming the payment. Otherwise, your customer will be redirected to
            // your `return_url`. For some payment methods like iDEAL, your customer will
            // be redirected to an intermediate site first to authorize the payment, then
            // redirected to the `return_url`.
            if (error.type === "card_error" || error.type === "validation_error") {
                showMessage(error.message);
            } else {
                showMessage("An unexpected error occurred.");
            }

            setLoading(false);
        }

        // Fetches the payment intent status after payment submission
        async function checkStatus() {
            const clientSecret = new URLSearchParams(window.location.search).get(
                "payment_intent_client_secret"
            );

            if (!clientSecret) {
                return;
            }

            const {
                paymentIntent
            } = await stripe.retrievePaymentIntent(clientSecret);

            switch (paymentIntent.status) {
                case "succeeded":
                    showMessage("Payment succeeded!");
                    break;
                case "processing":
                    showMessage("Your payment is processing.");
                    break;
                case "requires_payment_method":
                    showMessage("Your payment was not successful, please try again.");
                    break;
                default:
                    showMessage("Something went wrong.");
                    break;
            }
        }

        // ------- UI helpers -------

        function showMessage(messageText) {
            const messageContainer = document.querySelector("#payment-message");

            messageContainer.classList.remove("hidden");
            messageContainer.textContent = messageText;

            setTimeout(function() {
                messageContainer.classList.add("hidden");
                messageText.textContent = "";
            }, 4000);
        }

        // Show a spinner on payment submission
        function setLoading(isLoading) {
            if (isLoading) {
                // Disable the button and show a spinner
                document.querySelector("#submit").disabled = true;
                document.querySelector("#spinner").classList.remove("hidden");
                document.querySelector("#button-text").classList.add("hidden");
            } else {
                document.querySelector("#submit").disabled = false;
                document.querySelector("#spinner").classList.add("hidden");
                document.querySelector("#button-text").classList.remove("hidden");
            }
        }
    </script>
    <script>
        var myInterval = setInterval(function() {
            var current_timestamp = Date.now() / 1000 | 0;
            var link_valid = <?php echo $expiry_time ?>;
            var timestamp_in_seconds = link_valid - current_timestamp;
            const minutes = "0" + Math.floor(timestamp_in_seconds / 60);
            const seconds = "0" + (timestamp_in_seconds - minutes * 60);
            document.getElementById('timer').innerText = minutes.substr(-2) + ":" + seconds.substr(-2);
            if (timestamp_in_seconds < 1) {
                clearInterval(myInterval);
                location.reload(true);
            }
        }, 1000);
    </script>
</body>

</html>