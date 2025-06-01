<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skill Hub</title>

    <!-- Bootstrap CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Base Styles -->
    <style>
        :root {
            --pastel-blue: #B8E2F2;
            --light-pastel-blue: #E3F2F9;
            --pastel-orange: #FFD8B8;
            --light-pastel-orange: #FFE8D6;
            --deep-pastel-blue: #89CFF0;
            --deep-pastel-orange: #FFB347;
            --text-dark: #2A2A2A;
            --text-gray: #666666;
            --pure-white: #FFFFFF;
        }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, var(--light-pastel-blue), var(--light-pastel-orange));
            min-height: 100vh;
            color: var(--text-dark);
            line-height: 1.6;
        }

        .main-header {
            background-color: var(--pure-white);
            padding: 1rem 5%;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-dark);
            text-decoration: none;
        }

        .logo span {
            color: var(--deep-pastel-blue);
        }

        .main-content {
            margin-top: 80px;
            min-height: calc(100vh - 160px);
            padding: 2rem 5%;
        }

        .main-footer {
            background-color: var(--light-pastel-blue);
            padding: 1.5rem 5%;
            text-align: center;
        }

        .footer-content {
            max-width: 1400px;
            margin: 0 auto;
            color: var(--text-dark);
        }

        .footer-content a {
            color: var(--deep-pastel-blue);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .footer-content a:hover {
            color: var(--deep-pastel-orange);
        }

        @media (max-width: 768px) {
            .main-header {
                padding: 1rem;
            }

            .logo {
                font-size: 1.5rem;
            }

            .main-content {
                padding: 1rem;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <header class="main-header">
        <div class="header-content">
            <a href="/" class="logo">Skill.<span>Hub</span></a>
        </div>
    </header>

    <main class="main-content container">
        @yield('content')
    </main>

    <footer class="main-footer">
        <div class="footer-content">
            <p>&copy; 2025 Skill.Hub. All rights reserved. <a href="#contact">Contact Us</a></p>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle (CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Smooth Scroll -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
