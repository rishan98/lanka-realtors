<?php

return [
    'tagline' => 'The Smarter Way to Buy, Sell & Rent Property.',

    'logo' => 'images/logo.jpeg',

    'about' => [
        'eyebrow' => 'About Lanka Realtors Web LK',
        'title' => 'Sri Lanka\'s dedicated property advertising portal for real estate professionals',
        'intro' => [
            'Welcome to Lanka Realtors Web LK — Sri Lanka\'s dedicated property advertising portal created especially for real estate professionals, agencies, brokers, and property marketers.',
            'Established in February 2025, Lanka Realtors was built with a clear vision: to provide realtors with a professional digital platform that increases property visibility, attracts genuine buyers, and helps grow real estate businesses across Sri Lanka.',
            'In today\'s fast-moving property market, realtors need more than traditional advertising. They need a modern online presence that delivers reach, trust, and results. Lanka Realtors offers an easy-to-use and SEO-optimized property portal where agents and agencies can professionally showcase properties to a larger audience.',
        ],
        'established' => 'February 2025',
        'audiences_title' => 'Our platform is designed to support',
        'audiences' => [
            'Licensed real estate agents',
            'Property marketing companies',
            'Independent realtors',
            'Real estate agencies',
            'Property developers',
            'Land and commercial property marketers',
        ],
        'benefits_title' => 'At Lanka Realtors, we help realtors',
        'benefits' => [
            'Advertise properties professionally online',
            'Reach more buyers and investors across Sri Lanka',
            'Increase lead generation opportunities',
            'Build trust and brand visibility',
            'Promote residential and commercial listings effectively',
            'Gain better online exposure through SEO-focused marketing',
        ],
        'belief' => 'We believe realtors are the backbone of the property industry. That is why our platform focuses on giving real estate professionals the tools and visibility they need to succeed in the digital market.',
        'reach' => 'Whether you are marketing luxury homes, apartments, commercial buildings, lands, or investment properties, Lanka Realtors provides a trusted space to connect with serious buyers and investors.',
        'goal' => 'Our goal is to become one of Sri Lanka\'s most reliable and recognized real estate advertising platforms by empowering realtors with innovative digital marketing solutions and high-quality property exposure.',
        'cta_title' => 'Take your real estate business to the next level',
        'cta_text' => 'Join Lanka Realtors Web LK and grow with a platform built for professional realtors.',
    ],

    'seo' => [
        'default_description' => 'Browse property for sale, rent, and investment across Sri Lanka. Find verified agents, map search, and listings on Lanka Realtors.',
        'default_image' => null,
    ],

    'company' => [
        'email' => env('PORTAL_CONTACT_EMAIL', 'hello@lankarealtors.test'),
        'phone' => env('PORTAL_CONTACT_PHONE', '+94 11 234 5678'),
        'address_line' => '42 Galle Road, Colombo 03',
        'city' => 'Colombo, Sri Lanka',
        'hours' => 'Monday – Friday, 9:00 AM – 6:00 PM (SLST)',
    ],

    'promo_cards' => [
        [
            'title' => 'Find a verified realtor',
            'text' => 'Compare agents with active listings and reach out in one click.',
            'cta' => 'Browse agents',
            'route' => 'find-realtor',
            'params' => [],
        ],
        [
            'title' => 'Invest in new projects',
            'text' => 'Under-construction inventory with clear handover expectations.',
            'cta' => 'Explore invest',
            'route' => 'listings.index',
            'params' => ['kind' => 'invest'],
        ],
        [
            'title' => 'Tools & assistance',
            'text' => 'Budgeting, locality search, and map-based discovery—more tools coming soon.',
            'cta' => 'Open map search',
            'route' => 'locate',
            'params' => [],
        ],
    ],

    'advice_cards' => [
        [
            'title' => 'Research & insights',
            'text' => 'Understand pricing trends and documentation before you commit.',
            'href' => '#',
        ],
        [
            'title' => 'Legal & tax basics',
            'text' => 'Know your stamp duty, titles, and approvals—always verify with a lawyer.',
            'href' => '#',
        ],
        [
            'title' => 'Locality guide',
            'text' => 'Use Locate me and city filters to narrow schools, commute, and lifestyle fit.',
            'href' => '#',
        ],
        [
            'title' => 'List with confidence',
            'text' => 'Agents can publish rich listings with photos, BHK, and built-up area.',
            'route' => 'register',
            'params' => [],
        ],
    ],

    'budget_presets_lkr' => [
        5000000, 10000000, 15000000, 20000000, 30000000, 40000000, 50000000,
        75000000, 100000000, 150000000, 200000000, 300000000, 500000000,
    ],

    'sqft_presets' => [500, 750, 1000, 1200, 1500, 2000, 2500, 3000, 4000, 5000],

    /*
    | Footer “top cities / areas” style deep links (MagicBricks-style SEO columns).
    */
    'footer_columns' => [
        [
            'title' => 'Flats & apartments for sale',
            'links' => [
                ['label' => 'Colombo', 'route' => 'listings.index', 'params' => ['kind' => 'sale', 'subtype' => 'apartment', 'city' => 'Colombo']],
                ['label' => 'Kandy', 'route' => 'listings.index', 'params' => ['kind' => 'sale', 'subtype' => 'apartment', 'city' => 'Kandy']],
                ['label' => 'Galle', 'route' => 'listings.index', 'params' => ['kind' => 'sale', 'subtype' => 'apartment', 'city' => 'Galle']],
                ['label' => 'Negombo', 'route' => 'listings.index', 'params' => ['kind' => 'sale', 'subtype' => 'apartment', 'city' => 'Negombo']],
            ],
        ],
        [
            'title' => 'Houses & villas for sale',
            'links' => [
                ['label' => 'Colombo', 'route' => 'listings.index', 'params' => ['kind' => 'sale', 'subtype' => 'house', 'city' => 'Colombo']],
                ['label' => 'Mount Lavinia', 'route' => 'listings.index', 'params' => ['kind' => 'sale', 'subtype' => 'house', 'city' => 'Dehiwala-Mount Lavinia']],
                ['label' => 'Jaffna', 'route' => 'listings.index', 'params' => ['kind' => 'sale', 'subtype' => 'house', 'city' => 'Jaffna']],
                ['label' => 'Matara', 'route' => 'listings.index', 'params' => ['kind' => 'sale', 'subtype' => 'house', 'city' => 'Matara']],
            ],
        ],
        [
            'title' => 'Land & plots',
            'links' => [
                ['label' => 'Land for sale — Colombo', 'route' => 'listings.index', 'params' => ['kind' => 'sale', 'subtype' => 'land', 'city' => 'Colombo']],
                ['label' => 'Land for rent', 'route' => 'listings.index', 'params' => ['kind' => 'rental', 'subtype' => 'land']],
                ['label' => 'Commercial land', 'route' => 'listings.index', 'params' => ['subtype' => 'land']],
                ['label' => 'All land listings', 'route' => 'listings.index', 'params' => ['subtype' => 'land']],
            ],
        ],
        [
            'title' => 'Rentals & rooms',
            'links' => [
                ['label' => 'Apartments for rent — Colombo', 'route' => 'listings.index', 'params' => ['kind' => 'rental', 'subtype' => 'apartment', 'city' => 'Colombo']],
                ['label' => 'Rooms & annexe', 'route' => 'listings.index', 'params' => ['kind' => 'rental', 'subtype' => 'rooms']],
                ['label' => 'Short term rentals', 'route' => 'listings.index', 'params' => ['kind' => 'rental']],
                ['label' => 'Commercial for rent', 'route' => 'listings.index', 'params' => ['kind' => 'rental', 'subtype' => 'commercial']],
            ],
        ],
        [
            'title' => 'Company',
            'links' => [
                ['label' => 'About us', 'route' => 'about'],
                ['label' => 'Contact us', 'route' => 'contact'],
                ['label' => 'Find a realtor', 'route' => 'find-realtor'],
                ['label' => 'Grab me', 'route' => 'grab-me'],
            ],
        ],
    ],
];
