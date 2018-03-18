<?php

return [
  'upload' => [
    'width' => '1200',
    'quality' => '85',
    'image_metadata' => true,
    'eager' => [
      [
        'width' => '250',
        'crop' => 'scale'
      ],
      [
        'width' => '500',
        'crop' => 'scale'
      ]
    ]
  ]
];