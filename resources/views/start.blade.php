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
                    <input type='name' class="" name='name' id='name' style="width: 250px" placeholder='Введите имя' value='Name'><br>
                </div>
                <div class="form-group" id="formEmail">
                    <label for="email">Введите email</label>
                    <input type='email' class="" name='email' id='email' style="width: 250px" placeholder='Введите email' value='testtaskwork@gmail.com'><br>
                </div>
                <button type="button" id='btn-weather' class='btn btn-primary btn-lg'>Узнать погоду</button>
            </form>

            <div>
                <p id="time"></p>
            </div>

            <div id="output">
                <div hidden="true" id="hide">
                    <div class="weather-info">
                        <div id="location" class="info" hidden="true"></div>
                        <div id="temperature" class="info">
                            <span id="temperature-num"></span>
                            <span>°</span>
                            <span id="temperature-scale">C</span>
                        </div>
                        <div id="weather-condition" class="info" hidden="true"></div>
                        <div id="weather-icon" class="info" hidden="true"></div>
                    </div>
                </div>
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
    const loc = document.getElementById("location");
    const temNum = document.getElementById("temperature-num");
    const temScale = document.getElementById("temperature-scale");
    const weatherCon = document.getElementById("weather-condition");
    const weatherIcon = document.getElementById("weather-icon");

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(position => {
                getWeather(position.coords.latitude, position.coords.longitude);
            });
        } else {
            loc.innerHTML = "Геолокация не поддерживается браузером.";
        }
    }

    function getWeather(lat, long) {
        const root = "https://fcc-weather-api.glitch.me/api/current?";
        fetch(`${root}lat=${lat}&lon=${long}`, { method: "get" })
            .then(resp => resp.json())
            .then(data => {
                updateDataToUI(data.name, data.weather, data.main.temp);
            })
            .catch(function(err) {
                console.error(err);
            });
    }

    var temperatureInfo = '';
    function updateDataToUI(location, weather, temp) {
        weatherIcon.innerHTML = `<img src="${weather[0].icon}" />`;
        weatherCon.innerHTML = weather[0].main;
        loc.innerHTML = location;
        temNum.innerHTML = `${temp}`;
        temperatureInfo = `${temp}`;
    }

    window.onload = function() {
        getLocation();
    };

    $(document).ready(function () {
        $('#btn').click(function() {
            $('#btn').hide();
            document.getElementById('form').hidden = false;
            $('#btn-weather').click(function() {
                function checkTime(i) {
                    return (i < 10) ? "0" + i : i;
                }

                var info = '';
                var emailReceiver = $('#email').val();
                function startTime() {
                    var today = new Date(),
                        h = checkTime(today.getHours()),
                        m = checkTime(today.getMinutes());
                    info = "Время: " + h + ":" + m + "<br> в Москве погода ";
                    document.getElementById('time').innerHTML = info;
                    document.getElementById('hide').hidden = false;
                    t = setTimeout(function () {
                        startTime()
                    }, 500);
                }
                startTime();

                Email.send({
                    Host: "smtp.gmail.com",
                    Username: "", // put your email here
                    Password: "", // put your password here
                    To: emailReceiver,
                    From: "", // put your email here
                    Subject: "Время и погода в Москве",
                    Body: info + temperatureInfo + '°C',
                }).then(
                    $('#btn-weather').hide(),
                    $('#formName').hide(),
                    $('#formEmail').hide(),
                    document.getElementById("myModal").style.display = "block"
                );
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
</script>
