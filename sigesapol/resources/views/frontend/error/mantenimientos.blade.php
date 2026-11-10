<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sitio en Mantenimiento</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #3c763d;
            color: #343a40;
            line-height: 1.6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 0 20px;
        }
        
        .container {
            max-width: 800px;
            text-align: center;
            background-color: white;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .icon {
            font-size: 80px;
            margin-bottom: 20px;
            color: #007bff;
        }
        
        h1 {
            font-size: 36px;
            margin-bottom: 20px;
            color: #3c763d;
        }
        
        p {
            font-size: 18px;
            margin-bottom: 30px;
            color: #6c757d;
        }
        
        .contact {
            margin-top: 30px;
            font-size: 16px;
        }
        
        .social-icons {
            margin-top: 30px;
        }
        
        .social-icons a {
            display: inline-block;
            margin: 0 10px;
            font-size: 24px;
            color: #007bff;
            text-decoration: none;
        }
        
        .social-icons a:hover {
            color: #0056b3;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 30px 20px;
            }
            
            h1 {
                font-size: 28px;
            }
            
            p {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">🛠️</div>
        <h1>Sitio en Mantenimiento</h1>
        <p>Estamos trabajando para mejorar nuestro sitio web y brindarle una mejor experiencia. Volveremos pronto.</p>
        

        
        <div class="contact">
            <p>¿que está pasando?</p>
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
        </div>
        
        <div class="social-icons">
            
        </div>
    </div>
    

</body>
</html>