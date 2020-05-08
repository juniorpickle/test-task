<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Start</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

    <script src="https://smtpjs.com/v3/smtp.js"></script>

    <style>
        body {
            text-align: center;
            padding-top: 200px;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="content">
    <div>
        <button type="submit" id="btn" class="btn btn-success btn-lg">Старт</button>
    </div>

    <form action="" type="POST" hidden="true" id="form" name="form">
        {{ csrf_field() }}
        <div class="form-group" id="formName">
            <label for="name">Введите имя</label>
            <input type='name' class="" name='name' id='name' style="width: 250px" placeholder='Введите имя' value='Имя' required><br>
        </div>
        <div class="form-group" id="formEmail">
            <p id="noEmail"></p>
            <label for="email">Введите email</label>
            <input type='email' class="" name='email' id='email' style="width: 250px" placeholder='Введите email' value='testtaskwork@gmail.com' required><br>
        </div>
        <button type="button" id='btn-weather' class='btn btn-primary btn-lg'>Узнать погоду</button>
    </form>

    <div>
        <p id="time"></p>
        <span id="temp" hidden="true"></span>
    </div>



    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p>Сообщение отправлено на указанную почту</p>
        </div>
    </div>
</div>
</body>
</html>

<script>

    var info = '';
    $(document).ready(function () {

        let API_KEY = '7acdc0731927694027f7917e3c9fed53';

        var temperature = '';
        function getWeather(latitude, longtitude) {
            $.ajax({
                url: 'https://api.openweathermap.org/data/2.5/weather',
                data: {
                    lat: latitude,
                    lon: longtitude,
                    units: 'imperial',
                    APPID: API_KEY
                },
                success: data => {
                    temperature = ((5 / 9)  * (data["main"]["temp"] - 32)).toFixed(0) + " °C";
                }
            })
        }

        getWeather(55.751244, 37.618423);

        var inputEmail = document.getElementById('email');
        function ValidateEmail(inputEmail) {
            var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            if(inputEmail.value.match(mailformat)) {
                document.form.email.focus();
                return true;
            } else {
                document.getElementById('noEmail').innerHTML = "Неверно введен email";
            }
        }

        $('#btn').click(function() {
            $('#btn').hide();
            document.getElementById('form').hidden = false;
            $('#btn-weather').click(function() {

                $('#email').each(function() {
                    if(!$(this).val()){
                        document.getElementById('noEmail').innerHTML = "Введите email, чтобы мы могли отправить Вам письмо";
                        return false;
                    } else if (ValidateEmail(inputEmail) == true){

                        function checkTime(i) {
                            return (i < 10) ? "0" + i : i;
                        }

                        function startTime() {
                            var today = new Date(),
                                h = checkTime(today.getHours()),
                                m = checkTime(today.getMinutes());
                            info = "Время: " + h + ":" + m + "<br> в Москве погода " + temperature;
                            document.getElementById('time').innerHTML = info;
                            document.getElementById('temp').hidden = false;
                            t = setTimeout(function () {
                                startTime()
                            }, 500);
                        }
                        startTime();

                        var emailReceiver = $('#email').val();
                        function emailSend() {
                            Email.send({
                                SecureToken : "45c3a383-cb7a-44e0-a5bd-640e81388e01",
                                To : emailReceiver,
                                From : "testtaskwork@gmail.com",
                                Subject : "Время и погода в Москве",
                                Body : info
                            }).then(
                                $('#btn-weather').hide(),
                                $('#formName').hide(),
                                $('#formEmail').hide(),
                                document.getElementById("myModal").style.display = "block"
                            );
                        }
                        emailSend();
                    }
                });
            });
        });

        var modal = document.getElementById("myModal");

        var btn = document.getElementById("myBtn");

        var span = document.getElementsByClassName("close")[0];

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    });
</script>
