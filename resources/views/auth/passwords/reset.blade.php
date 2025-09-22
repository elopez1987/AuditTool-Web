<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restablecer Contraseña - YARDTRAX</title>
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
            overflow: auto;
        }
        .reset-container {
            background: rgba(255, 255, 255, 0.12);
            border-radius: 25px;
            overflow: hidden;
            max-width: 500px;
            width: 95%;
            margin: 20px auto;
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: fadeIn 1s ease;
        }
        .reset-panel {
            padding: 40px;
            background-color: #f8f9fa;
            border-radius: 25px;
        }
        .form-control {
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 12px;
            margin-bottom: 15px;
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
            color: white;
        }
        .header-title {
            font-weight: bold;
            font-size: 24px;
            text-align: center;
            margin-bottom: 10px;
            color: #333;
        }
        .header-subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .bottom-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }
        a {
            color: #cc2b5e;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
            color: #cc2b5e;
        }
        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .invalid-feedback {
            display: block;
            font-size: 12px;
            margin-top: -10px;
            margin-bottom: 10px;
        }
        .form-label {
            font-weight: 500;
            color: #333;
            margin-bottom: 5px;
        }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
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
    </style>
</head>
<body>
<canvas id="particles-bg"></canvas>
<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="reset-container">
        <div class="reset-panel">
            <div class="header-title">
                <i class="fas fa-key" style="color: #cc2b5e; margin-right: 10px;"></i>
                Restablecer Contraseña
            </div>
            <div class="header-subtitle">
                Ingresa tu nueva contraseña para completar el proceso
            </div>

            @if(session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group">
                    <label class="form-label">Correo Electrónico</label>
                    <input id="email" type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           name="email" value="{{ $email ?? old('email') }}" 
                           required autocomplete="email" readonly>
                    @error('email')
                        <span class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Nueva Contraseña</label>
                    <div class="input-group">
                        <input id="password" type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               name="password" placeholder="Mínimo 8 caracteres"
                               required autocomplete="new-password">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    @error('password')
                        <span class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Confirmar Contraseña</label>
                    <div class="input-group">
                        <input id="password-confirm" type="password" class="form-control" 
                               name="password_confirmation" placeholder="Repite la nueva contraseña"
                               required autocomplete="new-password">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-submit">
                    <i class="fas fa-check" style="margin-right: 8px;"></i>
                    Restablecer Contraseña
                </button>
            </form>

            <div class="bottom-link">
                <a href="{{ url('/') }}">
                    <i class="fas fa-arrow-left" style="margin-right: 5px;"></i>
                    Volver al inicio de sesión
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    // Partículas de fondo (simplificadas)
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
        for (let i = 0; i < 50; i++) {
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

    // Mostrar/ocultar contraseña
    function setupPasswordToggle(toggleId, inputId) {
        const toggle = document.getElementById(toggleId);
        const input = document.getElementById(inputId);
        
        if (toggle && input) {
            toggle.addEventListener('click', () => {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                toggle.innerHTML = type === 'password' 
                    ? '<i class="fa fa-eye"></i>' 
                    : '<i class="fa fa-eye-slash"></i>';
            });
        }
    }
    
    setupPasswordToggle('togglePassword', 'password');
    setupPasswordToggle('togglePasswordConfirm', 'password-confirm');
</script>
</body>
</html>
