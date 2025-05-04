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
        <div class="auth-buttons">
            <a href="/login" class="btn btn-login">Login</a>
            <a href="/register" class="btn btn-signup">Sign Up</a>
        </div>
    </header>

    <section class="hero">
        <div class="hero-content">
            <div class="hero-text">
                <h1>Master Programming Skills Online</h1>
                <p>Learn cutting-edge technologies from industry experts. Transform your career with comprehensive
                    coding courses.</p>
                <div class="button-group">
                    <a href="/browse-courses" class="btn btn-signup">Browse Courses</a>
                </div>
            </div>
            <div class="hero-image"></div>
        </div>
    </section>

    <section class="features">
        <div class="features-content">
            <div class="features-grid">
                <div class="feature-card">
                    <img src="https://img.icons8.com/color/96/code.png" alt="Expert-Led" class="feature-icon">
                    <h3>Expert-Led Curriculum</h3>
                    <p>Learn from industry professionals with real-world software development experience.</p>
                </div>
                <div class="feature-card">
                    <img src="https://img.icons8.com/color/96/laptop-coding.png" alt="Hands-on" class="feature-icon">
                    <h3>Hands-on Projects</h3>
                    <p>Build real-world applications and gain practical skills that employers value.</p>
                </div>
                <div class="feature-card">
                    <img src="https://img.icons8.com/color/96/student-center.png" alt="Community" class="feature-icon">
                    <h3>Collaborative Learning</h3>
                    <p>Join a supportive community of learners and grow together.</p>
                </div>
            </div>
        </div>
    </section>
    <section class="facility">
        <div class="h-[120vh] bg-skillhub-dark py-20 px-14 d-flex items-center justify-center">
            <div class="grid grid-cols-2 ">
                <div class="col-span-1 content-center">
                    <img src="{{ asset('image/code.png') }}" width="500" height="400">
                </div>
                <div class="col-span-1 ">
                    <h1 class="text-[3rem] mb-10">Facilities <span class="text-skillhub-logo-blue">available</span>
                    </h1>
                    <span class="text-xl">What you will get when you join this program</span>
                    <ol class="px-1 py-6 list-none space-y-5">
                        <li class="text-base"><i class="bi bi-check-square me-3 text-skillhub-logo-blue"></i> Access to
                            all premium courses</li>
                        <li class="text-base"><i class="bi bi-check-square me-3 text-skillhub-logo-blue"></i> Lifetime
                            access to learning materials</li>
                        <li class="text-base"><i class="bi bi-check-square me-3 text-skillhub-logo-blue"></i>
                            Interactive quizzes & assignments</li>
                        <li class="text-base"><i class="bi bi-check-square me-3 text-skillhub-logo-blue"></i>
                            Certificate of completion</li>
                        <li class="text-base"><i class="bi bi-check-square me-3 text-skillhub-logo-blue"></i>
                            Personalized mentor guidance</li>
                        <li class="text-base"><i class="bi bi-check-square me-3 text-skillhub-logo-blue"></i> Access to
                            student discussion forum</li>
                        <li class="text-base"><i class="bi bi-check-square me-3 text-skillhub-logo-blue"></i> Exclusive
                            webinars and workshops</li>
                    </ol>
                </div>
            </div>

        </div>
    </section>
    <section class="learning ">
        <div class="h-[140vh] bg-skillhub-medium py-10 px-14">
            <h1 class="text-[3rem] text-center pb-10">Learning <span class="text-skillhub-logo-blue">path</span></h1>
            <div class="grid grid-cols-3 gap-4">
                <div
                    class="col-span-1 h-[80vh] bg-skillhub-dark rounded-t-lg shadow-lg hover:-translate-y-2 transition-transform duration-300 ease-in-out">
                    <img src="{{ asset('image/learning/web.jpg') }}" alt="web" class="rounded-t-lg" height="300"
                        width="500">
                    <div class="px-3 pt-3">
                        <p class="text-[1.5rem]">Web development</p>
                        <p class="break-all">The Web Developer learning path is designed step-by-step to guide you from
                            beginner to advanced level. Starting with the basics of <span
                                class="text-skillhub-logo-blue">HTML</span>, <span
                                class="text-skillhub-logo-blue">CSS</span>, and <span
                                class="text-skillhub-logo-blue">Javascript</span>.</p>

                    </div>
                    <div class="pt-6 px-3">
                        <div class="grid grid-cols-10 gap-2 text-blue-950">
                            <div class="col-span-2 p-1 border text-center rounded-full bg-red-400">
                                Laravel
                            </div>
                            <div class="col-span-2 p-1 border text-center rounded-full bg-purple-400">
                                Php
                            </div>
                            <div class="col-span-3 p-1 border text-center rounded-full bg-orange-300">
                                Javascript
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="col-span-1 h-[80vh] bg-skillhub-dark rounded-t-lg shadow-lg hover:-translate-y-2 transition-transform duration-300 ease-in-out">
                    <img src="{{ asset('image/learning/mobile.png') }}" alt="web" class="rounded-t-lg" height="400"
                        width="500">
                    <div class="px-3 pt-3">
                        <p class="text-[1.5rem]">Mobile development</p>
                        <p class="break-all">The Mobile Developer learning path is crafted to help you master the skills
                            needed to build high-quality mobile applications. You’ll start with the fundamentals of
                            programming using languages like <span class="text-skillhub-logo-blue">Java</span>, <span
                                class="text-skillhub-logo-blue">Kotlin</span>, and <span
                                class="text-skillhub-logo-blue">Flutter</span>.</p>
                    </div>
                    <div class="pt-6 px-3">
                        <div class="grid grid-cols-10 gap-2 text-blue-950">
                            <div class="col-span-2 p-1 border text-center rounded-full bg-orange-400">
                                Java
                            </div>
                            <div class="col-span-2 p-1 border text-center rounded-full bg-purple-900">
                                kotlin
                            </div>
                            <div class="col-span-3 p-1 border text-center rounded-full bg-blue-400">
                                flutter
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="col-span-1 h-[80vh] bg-skillhub-dark rounded-t-lg shadow-lg hover:-translate-y-2 transition-transform duration-300 ease-in-out">
                    <img src="{{ asset('image/learning/sains.jpeg') }}" alt="web" class="rounded-t-lg" height="400"
                        width="500">
                    <div class="px-3 pt-3">
                        <p class="text-[1.5rem]">Data Science</p>
                        <p class="break-all">The Data Science learning path is designed to equip you with the knowledge
                            and tools needed to extract insights from data. You'll begin with foundational skills in
                            <span class="text-skillhub-logo-blue">Python</span> programming, statistics, and data
                            analysis.
                        </p>
                    </div>
                    <div class="pt-10 px-3">
                        <div class="grid grid-cols-10 gap-2 text-blue-950">
                            <div class="col-span-2 p-1 border text-center rounded-full bg-green-400">
                                Python
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="lets-go">
        <div class=" bg-skillhub-dark py-10 px-14">
            <h1 class="text-[3rem] text-center pb-10">
                Get started with Skill<span class="text-skillhub-logo-blue">Hub!</span>
            </h1>

            <div class="grid grid-cols-2 grid-rows-4 gap-4 mt-10 ">
                <div class="col-span-1 row-span-1 flex items-center justify-center ">
                    <span class="text-[1.3rem] text-center max-w-xl">
                        Learn as much as you want, with no time limits. Pay once, and enjoy lifetime access. All
                        materials are regularly updated to keep up with industry trends.
                    </span>
                </div>

                <div class="col-span-1 row-span-1 flex items-center justify-center h-[400px]">
                    <div class="relative w-full h-full">
                        <img src="{{ asset('image/fitur/class.jpg') }}" alt="learn"
                            class="rounded-xl shadow-xl w-full h-full object-cover" />
                        <div class="absolute inset-0 bg-gradient-to-l from-transparent to-skillhub-dark rounded-xl">
                        </div>
                    </div>
                </div>

                <div class="col-span-1 row-span-1 flex items-center justify-center h-[400px]">
                    <div class="relative w-full h-full">
                        <img src="{{ asset('image/fitur/online.jpg') }}" alt="learn"
                            class="rounded-xl shadow-xl w-full h-full object-cover" />
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent to-skillhub-dark rounded-xl">
                        </div>
                    </div>
                </div>

                <div class="col-span-1 row-span-1 flex items-center justify-center">
                    <span class="text-[1.3rem] text-center max-w-xl">
                        The learning experience at SkillHub is designed to be enjoyable and never boring. With
                        interactive videos, easy-to-follow explanations, and real-world examples, you'll love learning
                        for hours without even realizing it.
                    </span>
                </div>

                <div class="col-span-1 row-span-1 flex items-center justify-center h-[400px]">
                    <span class="text-[1.3rem] text-center max-w-xl">
                        Need help? Join our online consultation sessions or ask questions anytime in the class forum—our
                        mentors and peers are always ready to support you!
                    </span>
                </div>

                <div class="col-span-1 row-span-1 flex items-center justify-center">
                    <div class="relative w-full h-full">
                        <img src="{{ asset('image/fitur/zoom.jpg') }}" alt="learn"
                            class="rounded-xl shadow-xl w-full h-full object-cover" />
                        <div class="absolute inset-0 bg-gradient-to-l from-transparent to-skillhub-dark rounded-xl">
                        </div>
                    </div>

                </div>

                <div class="col-span-1 row-span-1 flex items-center justify-center h-[400px]">
                    <div class="relative w-full h-full">
                        <img src="{{ asset('image/fitur/sertifikat.jpg') }}" alt="learn"
                            class="rounded-xl shadow-xl w-full h-full object-cover" />
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent to-skillhub-dark rounded-xl">
                        </div>
                    </div>
                </div>

                <div class="col-span-1 row-span-1 flex items-center justify-center">
                    <span class="text-[1.3rem] text-center max-w-xl">
                        Show what you've learned! Get professionally assessed and earn a certificate to boost your
                        career.
                    </span>


                </div>


            </div>
        </div>

    </section>

    <section class="keuntungan">
        <div class="h-screen bg-skillhub-dark py-10 px-14">
            <h1 class="text-[3rem] text-center pb-10">
                What are the <span class="text-skillhub-logo-blue">benefits?</span>
            </h1>
            <div class="grid grid-cols-3">
                <div class="grid grid-rows-5 col-span-1 gap-5">
                    <div id="benefit1"
                        class="row-span-1 bg-skillhub-medium shadow rounded-xl p-4 hover:-translate-y-2 transition-transform duration-300 ease-in-out hover:bg-skillhub-logo-blue cursor-pointer">
                        Learn directly from industry experts
                    </div>
                    <div id="benefit2"
                        class="row-span-1 bg-skillhub-medium shadow rounded-xl p-4 hover:-translate-y-2 transition-transform duration-300 ease-in-out hover:bg-skillhub-logo-blue cursor-pointer">
                        Structured and up-to-date materials
                    </div>
                    <div id="benefit3"
                        class="row-span-1 bg-skillhub-medium shadow rounded-xl p-4 hover:-translate-y-2 transition-transform duration-300 ease-in-out hover:bg-skillhub-logo-blue cursor-pointer">
                        Direct consultation with mentors
                    </div>
                    <div id="benefit4"
                        class="row-span-1 bg-skillhub-medium shadow rounded-xl p-4 hover:-translate-y-2 transition-transform duration-300 ease-in-out hover:bg-skillhub-logo-blue cursor-pointer">
                        Lifetime access to learning videos
                    </div>
                    <div id="benefit5"
                        class="row-span-1 bg-skillhub-medium shadow rounded-xl p-4 hover:-translate-y-2 transition-transform duration-300 ease-in-out hover:bg-skillhub-logo-blue cursor-pointer">
                        Certification & learning evaluation
                    </div>
                </div>
                <div class="grid grid-rows-1 col-span-2">
                    <div class="row-span-1 px-20" >
                        <div id="benefit-content-1">
                            <p class="text-center">Gain insights from experienced mentors who are active professionals in
                                their fields.</p>
                                <img src="{{ asset('image/benefit/benefit1.jpg') }}" alt="learn"
                                    class="rounded-xl shadow-xl w-[100%] h-[300px] object-cover" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class=" bg-skillhub-medium py-10 px-14">
            <div class="grid grid-rows-5 px-14 ">
                <div class="grid grid-cols-3 row-span-4 gap-4">
                    <div class="col-span-1">
                        <div class="flex flex-col items-start">
                            <a href="/" class="logo">Skill<span>Hub</span></a>
                            <span>Discover the right class with the perfect mentor — only on SkillHub. Learn new skills, grow your career, and unlock your full potential!</span>

                        </div>
                    </div>
                    <div class="col-span-1 ">
                        <div class="flex flex-col items-str">
                            <h1 class="text-2xl font-bold mb-4">Kontak</h1>
                            <div><i class="bi bi-envelope-fill me-2 text-primary"></i> skillhub@gmail.com</div>

                            <!-- WhatsApp -->
                            <div><i class="bi bi-whatsapp me-2 text-success"></i> +62 8xx-xxxx-xxxx</div>

                            <!-- Lokasi -->
                            <div><i class="bi bi-geo-alt-fill me-2 text-danger"></i> Bandung, Indonesia</div>
                        </div>
                    </div>
                    <div class="col-span-1 ">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63358.17909990718!2d107.56075555!3d-6.90344995!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e7d3c7d92b75%3A0x301e8f1fc28b9c0!2sBandung%2C%20Kota%20Bandung%2C%20Jawa%20Barat!5e0!3m2!1sid!2sid!4v1713700971877!5m2!1sid!2sid" width="400" height="200" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
                <div class="row-span-1 flex flex-col justify-end h-full">
                    <span>© 2025 SkillHub. All rights reserved.</span>
                </div>
            </div>
        </div>
    </section>
</body>

</html>
