<?php
require_once(__DIR__ . '/menus/PrimaryMenuWalker.php');
require_once(__DIR__ . '/menus/PrimaryMenuItem.php');
require_once(__DIR__ . '/Forms/BaseFormController.php');
require_once(__DIR__ . '/Forms/ContactFormController.php');
require_once(__DIR__ . '/Forms/Sanitizers/BaseSanitizer.php');
require_once(__DIR__ . '/Forms/Sanitizers/EmailSanitizer.php');
require_once(__DIR__ . '/Forms/Sanitizers/TextSanitizer.php');
require_once(__DIR__ . '/Forms/Validators/BaseValidator.php');
require_once(__DIR__ . '/Forms/Validators/AcceptedValidator.php');
require_once(__DIR__ . '/Forms/Validators/EmailValidator.php');
require_once(__DIR__ . '/Forms/Validators/RequiredValidator.php');

add_action('init', 'portfolio_boot_theme', 1);

function portfolio_boot_theme()
{
    if (!session_id()) {
        session_start();
    }
}

add_filter('use_block_editor_for_post', '__return_false');
add_theme_support('post-thumbnails');
register_post_type('projet',[
    'label'=>'Projets',
    'labels'=>[
        'name'=>'Projets',
        'singular_name'=>'Projet'
    ],
    'description'=>'Portfolio pour présenter mes projets réaliser dans le cadre de mes études',
    'menu_position'=>5,
    'menu_icon'=>'dashicons-portfolio',
    'public' =>true,
    'has_archive'=>true,
    'show_ui' => true,
    'supports' => [
        'title',
        'editor',
        'thumbnail',
        'excerpt',
    ],
    'rewrite' => [
        'slug' => 'projet',
    ],
]);

register_post_type('message', [
    'label' => 'Messages de contact',
    'labels' => [
        'name' => 'Messages de contact',
        'singular_name' => 'Message de contact',
    ],
    'description' => 'Les messages envoyés par le formulaire de contact.',
    'public' => false,
    'show_ui' => true,
    'menu_position' => 10,
    'menu_icon' => 'dashicons-buddicons-pm',
    'capabilities' => [
        'create_posts' => false,
        'read_post' => true,
        'read_private_posts' => true,
        'edit_posts' => true,
    ],
    'map_meta_cap' => true,
]);

register_post_type('article',[
    'label'=>'Articles',
    'labels'=>[
        'name'=>'Articles',
        'singular_name'=>'Article'
    ],
    'description'=>'Listing des différents articles et leur lien vers les différentes plateformes',
    'menu_position'=>10,
    'menu_icon'=>'dashicons-text-page',
    'public' =>true,
    'has_archive'=>true,
    'show_ui' => true,
    'supports' => [
        'title',
        'editor',
        'thumbnail',
    ],
    'rewrite' => [
        'slug' => 'article',
    ],

]);

register_post_type('informations',[
    'label'=>'Informations de contact',
    'labels'=>[
        'name'=>'Informations de contact',
        'singular_name'=>'information'
    ],
    'description'=>'Informations (adresse, n° tel des collaborateur, titre)',
    'menu_position'=>10,
    'menu_icon'=>'dashicons-admin-home',
    'public' =>true,
    'has_archive'=>true,
    'show_ui' => true,
    'supports' => [
        'title',
        'editor',
        'thumbnail',
        'custom-fields',
    ],
    'rewrite' => [
        'slug' => 'information',
    ],

]);

register_post_type('Presentation',[
    'label'=>'Presentation',
    'labels'=>[
        'name'=>'Présentation du site',
        'singular_name'=>'Presentation'
    ],
    'description'=>'Présentation du site',
    'menu_position'=>10,
    'menu_icon'=>'dashicons-welcome-write-blog',
    'public' =>true,
    'has_archive'=>true,
    'show_ui' => true,
    'supports' => [
        'title',
        'editor',
        'custom-fields',
    ],
    'rewrite' => [
        'slug' => 'presentation',
    ],

]);

function antilope_get_presentations($count = 1){
    $presentations = new WP_Query([
        'post_type' => 'presentation',
        'orderby' =>'date',
        'order'=>'ASC',
        'posts_per_page' => $count,
    ]);
    return $presentations;
}

function antilope_get_informations($count = 20){
    $informations = new WP_Query([
        'post_type' => 'informations',
        'orderby' =>'date',
        'order'=>'ASC',
        'posts_per_page' => $count,
    ]);
    return $informations;
}

function ecosphair_get_articles($count = 20){
    $articles = new WP_Query([
        'post_type' => 'article',
        'orderby' =>'date',
        'order'=>'ASC',
        'posts_per_page' => $count,
    ]);
    return $articles;
}

function portfolio_get_projets($count = 20): WP_Query
{
    $projets = new WP_Query([
        'post_type' => 'projet',
        'orderby' =>'date',
        'order'=>'ASC',
        'posts_per_page' => $count,
    ]);
    return $projets;
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
register_nav_menu('primary', 'Navigation principale (haut de page)');
register_nav_menu('footer', 'Navigation de pied de page');
function portfolio_get_menu_items($location)
{
    $items = [];

    $locations = get_nav_menu_locations();

    if (!($locations[$location] ?? false)) {
        return $items;
    }

    $menu = $locations[$location];

    $posts = wp_get_nav_menu_items($menu);

    foreach ($posts as $post) {
        $item = new PrimaryMenuItem($post);

        if (!$item->isSubItem()) {
            $items[] = $item;
            continue;
        }

        foreach ($items as $parent) {
            if (!$parent->isParentFor($item)) continue;

            $parent->addSubItem($item);
        }
    }

    return $items;
}
add_action('admin_post_submit_contact_form', 'portfolio_handle_submit_contact_form');

function portfolio_handle_submit_contact_form()
{
    $form = new ContactFormController($_POST);
}

function portfolio_get_contact_field_value($field)
{
    if (!isset($_SESSION['contact_form_feedback'])) {
        return '';
    }

    return $_SESSION['contact_form_feedback']['data'][$field] ?? '';
}

function portfolio_get_contact_field_error($field)
{

    if (!isset($_SESSION['contact_form_feedback'])) {
        return '';
    }

    if (!($_SESSION['contact_form_feedback']['errors'][$field] ?? null)) {
        return '';
    }

    return '<p class="error">' . $_SESSION['contact_form_feedback']['errors'][$field] . '</p>';
}


function portfolio_mix($path)
{
    $path = '/' . ltrim($path, '/');

    if(! realpath(__DIR__ .'/public' . $path)) {
        return;
    }

    if(! ($manifest = realpath(__DIR__ .'/public/mix-manifest.json'))) {
        return get_stylesheet_directory_uri() . '/public' . $path;
    }

    $manifest = json_decode(file_get_contents($manifest), true);

    if(! array_key_exists($path, $manifest)) {
        return get_stylesheet_directory_uri() . '/public' . $path;
    }

    return get_stylesheet_directory_uri() . '/public' . $manifest[$path];
}

function my_function_admin_bar(){ return false; }
add_filter( 'show_admin_bar' , 'my_function_admin_bar');
