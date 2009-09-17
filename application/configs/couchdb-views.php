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
                    function(key, values, rereduce) {
                        total = 0;
                        
                        for (i = 0; i < values.length; ++i) {
                            total += values[i];
                        }
                        
                        return total;
                    }
EOJS
            )
        )
    )
);