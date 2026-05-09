<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet
    version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:xhtml="http://www.w3.org/1999/xhtml"
>

    <xsl:output
        method="html"
        version="1.0"
        encoding="UTF-8"
        indent="yes"
    />

    <xsl:template match="/">

        <html xmlns="http://www.w3.org/1999/xhtml">

            <head>

                <title>XML Sitemap</title>

                <meta
                    http-equiv="Content-Type"
                    content="text/html; charset=utf-8"
                />

                <meta
                    name="viewport"
                    content="width=device-width, initial-scale=1"
                />

                <style type="text/css">

                    body {
                        margin: 0;
                        padding: 40px 20px;
                        background: #f8fafc;
                        color: #1e293b;
                        font-family: Arial, sans-serif;
                        line-height: 1.6;
                    }

                    .container {
                        max-width: 1100px;
                        margin: 0 auto;
                        background: #ffffff;
                        border-radius: 12px;
                        padding: 30px;
                        box-shadow:
                            0 4px 10px rgba(0,0,0,0.05);
                    }

                    h1 {
                        margin-top: 0;
                        margin-bottom: 10px;
                        font-size: 32px;
                    }

                    .description {
                        margin-bottom: 30px;
                        color: #64748b;
                    }

                    .table-wrapper {
                        overflow-x: auto;
                    }

                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }

                    thead th {
                        background: #f1f5f9;
                        color: #0f172a;
                        font-size: 14px;
                        text-align: left;
                        padding: 14px;
                        border-bottom: 1px solid #cbd5e1;
                    }

                    tbody td {
                        padding: 14px;
                        border-bottom: 1px solid #e2e8f0;
                        font-size: 14px;
                        vertical-align: top;
                    }

                    tbody tr:nth-child(even) {
                        background: #f8fafc;
                    }

                    tbody tr:hover {
                        background: #eef2ff;
                    }

                    a {
                        color: #2563eb;
                        text-decoration: none;
                        word-break: break-word;
                    }

                    a:hover {
                        text-decoration: underline;
                    }

                    .badge {
                        display: inline-block;
                        padding: 4px 10px;
                        border-radius: 999px;
                        background: #e2e8f0;
                        font-size: 12px;
                        color: #334155;
                    }

                    .footer {
                        margin-top: 24px;
                        color: #64748b;
                        font-size: 13px;
                    }

                </style>

            </head>

            <body>

                <div class="container">

                    <h1>
                        XML Sitemap
                    </h1>

                    <p class="description">
                        This sitemap contains all publicly
                        accessible URLs on this website.
                    </p>

                    <!-- Sitemap Index -->
                    <xsl:if test="count(sitemap:sitemapindex/sitemap:sitemap) &gt; 0">

                        <div class="table-wrapper">

                            <table>

                                <thead>

                                    <tr>
                                        <th width="75%">
                                            Sitemap
                                        </th>

                                        <th width="25%">
                                            Last Modified
                                        </th>
                                    </tr>

                                </thead>

                                <tbody>

                                    <xsl:for-each select="sitemap:sitemapindex/sitemap:sitemap">

                                        <tr>

                                            <td>
                                                <a href="{sitemap:loc}">
                                                    <xsl:value-of select="sitemap:loc" />
                                                </a>
                                            </td>

                                            <td>
                                                <xsl:value-of
                                                    select="concat(
                                                        substring(sitemap:lastmod,1,10),
                                                        ' ',
                                                        substring(sitemap:lastmod,12,5)
                                                    )"
                                                />
                                            </td>

                                        </tr>

                                    </xsl:for-each>

                                </tbody>

                            </table>

                        </div>

                    </xsl:if>

                    <!-- URL Set -->
                    <xsl:if test="count(sitemap:urlset/sitemap:url) &gt; 0">

                        <div class="table-wrapper">

                            <table>

                                <thead>

                                    <tr>

                                        <th width="55%">
                                            URL
                                        </th>

                                        <th width="10%">
                                            Language
                                        </th>

                                        <th width="10%">
                                            Priority
                                        </th>

                                        <th width="10%">
                                            Change Frequency
                                        </th>

                                        <th width="15%">
                                            Last Modified
                                        </th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <xsl:for-each select="sitemap:urlset/sitemap:url">

                                        <tr>

                                            <td>
                                                <a href="{sitemap:loc}">
                                                    <xsl:value-of select="sitemap:loc" />
                                                </a>
                                            </td>

                                            <td>

                                                <xsl:choose>

                                                    <xsl:when test="xhtml:link/@hreflang">

                                                        <span class="badge">

                                                            <xsl:value-of
                                                                select="xhtml:link/@hreflang"
                                                            />

                                                        </span>

                                                    </xsl:when>

                                                    <xsl:otherwise>
                                                        —
                                                    </xsl:otherwise>

                                                </xsl:choose>

                                            </td>

                                            <td>

                                                <xsl:choose>

                                                    <xsl:when test="sitemap:priority">

                                                        <xsl:value-of
                                                            select="sitemap:priority"
                                                        />

                                                    </xsl:when>

                                                    <xsl:otherwise>
                                                        —
                                                    </xsl:otherwise>

                                                </xsl:choose>

                                            </td>

                                            <td>

                                                <xsl:choose>

                                                    <xsl:when test="sitemap:changefreq">

                                                        <xsl:value-of
                                                            select="sitemap:changefreq"
                                                        />

                                                    </xsl:when>

                                                    <xsl:otherwise>
                                                        —
                                                    </xsl:otherwise>

                                                </xsl:choose>

                                            </td>

                                            <td>

                                                <xsl:choose>

                                                    <xsl:when test="sitemap:lastmod">

                                                        <xsl:value-of
                                                            select="concat(
                                                                substring(sitemap:lastmod,1,10),
                                                                ' ',
                                                                substring(sitemap:lastmod,12,5)
                                                            )"
                                                        />

                                                    </xsl:when>

                                                    <xsl:otherwise>
                                                        —
                                                    </xsl:otherwise>

                                                </xsl:choose>

                                            </td>

                                        </tr>

                                    </xsl:for-each>

                                </tbody>

                            </table>

                        </div>

                    </xsl:if>

                    <div class="footer">

                        Generated by XML Sitemap.

                    </div>

                </div>

            </body>

        </html>

    </xsl:template>

</xsl:stylesheet>
