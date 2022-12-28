<?php

namespace TeamTraining\PostTypes;

class Workshop
{

    public static function init() 
    {
        add_action('init', 'TeamTraining\PostTypes\Workshop::registerPostType');
        add_action('init', 'TeamTraining\PostTypes\Workshop::registerTagTaxonomy');
    }

    public static function registerPostType()
    {
        $labels = array(
            'name'                  => 'Workshops',
            'singular_name'         => 'Workshop',
            'menu_name'             => 'Workshops',
            'name_admin_bar'        => 'Post Day',
            'archives'              => 'Workshop Archives',
            'attributes'            => 'Workshop Attributes',
            'parent_item_colon'     => 'Parent Workshop:',
            'all_items'             => 'All Workshops',
            'add_new_item'          => 'Add Workshop',
            'add_new'               => 'Add New Workshop',
            'new_item'              => 'New Workshop',
            'edit_item'             => 'Edit Workshop',
            'update_item'           => 'Update Workshop',
            'view_item'             => 'View Workshop',
            'view_items'            => 'View Workshops',
            'search_items'          => 'Search Workshop',
            'not_found'             => 'Not Workshop found',
            'not_found_in_trash'    => 'Not Workshop found in Trash',
            'featured_image'        => 'Featured Image',
            'set_featured_image'    => 'Set featured image',
            'remove_featured_image' => 'Remove featured image',
            'use_featured_image'    => 'Use as featured image',
            'insert_into_item'      => 'Insert into item',
            'uploaded_to_this_item' => 'Uploaded to this item',
            'items_list'            => 'Workshops list',
            'items_list_navigation' => 'Workshops list navigation',
            'filter_items_list'     => 'Filter Workshops list',
        );

        register_post_type( 'workshop', [
            'label'                 => 'Workshop',
            'description'           => 'Collection of Workshops',
            'labels'                => $labels,
            'supports'              => ['title', 'thumbnail', 'revisions', 'custom-fields', 'editor', 'page-attributes', 'post-formats', 'permalink'],
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'show_in_rest'          => true,
            'has_archive'           => 'Workshops',
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_day'       => 'page'
        ]);
    }

    public static function registerTagTaxonomy()
    {
       
	    $labels = [
            'name'                       => _x( 'Tags', 'Taxonomy General Name', 'text_domain' ),
            'singular_name'              => _x( 'Tag', 'Taxonomy Singular Name', 'text_domain' ),
            'menu_name'                  => __( 'Tags', 'text_domain' ),
            'all_items'                  => __( 'All Items', 'text_domain' ),
            'parent_item'                => __( 'Parent Item', 'text_domain' ),
            'parent_item_colon'          => __( 'Parent Item:', 'text_domain' ),
            'new_item_name'              => __( 'New Item Name', 'text_domain' ),
            'add_new_item'               => __( 'Add New Item', 'text_domain' ),
            'edit_item'                  => __( 'Edit Item', 'text_domain' ),
            'update_item'                => __( 'Update Item', 'text_domain' ),
            'view_item'                  => __( 'View Item', 'text_domain' ),
            'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
            'add_or_remove_items'        => __( 'Add or remove items', 'text_domain' ),
            'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
            'popular_items'              => __( 'Popular Items', 'text_domain' ),
            'search_items'               => __( 'Search Items', 'text_domain' ),
            'not_found'                  => __( 'Not Found', 'text_domain' ),
            'no_terms'                   => __( 'No items', 'text_domain' ),
            'items_list'                 => __( 'Items list', 'text_domain' ),
            'items_list_navigation'      => __( 'Items list navigation', 'text_domain' ),
        ];
        
        $args = [
            'labels'                     => $labels,
            'hierarchical'               => false,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'show_in_rest'               => true
        ];

	    register_taxonomy( 'workshop_tag', ['workshop'], $args );
    }

}