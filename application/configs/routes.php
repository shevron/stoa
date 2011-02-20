<?php

/**
 * Route Configuration
 * 
 */

return array(
    'new-post' => array(
        'route' => 'new-post', 
        'defaults' => array(
            'controller' => 'post',
            'action'     => 'new'
        )
    ),
    
    'show-post-id' => array(
        'route' => 'post/id/:id',
        'defaults' => array(
            'controller' => 'post',
            'action'     => 'show'
        )
    ),
    
    'show-post' => array(
        'route' => 'post/:title',
        'defaults' => array(
            'controller' => 'post',
            'action'     => 'show'
        )
    ),
    
    'show-tag' => array(
        'route' => 'tag/:tag',
        'defaults' => array(
            'controller' => 'post',
            'action'     => 'index'
        )
    )
);
