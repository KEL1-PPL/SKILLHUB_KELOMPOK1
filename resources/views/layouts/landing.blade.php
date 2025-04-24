<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillHub - Master Programming Skills</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        /* Navy Blue & White Color Scheme */
        :root {
            --navy-dark: #0F1C2E;
            --navy-medium: #1A2C3E;
            --navy-light: #2C3E50;
            --pure-white: #FFFFFF;
            --accent-blue: #3498DB;
            --text-light: #E0E6ED;
            --text-muted: #8899A6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background-color: var(--navy-dark);
            color: var(--text-light);
            line-height: 1.6;

        }

        /* Header Styling */
        header {
            background-color: var(--navy-medium);
            padding: 1rem 5%;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 2rem;
            font-weight: 700;
            color: var(--pure-white);
            text-decoration: none;
        }

        .logo span {
            color: var(--accent-blue);
        }


        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            border: 2px solid transparent;
        }

        .btn-login {
            background-color: transparent;
            color: var(--pure-white);
            border-color: var(--pure-white);
        }

        .btn-signup {
            background-color: var(--accent-blue);
            color: var(--pure-white);
        }

        .btn:hover {
            transform: translateY(-2px);
            opacity: 0.9;
        }

        /* Hero Section */
        .hero {
            margin-top: 80px;
            padding: 6rem 5%;
            min-height: 90vh;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, var(--navy-dark), var(--navy-medium));
        }

        .hero-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .hero-text h1 {
            font-size: 3.5rem;
            color: var(--pure-white);
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .hero-text p {
            font-size: 1.2rem;
            color: var(--text-muted);
            margin-bottom: 2.5rem;
        }

        .hero-image {
            background: url('https://source.unsplash.com/featured/?programming,coding') no-repeat center/cover;
            height: 500px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        /* Features Section */
        .features {
            background-color: var(--navy-medium);
            padding: 6rem 5%;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }

        .feature-card {
            background-color: var(--navy-light);
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-10px);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            margin-bottom: 1.5rem;
        }

        .feature-card h3 {
            color: var(--pure-white);
            margin-bottom: 1rem;
        }

        .feature-card p {
            color: var(--text-muted);
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .hero-content {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .hero-image {
                margin-top: 2rem;
                height: 300px;
            }
        }
    </style>
</head>

<body>
    <header>
        <a href="/" class="logo">Skill<span>Hub</span></a>
    </header>

    <div style="margin-top: 100px;">
        @yield('content')
    </div>
</body>

</html>
