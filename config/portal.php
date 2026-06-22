<?php

return [
    'tagline' => 'The Smarter Way to Buy, Sell & Rent Property.',

    'logo' => 'images/logo.png',

    /*
    | Hero search carousel (right column, above the search box).
    | image: path under public/ or a full https URL
    */
    'hero_carousel' => [
        [
            'image' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=900&q=80',
            'alt' => 'Luxury home with pool',
            'url' => null,
        ],
        [
            'image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=900&q=80',
            'alt' => 'Modern living room and terrace',
            'url' => null,
        ],
        [
            'image' => 'https://images.unsplash.com/photo-1613490493576-7fde63acd811?auto=format&fit=crop&w=900&q=80',
            'alt' => 'Contemporary villa exterior',
            'url' => null,
        ],
    ],

    'page_banners' => [
        'lands' => [
            'nav_label' => 'Lands',
            'preview_route' => 'lands.index',
            'preview_params' => [],
        ],
        'find-realtor' => [
            'nav_label' => 'Find realtor',
            'preview_route' => 'find-realtor',
            'preview_params' => [],
        ],
        'owners' => [
            'nav_label' => 'Owners',
            'preview_route' => 'owners',
            'preview_params' => [],
        ],
    ],

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
        'email' => env('PORTAL_CONTACT_EMAIL', 'contactus@lankarealtors.lk'),
        'phone' => env('PORTAL_CONTACT_PHONE', '+94 72 775 8910'),
        'address_line' => '42 Galle Road, Colombo 03',
        'city' => 'Colombo, Sri Lanka',
        'hours' => 'Monday – Friday, 9:00 AM – 6:00 PM (SLST)',
        'latitude' => 6.9018,
        'longitude' => 79.8612,
        'map_zoom' => 15,
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
            'title' => 'New development projects',
            'text' => 'Under-construction inventory with clear handover expectations.',
            'cta' => 'Explore projects',
            'route' => 'listings.browse-kind',
            'params' => ['kind' => 'projects'],
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
            'link_label' => 'Browse property listings in Sri Lanka',
            'route' => 'listings.index',
            'params' => [],
        ],
        [
            'title' => 'Legal & tax basics',
            'text' => 'Know your stamp duty, titles, and approvals—always verify with a lawyer.',
            'link_label' => 'Read about Lanka Realtors',
            'route' => 'about',
            'params' => [],
        ],
        [
            'title' => 'Locality guide',
            'text' => 'Use Locate me and city filters to narrow schools, commute, and lifestyle fit.',
            'link_label' => 'Open map-based property search',
            'route' => 'locate',
            'params' => [],
        ],
        [
            'title' => 'List with confidence',
            'text' => 'Agents can publish rich listings with photos, BHK, and built-up area.',
            'link_label' => 'Register as an agent',
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
                ['label' => 'Apartments for sale in Colombo', 'route' => 'listings.index', 'params' => ['kind' => 'sale', 'subtype' => 'apartment', 'city' => 'Colombo']],
                ['label' => 'Apartments for sale in Kandy', 'route' => 'listings.index', 'params' => ['kind' => 'sale', 'subtype' => 'apartment', 'city' => 'Kandy']],
                ['label' => 'Apartments for sale in Galle', 'route' => 'listings.index', 'params' => ['kind' => 'sale', 'subtype' => 'apartment', 'city' => 'Galle']],
                ['label' => 'Apartments for sale in Negombo', 'route' => 'listings.index', 'params' => ['kind' => 'sale', 'subtype' => 'apartment', 'city' => 'Negombo']],
            ],
        ],
        [
            'title' => 'Houses & villas for sale',
            'links' => [
                ['label' => 'Houses for sale in Colombo', 'route' => 'listings.index', 'params' => ['kind' => 'sale', 'subtype' => 'house', 'city' => 'Colombo']],
                ['label' => 'Houses for sale in Mount Lavinia', 'route' => 'listings.index', 'params' => ['kind' => 'sale', 'subtype' => 'house', 'city' => 'Dehiwala-Mount Lavinia']],
                ['label' => 'Houses for sale in Jaffna', 'route' => 'listings.index', 'params' => ['kind' => 'sale', 'subtype' => 'house', 'city' => 'Jaffna']],
                ['label' => 'Houses for sale in Matara', 'route' => 'listings.index', 'params' => ['kind' => 'sale', 'subtype' => 'house', 'city' => 'Matara']],
            ],
        ],
        [
            'title' => 'Land & plots',
            'links' => [
                ['label' => 'Land for sale — Colombo', 'route' => 'lands.index', 'params' => ['kind' => 'sale', 'city' => 'Colombo']],
                ['label' => 'Land for rent', 'route' => 'lands.index', 'params' => ['kind' => 'rental']],
                ['label' => 'All land listings', 'route' => 'lands.index'],
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
                ['label' => 'Owners', 'route' => 'owners'],
            ],
        ],
    ],
];
