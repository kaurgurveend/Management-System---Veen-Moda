<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veenmoda Textile - Premium Textile Solutions</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #7e22ce 100%);
            min-height: 100vh;
            overflow: hidden;
            position: relative;
        }

        /* Animated Background Shapes */
        .shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            animation: float 20s infinite ease-in-out;
        }

        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 70%;
            left: 80%;
            animation-delay: 2s;
        }

        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            top: 40%;
            left: 70%;
            animation-delay: 4s;
        }

        .shape:nth-child(4) {
            width: 100px;
            height: 100px;
            top: 80%;
            left: 20%;
            animation-delay: 1s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
                opacity: 0.5;
            }
            50% {
                transform: translateY(-30px) rotate(180deg);
                opacity: 0.8;
            }
        }

        /* Main Content */
        .container {
            position: relative;
            z-index: 2;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .content {
            text-align: center;
            color: white;
            max-width: 800px;
        }

        .logo {
            font-size: 1rem;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 2rem;
            opacity: 0;
            animation: fadeInDown 1s ease forwards;
            color: rgba(255, 255, 255, 0.8);
        }

        h1 {
            font-size: 5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            opacity: 0;
            animation: fadeInUp 1s ease forwards 0.2s;
            line-height: 1.2;
            background: linear-gradient(to right, #fff, #e0e7ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .tagline {
            font-size: 1.3rem;
            margin-bottom: 1rem;
            opacity: 0;
            animation: fadeInUp 1s ease forwards 0.4s;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 300;
        }

        .description {
            font-size: 1rem;
            margin-bottom: 3rem;
            opacity: 0;
            animation: fadeInUp 1s ease forwards 0.6s;
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.6;
        }

        .buttons {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
            opacity: 0;
            animation: fadeInUp 1s ease forwards 0.8s;
        }

        .btn {
            padding: 16px 40px;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: white;
            color: #1e3c72;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(10px);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: white;
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
        }

        /* Features Section */
        .features {
            display: flex;
            gap: 2rem;
            margin-top: 4rem;
            justify-content: center;
            flex-wrap: wrap;
            opacity: 0;
            animation: fadeInUp 1s ease forwards 1s;
        }

        .feature {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 1.5rem;
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            max-width: 200px;
        }

        .feature:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-5px);
        }

        .feature-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .feature-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .feature-text {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.7);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            body {
                overflow-y: auto;
            }
            .container {
                padding: 15px;
            }
            h1 {
                font-size: 2.5rem;
            }
            .tagline {
                font-size: 1rem;
            }
            .description {
                font-size: 0.9rem;
                margin-bottom: 2rem;
            }
            .buttons {
                flex-direction: column;
                align-items: center;
                gap: 1rem;
            }
            .btn {
                width: 100%;
                max-width: 300px;
                padding: 14px 30px;
                font-size: 0.95rem;
            }
            .features {
                gap: 1rem;
                margin-top: 3rem;
            }
            .feature {
                max-width: 100%;
                padding: 1.25rem;
            }
        }
        
        @media (max-width: 480px) {
            h1 {
                font-size: 2rem;
            }
            .tagline {
                font-size: 0.95rem;
            }
            .description {
                font-size: 0.85rem;
            }
            .logo {
                font-size: 0.75rem;
                margin-bottom: 1.5rem;
            }
            .btn {
                padding: 12px 25px;
                font-size: 0.9rem;
            }
            .feature {
                padding: 1rem;
            }
            .feature-icon {
                font-size: 1.5rem;
            }
            .feature-title {
                font-size: 0.9rem;
            }
            .feature-text {
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="container">
        <div class="content">
            <div class="logo">✦ VEENMODA ✦</div>
            
            <h1>Veenmoda Textile</h1>
            
            <p class="tagline">Premium Textile Management System</p>
            
            <p class="description">
                Revolutionizing textile industry with cutting-edge technology. 
                Manage your inventory, orders, and production seamlessly in one place.
            </p>
            
            <div class="buttons">
                <a href="{{ route('login') }}" class="btn btn-primary">
                    <span>→</span> Get Started
                </a>
            </div>

            <div class="features">
                <div class="feature">
                    <div class="feature-icon">📊</div>
                    <div class="feature-title">Smart Analytics</div>
                    <div class="feature-text">Real-time insights</div>
                </div>
                <div class="feature">
                    <div class="feature-icon">🚀</div>
                    <div class="feature-title">Fast & Reliable</div>
                    <div class="feature-text">Lightning speed</div>
                </div>
                <div class="feature">
                    <div class="feature-icon">🔒</div>
                    <div class="feature-title">Secure</div>
                    <div class="feature-text">Enterprise-grade</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>