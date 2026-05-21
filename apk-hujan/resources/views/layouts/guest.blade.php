<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.8s ease-out forwards',
                        'float': 'float 3s ease-in-out infinite',
                        'blob': 'blob 15s infinite alternate ease-in-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(50px, -70px) scale(1.2)' },
                            '66%': { transform: 'translate(-40px, 40px) scale(0.8)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        }
                    }
                }
            }
        }
    </script>

    <!-- Animate.css for quick soft animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <!-- Alpine.js CDN -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        [x-cloak] { display: none !important; }
        
        body {
            background-color: #F8FAFC; /* Slate 50 base for better blending */
            overflow-x: hidden;
        }

        .animate-delay-100 { animation-delay: 100ms; }
        .animate-delay-200 { animation-delay: 200ms; }
        .animate-delay-300 { animation-delay: 300ms; }
        .animate-delay-500 { animation-delay: 500ms; }
        .animate-delay-2000 { animation-delay: 2s; }
        .animate-delay-4000 { animation-delay: 4s; }
    </style>
</head>
<body class="font-sans antialiased text-slate-900 min-h-screen relative">
    
    <!-- Aurora Animated Background (Fixed and Enhanced) -->
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div class="absolute -top-20 -left-20 w-[30rem] h-[30rem] md:w-[45rem] md:h-[45rem] bg-blue-300 rounded-full mix-blend-multiply filter blur-[80px] opacity-50 animate-blob"></div>
        <div class="absolute top-1/4 -right-20 w-[30rem] h-[30rem] md:w-[45rem] md:h-[45rem] bg-sky-400 rounded-full mix-blend-multiply filter blur-[80px] opacity-40 animate-blob animate-delay-2000"></div>
        <div class="absolute -bottom-40 left-1/4 w-[30rem] h-[30rem] md:w-[45rem] md:h-[45rem] bg-indigo-300 rounded-full mix-blend-multiply filter blur-[80px] opacity-50 animate-blob animate-delay-4000"></div>
    </div>

    <div class="animate__animated animate__fadeIn relative z-10">
        @yield('content')
        
        @if(isset($slot))
            {{ $slot }}
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
</body>
</html>
