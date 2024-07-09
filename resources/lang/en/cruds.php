<?php

return [
    'userManagement'    => [
        'title'          => 'User management',
        'title_singular' => 'User management',
    ],
    'permission'        => [
        'title'          => 'Permissions',
        'title_singular' => 'Permission',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => '',
            'title'             => 'Title',
            'title_helper'      => '',
            'created_at'        => 'Created at',
            'created_at_helper' => '',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => '',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => '',
        ],
    ],
    'role'              => [
        'title'          => 'Roles',
        'title_singular' => 'Role',
        'fields'         => [
            'id'                 => 'ID',
            'id_helper'          => '',
            'title'              => 'Title',
            'title_helper'       => '',
            'permissions'        => 'Permissions',
            'permissions_helper' => '',
            'created_at'         => 'Created at',
            'created_at_helper'  => '',
            'updated_at'         => 'Updated at',
            'updated_at_helper'  => '',
            'deleted_at'         => 'Deleted at',
            'deleted_at_helper'  => '',
        ],
    ],
    'user'              => [
        'title'          => 'Users',
        'title_singular' => 'User',
        'fields'         => [
            'id'                       => 'ID',
            'id_helper'                => '',
            'name'                     => 'Name',
            'name_helper'              => '',
            'veepeeid'                 => 'Veepee ID',
            'veepeeid_helper'          => '',
            'email'                    => 'Email',
            'email_helper'             => '',
            'email_verified_at'        => 'Email verified at',
            'email_verified_at_helper' => '',
            'password'                 => 'Password',
            'password_helper'          => '',
            'roles'                    => 'Roles',
            'roles_helper'             => '',
            'remember_token'           => 'Remember Token',
            'remember_token_helper'    => '',
            'created_at'               => 'Created at',
            'created_at_helper'        => '',
            'updated_at'               => 'Updated at',
            'updated_at_helper'        => '',
            'deleted_at'               => 'Deleted at',
            'deleted_at_helper'        => '',
            'branch'                   => 'Branch',
        ],
    ],
    'productManagement' => [
        'title'          => 'Product Management',
        'title_singular' => 'Product Management',
    ],
    'productCategory'   => [
        'title'          => 'Categories',
        'title_singular' => 'Category',
        'fields'         => [
            'id'                 => 'ID',
            'id_helper'          => '',
            'name'               => 'Name',
				'slug' 				   => 'Slug',
            'slug_helper'        => '',
            'name_helper'        => '',
            'description'        => 'Description',
				'category_level'     => 'Category Level',
				'category_level_helper' => '',
            'description_helper' => '',
            'photo'              => 'Photo',
            'photo_helper'       => '',
            'created_at'         => 'Created at',
            'created_at_helper'  => '',
            'updated_at'         => 'Updated At',
            'updated_at_helper'  => '',
            'deleted_at'         => 'Deleted At',
            'deleted_at_helper'  => '',
        ],
    ],
    'productTag'        => [
        'title'          => 'Tags',
        'title_singular' => 'Tag',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => '',
            'name'              => 'Name',
            'name_helper'       => '',
            'created_at'        => 'Created at',
            'created_at_helper' => '',
            'updated_at'        => 'Updated At',
            'updated_at_helper' => '',
            'deleted_at'        => 'Deleted At',
            'deleted_at_helper' => '',
        ],
    ],
    'product'           => [
        'title'          => 'Products',
        'title_singular' => 'Product',
        'fields'         => [
            'id'                 => 'ID',
            'id_helper'          => '',
            'name'               => 'Name',
            'name_helper'        => '',
            'description'        => 'Description',
            'description_helper' => '',
            'price'              => 'Price',
            'price_helper'       => '',
            'category'           => 'Categories',
            'category_helper'    => '',
            'tag'                => 'Tags',
            'tag_helper'         => '',
            'photo'              => 'Photo',
            'photo_helper'       => '',
            'created_at'         => 'Created at',
            'created_at_helper'  => '',
            'updated_at'         => 'Updated At',
            'updated_at_helper'  => '',
            'deleted_at'         => 'Deleted At',
            'deleted_at_helper'  => '',
        ],
    ],
    // piyush  addon menus 
    'LocationManagement' => [
        'title'          => 'Add State and City',
        'title_singular' => 'Add State and City',
    ],
    'state' => [
        'title'          => 'States',
        'title_singular' => 'States',
    ],
    'city' => [
        'title'          => 'City',
        'title_singular' => 'City',
    ],
    'transport' => [
        'title'          => 'Transport Management',
        'title_singular' => 'Transport',
    ],
    'registration' => [
        'title'          => 'Registration Management',
        'title_singular' => 'Registration',
    ],
    'buyer' => [
        'title'          => 'Buyer Registration',
        'title_singular' => 'Buyer',
    ],
    'supplier' => [
        'title'          => 'Supplier Registration',
        'title_singular' => 'Supplier',
        'profile'        => 'Profile'
    ],

    'station' => [
        'title'          => 'Station Management',
        'title_singular' => 'Station',
    ],

    'branch' => [
        'title'          => 'Branch Management',
        'title_singular' => 'Branch',
    ],

    'item' => [
        'title'          => 'Item Management',
        'title_singular' => 'Item',
    ],

    'size' => [
        'title'          => 'Size Management',
        'title_singular' => 'Size',
    ],

    'brand' => [
        'title'          => 'Brand Management',
        'title_singular' => 'Brand',
    ],

    'color' => [
        'title'          => 'Color Management',
        'title_singular' => 'Color',
    ],

    'country' => [
        'title'          => 'Country Management',
        'title_singular' => 'Country',
    ],

    'order' => [
        'title'          => 'Order Modification',
        'title_singular' => 'Order',
        'delivery'       => 'Add Delivery',
        'dispatch'       => 'Courier to party',
        'approve_bill'   => 'Veepee Bill Pending',
        'accept_order_supplier'              => 'Accept Order Supplier',
        'accept_order_buyer' => 'Accept Order Buyer'
    ],
    'enquiry' => [
        'title'          => 'Enquiry Modification',
        'title_singular' => 'Enquiry'
    ],
    'settings'=>[
        'title_singular' => 'Setting'
    ],
    
];
