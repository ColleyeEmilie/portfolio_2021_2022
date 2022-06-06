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
register_nav_menu('footer', 'Emplacement des liens en bas de page');

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
// Utilitaire pour charger un fichier compilé par mix, avec cache bursting.

function dw_mix($path)
{
    $path = '/' . ltrim($path, '/');

    // Checker si le fichier demandé existe bien, sinon retourner NULL
    if(! realpath(__DIR__ . '/public' . $path)) {
        return;
    }

    // Check si le fichier mix-manifest existe bien, sinon retourner le fichier sans cache-bursting
    if(! ($manifest = realpath(__DIR__ . '/public/mix-manifest.json'))) {
        return get_stylesheet_directory_uri() . '/public' . $path;
    }

    // Ouvrir le fichier mix-manifest et lire le JSON
    $manifest = json_decode(file_get_contents($manifest), true);

    // Check si le fichier demandé est bien présent dans le mix manifest, sinon retourner le fichier sans cache-bursting
    if(! array_key_exists($path, $manifest)) {
        return get_stylesheet_directory_uri() . '/public' . $path;
    }

    // C'est OK, on génère l'URL vers la ressource sur base du nom de fichier avec cache-bursting.
    return get_stylesheet_directory_uri() . '/public' . $manifest[$path];
}

// On va se plugger dans l'exécution de la requête de recherche pour la contraindre à chercher dans les articles uniquement.

function dw_configure_search_query($query)
{
    if($query->is_search && ! is_admin() && ! is_a($query, DW_CustomSearchQuery::class)) {
        $query->set('post_type', 'post');
    }

    // Faire un système de filtres "custom" (sans passer par la méthode WP) :
    // if(is_archive() && isset($_GET['filter-country'])) {
    //     $query->set('tax_query', [
    //         ['taxonomy' => 'country', 'field' => 'slug', 'terms' => explode(',', $_GET['filter-country'])]
    //     ]);
    // }

    return $query;
}

add_filter('pre_get_posts', 'dw_configure_search_query');

// Fonction permettant d'inclure des composants et d'y injecter des variables locales (scope de l'appel de fonction)

function dw_include(string $partial, array $variables = [])
{
    extract($variables);

    include(__DIR__ . '/partials/' . $partial . '.php');
}

// Fonction permettant de récupérer la première page appartenant à un template donné

function dw_get_template_page(string $template)
{
    // Créer une WP_Query
    $query = new WP_Query([
        'post_type' => 'page', // Filtrer sur le post_type de type "page"
        'post_status' => 'publish', // Uniquement les pages publiées
        'meta_query' => [
            ['key' => '_wp_page_template', 'value' => $template . '.php'] // Filtrer sur le type de template utilisé
        ]
    ]);

    // Retourner la première occurrence pour cette requête (ou NULL)
    return $query->posts[0] ?? null;
}

function portfolio_get_projets($count = 20){
    //1. on instancie l'objet WP_Query
    $projets = new WP_Query([
        //arguments
        'post_type' => 'projet',
        'orderby' =>'date',
        'order'=>'ASC',
        'posts_per_page' => $count,
    ]);
    //2. on retourne l'objet WP_Query
    return $projets;
}
function portfolio_get_networks(){
    //1. on instancie l'objet WP_Query
    $networks = new WP_Query([
        //arguments
        'post_type' => 'network',
        'orderby' =>'date',
        'order'=>'ASC',
    ]);
    //2. on retourne l'objet WP_Query
    return $networks;
}

function portfolio_get_template_page(string $template){
    $query = new WP_Query([
        'post_type' => 'page',
        'post_status' => 'publish',
        'meta_query' => [
            [
                'key' => '_wp_page_template',
                'value' => $template . '.php',
            ],
        ]
    ]);
    return $query->posts[0] ?? null;
}