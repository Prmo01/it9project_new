<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karl Grocery System</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #0E9F6E;
            --primary-dark: #057a52;
            --dark: #1b1b18;
            --light: #FDFDFC;
            --gray-100: #EDEDEC;
            --gray-200: #A1A09A;
            --gray-300: #706f6c;
            --gray-400: #3E3E3A;
            --gray-500: #62605b;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light);
            color: var(--dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            overflow-x: hidden;
        }
        
        .dark body {
            background-color: #0a0a0a;
            color: var(--gray-100);
        }
        
        .container {
            width: 100%;
            max-width: 1200px;
            padding: 0 1.5rem;
            margin: 0 auto;
        }
        
        header {
            width: 100%;
            padding: 1.5rem 0;
            position: relative;
        }
        
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .logo-icon {
            font-size: 1.75rem;
        }
        
        .nav-links {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }
        
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.875rem;
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: white;
            border: 1px solid var(--primary);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        
        .btn-outline {
            background-color: transparent;
            color: var(--dark);
            border: 1px solid rgba(25, 20, 0, 0.2);
        }
        
        .dark .btn-outline {
            color: var(--gray-100);
            border-color: var(--gray-400);
        }
        
        .btn-outline:hover {
            border-color: rgba(25, 20, 0, 0.3);
        }
        
        .dark .btn-outline:hover {
            border-color: var(--gray-500);
        }
        
        .hero {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 4rem 1rem;
            position: relative;
        }
        
        .hero::before, .hero::after {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            z-index: -1;
            filter: blur(100px);
            opacity: 0.1;
        }
        
        .hero::before {
            background-color: var(--primary);
            top: -300px;
            left: -200px;
        }
        
        .hero::after {
            background-color: #FFC53D;
            bottom: -300px;
            right: -200px;
        }
        
        .hero h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }
        
        .hero p {
            font-size: 1.125rem;
            color: var(--gray-300);
            margin-bottom: 2.5rem;
            max-width: 600px;
        }
        
        .dark .hero p {
            color: var(--gray-200);
        }
        
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin: 4rem 0;
        }
        
        .feature-card {
            background-color: white;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem;
        }
        
        .dark .feature-card {
            background-color: rgba(255, 255, 255, 0.05);
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }
        
        .feature-icon {
            width: 60px;
            height: 60px;
            background-color: rgba(14, 159, 110, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            color: var(--primary);
        }
        
        .feature-card h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .feature-card p {
            font-size: 0.9rem;
            color: var(--gray-300);
            text-align: center;
        }
        
        .dark .feature-card p {
            color: var(--gray-200);
        }
        
        .cta {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .bg-shape {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 250px;
            background-color: rgba(14, 159, 110, 0.03);
            clip-path: polygon(0 100%, 100% 50%, 100% 100%, 0% 100%);
            z-index: -2;
        }
        
        footer {
            margin-top: auto;
            width: 100%;
            padding: 2rem 0;
            text-align: center;
            font-size: 0.875rem;
            color: var(--gray-300);
        }
        
        .dark footer {
            color: var(--gray-400);
        }
        
        @media (min-width: 768px) {
            .hero h1 {
                font-size: 3.5rem;
            }
            
            .hero p {
                font-size: 1.25rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <nav>
                <div class="logo">
                    <span class="logo-icon">🛒</span>
                    <span>Karl Grocery</span>
                </div>
                <div class="nav-links">
                    <a href="{{ route('login') }}" class="btn btn-outline">Log In</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Sign Up</a>
                </div>
            </nav>
        </header>
        
        <main>
            <section class="hero">
                <h1>Welcome to Karl Grocery System</h1>
                <p>Your one-stop solution for all your grocery needs. Fresh, fast, and reliable delivery to your doorstep.</p>
                
                <div class="cta">
                    <a href="{{ route('login') }}" class="btn btn-primary">Get Started</a>
                    <a href="{{ route('register') }}" class="btn btn-outline">Create Account</a>
                </div>
                
                <div class="features">
                    <div class="feature-card">
                        <div class="feature-icon">🥕</div>
                        <h3>Fresh Products</h3>
                        <p>We source the freshest fruits, vegetables, and products directly from local farmers and trusted suppliers.</p>
                    </div>
                    
                 
                    
                    <div class="feature-card">
                        <div class="feature-icon">💸</div>
                        <h3>Best Prices</h3>
                        <p>Competitive prices and regular deals to ensure you get the best value for your money.</p>
                    </div>
                </div>
            </section>
        </main>
        
        <footer>
            <!--<p>&copy; 2025 Karl Grocery System. All rights reserved.</p>-->
        </footer>
    </div>
    
    <div class="bg-shape"></div>
</body>
</html>