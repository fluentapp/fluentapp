<?php

// Production environment

return function (array $settings): array {
    $settings['db']['database'] = 'fluentapp';

    return $settings;
};
