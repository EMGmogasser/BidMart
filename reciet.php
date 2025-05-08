<head>
    <meta charset="utf-8" />
    <title>Reciet</title>
    <link rel="icon" href="assets/img/logo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v5.15.4/css/all.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        .logo{
            display: flex;
            justify-content: center;
        }
        .logo img{
            width: 90px;
        }
        .reciet,.details ul{
            display:flex;
            flex-direction:column;
            gap:15px;
        }
        .reciet{
            width: 50%;
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            box-shadow: var(--shadow2);
            padding: 20px;
            border-radius: 10px;
        }
        ul{
            list-style-type: none;
        }
        li{
            display:flex;
            justify-content:space-between;
        }
        .status{
            color:green;
            text-align:center;
            text-transform: uppercase;
        }
        .DECLINED{
            color:red;
        }
        button{
            text-align:center;
            margin: auto;
            width: 150px;
        }
        @media (max-width: 767px) {
            .reciet{
                width: 90%;
            }
        }
        @media (max-width: 500px) {
            li{
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="reciet">
        <div class="logo"><img src="assets/img/logo.png" alt=""></div>
        <h2 class="status"></h2>
        <div class="details">
            <ul> 
                <li>
                    <p>User name</p>
                    <p class="user"></p>
                </li>
                <li>
                    <p>Paymeny ID</p>
                    <p class="id"></p>
                </li>
                <li>
                    <p>Amount</p>
                    <p class="amount"></p>
                </li>
                <li>
                    <p>fees</p>
                    <p class="fees"></p>
                </li>
                <li>
                    <p>Total</p>
                    <p class="total"></p>
                </li>
            </ul>
        </div>
        <button  class="primary-btn">Proceed</button>
    </div>
</body>

<script>
    const params = new URLSearchParams(window.location.search);
    const tap_id = params.get('tap_id');
    const url=`https://hk.herova.net/payment/ret_pay.php?tap_id=${tap_id}`;
    const status = document.querySelector('.status');
    const id = document.querySelector('.id');
    const user = document.querySelector('.user');
    const amount = document.querySelector('.amount');
    const fees = document.querySelector('.fees');
    const total = document.querySelector('.total');
    const currency = document.querySelector('.currency');

    const proceedBtn = document.querySelector('button');
    proceedBtn.addEventListener('click', () => {
        window.location.href = `${localStorage.getItem('currentPage')}`;
    });

    async function recietDetails(){
        const res = await fetch(url);
        const data = await res.json();

        const reciet = {
            id:data.id,
            status:data.status,
            amount:data.amount,
            currency:data.currency,
        };
        status.textContent = "payment "+ reciet.status;
        status.classList.add(reciet.status);
        id.textContent = reciet.id;
        user.textContent = data.customer.first_name;
        amount.textContent = reciet.amount/1.05+" "+ reciet.currency;
        fees.textContent = reciet.amount/1.05*0.05+" "+ reciet.currency;
        total.textContent = reciet.amount+" "+ reciet.currency;

        console.log(data);
    }
    recietDetails();
</script>