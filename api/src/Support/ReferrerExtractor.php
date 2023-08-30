<?php

namespace App\Support;

/*
 * @author Mohiddeen Mneimne
 *
 * This class will try to sanitize and group HTTP Referrer header
 *
 * Sanitization: removes www. from a given value
 *
 * Grouping: convert google.com.lb, google.com.sa, google.ae, etc..
 * to Google
 *
 * The list of groups will grow with time
 *
 */

final class ReferrerExtractor
{
    /**
     * The domain name is used as a key to have O(1) complexity
     * when searching for a domain and returning a unified name.
     * @var array
     */
    public $referrerList = [
        'google.com' => 'Google',
        'google.com.lb' => 'Google', // Lebanon
        'google.ae' => 'Google', // UAE
        'google.co.uk' => 'Google', // UK
        'google.ca' => 'Google', // Canada
        'google.fr' => 'Google', // France
        'google.de' => 'Google', // Germany
        'google.co.in' => 'Google', // India
        'google.co.jp' => 'Google', // Japan
        'google.com.br' => 'Google', // Brazil
        'google.com.au' => 'Google', // Australia
        'google.es' => 'Google', // Spain
        'google.it' => 'Google', // Italy
        'google.com.mx' => 'Google', // Mexico
        'google.nl' => 'Google', // Netherlands
        'google.co.za' => 'Google', // South Africa
        'google.com.tr' => 'Google', // Turkey
        'google.ru' => 'Google', // Russia
        'google.cn' => 'Google', // China
        'google.co.kr' => 'Google', // South Korea
        'google.co.th' => 'Google', // Thailand
        //DuckDuckGo
        'duckduckgo.com' => 'DuckDuckGo',
        //Bing
        'bing.com' => 'Bing',
        'www.bing.com' => 'Bing',
        'msnbc.msn.com' => 'Bing',
        'dizionario.it.msn.com' => 'Bing',
        'cc.bingj.com' => 'Bing',
        'm.bing.com' => 'Bing',
    ];

    /**
     *
     * @param string $url
     * @return string
     */
    public function extract(string $url): string
    {

        // This should get the domain and remove any www. subdomain
        $parsedUrl = parse_url($url);
        $host = $parsedUrl['host'];
        if (str_replace('www.', '', $host)) {
            $host = str_replace('www.', '', $host);
        }

        // No we return a unified name if it exists in the array or the original
        // domain name

        return isset($this->referrerList[$host]) ?
                $this->referrerList[$host] :
                $host;
    }
}
