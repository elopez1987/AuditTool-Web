<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>YARDTRAX</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            align-items: center;
            justify-content: center;
            background: linear-gradient(-45deg, #2a3f8c, #2193b0, #6dd5ed, #cc2b5e);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            overflow: hidden;
        }
        .login-container {
            background: rgba(255, 255, 255, 0.12);
            border-radius: 25px;
            overflow: hidden;
            max-width: 900px;
            width: 95%;
            display: flex;
            flex-direction: column;
            z-index: 1;
            margin: 20px auto;
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: fadeIn 1s ease;
            box-shadow: none;
        }
        @media (min-width: 768px) {
            .login-container {
                flex-direction: row;
            }
        }
        .left-panel {
            background: linear-gradient(135deg, #2193b0, #6dd5ed, #cc2b5e);
            color: white;
            padding: 30px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            border-top-left-radius: 25px;
            border-bottom-left-radius: 25px;
        }
        .left-panel h2 {
            font-size: 32px;
            font-weight: bold;
        }
        .left-panel p {
            font-size: 16px;
            margin-top: 10px;
        }
        #phraseCarousel {
            font-size: 16px;
            font-style: italic;
            transition: opacity 0.5s ease-in-out;
            margin-top: auto;
        }
        .right-panel {
            flex: 1;
            padding: 30px;
            background-color: #f8f9fa;
            border-top-right-radius: 25px;
            border-bottom-right-radius: 25px;
        }
        .form-control {
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 12px;
        }
        .form-control:focus {
            border-color: #cc2b5e;
            box-shadow: 0 0 0 0.2rem rgba(204, 43, 94, 0.25);
        }
        .btn-submit {
            background: linear-gradient(to right, #cc2b5e, #753a88);
            color: white;
            border: none;
            width: 100%;
            padding: 12px;
            font-weight: bold;
            border-radius: 8px;
            transition: background 0.3s ease;
        }
        .btn-submit:hover {
            opacity: 0.95;
            background: linear-gradient(to right, #d64e7b, #9154a8);
        }
        .greeting {
            font-weight: bold;
            font-size: 24px;
        }
        .greeting span {
            color: #cc2b5e;
        }
        .bottom-link {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }
        a {
            color: #6dd5ed;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        #particles-bg {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        @media (max-width: 767px) {
            .left-panel {
                display: none; /* Ocultar la izquierda */
            }
        }


    </style>
</head>
<body>
<canvas id="particles-bg"></canvas>
<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="login-container">

        <div class="left-panel">
            <h2>YardTrax</h2>
            <p>La plataforma que lleva tu operación al siguiente nivel</p>
            <!-- Imagen decorativa estilo digital/logística -->
            <div class="image-container">
                <img src="{{asset('img/imagen1.png')}}" alt="Logística Digital" class="logistics-img" style="max-width: 90%; margin: auto; display: block; border-radius: 10px">
            </div>

            <div class="mt-auto">
                <div id="phraseCarousel">Mejora la eficiencia en la gestión logística.</div>
            </div>
        </div>

        <div class="right-panel">
            <div class="greeting">Hola! <span id="timeGreeting">Buenos días</span></div>
            <p class="mb-4">Inicia sesión en tu cuenta</p>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label for="login">Usuario o Correo electrónico</label>
                    <input type="text" class="form-control" value="{{old('login')}}" name="login" id="login" placeholder="Ingresa usuario o correo electrónico">
                </div>
                <div class="form-group">
                    <label>Contraseña</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" value="{{old('password')}}" name="password" placeholder="Contraseña">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-submit">Iniciar Sesión</button>
                <div class="bottom-link">
                    <a href="https://traxion.global/traxion-logistic/privacidad" target="_blank">Política de privacidad</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    // Saludo según la hora
    const greetingElement = document.getElementById('timeGreeting');
    const currentHour = new Date().getHours();
    if (currentHour < 12) {
        greetingElement.textContent = 'Buenos días';
    } else if (currentHour < 18) {
        greetingElement.textContent = 'Buenas tardes';
    } else {
        greetingElement.textContent = 'Buenas noches';
    }

    // Carrusel de frases
    const phrases = [
        "Mejora la eficiencia en la gestión logística.",
        "Facilita el control y seguimiento de los eventos en tiempo real.",
        "Optimiza la asignación y planificación de viajes.",
        "Reduce errores en la administración de cargas y unidades.",
        "Agiliza la comunicación entre Tráfico y Operaciones.",
        "Genera reportes precisos para una mejor toma de decisiones.",
        "Incrementa la productividad del personal logístico.",
        "Permite una gestión integral desde la asignación hasta la salida de unidades.",
        "Minimiza los tiempos de espera y retrasos en el proceso.",
        "Mejora la visibilidad y control de las actividades logísticas."
    ];
    let currentPhraseIndex = 0;
    const phraseElement = document.getElementById('phraseCarousel');
    let intervalId;
    function startCarousel() {
        intervalId = setInterval(() => {
            phraseElement.style.opacity = 0;
            setTimeout(() => {
                currentPhraseIndex = (currentPhraseIndex + 1) % phrases.length;
                phraseElement.textContent = phrases[currentPhraseIndex];
                phraseElement.style.opacity = 1;
            }, 500);
        }, 3000);
    }
    phraseElement.addEventListener('mouseenter', () => clearInterval(intervalId));
    phraseElement.addEventListener('mouseleave', startCarousel);
    startCarousel();

    // Partículas de fondo
    const canvas = document.getElementById('particles-bg');
    const ctx = canvas.getContext('2d');
    let particlesArray = [];
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
    window.addEventListener('resize', () => {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    });
    class Particle {
        constructor() {
            this.x = Math.random() * canvas.width;
            this.y = Math.random() * canvas.height;
            this.size = Math.random() * 2 + 1;
            this.speedX = Math.random() * 0.5 - 0.25;
            this.speedY = Math.random() * 0.5 - 0.25;
            this.opacity = Math.random() * 0.5 + 0.2;
        }
        update() {
            this.x += this.speedX;
            this.y += this.speedY;
            if (this.x < 0 || this.x > canvas.width) this.speedX *= -1;
            if (this.y < 0 || this.y > canvas.height) this.speedY *= -1;
        }
        draw() {
            ctx.fillStyle = `rgba(255, 255, 255, ${this.opacity})`;
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
            ctx.fill();
        }
    }
    function initParticles() {
        particlesArray = [];
        for (let i = 0; i < 100; i++) {
            particlesArray.push(new Particle());
        }
    }
    function animateParticles() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        for (let particle of particlesArray) {
            particle.update();
            particle.draw();
        }
        requestAnimationFrame(animateParticles);
    }
    initParticles();
    animateParticles();

    // Mostrar u ocultar contraseña
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    togglePassword.addEventListener('click', () => {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        togglePassword.innerHTML = type === 'password'
            ? '<i class="fa fa-eye"></i>'
            : '<i class="fa fa-eye-slash"></i>';
    });
</script>
</body>
</html>
