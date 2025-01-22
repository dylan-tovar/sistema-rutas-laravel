<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Optimización de Rutas</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
   
</head>
<body class="transition-all duration-500 ease-in-out bg-white text-pTxt dark:bg-bgDark dark:text-pTxtDark">
    <header class="fixed top-0 left-0 w-full mx-auto flex justify-between shadow dark:shadow-pTxt items-center py-3 px-24 bg-white dark:bg-bgDark z-50 ">
        <a href="/" class="text-2xl font-bold flex items-center">
            <!-- SVG Logo -->
            <svg id="logo-54" class="h-8 w-auto flex absolute" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 170 41" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M20.6841 40.138C31.7298 40.138 40.6841 31.1837 40.6841 20.138C40.6841 9.09234 31.7298 0.138031 20.6841 0.138031C9.63837 0.138031 0.684082 9.09234 0.684082 20.138C0.684082 31.1837 9.63837 40.138 20.6841 40.138ZM26.9234 9.45487C27.2271 8.37608 26.1802 7.73816 25.2241 8.41933L11.8772 17.9276C10.8403 18.6663 11.0034 20.138 12.1222 20.138L15.6368 20.138V20.1108H22.4866L16.9053 22.0801L14.4448 30.8212C14.1411 31.9 15.1879 32.5379 16.1441 31.8567L29.491 22.3485C30.5279 21.6098 30.3647 20.138 29.246 20.138L23.9162 20.138L26.9234 9.45487Z" class="ccustom" fill="#eb5b38"></path>
            </svg>
        
            <!-- Text -->
            <span class="ml-11">OptimizaRutas</span>
        </a>
        
        
        
        <nav class="flex space-x-4 items-center">
            <x-dark-mode-toggle />
            <a href="#como-funciona" class="text-sm text-sText dark:text-sTextDark dark:hover:text-flamingo-500 hover:text-flamingo-400">¿Cómo funciona?</a>
            <a href={{ url('/login')}} class="hover:text-flamingo-400 dark:hover:text-flamingo-500">Iniciar Sesión</a>
            <a href={{ url('/register') }} class="px-6 py-1.5 border rounded-lg border-flamingo-400 bg-flamingo-500 text-white  dark:border-flamingo-500 hover:scale-105 transition duration-300">Registrarse</a>
        </nav>
    </header>
 
    <main class="mx-auto max-w-7xl mt-16">

        {{-- Hero Section --}}
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center py-16 px-6">
            <!-- Left: Texto -->
            <div class="max-w-xl mx-auto lg:mx-0 text-center lg:text-left">
                <h2 class="text-5xl font-bold mb-3 leading-tight">Optimiza tu Logística</h2>
                <p class="pt-1 text-2xl font-semibold text-flamingo-500 dark:text-flamingo-400 mb-4">
                    Simplifica la gestión de rutas y vehículos
                </p>
                <p class="text-lg text-sText dark:text-sTextDark leading-tight mb-8">
                    Nuestra plataforma de optimización de rutas ayuda a reducir costos operativos, agilizar la entrega de pedidos y mejorar la eficiencia de tu flota de vehículos. Todo en un solo lugar.
                </p>
                <dl class="space-y-5">
                    <div class="flex items-start space-x-4">
                        <i class="material-icons text-flamingo-500 dark:text-flamingo-400">settings</i>
                        <div>
                            <dt class="text-lg text-letters font-semibold">Optimización Automática</dt>
                            <dd class="text-sText dark:text-sTextDark">
                                Genera rutas eficientes en segundos basadas en tus necesidades específicas.
                            </dd>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <i class="material-icons text-flamingo-500 dark:text-flamingo-400">local_shipping</i>
                        <div>
                            <dt class="text-lg font-semibold">Gestión de Vehículos</dt>
                            <dd class="text-sText dark:text-sTextDark">
                                Asigna y gestiona tu flota de vehículos de manera inteligente y automática.
                            </dd>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <i class="material-icons text-flamingo-500 dark:text-flamingo-400">shopping_cart</i>
                        <div>
                            <dt class="text-lg font-semibold">Pedidos Centralizados</dt>
                            <dd class="text-sText dark:text-sTextDark">
                                Controla y organiza todos tus pedidos en una sola plataforma.
                            </dd>
                        </div>
                    </div>
                </dl>
            </div>
        
            <!-- Right: Imagen -->
            <div class="relative">
                <!-- Contenedor de la imagen -->
                <div class="bg-flamingo-50 dark:bg-bgDark rounded-xl p-6 shadow-lg">
                    <!-- Imagen para modo claro -->
                    <img src="https://images.unsplash.com/photo-1731424377028-e825bcdec909?q=80&w=3540&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" 
                         alt="Optimización en acción" 
                         class="w-full max-w-xl mx-auto rounded-lg shadow-xl ring-1 ring-gray-400/10 dark:hidden">
        
                    <!-- Imagen para modo oscuro -->
                    <img src="https://images.unsplash.com/photo-1684313363318-b86012a45d9a?q=80&w=3432&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" 
                         alt="Optimización en acción (oscuro)" 
                         class="w-full max-w-xl mx-auto rounded-lg shadow-xl ring-1 ring-gray-400/10 hidden dark:block">
        
                    <!-- Efecto animado -->
                    <div class="absolute inset-0 bg-gradient-to-r from-flamingo-500 via-flamingo-300 to-flamingo-500 opacity-25 rounded-xl blur-xl -z-10"></div>
                </div>
            </div>
        </section>
        

        {{-- Cómo Funciona --}}

        <section id="como-funciona" class="py-16 text-center">
            <div class="mb-10 text-xs border-b border-accent dark:border-pTxt "></div>
            <h2 class="text-4xl font-bold mb-6">¿Cómo funciona?</h2>
            <p class="text-lg mb-8">
                En tres simples pasos, nuestra plataforma optimiza todo el proceso de asignación de vehículos y distribución de pedidos.
            </p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach([
                    ['title' => '1. Ingreso de Pedidos', 'text' => 'Ingresa los pedidos en el sistema, indicando las ubicaciones de entrega, prioridades y restricciones de tiempo.'],
                    ['title' => '2. Asignación de Vehículos', 'text' => 'Selecciona o deja que el sistema asigne más vehículos más adecuados para cada ruta.'],
                    ['title' => '3. Optimización de Rutas', 'text' => 'Con el sistema de asignación de vehículos, optimiza las rutas de entrega de tus pedidos.']
                ] as $step)
                    <div class="p-6 bg-white dark:bg-[#18181b] rounded-lg shadow-md dark:shadow-pTxt">
                        <h3 class="text-2xl text-flamingo-500 dark:text-flamingo-400 font-semibold mb-4">{{ $step['title'] }}</h3>
                        <p class="text-sText dark:text-sTextDark">{{ $step['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- CTA --}}
        <section class="py-16 border border-accent dark:border-sText rounded-t-lg text-center pb-20">
            <h2 class="text-4xl font-bold  mb-6">¿Aún no te has registrado?</h2>
            <p class="text-lg text-sText dark:text-sTextDark mb-8">
                Regístrate ahora y descubre cómo nuestra plataforma puede mejorar tus operaciones logísticas.
            </p>
            <a href="" class="px-6 py-3 border rounded-lg border-flamingo-600 text-flamingo-600  hover:bg-flamingo-500 hover:text-white  dark:border-flamingo-400 dark:text-white dark:hover:bg-flamingo-400 dark:hover:text-white  transition duration-300">
                Regístrate ahora
            </a>
        </section>
    </main>

    <footer class="bg-flamingo-400 dark:bg-flamingo-500 text-white py-6 text-center">
        <p>&copy; {{ now()->year }} OptimizaRutas. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
