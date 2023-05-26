<?php declare(strict_types=1);

namespace XoopsModules\Xoopsfaq\Common;

use XoopsModules\Xoopsfaq\{
    Contents,
    ContentsHandler
};

class Jsonld
{
    public static function getJsonld(?array $data, $settings = null): string
    {
        $out    = '';
        $jsonld = [];
        $schema = [];

        foreach ($data as $key => $value) {
            $schema['@context']   = 'https://schema.org/';
            $schema['@type']      = 'FAQPage';
            $schema['mainEntity'] = [
                '@type'          => 'Question',
                'name'           => $value['title'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => $value['answer'],
                ],
            ];
            $jsonld[]             = $schema;
        }

        $out .= '<script type="application/ld+json">' . json_encode($jsonld, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';

        return $out;
    }
}
