<?php

return [
    // The model which creates the comments aka the User model
    'models' => [
        /**
         * Commenter model
         */
        'commenter' => \App\User::class,
        /**
         * Comment model
         */
        'comment' => \ExclusiveDev\Commenist\Entity\Comment::class
    ],    
    'purifier' => [
        'HTML_Allowed' => 'p',
    ],
    'route' => [
        'root' => 'api',
        'group' => 'comments'
    ],
    'policy_prefix' => 'comments',
    'testing' => [
        'seeding' => [
            'commentable' => '\App\Post',
            'commenter' => '\App\User'
        ]
    ],
    /**
     * Only for API
     *
     * @example ['get']['preprocessor']['user'] => App\UseCases\CommentPreprocessor\User::class
     */
    'api' => [
        'get' => [
            'preprocessor' => [
                'user' => null,
                'comment' => null
            ]
        ]
    ]
];