<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    @vite('resources/css/app.css')
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
</head>

<body class="flex h-screen items-center justify-center bg-gray-100 gap-8 text-pTxt dark:bg-bgDark dark:text-pTxtDark transition-all duration-500 ease-in-out">
    <x-dark-mode-toggle />
    <div class="bg-white dark:bg-[#18181b] flex rounded-2xl shadow-lg dark:shadow-pTxt max-w-5xl p-8 gap-10">
        <!-- Formulario de Registro -->
        <div class="w-1/2 items-center mt-12 "> 
            <h2 class="font-bold text-4xl">Iniciar Sesión</h2>
            <p class="text-md mt-3 text-sText dark:text-sTextDark"></p>

            <form action="{{ route('login') }}" method="POST" class="mt-6 flex flex-col gap-4">
                @csrf
                <!-- Correo Electrónico -->
                <div>
                    <label for="email" class="text-sm font-semibold text-pTxt dark:text-pTxtDark">Correo Electrónico</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="{{ old('email') }}"
                        required
                        class="mt-2 w-full border border-accent rounded-xl p-3 focus:ring-2 focus:ring-flamingo-500 hover:border-flamingo-500 dark:bg-[#333333] dark:border-sTextDark" />
                    @error('email')
                        <span class="text-red-500 text-xs flex items-center mt-1 ml-2"><i class="material-icons mr-1" style="font-size: 15px;">error</i>  {{ $message }}</span>
                    @enderror
                </div>
            
                <!-- Contraseña -->
                <div>
                    <label for="password" class="text-sm font-semibold text-pTxt dark:text-pTxtDark">Contraseña</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        required
                        class="mt-2 w-full border border-accent rounded-xl p-3 focus:ring-2 focus:ring-flamingo-500 hover:border-flamingo-500 dark:bg-[#333333] dark:border-sTextDark" />
                    @error('password')
                        <span class="text-red-500 text-xs flex items-center mt-1 ml-2"><i class="material-icons mr-1" style="font-size: 15px;">error</i>  {{ $message }}</span>
                    @enderror
                </div>
            
                <!-- Botón de Login -->
                <button type="submit"
                        class="w-full bg-flamingo-500 text-white rounded-md p-3 mt-5 hover:scale-105 transition-transform duration-300">
                    Iniciar Sesión
                </button>
            </form>
            

            <div class="mt-6 text-xs border-b border-accent dark:border-pTxt"></div>
            <div class="mt-4 text-sm flex justify-between items-center">
                <span class="px-2 py-1  text-sText dark:text-sTextDark">¿No tienes una cuenta?</span>
                <a href={{url('/register')}}>
                    <button class="py-2 px-5 bg-white border border-accent rounded-xl transition duration-300 hover:border-flamingo-500 hover:scale-105 dark:bg-[#333333] dark:border-sTextDark dark:hover:border-flamingo-500">
                        Registrate
                    </button>
                </a>
            </div>
        </div>

        <!-- Imagen -->
        <div class="w-1/2 flex justify-center items-center">
            <img src="https://plus.unsplash.com/premium_photo-1679168116815-e1c85358c4f5?q=80&w=3387&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                alt="Imagen de inicio" class="max-w-full h-auto rounded-lg shadow-md" />
        </div>
    </div>
</body>

</html>
