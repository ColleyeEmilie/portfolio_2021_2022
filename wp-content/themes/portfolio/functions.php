<?php

require_once(__DIR__ . '/Menus/PrimaryMenuWalker.php');
require_once(__DIR__ . '/Menus/PrimaryMenuItem.php');
require_once(__DIR__ . '/Forms/BaseFormController.php');
require_once(__DIR__ . '/Forms/ContactFormController.php');
require_once(__DIR__ . '/Forms/Sanitizers/BaseSanitizer.php');
require_once(__DIR__ . '/Forms/Sanitizers/TextSanitizer.php');
require_once(__DIR__ . '/Forms/Sanitizers/EmailSanitizer.php');
require_once(__DIR__ . '/Forms/Validators/BaseValidator.php');
require_once(__DIR__ . '/Forms/Validators/RequiredValidator.php');
require_once(__DIR__ . '/Forms/Validators/EmailValidator.php');
require_once(__DIR__ . '/Forms/Validators/AcceptedValidator.php');

add_action('init', 'dw_init_php_session', 1);

function dw_init_php_session()
{
    if(! session_id()) {
        session_start();
    }
}


// Désactiver l'éditeur "Gutenberg" de Wordpress
add_filter('use_block_editor_for_post', '__return_false');

// Activer les images sur les articles
add_theme_support('post-thumbnails');

/* Enregistrer un type de ressources (custom post type) pour le portfolio */
register_post_type('portfolio', [
    'label'=>'Projet',
    'labels'=>[ 'name'=> 'Projets', 'singular_name'=> 'Projet'],
    'description'=>'La ressource permettant de gérer le portfolio',
    'public'=> true,
    'menu_position'=> 5,
    'menu_icon'=> 'dashicons-format-aside',
    'supports' => ['title','editor','thumbnail'],
    'rewrite' => ['slug' => 'projet'],
]);

register_post_type('message', [
    'label' => 'Messages de contact',
    'labels' => ['name' => 'Messages de contact', 'singular_name' => 'Message de contact',],
    'description' => 'Les messages envoyés par les utilisateurs via le formulaire de contact.',
    'public' => false,
    'show_ui' => true,
    'menu_position' => 15,
    'menu_icon' => 'dashicons-buddicons-pm',
    'capabilities' => ['create_posts' => false,],
    'map_meta_cap' => true,
]);

// Récupérer les trips via une requête Wordpress
function dw_get_trips($count = 20)
{
    // 1. on instancie l'objet WP_Query
    $trips = new WP_Query([
        'post_type' => 'trip',
        'orderby' => 'date',
        'order' => 'DESC',
        'posts_per_page' => $count,
    ]);

    // 2. on retourne l'objet WP_Query
    return $trips;
}

// Enregistrer les menus de navigation

register_nav_menu('primary', 'Emplacement de la navigation principale de haut de page');
register_nav_menu('footer', 'Emplacement de la navigation de pied de page');

// Définition de la fonction retournant un menu de navigation sous forme d'un tableau de liens de niveau 0.

function dw_get_menu_items($location)
{
    $items = [];

    // Récupérer le menu qui correspond à l'emplacement souhaité
    $locations = get_nav_menu_locations();

    if(! ($locations[$location] ?? false)) {
        return $items;
    }

    $menu = $locations[$location];

    // Récupérer tous les éléments du menu en question
    $posts = wp_get_nav_menu_items($menu);

    // Traiter chaque élément de menu pour le transformer en objet
    foreach($posts as $post) {
        // Créer une instance d'un objet personnalisé à partir de $post
        $item = new PrimaryMenuItem($post);

        // Ajouter cette instance soit à $items (s'il s'agit d'un élément de niveau 0), soit en tant que sous-élément d'un item déjà existant.
        if(! $item->isSubItem()) {
            // Il s'agit d'un élément de niveau 0, on l'ajoute au tableau
            $items[] = $item;
            continue;
        }

        // Ajouter l'instance comme "enfant" d'un item existant
        foreach($items as $existing) {
            if(! $existing->isParentFor($item)) continue;

            $existing->addSubItem($item);
        }
    }

    // Retourner les éléments de menu de niveau 0
    return $items;
}

/*---------------------------------*/
/*-----------FORMULAIRE------------*/
/*---------------------------------*/

// Gérer l'envoi de formulaire personnalisé

add_action('admin_post_submit_contact_form', 'dw_handle_submit_contact_form');

function dw_handle_submit_contact_form()
{
    // Instancier le controlleur du form
    $form = new ContactFormController($_POST);
}

function dw_get_contact_field_value($field)
{
    if(! isset($_SESSION['contact_form_feedback'])) {
        return '';
    }

    return $_SESSION['contact_form_feedback']['data'][$field] ?? '';
}

function dw_get_contact_field_error($field)
{
    if(! isset($_SESSION['contact_form_feedback'])) {
        return '';
    }

    if(! ($_SESSION['contact_form_feedback']['errors'][$field] ?? null)) {
        return '';
    }

    return '<p>Ce champ ne respecte pas : ' . $_SESSION['contact_form_feedback']['errors'][$field] . '</p>';
}