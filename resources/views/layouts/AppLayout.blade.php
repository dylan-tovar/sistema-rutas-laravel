<!DOCTYPE html>
<html lang="en">
<head>
    <script>
        // Verificar el modo guardado en el almacenamiento local o las preferencias del sistema
        const theme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        if (theme === 'dark') {
            document.documentElement.classList.add('dark'); // Añade la clase `dark` antes de que se renderice la página
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'OptimizaRutas')</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,400,0,0&icon_names=dock_to_right" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    @stack('head')
</head>
<body class="flex h-screen bg-[#FAFAFA] text-pTxt dark:bg-[#18181a] dark:text-pTxtDark transition-all duration-800 ease-in-out p-4 pl-0" x-data="{ isAsideExpanded: true }">

    <!-- Contenedor principal -->
    <div class="flex h-full w-full">
        
        <!-- Aside -->
        <x-aside-content />

        <!-- Section -->
        <section 
            class="flex flex-col w-full h-full mx-auto transition-all duration-300 ease-in-out bg-white border border-accent shadow-md shadow-sText dark:bg-[#09090b] dark:border-bgDark dark:shadow-pTxt rounded-3xl" 
            :class="{ 'ml-2': !isAsideExpanded, '': isAsideExpanded }"
        >
        <header class="flex justify-between items-center w-full mx-auto p-5 px-7">
            <!-- Botón para alternar el menú y breadcrumbs -->
            <div class="flex items-center space-x-6">
                <!-- Botón para alternar el menú -->
                <button 
                @click="isAsideExpanded = !isAsideExpanded; isRotated = !isRotated" 
                class="focus:outline-none p-2 rounded-full flex items-center justify-center transition-transform duration-300"
                x-data="{ isRotated: false }"
            >
                <span 
                    class="material-symbols-outlined text-2xl transform transition-transform duration-800" 
                    :class="{ 'rotate-45': isRotated }"
                >
                    dock_to_right
                </span>
            </button>
            
            
                <!-- Breadcrumbs -->
                <div>
                    @if(isset($breadcrumbs))
                        <nav class="flex items-center space-x-1 text-sm" aria-label="Breadcrumb">
                            <ol class="flex items-center">
                                @foreach ($breadcrumbs as $breadcrumb)
                                    <li>
                                        @if ($breadcrumb['url'])
                                            <a href="{{ $breadcrumb['url'] }}" class="dark:text-flamingo-500 text-flamingo-400 hover:underline">
                                                {{ $breadcrumb['name'] }}
                                            </a>
                                        @else
                                            <span class="dark:text-gray-200">
                                                {{ $breadcrumb['name'] }}
                                            </span>
                                        @endif
                                    </li>
                                    @if (!$loop->last)
                                        <li>
                                            <span class="mx-2 dark:text-gray-400">/</span>
                                        </li>
                                    @endif
                                @endforeach
                            </ol>
                        </nav>
                    @endif
                </div>
            </div>
        
            <!-- Rol del usuario -->
            <div>
                @if (auth()->user()->hasRole('admin'))
                    <p class="text-white">Bienvenido, Administrador</p>
                @elseif (auth()->user()->hasRole('user'))
                    <p class="text-white">Bienvenido, Usuario</p>
                @elseif (auth()->user()->hasRole('driver'))
                    <p class="text-white">Bienvenido, Conductor</p>
                @endif
            </div>
        
            <!-- Botón de modo oscuro -->
            <x-dark-mode-toggle />
        </header>
        
            
            <main class="p-4">
                @yield('content')
            </main>
        </section>
    </div>
</body>

</html>
