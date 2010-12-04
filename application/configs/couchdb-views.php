<?php

/**
 * CouchDB Views
 */

return array(
    'post' => array(
        'language' => 'javascript',
        'views'    => array(
            'recent-posts' => array(
                'map' => <<<EOJS
                    function(doc) {
                        if (doc['@doctype'] == 'Post' && doc.published) {
                            emit([doc.created_at, doc._id], doc);
                        }
                    }
EOJS
            ),
            
            'with-comments' => array(
                'map' => <<<EOJS
                    function(doc) {
                        if (doc['@doctype'] == 'Post') {
                            emit([doc._id, "I"], doc);
                        } else if (doc['@doctype'] == 'Comment') {
                            emit([doc.post_id, doc.created_at], doc);
                        }
                    }
EOJS
            ),
            
            'comment-count' => array(
                'map' => <<<EOJS
                    function(doc) {
                        if (doc['@doctype'] == 'Post') {
                            emit(doc._id, 0);
                        } else if (doc['@doctype'] == 'Comment') {
                            emit(doc.post_id, 1);
                        }
                    }
EOJS
                ,
                'reduce' => <<<EOJS
                    function(key, values) {
                        total = 0;
                        
                        for (i = 0; i < values.length; ++i) {
                            total += values[i];
                        }
                        
                        return total;
                    }
EOJS
            ),
            
            'by-tag' => array(
                'map' => <<<EOJS
                    function(doc) {
                        if (doc['@doctype'] == 'Post' && doc.published) {
                            for (i = 0; i < doc.tags.length; i++) {
                                emit([doc.tags[i], doc.created_at], doc);
                            }
                        }
                    }
EOJS
            ),
            
            'by-normalized-title' => array(
                'map' => <<<EOJS
                    function(doc) {
                        if (doc['@doctype'] == 'Post' && doc.published) {
                            emit(doc.normalized_title, doc);
                        }
                    }
EOJS
            )
        )
    ),
    
    'tag' => array(
        'language' => 'javascript',
        'views'    => array(
            'popular' => array(
                'map' => <<<EOJS
                    function(doc) {
                        if (doc['@doctype'] == 'Post' && doc.published) {
                            for (i = 0; i < doc.tags.length; i++) {
                                emit(doc.tags[i], 1);
                            }
                        }
                    }
EOJS
                ,
                'reduce' => <<<EOJS
                    function (keys, values, rereduce) {
                        total = 0;
                        
                        for (i = 0; i < values.length; i++) {
                            total += values[i];
                        }
                        
                        return total;
                    }
EOJS
            ),
            'related' => array(
                'map' => <<<EOJS
                    function(doc) {
                        if (doc['@doctype'] == 'Post' && doc.published) {
                            for (i = 0; i < doc.tags.length; i++) {
                                relatedTags = {};
                                for (j = 0; j < doc.tags.length; j++) {
                                    if (j != i) { 
                                        relatedTags[doc.tags[j]] = 1; 
                                    }
                                }
                                
                                emit(doc.tags[i], relatedTags);
                            }
                        }
                    }
EOJS
            )
        )
    )
);
