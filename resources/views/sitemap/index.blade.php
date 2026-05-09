@php
    echo '<?xml version="1.0" encoding="UTF-8"?>';
@endphp

<?xml-stylesheet
    type="text/xsl"
    href="{{ asset('sitemap.xsl') }}"
?>

<sitemapindex
    xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
>

    @foreach($sitemaps as $sitemap)

        <sitemap>

            <loc>
                {{ e($sitemap['loc']) }}
            </loc>

            @if(! empty($sitemap['lastmod']))

                <lastmod>
                    {{
                        \Carbon\Carbon::parse(
                            $sitemap['lastmod']
                        )->toAtomString()
                    }}
                </lastmod>

            @endif

        </sitemap>

    @endforeach

</sitemapindex>
