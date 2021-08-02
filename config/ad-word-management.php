<?php

/*
 * You can place your custom package configuration in here.
 */

return [
    'google' => [
        'campaigns' => [
            'fields' =>
                [
                    'Id'     => [
                        'database' => 'id',
                        'method'   => 'getId'
                    ],
                    'Name'   => [
                        'database' => 'name',
                        'method'   => 'getName'
                    ],
                    'Status' => [
                        'database' => 'status',
                        'method'   => 'getStatus'
                    ],
                    'Amount' => [
                        'database' => 'budget',
                        'method'   => 'getBudget.getAmount.getMicroAmount'
                    ],

                ],
            'status' => [
                'ENABLED', 'PAUSED'
            ]
        ],
        'adGroups'  => [
            'fields' => [
                'Id'           => [
                    'database' => 'id',
                    'method'   => 'getId'
                ],
                'CampaignId'   => [
                    'database' => 'campaign_id',
                    'method'   => 'getCampaignId'
                ],
                'Name'         => [
                    'database' => 'name',
                    'method'   => 'getName'
                ],
                'Status'       => [
                    'database' => 'status',
                    'method'   => 'getStatus'
                ],
                'TargetCpaBid' => [
                    'database' => 'bid',
                    'method'   => 'getBiddingStrategyConfiguration.getBids',
                    'custom'   => 'getGoogleAdGroupBid'
                ]
            ],
            'status' => [
                'ENABLED', 'PAUSED'
            ]
        ],
        'ads'       => [
            'fields'  => [
                'Id'                          => [],
                'CreativeFinalUrls'           => [],
                'CreativeFinalMobileUrls'     => [],
                'CreativeFinalAppUrls'        => [],
                'CreativeTrackingUrlTemplate' => [],
                'CreativeUrlCustomParameters' => []
            ],
            'status'  => [
                'ENABLED', 'PAUSED'
            ],
            'type'    => [
                'EXPANDED_TEXT_AD',
                'GMAIL_AD',
                'IMAGE_AD',
                'TEXT_AD'
            ],
            'reports' => [
                'type'   => 'ADGROUP_PERFORMANCE_REPORT',
                'fields' => [
                    'AdGroupId',
                    'Impressions',
                    'AveragePosition',
                    'Clicks',
                    'Cost',
                    'Date',
                    'SearchImpressionShare',

                ],
                'status' => [ 'ENABLED', 'PAUSED' ]
            ]
        ],
        'reports'   => [
            'type'   => 'ADGROUP_PERFORMANCE_REPORT',
            'fields' => [
                'AdGroupId',
                'Impressions',
                'AveragePosition',
                'Clicks',
                'Cost',
                'Date'
            ],
            'status' => [ 'ENABLED', 'PAUSED' ]
        ],
    ],
    'bing'   => [
        'auth'      => [
            'redirect_uri'  => 'https://dev-optimization.beanonetwork.com',
            'client_id'     => 'a8adce8e-64e8-446d-858d-43b0816c0b53',
            'client_secret' => '5fhWEoAFViAjPbYJmwKd1SK',
            //dev client //
//            'client_id'     => 'db41b09d-6e50-4f4a-90ac-5a99caefb52f',
        ],
        'campaigns' => [
            'fields' =>
                [
                    'Id'     => [
                        'database' => 'id',
                        'method'   => 'Id'
                    ],
                    'Name'   => [
                        'database' => 'name',
                        'method'   => 'Name'
                    ],
                    'Status' => [
                        'database' => 'status',
                        'method'   => 'Status'
                    ],
                    'Amount' => [
                        'database'       => 'budget',
                        'method'         => 'DailyBudget',
                        'customFunction' => 'multiply'
                    ],

                ],
            'status' => [
                'ENABLED', 'PAUSED'
            ]
        ],
        'adGroups'  => [
            'fields' => [
                'AdGroupId'  => [
                    'database' => 'id',
                    'method'   => 'Id'
                ],
                'CampaignId' => [
                    'database' => 'campaign_id',
                    'method'   => 'CampaignId'
                ],
                'Name'       => [
                    'database' => 'name',
                    'method'   => 'Name'
                ],
                'Status'     => [
                    'database' => 'status',
                    'method'   => 'Status'
                ],
                'Bid'        => [
                    'database'       => 'bid',
                    'method'         => 'CpcBid.Amount',
                    'customFunction' => 'multiply'
                ]
            ],
        ],
        'reports'   => [
            'type'   => 'ADGROUP_PERFORMANCE_REPORT',
            'fields' => [
                'AdGroupId',
                'Impressions',
                'AveragePosition',
                'Clicks',
                'Spend',
                'TimePeriod'
            ]
        ],
    ],
    'gemini' => [
        'campaigns' => [
            'fields' =>
                [
                    'Id'     => [
                        'database' => 'id',
                        'method'   => 'id'
                    ],
                    'Name'   => [
                        'database' => 'name',
                        'method'   => 'campaignName'
                    ],
                    'Status' => [
                        'database' => 'status',
                        'method'   => 'status'
                    ],
                    'Amount' => [
                        'database'       => 'budget',
                        'method'         => 'budget',
                        'customFunction' => 'multiply'
                    ],

                ],
            'status' => [
                'ENABLED', 'PAUSED'
            ]
        ],
        'adGroups'  => [
            'fields' =>
                [
                    'Id'         => [
                        'database' => 'id',
                        'method'   => 'id'
                    ],
                    'Name'       => [
                        'database' => 'name',
                        'method'   => 'adGroupName'
                    ],
                    'Status'     => [
                        'database' => 'status',
                        'method'   => 'status'
                    ],
                    'Amount'     => [
                        'database'       => 'bid',
                        'method'         => 'bidSet.bids.0.value',
                        'customFunction' => 'multiply',
                    ],
                    'CampaignId' => [
                        'database' => 'campaign_id',
                        'method'   => 'campaignId'
                    ]

                ],
            'status' => [
                'ENABLED', 'PAUSED'
            ]
        ],
        'ads'       => [
            'fields' =>
                [
                    'Id'      => [
                        'database' => 'id',
                        'method'   => 'id'
                    ],
                    'AdGroup' => [
                        'database' => 'ad_group_id',
                        'method'   => 'adGroupId'
                    ]
                ]
        ],
        'reports'   => [
            'type'   => 'performance_stats',
            'fields' => [
                'Ad Group ID',
                'Impressions',
                'Average Position',
                'Clicks',
                'Spend',
                'Day'

            ]
        ]
    ]
];
