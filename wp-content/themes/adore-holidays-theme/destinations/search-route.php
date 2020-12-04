<?php

add_action('rest_api_init', 'destinationRegisterSearch');

function destinationRegisterSearch() {
    register_rest_route('destination/v1', 'search', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'destinationSearchResults'
    ));
}

function destinationSearchResults($data) {
    $mainQuery = new WP_Query(array(
        'post_type' => array('post', 'page', 'category', 'destination', 'villa', 'event'),
        's' => sanitize_text_field($data['term']) 
    ));

    $results = array(
        'generalInfo' => array(),
        'category' => array(),
        'destination' => array(),
        'villa' => array(),
        'event' => array()
    );

    while($mainQuery->have_posts()) {
        $mainQuery->the_post();

        if (get_post_type() == 'post' OR get_post_type() == 'page') {
            array_push($results['generalInfo'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink()
            ));
        }

        if (get_post_type() == 'category') {
            array_push($results['category'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink()
            ));
        }
        
        if (get_post_type() == 'destination') {
            array_push($results['destination'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'image' => get_the_post_thumbnail_url(0 , 'locationThumbnail'),
                'id' => get_the_id()
            ));
        }

        if (get_post_type() == 'villa') {
            array_push($results['villa'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'image' => get_the_post_thumbnail_url(0 , 'locationThumbnail')
            ));
        }

        if (get_post_type() == 'event') {
            array_push($results['event'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink()
            ));
        }
    }

    if ($results['destination']) {
        $destinationMetaQuery = array('relation' => 'OR');

    foreach($results['destination'] as $item) {
        array_push($destinationMetaQuery, array(
            'key' => 'related_destinations',
            'compare' => 'LIKE',
            'value' => '"' . $item['id'] . '"'
        ));
    }

    $villaRelationshipQuery = new WP_Query(array(
        'post_type' => 'villa',
        'meta_query' => $destinationMetaQuery
    ));


    while($villaRelationshipQuery->have_posts()) {
        $villaRelationshipQuery->the_post();

        if (get_post_type() == 'villa') {
            array_push($results['villa'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'image' => get_the_post_thumbnail_url(0 , 'locationThumbnail')
            ));
        }


    }

    $results['villa'] = array_values(array_unique($results['villa'], SORT_REGULAR));
    

    }  

    return $results;
}

