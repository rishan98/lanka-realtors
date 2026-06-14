<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Listing kinds & subtypes (real-estate taxonomy)
    |--------------------------------------------------------------------------
    */
    'kinds' => [
        'sale' => [
            'label' => 'For Sale',
            'nav_label' => 'Sales',
            'subtypes' => [
                'house' => 'House',
                'apartment' => 'Apartment',
                'land' => 'Land',
                'commercial' => 'Commercial',
                'bungalow' => 'Bungalow',
                'villa' => 'Villa',
            ],
        ],
        'rental' => [
            'label' => 'For Rent',
            'nav_label' => 'Rentals',
            'subtypes' => [
                'house' => 'House',
                'apartment' => 'Apartment',
                'land' => 'Land',
                'commercial' => 'Commercial',
                'bungalow' => 'Bungalow',
                'villa' => 'Villa',
                'rooms' => 'Rooms',
                'annexe' => 'Annexe',
            ],
        ],
        'projects' => [
            'label' => 'Projects',
            'nav_label' => 'Projects',
            'subtypes' => [
                'house' => 'House',
                'apartment' => 'Apartment',
                'land' => 'Land',
                'commercial' => 'Commercial',
            ],
        ],
        'wanted' => [
            'label' => 'Wanted',
            'nav_label' => 'Wanted',
            'subtypes' => [
                'house' => 'House',
                'apartment' => 'Apartment',
                'land' => 'Land',
                'commercial' => 'Commercial',
                'bungalow' => 'Bungalow',
                'villa' => 'Villa',
                'rooms' => 'Rooms',
                'annexe' => 'Annexe',
            ],
        ],
    ],

    'furnishing_options' => [
        'furnished' => 'Furnished',
        'semi_furnished' => 'Semi-furnished',
        'unfurnished' => 'Unfurnished',
    ],

    'land_size_units' => [
        'perches' => 'Perches',
        'acres' => 'Acres',
    ],

    'max_images' => 10,

    'max_images_by_kind' => [
        'wanted' => 1,
    ],

    'land_hidden_fields' => [
        'bedrooms',
        'bathrooms',
        'built_area_sqft',
        'floors',
        'furnishing_status',
        'parking_available',
    ],

    'homepage_quick' => [
        'buy' => ['kind' => 'sale'],
        'rent' => ['kind' => 'rental'],
        'plot' => ['subtype' => 'land'],
        'commercial' => ['subtype' => 'commercial'],
        'apartments' => ['subtype' => 'apartment'],
        'projects' => ['kind' => 'projects'],
    ],

    'kind_fields' => [
        'sale' => ['city_id', 'latitude', 'longitude', 'bedrooms', 'bathrooms', 'land_size', 'land_size_unit', 'built_area_sqft', 'price', 'floors', 'furnishing_status', 'parking_available', 'title', 'description', 'contact_number', 'images'],
        'rental' => ['city_id', 'latitude', 'longitude', 'bedrooms', 'bathrooms', 'land_size', 'land_size_unit', 'built_area_sqft', 'price', 'floors', 'furnishing_status', 'parking_available', 'title', 'description', 'contact_number', 'images', 'advance_payment_months', 'deposit_months', 'short_term_available', 'bills_included'],
        'projects' => ['city_id', 'latitude', 'longitude', 'bedrooms', 'bathrooms', 'land_size', 'land_size_unit', 'built_area_sqft', 'price', 'floors', 'furnishing_status', 'parking_available', 'title', 'description', 'contact_number', 'images'],
        'wanted' => ['city_id', 'title', 'description', 'contact_number', 'images'],
    ],

    'required_fields' => [
        'sale' => ['city_id', 'bedrooms', 'title', 'description', 'contact_number', 'images'],
        'rental' => ['city_id', 'bedrooms', 'title', 'description', 'contact_number', 'images'],
        'projects' => ['city_id', 'bedrooms', 'title', 'description', 'contact_number', 'images'],
        'wanted' => ['city_id', 'title', 'description', 'contact_number', 'images'],
    ],
];
