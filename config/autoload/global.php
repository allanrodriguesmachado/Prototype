<?php

/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

use Laminas\Http\Client;

return [
    /**
     * Connection LDAP
     */
    'auth' => [
        'class' => 'Laminas\Authentication\Adapter\Ldap',
        'config' => [
            'server1' => [
                'host' => "ldap.forumsys.com",
                'username' => "cn=read-only-admin,dc=example,dc=com",
                'password' => 'password',
                'accountFilterFormat' => '(&(objectClass=inetOrgPerson)(uid=%s))',
                'bindRequiresDn' => true,
                'baseDn' => 'dc=example,dc=com',
            ],
        ],
    ],

    /**
     * WebServices Mercatus
     */

//    'client' => [
//        'class' => 'Laminas\Http\Client',
//        'config' => [
//            'client' => [
//                'uri' => 'http://cep.mercatus.com.br/cep.php?',
//                [
//                    'maxredirects' => 0,
//                    'tempo limite' => 30
//                ],
//                'params' => 'setParameterGet',
//                [
//                    'cep' => cep,
//                    'format' => 'json',
//                    'key' => 'f7a73a53e5e2866c49c57df8583ce1e5'
//                ]
//            ]
//        ]
//    ],


    'session' => [
        'class' => 'Laminas\Session\Container',
        'config' => [
            'name' => 'portal'
        ],
    ],
];
