<?php
/**
 * Main admin page template
 */
?>
<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <div class="notice notice-info">
        <p>Bienvenue dans l'administration du plugin My Istymo.</p>
    </div>
    <div class="card">
        <h2>Fonctionnalités disponibles</h2>
        <ul>
            <li>DPE - Utilisez le shortcode <code>[my_istymo_dpe]</code></li>
            <li>SCI - Utilisez le shortcode <code>[my_istymo_sci]</code></li>
        </ul>
    </div>
    <div class="card">
        <h2>Prérequis</h2>
        <p>1. Assurez-vous que les utilisateurs ont un champ ACF 'code_postal_user' configuré dans leur profil.</p>
        <p>2. Les codes postaux doivent être séparés par des points-virgules (;) dans le champ ACF.</p>
    </div>
</div>