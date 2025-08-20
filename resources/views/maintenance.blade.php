<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Приложение на обслуживании</title>
    <style>
        :root {
            --primary: #ff6b6b;
            --dark: #1a1a2e;
            --darker: #0f0f1a;
        }

        @font-face {
            font-family: "v-sans";
            font-weight: 500;
            src: url("/fonts/Golos-Text_Medium.woff2");
        }

        @font-face {
            font-family: "v-sans";
            font-weight: 600;
            src: url("/fonts/Golos-Text_DemiBold.woff2");
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'v-sans';
            background: var(--darker);
            color: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                radial-gradient(circle at 20% 30%, rgba(255, 107, 107, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(86, 171, 255, 0.1) 0%, transparent 50%);
            z-index: -1;
        }

        .container {
            text-align: center;
            z-index: 1;
            padding: 40px;
            background: rgba(26, 26, 46, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            max-width: 500px;
            margin: 20px;
        }

        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            animation: float 3s ease-in-out infinite;
        }

        h1 {
            font-size: 2.2rem;
            margin-bottom: 15px;
            background: linear-gradient(45deg, var(--primary), #56abff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        p {
            color: #a0a0c0;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .status {
            display: inline-block;
            padding: 8px 16px;
            background: rgba(255, 107, 107, 0.2);
            color: var(--primary);
            border-radius: 20px;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }

        .loader {
            width: 100%;
            height: 4px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 2px;
            overflow: hidden;
            margin: 20px 0;
        }

        .loader-bar {
            height: 100%;
            width: 40%;
            background: linear-gradient(90deg, var(--primary), #56abff);
            border-radius: 2px;
            animation: loading 2s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        @keyframes loading {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(250%); }
        }

        .social {
            margin-top: 30px;
        }

        .social a {
            color: #a0a0c0;
            text-decoration: none;
            margin: 0 10px;
            transition: color 0.3s;
        }

        .social a:hover {
            color: var(--primary);
        }
    </style>
</head>
    <body>
        <div class="background"></div>

        <div class="container">
            <div class="logo">⚡</div>
            <h1>Обновление системы</h1>
            <p>Мы работаем над улучшением производительности и добавлением новых функций.</p>

            <div class="status">Ожидаемое время восстановления: {{ $retryAfter ?? 'менее часа' }}</div>

            <div class="loader">
                <div class="loader-bar"></div>
            </div>

            <p>Спасибо за ваше терпение!</p>

            <div class="social">
                <a href="https://aokb28.su" target="_blank">Наш сайт</a> •
                <a href="https://t.me/gauz_ao_aokb" target="_blank">Telegram</a> •
                <a href="https://vk.com/gauz_ao_aokb" target="_blank">ВКонтакте</a>
            </div>
        </div>
    </body>
</html>
