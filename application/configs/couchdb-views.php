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
                            emit([doc._id, "I"], null);
                        } else if (doc['@doctype'] == 'Comment') {
                            emit([doc.post_id, doc.created_at], null);
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
                            emit(doc.normalized_title, null);
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
                            	tag = doc.tags[i];
                            	for (j = i + 1; j < doc.tags.length; j++) {
                                    relatedTag = doc.tags[j];
                                    emit([tag, relatedTag], 1);
                                    emit([relatedTag, tag], 1); 
                                }
                            }
                        }
                    }
EOJS
                ,
                'reduce' => <<<EOJS
                	function(keys, values, rereduce) {
                		total = 0;
                		for (i = 0; i < values.length; i++) {
                            total += values[i];
                        }
                        return total;
                    }
EOJS
            )
        )
    )
);
