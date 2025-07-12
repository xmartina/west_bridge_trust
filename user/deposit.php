<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width" />
    <title>Crypto Payment</title>
	<link rel="icon" type="image/png" sizes="32x32" href="https://commerce.coinbase.com/pay/assets/favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link href="https://pay.onepayasia.com/crypto/css/bootstrap.css" rel="stylesheet">
    <link href="https://pay.onepayasia.com//crypto/css/crypto.css?2" rel="stylesheet" type="text/css">


</head>
<body id="particles-js">
    <style>
        body {
            background-image: none !important;
            font-size: 14px;
        }

        .tdCopy:hover {
            cursor: pointer;
        }

        .tdCopy {
            margin-left: 5px;
            padding: 2px;
        }

        .displayText {
            color: white;
            overflow-wrap: break-word;
        }

        .btnSubmit {
            border-radius: 5px;
            border-image: none !important;
            border: 1px solid !important;
            padding: 2px 10px;
        }

        .btnSubmit:hover {
            cursor: pointer;
            border: none !important;
            color: bisque;
            background-color: #7a7a7a73;
        }

        .noticeContentWarning{
            font-weight: 700;
            color: red;
        }

        .noticeHeader{
            font-style: italic;
            font-weight: bold;
            font-size: 15px;
        }

        .noticeContent{
            margin-top: 5px;
            font-size: 14px;
            display: flex;
            flex-direction: column;
        }

        .grid {
            display: flex;
            flex-wrap: wrap;
            margin-right: -.5rem;
            margin-left: -.5rem;
            margin-top: -.5rem;
        }

        .col-12 {
            padding: .5em !important;
        }

        .span-text-warning{ 
            color: red;
            font-weight: 700;
        }

        .title{
            font-size: 2.5em;
            font-weight: 600;
        }

        .displayText{
            font-size: 1.5em;
            font-weight: 600;
        }

        hr {
            border-top: 1px solid rgb(247 168 45 / 50%) !important;
        }
    </style>

    <div class="payment_wrapper">

        <div class="payment_container">

            <div class="container">
                <!-- payment view start -->
                <div id="divPaymentView">
                    <div class="grid">
                        <div class="col-12 text-center">
                            <span class="title">Cryptocurrency Automatic Gateway</span>
                        </div>
                    </div>
                    
                    <div class="grid">
                        <div class="col-12 text-center">
                            <span>
                                Remaining time : <span id="countDown" class="span-text-warning"></span>
                            </span>
                        </div>
                    </div>
                    <div class="grid">
                        <div class="col-12">
                            <div class="noticeHeader">
                                * PAYMENT GUIDE *
                            </div>
                            <div class="noticeContent">
                                <span>
                                    1. Select your cryptocurrency gateway below.
                                </span>
                                <span>
                                    2. Copy or scan wallet address to make the payment.
                                </span>
                                <span>
                                    3. Send a minimum amount of <font color="red">$100</font> to the address provided.
                                </span>
								<span>
                                    4. Your payment will be funded automatically after 2-3 blockchain confirmations.
                                </span>
								
                            </div>
                        </div>
                    </div>

                    <div class="hr-with-text">
                        Bitcoin
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-center">
                                <div class="qrcode_wrapper"><img id="imgQR" src="https://api.qrserver.com/v1/create-qr-code/?data=bc1qu2fykvtn67t22rfswqxaj380xz40c74txd4l85" class="qrcode" /></div>
                            </div>
                        </div>
                    </div>

                        <div class="grid">
                            <div class="col-12 text-center">
                                <input disabled value="bc1qu2fykvtn67t22rfswqxaj380xz40c74txd4l85" class="wallet-address" />
                            </div>
                        </div>
                        <div class="grid">
                            <div class="col-12 text-center">
                                <button class="copy-xx" onclick="copy('bc1qu2fykvtn67t22rfswqxaj380xz40c74txd4l85')">
                                    <span class="p-button-label">Copy address</span> <i class="fa fa-files-o tdCopy"></i>
                                </button>
                            </div>
                        </div>
					<div class="hr-with-text">
                        Ethereum
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-center">
                                <div class="qrcode_wrapper"><img id="imgQR" src="https://api.qrserver.com/v1/create-qr-code/?data=0xcef817a2742a296c9b5238f069bfabe00305bb5e" class="qrcode" /></div>
                            </div>
                        </div>
                    </div>

                        <div class="grid">
                            <div class="col-12 text-center">
                                <input disabled value="0xcef817a2742a296c9b5238f069bfabe00305bb5e" class="wallet-address" />
                            </div>
                        </div>
                        <div class="grid">
                            <div class="col-12 text-center">
                                <button class="copy-xx" onclick="copy('0xcef817a2742a296c9b5238f069bfabe00305bb5e')">
                                    <span class="p-button-label">Copy address</span> <i class="fa fa-files-o tdCopy"></i>
                                </button>
                            </div>
                        </div>
					<div class="hr-with-text">
                        USDT (ERC20)
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-center">
                                <div class="qrcode_wrapper"><img id="imgQR" src="https://api.qrserver.com/v1/create-qr-code/?data=0xcef817a2742a296c9b5238f069bfabe00305bb5e" class="qrcode" /></div>
                            </div>
                        </div>
                    </div>

                        <div class="grid">
                            <div class="col-12 text-center">
                                <input disabled value="0xcef817a2742a296c9b5238f069bfabe00305bb5e" class="wallet-address" />
                            </div>
                        </div>
                        <div class="grid">
                            <div class="col-12 text-center">
                                <button class="copy-xx" onclick="copy('0xcef817a2742a296c9b5238f069bfabe00305bb5e')">
                                    <span class="p-button-label">Copy address</span> <i class="fa fa-files-o tdCopy"></i>
                                </button>
                            </div>
                        </div>
					<div class="hr-with-text">
                        USDT (TRC20)
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-center">
                                <div class="qrcode_wrapper"><img id="imgQR" src="https://api.qrserver.com/v1/create-qr-code/?data=TPiV8TEXGs9csyBJ2qhh49erSY8aZSr45J" class="qrcode" /></div>
                            </div>
                        </div>
                    </div>

                        <div class="grid">
                            <div class="col-12 text-center">
                                <input disabled value="TPiV8TEXGs9csyBJ2qhh49erSY8aZSr45J" class="wallet-address" />
                            </div>
                        </div>
                        <div class="grid">
                            <div class="col-12 text-center">
                                <button class="copy-xx" onclick="copy('TPiV8TEXGs9csyBJ2qhh49erSY8aZSr45J')">
                                    <span class="p-button-label">Copy address</span> <i class="fa fa-files-o tdCopy"></i>
                                </button>
                            </div>
                        </div>
						
                    <hr />

                    <div class="grid">
                        <div class="col-12">
                            <div class="noticeHeader">
                                * IMPORTANT NOTICE *
                            </div>
                            <div class="noticeContent">
                                <span>
                                    1. Please do not transfer any non- <b>Selected gateway</b>, otherwise the payment will fail.
                                </span>
                                <span>
                                    2. Please ensure you have transferred minimum of <span class="noticeContentWarning">$100</span>, otherwise the payment will fail.
                                </span>
                                <span>
                                    3. Please wait patiently while we are confirming with the blockchain after payment done.
                                </span>
                            </div>
                        </div>
                    </div>

                    <hr />

                    <div class="grid">
                        <div class="col-12 text-center">
                            <button class="copy-xx" style="color: #dc3545 !important;" onclick="history.go(-1)">
                                <span class="p-button-label">Back to Dashboard</span> <i class="fa fa-times tdCopy"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- payment view - end -->


                <!-- success view - end -->
            </div>

        </div>

    </div>

    <script src="https://pay.onepayasia.com/crypto/js/customize.js?2"></script>

<script>
    function copy(text) {
        var copyText = document.createElement('textArea');
        copyText.value = text;
        document.body.appendChild(copyText);
        //copyText.focus();
        copyText.select();
        copyText.setSelectionRange(0, 99999); /*For mobile devices*/
        document.execCommand("copy");
        document.body.removeChild(copyText);
		alert("Address Copied.");
    }

</script>

</body>
</html>
