<button onclick="toggleTheme()" class="focus:outline-none mr-2 mt-1">
    <i class="material-icons">contrast</i>
</button>

<script>
    // Configurar el tema inicial al cargar la página
    document.addEventListener('DOMContentLoaded', () => {
        // Verifica si existe una preferencia guardada en localStorage
        const savedTheme = localStorage.getItem('theme');

        // Si se guardó 'dark', aplica el modo oscuro
        if (savedTheme === 'dark') {
            document.documentElement.classList.add('dark');
        } else if (savedTheme === 'light') {
            document.documentElement.classList.remove('dark');
        } else {
            // Si no hay preferencia guardada, detecta la preferencia del sistema
            const systemPrefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (systemPrefersDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
    });

    // Función para alternar entre los modos claro y oscuro
    function toggleTheme() {
        const isDark = document.documentElement.classList.toggle('dark');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
    }
</script>
