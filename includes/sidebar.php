<?php
require_once __DIR__ . '/../funciones/config.php';



$current_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$base_url_path = parse_url(BASE_URL, PHP_URL_PATH);
if ($base_url_path !== '/' && strpos($current_path, $base_url_path) === 0) {
    $current_path = substr($current_path, strlen($base_url_path));
}
if (substr($current_path, 0, 1) !== '/') {
    $current_path = '/' . $current_path;
}

?>

<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand" href="<?= BASE_URL ?>vistas/dashboard.php">
            <span class="align-middle">AdminKit</span>
        </a>

        <ul class="sidebar-nav">
            

            <?php
            // Definir un array de elementos del menú con sus rutas y etiquetas
            $menu_items = [
                ['label' => 'Listado', 'icon' => 'list', 'url' => BASE_URL . 'vistas/listado_proyectos.php', 'group' => 'Proyectos'],
                ['label' => 'Cargar nuevo', 'icon' => 'file', 'url' => BASE_URL . 'vistas/carga_proyecto.php', 'group' => 'Proyectos'],
                ['label' => 'Listado de empresas', 'icon' => 'award', 'url' => BASE_URL . 'vistas/listado_empresas.php', 'group' => 'Empresas'],
                
                
            ];
            
            // Solo si es administrador, mostrar acceso a usuarios
            if (isset($_SESSION['usuario']) && $_SESSION['rol'] === 'Administrador') {
                $menu_items[] = ['label' => 'Cargar nueva', 'icon' => 'file', 'url' => BASE_URL . 'vistas/carga_empresa.php', 'group' => 'Empresas'];
                $menu_items[] = ['label' => 'Listado de países', 'icon' => 'map-pin', 'url' => BASE_URL . 'vistas/listado_paises.php', 'group' => 'Empresas'];
                $menu_items[] = ['label' => 'Listado de usuarios', 'icon' => 'user', 'url' => BASE_URL . 'vistas/listado_usuarios.php', 'group' => 'Personal'];
                
            }

            $current_group = '';
            foreach ($menu_items as $item) {
                // Si el grupo cambia, añade un nuevo encabezado de sección
                if ($item['group'] !== $current_group) {
                    if ($current_group !== '') {
                        // Cierra el li anterior si no es el primero
                        echo '</ul>'; // Cierra la lista anterior si existía
                    }
                    echo '<li class="sidebar-header">' . htmlspecialchars($item['group']) . '</li>';
                    $current_group = $item['group'];
                    echo '<ul class="sidebar-nav">'; // Abre una nueva lista para el nuevo grupo
                }

                // Determinar si el elemento actual debe ser 'active'
                $is_active = (str_replace(BASE_URL, '/', $item['url']) === $current_path) ? 'active' : '';
                

                echo '<li class="sidebar-item ' . $is_active . '">';
                echo '<a class="sidebar-link" href="' . htmlspecialchars($item['url']) . '">';
                echo '<i class="align-middle me-2" data-feather="' . htmlspecialchars($item['icon']) . '"></i> ';
                echo '<span class="align-middle">' . htmlspecialchars($item['label']) . '</span>';
                echo '</a>';
                echo '</li>';
            }
            
            if ($current_group !== '') {
                echo '</ul>';
            }
            ?>
        </ul>
    </div>
</nav>