<div class="l-navbar" id="nav-bar">
    <nav class="nav">
        <div class="nav-link-logo"> 
            <a href="admin-index.php" class="nav_logo"> <i class='bx bxs-home' ></i> 
                <span id="greetings-span" class="nav_logo-name"><?php include('./src/views/components/greetings.view.php'); echo $greeting; ?></span> 
            </a>
            <div class="nav_list">
                <a data-bs-toggle="tooltip" data-bs-placement="right" title="Dashboard" id="nav-link-dashboard" style="cursor:pointer;" onclick="redirect('admin-index.php')" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Dashboard</span> </a> 
                <a data-bs-toggle="tooltip" data-bs-placement="right" title="Usuários" id="nav-link-usuarios" style="cursor:pointer;" onclick="redirect('usuarios.php')" class="nav_link"> <i class='bx bx-user nav_icon'></i> <span class="nav_name">Usuários</span> </a> 
                <a data-bs-toggle="tooltip" data-bs-placement="right" title="Pessoas" id="nav-link-pessoas" style="cursor:pointer;" onclick="redirect('pessoa.php')" class="nav_link"> <i class='bx bxs-graduation nav_icon'></i> <span class="nav_name">Pessoas</span> </a> 
                <!-- <a data-bs-toggle="tooltip" data-bs-placement="right" title="Regras" id="nav-link-regras" style="cursor:pointer;" onclick="redirect('regras.php')" class="nav_link"> <i class='bx bx-bookmark nav_icon'></i> <span class="nav_name">Regras</span> </a>  -->
                <a data-bs-toggle="tooltip" data-bs-placement="right" title="Presenças" id="nav-link-pessoas" style="cursor:pointer;" onclick="redirect('presencas.php')" class="nav_link"> <i class='bx bx-list-check nav_icon'></i> <span class="nav_name">Presenças</span> </a> 
                <a data-bs-toggle="tooltip" data-bs-placement="right" title="Configurações" id="nav-link-configs" style="cursor:pointer;" onclick="redirect('configuracao.php')" class="nav_link"> <i class='bx bx-cog nav_icon'></i> <span class="nav_name">Configurações</span> </a> 
            </div>
        </div> 
        <div class="nav-link-logo"> 
            <a href="./src/controllers/admin/logoutAdminController.php" class="nav_link"> <i class='bx bx-log-out nav_icon'></i> <span class="nav_name">Deslogar</span> </a>
        </div>
    </nav>
</div>

<script>   
    document.getElementById('greetings-span').append(window.localStorage.getItem('userLoggedNome') + ' !')
</script>