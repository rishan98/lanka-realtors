<?php

return [
    'tagline' => 'Properties for every Sri Lankan buyer & renter.',

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
