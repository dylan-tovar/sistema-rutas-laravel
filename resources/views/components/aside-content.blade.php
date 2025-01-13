
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
                   class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-100 dark:hover:bg-[#333] transition-all duration-300">
                    <span 
                        class="font-bold text-lg text-flamingo-500 transition-opacity duration-300" 
                        :class="{ 'opacity-0': !isAsideExpanded, 'opacity-100': isAsideExpanded }"
                        >
                        LOGO
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
                            <a href="{{ route('admin.routes')}}" class="block py-1 text-sm hover:underline">Rutas Activas</a>
                        </li>
                        <li>
                            <a href="#" class="block py-1 text-sm hover:underline">Historial de Rutas</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" 
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
                    <a href="#" 
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
                            <a href="{{ route('user.my.orders')}}" class="block py-1 text-sm hover:underline">Pedidos Activas</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" 
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
                    <a href="#" 
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
                    <a href="#" 
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
                    <a href="#" 
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
                    <a href="#" 
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