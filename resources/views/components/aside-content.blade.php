
    <!-- Very little is needed to make a happy life. - Marcus Aurelius -->
<aside 
    class="transition-all duration-300 ease-in-out p-2 px-2  "
    :class="{ 'w-14 border-r border-gray-300 dark:border-pTxt': !isAsideExpanded, 'w-60': isAsideExpanded }"
    >
    <nav class="flex flex-col h-full py-2">
        <ul class="space-y-1">
            <!-- Logo o título -->
            <li>
                <a href="#" 
                   class="flex items-center py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-[#333] transition-all duration-300"
                   :class="{ 'px-1': !isAsideExpanded, 'px-2': isAsideExpanded }"
                    >
                    <svg id="logo-54" class="h-8 w-auto flex absolute" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 170 41" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M20.6841 40.138C31.7298 40.138 40.6841 31.1837 40.6841 20.138C40.6841 9.09234 31.7298 0.138031 20.6841 0.138031C9.63837 0.138031 0.684082 9.09234 0.684082 20.138C0.684082 31.1837 9.63837 40.138 20.6841 40.138ZM26.9234 9.45487C27.2271 8.37608 26.1802 7.73816 25.2241 8.41933L11.8772 17.9276C10.8403 18.6663 11.0034 20.138 12.1222 20.138L15.6368 20.138V20.1108H22.4866L16.9053 22.0801L14.4448 30.8212C14.1411 31.9 15.1879 32.5379 16.1441 31.8567L29.491 22.3485C30.5279 21.6098 30.3647 20.138 29.246 20.138L23.9162 20.138L26.9234 9.45487Z" class="ccustom" fill="#eb5b38"></path>
                    </svg>
                    <span 
                        class="font-bold ml-10 text-lg text-flamingo-500 transition-opacity duration-300" 
                        :class="{ 'opacity-0': !isAsideExpanded, 'opacity-100': isAsideExpanded }"
                        >
                        OptimizaRutas
                    </span>
                </a>
            </li>
            <!-- Ítems de navegación -->
            {{-- ADIMIN CONTENT --}}
            @if (auth()->user()->hasRole('admin'))
                <li>
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-[#333] transition-all duration-300"
                       :class="{ 'px-2': !isAsideExpanded, 'px-4' : isAsideExpanded }"
                       >
                        <i class="material-icons mr-2" style="font-size: 20px">home</i>
                        <span 
                            class="transition-opacity duration-300 text-sm" 
                            :class="{ 'opacity-0': !isAsideExpanded, 'opacity-100': isAsideExpanded }"
                            >
                            Inicio
                        </span>
                    </a>
                </li>
                <li x-data="{ isOpen: false }" class="relative">
                    <a href="#" 
                       @click="isAsideExpanded = true; isOpen = !isOpen" 
                       class="flex items-center justify-between py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-[#333] transition-all duration-300"
                       :class="{ 'px-2': !isAsideExpanded, 'px-4': isAsideExpanded }"
                    >
                        <div class="flex items-center">
                            <i class="material-icons mr-2" style="font-size: 22px">manage_accounts</i>
                            <span 
                                class="transition-opacity duration-300 text-sm" 
                                :class="{ 'opacity-0': !isAsideExpanded, 'opacity-100': isAsideExpanded }"
                            >
                                Gestión
                            </span>
                        </div>
                        <i 
                            class="material-icons text-sm transition-transform duration-300"
                            :class="{ 'rotate-180': isOpen, 'opacity-0': !isAsideExpanded, 'opacity-100': isAsideExpanded }"
                        >
                            expand_more
                        </i>
                    </a>
                
                    <!-- Sublistas -->
                    <ul 
                        x-show="isOpen" 
                        x-transition 
                        class="mt-2 ml-6 space-y-2 border-l border-gray-300 dark:border-pTxt"
                        :class="{ 'pl-4': isAsideExpanded, 'pl-0': !isAsideExpanded }"
                        x-init="$watch('isAsideExpanded', value => { if (!value) isOpen = false })"
                    >
                        <li>
                            <a href="{{ route('admin.users') }}" class="block py-1 text-sm hover:underline">Usuarios</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.vehicles') }}"  class="block py-1 text-sm hover:underline">Vehiculos</a>
                        </li>
                    </ul>
                </li>
                <li x-data="{ isOpen: false }" class="relative">
                    <a href="#" 
                       @click="isAsideExpanded = true; isOpen = !isOpen" 
                       class="flex items-center justify-between py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-[#333] transition-all duration-300"
                       :class="{ 'px-2': !isAsideExpanded, 'px-4': isAsideExpanded }"
                    >
                        <div class="flex items-center">
                            <i class="material-icons mr-2" style="font-size: 22px">route</i>
                            <span 
                                class="transition-opacity duration-300 text-sm" 
                                :class="{ 'opacity-0': !isAsideExpanded, 'opacity-100': isAsideExpanded }"
                            >
                                Rutas
                            </span>
                        </div>
                        <i 
                            class="material-icons text-sm transition-transform duration-300"
                            :class="{ 'rotate-180': isOpen, 'opacity-0': !isAsideExpanded, 'opacity-100': isAsideExpanded }"
                        >
                            expand_more
                        </i>
                    </a>
                
                    <!-- Sublistas -->
                    <ul 
                        x-show="isOpen" 
                        x-transition 
                        class="mt-2 ml-6 space-y-2 border-l border-gray-300 dark:border-pTxt"
                        :class="{ 'pl-4': isAsideExpanded, 'pl-0': !isAsideExpanded }"
                        x-init="$watch('isAsideExpanded', value => { if (!value) isOpen = false })"
                    >
                        <li>
                            <a href="{{ route('admin.new.route') }}" class="block py-1 text-sm hover:underline">Crear una Ruta</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.routes') }}" class="block py-1 text-sm hover:underline">Rutas Activas</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.routes.history') }}" class="block py-1 text-sm hover:underline">Historial de Rutas</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('admin.reports') }}" 
                       class="flex items-center py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-[#333] transition-all duration-300"
                       :class="{ 'px-2': !isAsideExpanded, 'px-4' : isAsideExpanded }"
                       >
                        <i class="material-icons mr-2" style="font-size: 20px">summarize</i>
                        <span 
                            class="transition-opacity duration-300 text-sm" 
                            :class="{ 'opacity-0': !isAsideExpanded, 'opacity-100': isAsideExpanded }"
                            >
                            Reportes
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.profile.edit') }}" 
                       class="flex items-center py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-[#333] transition-all duration-300"
                       :class="{ 'px-2': !isAsideExpanded, 'px-4' : isAsideExpanded }"
                       >
                        <i class="material-icons mr-2" style="font-size: 20px">settings</i>
                        <span 
                            class="transition-opacity duration-300 text-sm" 
                            :class="{ 'opacity-0': !isAsideExpanded, 'opacity-100': isAsideExpanded }"
                            >
                            Configuración
                        </span>
                    </a>
                </li>

            {{-- USER CONTENT --}}
            @elseif (auth()->user()->hasRole('user'))
                <li>
                    <a href="{{ route('user.dashboard') }}" 
                       class="flex items-center py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-[#333] transition-all duration-300"
                       :class="{ 'px-2': !isAsideExpanded, 'px-4' : isAsideExpanded }"
                       >
                        <i class="material-icons mr-2" style="font-size: 20px">home</i>
                        <span 
                            class="transition-opacity duration-300 text-sm" 
                            :class="{ 'opacity-0': !isAsideExpanded, 'opacity-100': isAsideExpanded }"
                            >
                            Inicio
                        </span>
                    </a>
                </li>
                <li x-data="{ isOpen: false }" class="relative">
                    <a href="#" 
                       @click="isAsideExpanded = true; isOpen = !isOpen" 
                       class="flex items-center justify-between py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-[#333] transition-all duration-300"
                       :class="{ 'px-2': !isAsideExpanded, 'px-4': isAsideExpanded }"
                    >
                        <div class="flex items-center">
                            <i class="material-icons mr-2" style="font-size: 22px">location_on</i>
                            <span 
                                class="transition-opacity duration-300 text-sm" 
                                :class="{ 'opacity-0': !isAsideExpanded, 'opacity-100': isAsideExpanded }"
                            >
                                Direcciones
                            </span>
                        </div>
                        <i 
                            class="material-icons text-sm transition-transform duration-300"
                            :class="{ 'rotate-180': isOpen, 'opacity-0': !isAsideExpanded, 'opacity-100': isAsideExpanded }"
                        >
                            expand_more
                        </i>
                    </a>
                
                    <!-- Sublistas -->
                    <ul 
                        x-show="isOpen" 
                        x-transition 
                        class="mt-2 ml-6 space-y-2 border-l border-gray-300 dark:border-pTxt"
                        :class="{ 'pl-4': isAsideExpanded, 'pl-0': !isAsideExpanded }"
                        x-init="$watch('isAsideExpanded', value => { if (!value) isOpen = false })"
                    >
                        <li>
                            <a href="{{ route('user.my.address')}}" class="block py-1 text-sm hover:underline">Mis Direcciones</a>
                        </li>
                        <li>
                            <a href="{{ route('user.address')}}" class="block py-1 text-sm hover:underline">Agregar Dirección</a>
                        </li>
                    </ul>
                </li>
                <li x-data="{ isOpen: false }" class="relative">
                    <a href="#" 
                       @click="isAsideExpanded = true; isOpen = !isOpen" 
                       class="flex items-center justify-between py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-[#333] transition-all duration-300"
                       :class="{ 'px-2': !isAsideExpanded, 'px-4': isAsideExpanded }"
                    >
                        <div class="flex items-center">
                            <i class="material-icons mr-2" style="font-size: 20px">inventory_2</i>
                            <span 
                                class="transition-opacity duration-300 text-sm" 
                                :class="{ 'opacity-0': !isAsideExpanded, 'opacity-100': isAsideExpanded }"
                            >
                                Pedidos
                            </span>
                        </div>
                        <i 
                            class="material-icons text-sm transition-transform duration-300"
                            :class="{ 'rotate-180': isOpen, 'opacity-0': !isAsideExpanded, 'opacity-100': isAsideExpanded }"
                        >
                            expand_more
                        </i>
                    </a>
                
                    <!-- Sublistas -->
                    <ul 
                        x-show="isOpen" 
                        x-transition 
                        class="mt-2 ml-6 space-y-2 border-l border-gray-300 dark:border-pTxt"
                        :class="{ 'pl-4': isAsideExpanded, 'pl-0': !isAsideExpanded }"
                        x-init="$watch('isAsideExpanded', value => { if (!value) isOpen = false })"
                    >
                        <li>
                            <a href="{{ route('user.order')}}" class="block py-1 text-sm hover:underline">Crear una Pedido</a>
                        </li>
                        <li>
                            <a href="{{ route('user.my.orders')}}" class="block py-1 text-sm hover:underline">Pedidos Activos</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('user.orders.history') }}" 
                       class="flex items-center py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-[#333] transition-all duration-300"
                       :class="{ 'px-2': !isAsideExpanded, 'px-4' : isAsideExpanded }"
                       >
                        <i class="material-icons mr-2" style="font-size: 20px">history</i>
                        <span 
                            class="transition-opacity duration-300 text-sm" 
                            :class="{ 'opacity-0': !isAsideExpanded, 'opacity-100': isAsideExpanded }"
                            >
                            Historial de Pedidos
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.profile.edit') }}" 
                       class="flex items-center py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-[#333] transition-all duration-300"
                       :class="{ 'px-2': !isAsideExpanded, 'px-4' : isAsideExpanded }"
                       >
                        <i class="material-icons mr-2" style="font-size: 20px">settings</i>
                        <span 
                            class="transition-opacity duration-300 text-sm" 
                            :class="{ 'opacity-0': !isAsideExpanded, 'opacity-100': isAsideExpanded }"
                            >
                            Configuración
                        </span>
                    </a>
                </li>
            {{-- DRIVER CONTENT --}}
            @elseif (auth()->user()->hasRole('driver'))
                <li>
                    <a href="{{ route('driver.dashboard') }}" 
                       class="flex items-center py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-[#333] transition-all duration-300"
                       :class="{ 'px-2': !isAsideExpanded, 'px-4' : isAsideExpanded }"
                       >
                        <i class="material-icons mr-2" style="font-size: 20px">home</i>
                        <span 
                            class="transition-opacity duration-300 text-sm" 
                            :class="{ 'opacity-0': !isAsideExpanded, 'opacity-100': isAsideExpanded }"
                            >
                            Inicio
                        </span>
                    </a>
                </li>
                {{-- <li x-data="{ isOpen: false }" class="relative">
                    <a href="#" 
                       @click="isAsideExpanded = true; isOpen = !isOpen" 
                       class="flex items-center justify-between py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-[#333] transition-all duration-300"
                       :class="{ 'px-2': !isAsideExpanded, 'px-4': isAsideExpanded }"
                    >
                        <div class="flex items-center">
                            <i class="material-icons mr-2" style="font-size: 22px">location_on</i>
                            <span 
                                class="transition-opacity duration-300 text-sm" 
                                :class="{ 'opacity-0': !isAsideExpanded, 'opacity-100': isAsideExpanded }"
                            >
                                Direcciones
                            </span>
                        </div>
                        <i 
                            class="material-icons text-sm transition-transform duration-300"
                            :class="{ 'rotate-180': isOpen, 'opacity-0': !isAsideExpanded, 'opacity-100': isAsideExpanded }"
                        >
                            expand_more
                        </i>
                    </a>
                
                    <!-- Sublistas -->
                    <ul 
                        x-show="isOpen" 
                        x-transition 
                        class="mt-2 ml-6 space-y-2 border-l border-gray-300 dark:border-pTxt"
                        :class="{ 'pl-4': isAsideExpanded, 'pl-0': !isAsideExpanded }"
                        x-init="$watch('isAsideExpanded', value => { if (!value) isOpen = false })"
                    >
                        <li>
                            <a href="#" class="block py-1 text-sm hover:underline">Mis Direcciones</a>
                        </li>
                        <li>
                            <a href="#" class="block py-1 text-sm hover:underline">Agregar Dirección</a>
                        </li>
                    </ul>
                </li> --}}
                <li>
                    <a href="{{ route('driver.routes') }}" 
                       class="flex items-center py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-[#333] transition-all duration-300"
                       :class="{ 'px-2': !isAsideExpanded, 'px-4' : isAsideExpanded }"
                       >
                        <i class="material-icons mr-2" style="font-size: 20px">local_shipping</i>
                        <span 
                            class="transition-opacity duration-300 text-sm" 
                            :class="{ 'opacity-0': !isAsideExpanded, 'opacity-100': isAsideExpanded }"
                            >
                            Ruta Activa
                        </span>
                    </a>
                </li>
                <li>
                    <a href="#" 
                       class="flex items-center py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-[#333] transition-all duration-300"
                       :class="{ 'px-2': !isAsideExpanded, 'px-4' : isAsideExpanded }"
                       >
                        <i class="material-icons mr-2" style="font-size: 20px">where_to_vote</i>
                        <span 
                            class="transition-opacity duration-300 text-sm" 
                            :class="{ 'opacity-0': !isAsideExpanded, 'opacity-100': isAsideExpanded }"
                            >
                            Historial de Entregas
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('driver.profile.edit') }}" 
                       class="flex items-center py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-[#333] transition-all duration-300"
                       :class="{ 'px-2': !isAsideExpanded, 'px-4' : isAsideExpanded }"
                       >
                        <i class="material-icons mr-2" style="font-size: 20px">settings</i>
                        <span 
                            class="transition-opacity duration-300 text-sm" 
                            :class="{ 'opacity-0': !isAsideExpanded, 'opacity-100': isAsideExpanded }"
                            >
                            Configuración
                        </span>
                    </a>
                </li>
            @endif
            
        </ul>
        <div class="mt-auto" :class="{ 'px-0': !isAsideExpanded, 'px-2': isAsideExpanded }">
            <form action="{{ route('logout') }}" method="POST" class="p-0 m-0">
                @csrf
                <button type="submit"
                    class="flex items-center py-2 rounded-lg border border-red-600 text-red-600 dark:border-red-500 dark:text-red-500 hover:bg-red-100 dark:hover:bg-red-500 dark:hover:text-white transition-all duration-300 w-full"
                    :class="{ 'px-2 py-0': !isAsideExpanded, 'px-4' : isAsideExpanded }"
                    >
                    <i class="material-icons mr-2" style="font-size: 20px">logout</i>
                    <span 
                        class="transition-opacity duration-300 text-sm" 
                        :class="{ 'opacity-0': !isAsideExpanded, 'opacity-100': isAsideExpanded }"
                        >
                        Cerrar Sesión
                    </span>
                </button>
            </form>
        </div>
    </nav>
</aside>