<?php

return [
//        'tags' => [
//            'a', 'abbr', 'applet', 'area', 'audio', 'base', 'br', 'button', 'canvas', 'caption', 'cite', 'col',
//            'colgroup', 'datalist', 'dd', 'del', 'dfn', 'dialog', 'dir', 'dl', 'dt', 'em', 'embed', 'footer', 'h1',
//            'h2', 'h3', 'h4', 'h5', 'h6', 'head', 'header', 'hr', 'iframe', 'img', 'input', 'ins', 'kbd', 'keygen',
//            'label', 'legend', 'li', 'link', 'map', 'menu', 'menuitem', 'meta', 'meter', 'nav', 'object', 'ol',
//            'optgroup', 'option', 'param', 'progress', 's', 'samp', 'script', 'select', 'source', 'style', 'sub', 'sup',
//            'table', 'tbody', 'textarea', 'tfoot', 'tr', 'track', 'tt', 'ul', 'var', 'video', 'wbr'
//        ],
//        'attributes' => ['header', 'footer', 'nav']
//    ],
    'headline' => [
        'scoring' => [

            'withChildren' => [
                'tags' => 'p',
                'min_words' => 5,
                'points' => '1'
            ],

            'withSiblings' => [
                'tags' => 'p'
            ],

        ]
    ]
];
