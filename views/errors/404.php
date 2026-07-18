<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Page Not Found | <?= APP_NAME ?></title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Poppins:wght@700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #070512;
            color: rgba(240,230,255,0.75);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            background-image:
                radial-gradient(ellipse at 50% 50%, rgba(191,95,255,0.15) 0%, transparent 60%);
        }
        h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 8rem;
            font-weight: 800;
            line-height: 1;
            background: linear-gradient(135deg, #06FFA5 0%, #BF5FFF 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
            filter: drop-shadow(0 0 20px rgba(6,255,165,0.25));
        }
        .error-card {
            background: #130E28;
            border: 1px solid rgba(191,95,255,0.2);
            border-radius: 20px;
            padding: 3rem;
            max-width: 480px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.5);
            text-align: center;
        }
        .btn-home {
            background: linear-gradient(135deg, var(--neon-cyan) 0%, var(--neon-cyan-dark) 100%) !important;
            color: #060C14 !important;
            border: none !important;
            border-radius: 10px !important;
            padding: 0.75rem 1.8rem !important;
            font-weight: 600 !important;
            box-shadow: 0 4px 16px rgba(6,255,165,0.25) !important;
            transition: all 0.3s ease !important;
            text-decoration: none;
            display: inline-block;
        }
        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 24px rgba(6,255,165,0.4) !important;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center">
        <div class="error-card">
            <h1>404</h1>
            <h3 class="fw-bold text-white mb-3">Page Not Found</h3>
            <p class="mb-4" style="color:rgba(240,230,255,0.45); font-size:0.9rem; line-height:1.6;">
                The page you are looking for does not exist or has been relocated to another galaxy.
            </p>
            <a href="/dashboard" class="btn-home">
                Go Back Home
            </a>
        </div>
    </div>
</body>
</html>
