# HDS Onderhoudsdiensten — SEO Implementation Specification

**Document ID:** SEO-001 | **Version:** 1.0.0 | **Status:** Implementation-Ready
**Project:** helderduidelijkschoon.nl — Ground-Up Rebuild
**Language:** Nederlands (nl-NL) | **Date:** July 2026
**Referenced Documents:** RTM-001 (REQ-SEO-001..028), FS-001 (§11), NFR-001 (§9), SA-001 (§14), DS-001 (§13), WTA-001 (§12), SRC-07 (SEO Audit)

---

## 1. SEO Goals

### 1.1 Business Goals

| # | Goal | Metric | Target |
|---|---|---|---|
| BG01 | Restore organic search visibility for all 7 service lines | Each service page indexed in Google within 30 days of launch | 7/7 indexed |
| BG02 | Generate qualified B2B leads via organic search | Contact + Quote form submissions attributed to organic search | ≥ 5/month post-launch |
| BG03 | Establish local search presence in West-Brabant / Zeeland | LocalBusiness schema validated; GBP linked; NAP consistent | Google Rich Results Test pass |
| BG04 | Outrank competitors for primary service keywords | Track keyword rankings vs pre-migration baseline | Top 10 for "reguliere schoonmaak [regio]" within 6 months |
| BG05 | Eliminate all current-site SEO defects | Zero broken sitemaps; zero broken pages; zero missing meta | 100% at launch |

### 1.2 Organic Traffic Goals

| # | Goal | Baseline (Current Site) | Target (6 Months Post-Launch) |
|---|---|---|---|
| OG01 | Increase indexed pages | ~12 pages (sitemap broken) | 30+ pages indexed |
| OG02 | Improve average CTR from SERPs | Unknown (no GSC data?) | ≥ 3% for branded queries; ≥ 1.5% for service queries |
| OG03 | Increase organic impressions | Pre-migration baseline (GSC export) | +50% within 6 months |
| OG04 | Reduce crawl errors | Page sitemap HTTP 500; 404 on primary service | Zero crawl errors in GSC |

### 1.3 Lead Generation Goals

| # | Goal | Metric |
|---|---|---|
| LG01 | Contact form submissions from organic search | Track via GA4 `form_submission` event + UTM source |
| LG02 | Quote request submissions from organic search | Track via GA4 `quote_request` event |
| LG03 | Phone calls from organic search (attributed) | Track via GA4 `phone_click` event |
| LG04 | Reduce bounce rate on service pages | < 60% bounce rate (organic traffic) |

### 1.4 Local SEO Goals

| # | Goal | Metric |
|---|---|---|
| LS01 | Appear in Google Maps / Local Pack for cleaning service queries | GBP verified + LocalBusiness schema valid + NAP consistent |
| LS02 | Consistent NAP across website, GBP, and directories | Manual audit of all listings |
| LS03 | Generate reviews on Google Business Profile | ≥ 5 reviews within 6 months (client responsibility) |
| LS04 | Service area clearly communicated to search engines | LocalBusiness schema with `areaServed` |

---

## 2. Technical SEO

### 2.1 URL Structure

**Base Domain:** `https://helderduidelijkschoon.nl/` (canonical, non-www, HTTPS, trailing slash)

| Rule | Specification | Implementation |
|---|---|---|
| **Protocol** | HTTPS only | Cloudflare: Always Use HTTPS enabled. HTTP → 301 to HTTPS. |
| **www / non-www** | Non-www canonical | Cloudflare Page Rule or Rank Math: www → 301 to non-www. |
| **Trailing Slash** | WITH trailing slash for all pages | WordPress permalink `/%postname%/` produces trailing slash automatically. |
| **Case** | All lowercase | Standard WordPress slug behavior. |
| **Language** | Dutch words, hyphens between words | Manual slug entry when creating pages. Example: `/reguliere-schoonmaak/` |
| **Diacritics** | No diacritics in slugs | WordPress auto-strips. `industriële` → `industriele`. |
| **Depth** | Maximum 1 level from root | Flat URL structure. Exceptions: `/product/{slug}/`, `/kennisbank/{slug}/`. |
| **File Extensions** | None | Clean URLs only. No `.html`, `.php`. |
| **Query Parameters** | None for pages | No `?page_id=N`. All pages use descriptive slugs. |

### 2.2 Permalink Policy

| Setting | Value |
|---|---|
| **Permalink Structure** | `/%postname%/` (Post name) |
| **Category Base** | `kennisbank` |
| **Tag Base** | Not used (tags not implemented at launch) |
| **Product Permalink Base** | `/product/` (WooCommerce default) |

**Blog URL Format:** `/kennisbank/{post-slug}/` — permanent URLs. No date prefix (`/2026/07/`). Rationale: Permanent URLs are better for SEO longevity; date-prefixed URLs look stale after a few years.

### 2.3 Canonical Rules

| Rule | Specification | Implementation |
|---|---|---|
| **Self-Referencing** | Every page has a self-referencing canonical URL | Rank Math Pro auto-generates |
| **Trailing Slash** | Canonical includes trailing slash | WordPress default |
| **Pagination** | Page 2 → canonical points to page 1 | Rank Math Pro pagination settings |
| **WooCommerce** | Product variations → canonical points to parent product | WooCommerce default |
| **Cross-Domain** | None. All canonicas are self-referencing on `helderduidelijkschoon.nl`. | — |

**Verification:** Screaming Frog crawl → Canonicals tab → "Canonicalised" = zero for unexpected pages. "Canonical points to different URL" = zero (except paginated archives pointing to page 1).

### 2.4 Redirect Policy

| Type | Usage | Implementation |
|---|---|---|
| **301 (Moved Permanently)** | Old URL → New URL. Passes ~90-99% of link equity. | Rank Math Pro Redirect Manager |
| **302 (Found)** | **NOT USED.** Never. | — |
| **307 (Temporary)** | **NOT USED.** Never. | — |
| **410 (Gone)** | Content permanently removed. Tell search engines to de-index. | Rank Math Pro Redirect Manager |

**Redirect Chain Rule:** Zero tolerance. A → B → C is forbidden. Every old URL must redirect directly to the final destination (A → C). Verified via `httpstatus.io`.

**Redirect Map:**

| # | Old URL | HTTP Status | New URL |
|---|---|---|---|
| 1 | `/glasbewassing` (no trailing slash) | 301 | `/glasbewassing/` |
| 2 | `/vve` | 301 | `/vve-service/` |
| 3 | `/vve/` | 301 | `/vve-service/` |
| 4 | `/?page_id=318` | 301 | `/reguliere-schoonmaak/` |
| 5 | `http://helderduidelijkschoon.nl/*` | 301 | `https://helderduidelijkschoon.nl/*` |
| 6 | `http://www.helderduidelijkschoon.nl/*` | 301 | `https://helderduidelijkschoon.nl/*` |
| 7 | `https://www.helderduidelijkschoon.nl/*` | 301 | `https://helderduidelijkschoon.nl/*` |
| 8 | `/2015/06/29/hallo-wereld/` | 410 | — |
| 9 | `/2015/08/25/kwaliteit-veiligheid/` | 410 | — |

**Additional redirects** (if legacy domain PDFs are migrated):
| 10+ | `https://hds-onderhoudsdiensten.nl/pdfs/*` | 301 | `https://helderduidelijkschoon.nl/wp-content/uploads/*` |

### 2.5 404 Handling

| Requirement | Implementation |
|---|---|
| **Custom 404 Page** | `404.php` template. Returns true HTTP 404 status code. |
| **404 Content** | "Pagina niet gevonden" heading. Search bar. Key links (Home, Diensten, Contact, FAQ). Phone + Email. |
| **404 Monitoring** | Rank Math Pro 404 Monitor enabled. All 404 hits logged. |
| **404 Review** | Weekly review of 404 log. High-traffic 404s → create 301 redirects. |
| **Soft 404s** | GSC "Soft 404" report monitored. Zero soft 404s targeted. |

### 2.6 XML Sitemap

| Requirement | Implementation |
|---|---|
| **Generator** | Rank Math Pro |
| **Index URL** | `/sitemap_index.xml` — returns HTTP 200 with valid XML |
| **Sub-Sitemaps** | `page-sitemap.xml`, `post-sitemap.xml`, `product-sitemap.xml` |
| **Included** | All public pages, published posts, published products |
| **Excluded** | Attachment pages, author archives, `/bedankt/` (noindex), cart/checkout/account pages |
| **Ping Search Engines** | Enabled (Rank Math auto-pings Google + Bing on content change) |
| **Submission** | Submitted to Google Search Console + Bing Webmaster Tools at launch |
| **Verification** | GSC → Sitemaps → "Status: Success" for all sub-sitemaps. Zero errors. |

**Critical Check:** `/page-sitemap.xml` must return HTTP 200. The current site's page sitemap returns HTTP 500 — this is a launch-blocking defect that the rebuild must resolve.

### 2.7 robots.txt

**Generated by:** Rank Math Pro at `/robots.txt`.

**Content:**
```
User-agent: *
Disallow: /wp-admin/
Disallow: /wp-includes/
Allow: /wp-admin/admin-ajax.php
Disallow: /wp-content/plugins/
Disallow: /wp-content/themes/
Disallow: /bedankt/
Disallow: /winkelmand/
Disallow: /afrekenen/
Disallow: /mijn-account/
Disallow: /*?* (except WooCommerce query parameters)

Sitemap: https://helderduidelijkschoon.nl/sitemap_index.xml
```

**Verification:** `/robots.txt` returns HTTP 200. GSC → robots.txt Tester → "Allowed" for key pages. "Disallowed" for blocked paths.

### 2.8 Pagination

| Requirement | Implementation |
|---|---|
| **Blog Archive** | Standard WordPress pagination (`the_posts_pagination()`). URL: `/kennisbank/page/2/`. |
| **WooCommerce Shop** | Standard WooCommerce pagination. URL: `/winkel/page/2/`. |
| **Canonical** | Page 2+ → canonical points to page 1 (root of the archive). |
| **`rel="next"` / `rel="prev"`** | Not implemented. Google deprecated these in 2019. |
| **Noindex** | Paginated pages are NOT noindexed. Google handles pagination via canonical + crawl budget. |

### 2.9 Attachment Pages

| Requirement | Implementation |
|---|---|
| **Redirect** | Attachment page URLs → 301 redirect to parent post/page. |
| **Sitemap Exclusion** | Attachment pages excluded from XML sitemap (Rank Math setting). |
| **Theme** | `template_redirect` hook in `inc/security.php` redirects attachment pages. |

**Rationale:** The current site has ~50 attachment pages indexed in the sitemap, consuming crawl budget and producing thin/duplicate content. All attachment pages are redirected and removed from sitemaps.

### 2.10 HTTPS

| Requirement | Implementation |
|---|---|
| **Enforcement** | HTTP → 301 to HTTPS. Cloudflare "Always Use HTTPS" enabled. |
| **HSTS** | `Strict-Transport-Security: max-age=31536000; includeSubDomains; preload` |
| **SSL Certificate** | Cloudflare Edge Certificate (free). Origin: Let's Encrypt or Cloudflare Origin CA. |
| **Mixed Content** | Zero tolerance. All assets loaded via HTTPS. |
| **Verification** | SSL Labs grade A+. `securityheaders.com` HSTS present. Screaming Frog: zero HTTP URLs crawled. |

---

## 3. Metadata Standards

### 3.1 Title Tag Templates

| Page Type | Title Pattern | Max Length |
|---|---|---|
| **Homepage** | `HDS Onderhoudsdiensten | Schoonmaak- en Onderhoudsdiensten West-Brabant Zeeland` | 60 chars |
| **Service Pages** | `{Service Name} — HDS Onderhoudsdiensten` | 60 chars |
| **Category Landings** | `{Category Name} — HDS Onderhoudsdiensten` | 60 chars |
| **About Pages** | `{Page Title} — HDS Onderhoudsdiensten` | 60 chars |
| **Contact** | `Contact — HDS Onderhoudsdiensten` | 60 chars |
| **Offerte** | `Offerte Aanvragen — HDS Onderhoudsdiensten` | 60 chars |
| **Legal Pages** | `{Page Title} — HDS Onderhoudsdiensten` | 60 chars |
| **Blog Posts** | `{Post Title} — HDS Onderhoudsdiensten` | 60 chars |
| **WooCommerce Products** | `{Product Name} — HDS Onderhoudsdiensten` | 60 chars |
| **404** | `Pagina niet gevonden — HDS Onderhoudsdiensten` | 60 chars |

### 3.2 Per-Page Title Tags

| Page | Title Tag |
|---|---|
| P01 Home | `HDS Onderhoudsdiensten | Schoonmaak- en Onderhoudsdiensten West-Brabant Zeeland` |
| P02 Glasbewassing | `Glasbewassing — HDS Onderhoudsdiensten` |
| P03 Gevelreiniging | `Gevelreiniging — HDS Onderhoudsdiensten` |
| P04 Reguliere Schoonmaak | `Reguliere Schoonmaak — HDS Onderhoudsdiensten` |
| P05 Vloeronderhoud | `Vloeronderhoud — HDS Onderhoudsdiensten` |
| P06 VVE Service | `VVE Service — HDS Onderhoudsdiensten` |
| P07 Oplevering Schoonmaak | `Oplevering Schoonmaak — HDS Onderhoudsdiensten` |
| P08 Industriele Schoonmaak | `Industriele Schoonmaak — HDS Onderhoudsdiensten` |
| P09 Glas & Gevel | `Glas & Gevel Reiniging — HDS Onderhoudsdiensten` |
| P10 Schoonmaakdiensten | `Schoonmaakdiensten — HDS Onderhoudsdiensten` |
| P11 Over HDS | `Over HDS — HDS Onderhoudsdiensten` |
| P12 Kwaliteit & Veiligheid | `Kwaliteit & Veiligheid — HDS Onderhoudsdiensten` |
| P13 Referenties | `Referenties — HDS Onderhoudsdiensten` |
| P14 Vacatures | `Vacatures — HDS Onderhoudsdiensten` |
| P15 Downloads | `Downloads — HDS Onderhoudsdiensten` |
| P16 Contact | `Contact — HDS Onderhoudsdiensten` |
| P17 Offerte Aanvragen | `Offerte Aanvragen — HDS Onderhoudsdiensten` |
| P18 Veelgestelde Vragen | `Veelgestelde Vragen — HDS Onderhoudsdiensten` |
| P19 Privacyverklaring | `Privacyverklaring — HDS Onderhoudsdiensten` |
| P20 Cookiebeleid | `Cookiebeleid — HDS Onderhoudsdiensten` |
| P21 Algemene Voorwaarden | `Algemene Voorwaarden — HDS Onderhoudsdiensten` |
| P22 Disclaimer | `Disclaimer — HDS Onderhoudsdiensten` |
| P23 Luchtreiniging | `Luchtreiniging — HDS Onderhoudsdiensten` |
| P24 Winkel | `Winkel — HDS Onderhoudsdiensten` |

### 3.3 Meta Description Standards

**Format:** 150–160 characters. Structure: `{Primary Keyword} | {Location/Context} | {Value Proposition} | {CTA}`. Written in Dutch. Unique per page. Zero auto-generated or duplicate descriptions.

**Per-Page Meta Descriptions:**

| Page | Meta Description |
|---|---|
| P01 Home | `Professionele schoonmaak- en onderhoudsdiensten in West-Brabant en Zeeland. Vrijblijvende offerte. Vast opgeleid personeel. Neem contact op voor een vrijblijvende offerte.` |
| P02 Glasbewassing | `Glasbewassing voor bedrijven in West-Brabant en Zeeland. Veiligheid gecertificeerd personeel. Vrijblijvende offerte aanvragen.` |
| P03 Gevelreiniging | `Gevelreiniging, impregneren en graffiti verwijderen in West-Brabant en Zeeland. Professionele gevelreiniging met diploma. Vrijblijvende offerte.` |
| P04 Reguliere Schoonmaak | `Reguliere schoonmaak voor kantoren en bedrijven in West-Brabant en Zeeland. Dagelijkse en wekelijkse schoonmaak op maat. Vrijblijvende offerte aanvragen.` |
| P05 Vloeronderhoud | `Vloeronderhoud voor marmoleum, natuursteen, hout en tapijt in West-Brabant en Zeeland. Machinale reiniging. Vrijblijvende offerte.` |
| P06 VVE Service | `VVE schoonmaak voor wooncomplexen. Trappenhuizen, hallen, garages. Aangesloten bij VvE Belang. Vrijblijvende offerte.` |
| P07 Oplevering Schoonmaak | `Oplevering schoonmaak na bouw en renovatie in West-Brabant en Zeeland. Volledige reiniging inclusief glasbewassing. Vrijblijvende offerte.` |
| P08 Industriele Schoonmaak | `Industriele schoonmaak voor fabrieken en magazijnen. Leidingen, productievloeren, machines. Minimale productiestilstand. Vrijblijvende offerte.` |
| P09 Glas & Gevel | `Glas en gevel reiniging voor bedrijven in West-Brabant en Zeeland. Glasbewassing, gevelreiniging, impregneren. Vrijblijvende offerte.` |
| P10 Schoonmaakdiensten | `Alle schoonmaakdiensten voor bedrijven: reguliere schoonmaak, vloeronderhoud, VVE, oplevering en industriele reiniging. Vrijblijvende offerte.` |
| P16 Contact | `Contact opnemen met HDS Onderhoudsdiensten. Telefoon 0164-652846. Vrijblijvende offerte aanvragen via ons contactformulier.` |
| P17 Offerte | `Vrijblijvende offerte aanvragen voor schoonmaak- en onderhoudsdiensten. Vul het formulier in en ontvang binnen 1 werkdag een offerte op maat.` |

### 3.4 OpenGraph Tags

All pages have complete OpenGraph tags generated by Rank Math Pro.

| Tag | Content | Source |
|---|---|---|
| `og:locale` | `nl_NL` | WordPress language setting |
| `og:type` | `website` (pages), `article` (blog posts), `product` (WC products) | Rank Math auto-detect |
| `og:title` | Same as HTML `<title>` | Rank Math per-page title |
| `og:description` | Same as meta description | Rank Math per-page description |
| `og:url` | Canonical URL | Rank Math canonical |
| `og:image` | Featured image (pages) or site-wide social share image (1200×630px) | Rank Math |
| `og:site_name` | `HDS Onderhoudsdiensten` | Rank Math setting |

### 3.5 Twitter Cards

| Tag | Content |
|---|---|
| `twitter:card` | `summary_large_image` |
| `twitter:title` | Same as `og:title` |
| `twitter:description` | Same as `og:description` |
| `twitter:image` | Same as `og:image` |

### 3.6 Robots Meta

| Page(s) | `robots` Meta Tag | Sitemap Inclusion |
|---|---|---|
| All public pages (Home, Services, About, Contact, Offerte, Blog, Shop) | `<meta name="robots" content="index, follow">` | Included |
| Legal pages (Privacyverklaring, Cookiebeleid, Algemene Voorwaarden, Disclaimer) | `index, follow` | Included |
| `/bedankt/` (P32) | `<meta name="robots" content="noindex, nofollow">` | **Excluded** |
| WooCommerce cart, checkout, account | `noindex, nofollow` (WC default) | Excluded |
| Search results | `noindex, follow` (Rank Math setting) | Excluded |
| Paginated archives (page 2+) | `index, follow` (canonical to page 1) | Included |

### 3.7 Author Metadata

- Author archives: **Disabled** (`inc/security.php` redirects `?author=N` to homepage).
- No author bio pages.
- No author metadata in `<head>`.
- Blog post bylines are visual only (date, optional category) — no linked author names.

### 3.8 Language Metadata

| Tag | Content |
|---|---|
| `<html lang="nl-NL">` | Set via `language_attributes()` in `parts/header.php` |
| `og:locale` | `nl_NL` (Rank Math auto) |
| `hreflang` | `nl` + `x-default` on homepage (Rank Math Pro setting) |

---

## 4. Structured Data

### 4.1 Schema Inventory

| # | Schema Type | Applies To | Implementation | Validation |
|---|---|---|---|---|
| 1 | `WebSite` + `SearchAction` | All pages | Rank Math Pro auto | — |
| 2 | `WebPage` | All pages | Rank Math Pro auto | — |
| 3 | `BreadcrumbList` | All inner pages | Rank Math Pro + theme (`parts/breadcrumbs.php`) | Google Rich Results Test |
| 4 | `Organization` + `sameAs` | All pages | Theme `inc/schema.php` → `hds_get_organization_schema()` | Google Rich Results Test |
| 5 | `LocalBusiness` (HomeAndConstructionBusiness) | Home (P01), Contact (P16), Over HDS (P11) | Theme `inc/schema.php` → `hds_get_localbusiness_schema()` | Google Rich Results Test |
| 6 | `Service` | P02–P08 (each service page) | Theme `inc/schema.php` → `hds_get_service_schema($post_id)` | Google Rich Results Test |
| 7 | `FAQPage` | P18 (Veelgestelde Vragen) | Rank Math Pro auto from Yoast/Rank Math FAQ blocks | Google Rich Results Test |
| 8 | `Product` | P25 (WooCommerce products ×14) | WooCommerce auto + Rank Math enhancement | Google Rich Results Test |
| 9 | `JobPosting` | Per active vacancy on P14 | Theme `inc/schema.php` → `hds_get_jobposting_schema($vacancy_id)` | Google Rich Results Test |

### 4.2 Organization Schema

**Generated by:** `hds_get_organization_schema()` in `inc/schema.php`. Output on all pages.

```json
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "HDS Onderhoudsdiensten",
  "url": "https://helderduidelijkschoon.nl",
  "email": "info@helderduidelijkschoon.nl",
  "telephone": "0164-652846",
  "sameAs": [
    "https://www.facebook.com/helderduidelijkschoon/",
    "https://www.instagram.com/hds_schoonmaakdiensten/"
  ]
}
```

**Conditional fields:** `sameAs` only includes URLs that are set in the Customizer. If Facebook/Instagram/GBP URLs are empty, the `sameAs` array omits them.

### 4.3 LocalBusiness Schema

**Generated by:** `hds_get_localbusiness_schema()` in `inc/schema.php`. Output on Home, Contact, and Over HDS pages.

```json
{
  "@context": "https://schema.org",
  "@type": "HomeAndConstructionBusiness",
  "@id": "https://helderduidelijkschoon.nl/#localbusiness",
  "name": "HDS Onderhoudsdiensten",
  "description": "Professionele schoonmaak- en onderhoudsdiensten in West-Brabant en Zeeland.",
  "url": "https://helderduidelijkschoon.nl",
  "telephone": "0164-652846",
  "email": "info@helderduidelijkschoon.nl",
  "priceRange": "€€",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "[Straat + Huisnummer — MI-01]",
    "postalCode": "[Postcode — MI-01]",
    "addressLocality": "[Plaats — MI-01]",
    "addressCountry": "NL"
  },
  "openingHours": [
    "Ma-Vr 08:00-17:00"
  ]
}
```

**Conditional fields:**
- `address`: Omitted if `hds_address` Customizer field is empty.
- `openingHours`: Omitted if `hds_opening_hours` Customizer field is empty.
- `image`: Includes custom logo if set; omitted otherwise.
- `geo` (GeoCoordinates): **Not included.** Requires latitude/longitude which the client has not provided.

### 4.4 Service Schema

**Generated by:** `hds_get_service_schema($post_id)` in `inc/schema.php`. Output on each service page (P02–P08).

```json
{
  "@context": "https://schema.org",
  "@type": "Service",
  "name": "Glasbewassing",
  "description": "Glasbewassing voor bedrijven in West-Brabant en Zeeland...",
  "provider": {
    "@type": "Organization",
    "name": "HDS Onderhoudsdiensten"
  },
  "url": "https://helderduidelijkschoon.nl/glasbewassing/",
  "areaServed": {
    "@type": "City",
    "name": "Bergen op Zoom"
  },
  "serviceType": "Glasbewassing"
}
```

**Description source:** `wp_trim_words( wp_strip_all_tags( get_the_excerpt() ?: post_content ), 30, '...' )`.

**Area served:** Uses `hds_postal_city` Customizer value (falls back to "West-Brabant en Zeeland" if empty).

### 4.5 BreadcrumbList Schema

**Dual source:**
1. Rank Math Pro auto-generates BreadcrumbList JSON-LD.
2. Theme (`parts/breadcrumbs.php`) outputs visible breadcrumbs with Schema.org microdata (`itemscope`, `itemprop`, `itemtype`).

**Both must exist** — JSON-LD for search engines, microdata for rich results, and visible breadcrumbs for users and accessibility.

**Crawl depth:** Rank Math is configured to **disable** its own BreadcrumbList JSON-LD if the theme already outputs microdata, preventing duplicate schema. **Configuration check:** Rank Math → General Settings → Breadcrumbs → "Remove BreadcrumbList JSON-LD" = Enabled.

### 4.6 FAQPage Schema

**Generated by:** Rank Math Pro auto-detects Yoast/Rank Math FAQ blocks in `the_content()` and generates FAQPage JSON-LD.

**Page:** P18 (`/veelgestelde-vragen/`)

**Content Format (in Block Editor):**
```
<!-- wp:yoast/faq-block -->
  <!-- wp:yoast/faq-question -->
    <!-- wp:heading -->Wat zijn de kosten?<!-- /wp:heading -->
    <!-- wp:paragraph -->De kosten zijn afhankelijk van...<!-- /wp:paragraph -->
  <!-- /wp:yoast/faq-question -->
<!-- /wp:yoast/faq-block -->
```

**No theme schema function needed.** The theme's `hds_get_faqpage_schema()` function has been removed (Post-PVR Correction C01). Rank Math handles FAQPage schema entirely.

### 4.7 Product Schema

**Generated by:** WooCommerce (core) + Rank Math Pro (enhancement).

**Applies to:** All 14 Airfixr product pages.

**Includes:** `name`, `description`, `image`, `sku`, `offers` (price, currency, availability), `brand`.

### 4.8 JobPosting Schema

**Generated by:** `hds_get_jobposting_schema($vacancy_id)` in `inc/schema.php`. Output on the `/vacatures/` page for each active vacancy.

```json
{
  "@context": "https://schema.org",
  "@type": "JobPosting",
  "title": "Glas- en Gevelreiniger",
  "description": "Wij zoeken een enthousiaste...",
  "datePosted": "2026-07-01",
  "hiringOrganization": {
    "@type": "Organization",
    "name": "HDS Onderhoudsdiensten"
  },
  "jobLocation": {
    "@type": "Place",
    "address": {
      "@type": "PostalAddress",
      "addressLocality": "Bergen op Zoom",
      "addressCountry": "NL"
    }
  },
  "employmentType": "PART_TIME",
  "workHours": "32-40 uur per week",
  "validThrough": "2026-12-31"
}
```

**Conditional fields:** `jobLocation` (requires `hds_location`), `workHours` (requires `hds_hours_per_week`), `validThrough` (requires `hds_deadline`). Each is omitted if the corresponding post meta is empty.

### 4.9 WebSite + SearchAction Schema

**Generated by:** Rank Math Pro auto. Includes `potentialAction` → `SearchAction` with search URL template: `https://helderduidelijkschoon.nl/?s={search_term_string}`.

### 4.10 Schema Validation Strategy

| Step | Tool | Frequency |
|---|---|---|
| 1. Validate each schema type | Google Rich Results Test (per URL) | During Sprint 5 implementation |
| 2. Validate all 32 pages | Google Rich Results Test + Screaming Frog custom extraction | Sprint 7 QA gate |
| 3. Post-launch monitoring | GSC → Enhancements report | Weekly for first 30 days |
| 4. Fix warnings/errors | Based on GSC report | Within 7 days of detection |

**Pass Condition:** All 9 schema types return zero errors in Google Rich Results Test. Zero critical warnings. Informational notes acceptable.

---

## 5. Information Architecture for SEO

### 5.1 Internal Linking Strategy

**Mandatory cross-links between service pages** (see FS §4.2 cross-link matrix):

| Source Page | Links To |
|---|---|
| P02 Glasbewassing | P03 Gevelreiniging, P04 Reguliere Schoonmaak, P07 Oplevering Schoonmaak |
| P03 Gevelreiniging | P02 Glasbewassing, P08 Industriele Schoonmaak |
| P04 Reguliere Schoonmaak | P05 Vloeronderhoud, P02 Glasbewassing, P06 VVE Service |
| P05 Vloeronderhoud | P04 Reguliere Schoonmaak, P07 Oplevering Schoonmaak |
| P06 VVE Service | P04 Reguliere Schoonmaak, P02 Glasbewassing |
| P07 Oplevering Schoonmaak | P04 Reguliere Schoonmaak, P02 Glasbewassing, P05 Vloeronderhoud |
| P08 Industriele Schoonmaak | P04 Reguliere Schoonmaak, P03 Gevelreiniging |

**Additional Internal Linking Rules:**
- Every service page links to `/offerte-aanvragen/` (primary CTA).
- Every service page links to `/contact/` in the cross-sell or footer.
- Homepage Service Card Grid links to all 7 services.
- Footer links to all 7 services, all legal pages, and contact.
- Blog posts link to related services where contextually relevant.
- Category landing pages (P09, P10) link to their sub-service pages.

### 5.2 Content Hierarchy (Heading Structure)

| Level | Usage | Rule |
|---|---|---|
| **H1** | Page title | Exactly 1 per page. Never more. |
| **H2** | Major sections | "Onze aanpak", "Diensten", "Veiligheid & Kwaliteit" |
| **H3** | Sub-sections | Within an H2 section (e.g., individual service bullet groups) |
| **No skipped levels** | — | H1 → H2 → H3. Never H1 → H3 without H2 between. |

### 5.3 Breadcrumbs

- Visible on all inner pages (not Homepage).
- Structure: `Home > [Page Name]` (flat — no intermediate hierarchy because URLs are flat).
- Schema.org `BreadcrumbList` microdata on the `<ol>` element.
- Link to Homepage in first position.
- Current page as last item (not linked, `aria-current="page"`).

### 5.4 Navigation for SEO

- **Primary Navigation:** Links to all major sections (DIENSTEN, OVER HDS, LUCHTREINIGING, CONTACT). Dropdowns provide direct links to service pages — crawlable, not JS-dependent.
- **Footer Navigation:** Links to all services, about pages, legal pages, contact. Provides a site-wide crawlable link structure.
- **Menu Implementation:** All navigation is via WordPress Menus (Appearance → Menus) rendered as standard `<ul>` / `<li>` HTML. Fully crawlable.

### 5.5 Related Content (Blog)

- **Related Posts** section at the bottom of each blog post (optional — Block Editor pattern).
- Queries posts in the same category, excluding the current post.
- 2–3 related posts with thumbnail, title, and link.

---

## 6. Content SEO

### 6.1 Homepage (P01)

| Requirement | Specification |
|---|---|
| **H1** | "Helder en Duidelijk voor het Schoonste resultaat!" (tagline) |
| **Content** | 300+ words Dutch |
| **Primary Keyword** | "schoonmaakdiensten", "onderhoudsdiensten" |
| **Secondary Keywords** | "West-Brabant", "Zeeland", "schoonmaakbedrijf" |
| **Internal Links** | Service Card Grid links to all 7 services. CTA to `/offerte-aanvragen/`. |
| **Images** | Icons for service cards (SVG, decorative — `alt=""`). Logo (descriptive alt text). |

### 6.2 Service Pages (P02–P08)

| Requirement | Specification |
|---|---|
| **Content** | 300+ words Dutch per page |
| **H1** | Service name (e.g., "Glasbewassing") |
| **H2 Sections** | Minimum 2 H2 sections. Recommended: "Onze aanpak", "Diensten", "Veiligheid & Kwaliteit". |
| **Primary Keyword** | Service name as keyword (e.g., "glasbewassing") |
| **Secondary Keywords** | Location + service modifier (e.g., "glasbewassing Bergen op Zoom", "glazenwasser West-Brabant") |
| **Internal Links** | Cross-links to 2–3 related services per the cross-link matrix (see §5.1) |
| **CTA** | "Vrijblijvende offerte" → `/offerte-aanvragen/` |
| **Images** | Service-specific photos (MI-09). Alt text: descriptive Dutch. File name: `{service}-{context}-{location}.webp` |

### 6.3 Category Landing Pages (P09–P10)

| Requirement | Specification |
|---|---|
| **Content** | 500+ words Dutch per page |
| **P09 H1** | "Glas & Gevel Reiniging" |
| **P10 H1** | "Schoonmaakdiensten" |
| **Primary Keywords** | P09: "glas en gevel reiniging". P10: "schoonmaakdiensten". |
| **Service Card Grid** | P09: 2 cards (Glasbewassing, Gevelreiniging). P10: 5 cards (Reguliere, Vloer, VVE, Oplevering, Industrieel). |
| **CTA** | "Vrijblijvende offerte" → `/offerte-aanvragen/` |

### 6.4 About Pages (P11–P12)

| Requirement | Specification |
|---|---|
| **P11 Content** | 500+ words. Company history, USPs, values, certifications. |
| **P12 Content** | 300+ words. Kwaliteit, Veiligheid, MVO sections. |
| **Keywords** | P11: "schoonmaakbedrijf West-Brabant", "over HDS". P12: "kwaliteit schoonmaak", "veiligheid schoonmaak", "OSB gecertificeerd". |
| **Trust Signals** | OSB membership link, VvE Belang link, certification mentions. |

### 6.5 Referenties (P13)

| Requirement | Specification |
|---|---|
| **Content** | 300+ words |
| **Keywords** | "referenties schoonmaakbedrijf", "klanten HDS" |
| **Testimonials** | `hds/testimonial` custom block. Queries `hds_testimonial` CPT. Each testimonial in `<blockquote>`. Star ratings. |
| **Client Logos** | Alt text = company name. Grid or carousel layout. |

### 6.6 Vacatures (P14)

| Requirement | Specification |
|---|---|
| **Content** | 300+ words (including vacancy text) |
| **Keywords** | "vacature schoonmaak", "schoonmaak vacature West-Brabant" |
| **Schema** | JobPosting per active vacancy |
| **Content Format** | HTML text (NOT scanned JPG images — this was a critical defect on the current site) |

### 6.7 Downloads (P15)

| Requirement | Specification |
|---|---|
| **Content** | 150+ words |
| **Keywords** | "downloads schoonmaak", "algemene voorwaarden schoonmaak" |
| **PDFs** | Hosted on primary domain (not legacy domain). Descriptive filenames. |

### 6.8 Blog / Kennisbank (P29–P30)

| Requirement | Specification |
|---|---|
| **Posts** | 5–10 initial articles; minimum 500 words each |
| **Topics** | Cleaning tips, industry insights, service deep-dives, project showcases |
| **Headings** | H1 = post title. H2 for sections. H3 for sub-sections. |
| **Images** | Featured image for each post. Alt text in Dutch. |
| **Internal Links** | Link to relevant service pages from blog posts (contextual linking). |
| **Categories** | Blog-specific categories (e.g., "Schoonmaaktips", "Projecten", "Veiligheid"). |
| **Permalink** | `/kennisbank/{slug}/` — permanent, no date prefix |
| **Comments** | Disabled |

### 6.9 WooCommerce Product Pages (P25)

| Requirement | Specification |
|---|---|
| **Product Titles** | Existing product names migrated from current site |
| **Descriptions** | Migrated. Enhance with keyword-rich content if thin. |
| **Images** | Alt text = product name. WebP optimized. |
| **Schema** | Product schema via WooCommerce + Rank Math |

### 6.10 Content Length Targets

| Page Type | Minimum Words | Optimal Words |
|---|---|---|
| Service pages (P02–P08) | 300 | 400–600 |
| Category landings (P09–P10) | 500 | 600–800 |
| About pages (P11–P12) | 300–500 | 500–700 |
| Contact / Offerte (P16–P17) | 150 | 200–300 |
| Legal pages (P19–P22) | 150–500 | Per legal requirements |
| Blog posts (P30) | 500 | 800–1500 |
| FAQ (P18) | 300 (combined) | 500+ combined |

### 6.11 Heading Hierarchy

Enforced via Block Editor. Content editors use Heading blocks (H2, H3, H4) within content. Page templates place H1 automatically (the page title). Editors must not insert their own H1 in the content area.

**Verification:** Screaming Frog → H1 tab → exactly 1 per page. H2 tab → at least 2 on service pages. No skipped heading levels.

### 6.12 Keyword Placement

| Location | Keyword Presence |
|---|---|
| **Title Tag** | Primary keyword (mandatory) |
| **Meta Description** | Primary keyword + location + CTA (mandatory) |
| **H1** | Primary keyword or close variant (mandatory — H1 is the page title/service name) |
| **First Paragraph** | Primary keyword within first 100 words (recommended) |
| **H2 Headings** | Secondary keywords or variants (recommended) |
| **Image Alt Text** | Keywords where descriptive and natural (not keyword-stuffed) |
| **URL Slug** | Primary keyword (mandatory — the slug IS the keyword for service pages) |

### 6.13 Image Optimization for SEO

| Requirement | Specification |
|---|---|
| **Format** | WebP primary. PNG/JPEG fallback via `<picture>`. |
| **File Naming** | Lowercase, hyphens, Dutch keywords. Pattern: `{service}-{context}-{location}.webp`. |
| **Alt Text** | Descriptive Dutch. Describes image content + relevance. Never keyword-stuffed. |
| **Dimensions** | Explicit `width`/`height` attributes. Prevents CLS (Core Web Vital). |
| **Responsive** | `srcset` attribute (WordPress auto-generates). |
| **Lazy Loading** | `loading="lazy"` below fold. `fetchpriority="high"` on LCP image. |

### 6.14 Alt Text Policy

| Image Type | Alt Text Rule | Example |
|---|---|---|
| Service photos | Describe the service being performed + context | "Glasbewassing van een kantoortoren in Bergen op Zoom" |
| Team photos | Describe the team + activity | "HDS schoonmaakteam in herkenbare bedrijfskleding" |
| Before/after photos | Describe the transformation | "Vloer voor en na machinale reiniging" |
| Client logos | Company name | "Logo van [Bedrijfsnaam]" |
| Product photos | Product name + variant | "Airfixr 60 luchtreiniger — voorzijde" |
| Icons with text labels | `alt=""` (decorative) | — |
| Decorative elements | `alt=""` | — |

---

## 7. Local SEO

### 7.1 NAP Consistency

**NAP = Name, Address, Phone.** Must be identical across all platforms.

| Platform | Name | Address | Phone |
|---|---|---|---|
| **Website** | HDS Onderhoudsdiensten | [MI-01] | 0164-652846 |
| **Google Business Profile** | HDS Onderhoudsdiensten | [MI-01] | 0164-652846 |
| **Facebook** | HDS Onderhoudsdiensten | [MI-01] | 0164-652846 |
| **VvE Belang** | HDS Onderhoudsdiensten | [MI-01] | 0164-652846 |
| **OSB (if listed)** | HDS Onderhoudsdiensten | [MI-01] | 0164-652846 |

**Implementation:** All NAP data sourced from the Theme Customizer as single source of truth. This guarantees consistency across footer, Contact page, and LocalBusiness schema.

**Verification:** Manual audit of all platforms. NAP must be character-for-character identical. "Straat 1" vs "Straat 1A" is a mismatch.

### 7.2 Google Business Profile

| Requirement | Status | Owner |
|---|---|---|
| Claim and verify GBP listing | **Assumption:** Client has GBP (MI-21). Developer assists with verification. | Client |
| NAP identical to website | Single source of truth in Customizer | Developer ensures website; client ensures GBP |
| Categories | "Schoonmaakbedrijf", "Glazenwasser" (primary). Additional: "Vloerenwinkel" (if applicable). | Client (with developer guidance) |
| Service area | Municipalities in West-Brabant and Zeeland (MI-05) | Client |
| Business hours | From Customizer `hds_opening_hours` (MI-04) | Client |
| Photos | 10+ photos: exterior, team, before/after, vehicles, logo | Client (MI-09) |
| Website link | `https://helderduidelijkschoon.nl/` | Client |
| Posts | Monthly updates/offers | Client (post-launch) |
| Reviews | Encourage clients to leave reviews | Client |

### 7.3 Service Area

**On-site display:** Homepage "Service Area" block displays text: "Wij bedienen bedrijven in heel West-Brabant en Zeeland."

**Schema:** LocalBusiness `areaServed` points to `hds_postal_city` Customizer value. If not set, omitted.

**Future:** Location-specific landing pages (`/schoonmaakbedrijf-bergen-op-zoom/`, `/schoonmaakbedrijf-roosendaal/`) are a post-launch enhancement. **Not in scope for Sprint 1–8.**

### 7.4 Location Pages

**Not implemented in current scope.** The site targets a region (West-Brabant / Zeeland), not individual cities. Location pages are a post-launch enhancement contingent on client providing service area details (MI-05).

### 7.5 Reviews

- **Google Reviews:** Client encourages clients to leave reviews on GBP.
- **On-site Testimonials:** Displayed via `hds_testimonial` CPT. Star ratings rendered in HTML (not schema — Review schema is post-launch).
- **No third-party review widget** is embedded (avoids external JS dependency and performance impact).

### 7.6 Maps

- **Contact Page:** Google Maps embed if address (MI-01) is known. Wrapped in Complianz cookie consent placeholder (map only loads after consent).
- **Homepage:** No map by default. Text-based service area description.

---

## 8. Performance SEO

Performance is an SEO ranking factor. Core Web Vitals directly impact search rankings.

### 8.1 Core Web Vitals Targets

| Metric | Target | SEO Impact |
|---|---|---|
| **LCP (Largest Contentful Paint)** | < 2.5s | Direct ranking factor (Google) |
| **INP (Interaction to Next Paint)** | < 200ms | Replaces FID as Core Web Vital (March 2024) |
| **CLS (Cumulative Layout Shift)** | < 0.1 | Direct ranking factor (Google) |
| **TTFB (Time to First Byte)** | < 600ms | Indirect — influences LCP |

### 8.2 Image Optimization for Performance

| Requirement | Implementation | SEO Benefit |
|---|---|---|
| WebP format | ShortPixel/Imagify auto-convert on upload | 25–35% smaller files vs JPEG/PNG → faster LCP |
| Compression | Quality 85+ (visually lossless) | Smaller files without visible quality loss |
| Responsive images | `srcset` with 400w, 800w, 1200w | Mobile users download appropriately sized images |
| Lazy loading | `loading="lazy"` below fold | Reduces initial page weight → faster LCP |
| LCP image | `fetchpriority="high"`, no lazy loading | Prioritizes hero image loading → faster LCP |
| Explicit dimensions | `width`/`height` attributes | Prevents CLS → better CLS score |

### 8.3 Caching for Performance (and SEO)

| Layer | SEO Benefit |
|---|---|
| Cloudflare CDN | Faster TTFB for geographically distributed users. Full-page caching reduces server load. |
| FlyingPress Page Cache | Instant page delivery for repeat visitors. Critical CSS inlined. |
| Redis Object Cache | Faster database queries → faster TTFB. |
| Browser Cache (1yr versioned assets) | Instant repeat-visit loading for static assets. |

### 8.4 JavaScript Strategy

| Requirement | SEO Benefit |
|---|---|
| No render-blocking JS | Faster FCP → better LCP |
| `defer` attribute | JS loads after HTML parse → faster page render |
| No jQuery (theme code) | 30 KB saved → faster page load |
| Progressive enhancement | Content accessible even if JS fails → better crawlability |

### 8.5 Critical CSS

| Requirement | SEO Benefit |
|---|---|
| Critical CSS inlined in `<head>` | Above-fold content renders immediately → faster LCP |
| Non-critical CSS deferred | Total CSS payload reduced on initial load |
| Auto-generated by FlyingPress | No manual CSS management needed |

---

## 9. Indexation Strategy

### 9.1 Pages to Index (`index, follow`)

All 32 pages EXCEPT the explicitly noindex'd pages below.

### 9.2 Pages to Noindex

| Page | Reason |
|---|---|
| `/bedankt/` (P32) | Post-form confirmation page. No unique content value. Prevents thank-you pages from appearing in SERPs. |
| Search results pages (`/?s=...`) | No unique content. Prevents thin search result pages from being indexed. |
| WooCommerce cart, checkout, account | Transactional pages. No informational content. |

### 9.3 Archives

| Archive | Indexable? | Reason |
|---|---|---|
| Blog index (`/kennisbank/`) | Yes | Unique content (list of blog posts). |
| Blog paginated (`/kennisbank/page/2/`) | Yes (canonical to page 1) | Each page has unique content (different posts). |
| Category archives (`/kennisbank/category/{slug}/`) | Yes | Curated groupings of posts. |
| Author archives | Disabled | Redirected to homepage. |
| Date archives | Disabled | WordPress default (not explicitly disabled; low priority). |
| Tag archives | Not used | Tags not implemented at launch. |

### 9.4 Media Pages

| Page Type | Policy |
|---|---|
| Attachment pages | Redirected to parent post/page via `inc/security.php`. Excluded from sitemap. |
| Image file URLs | Indexable (Google Images). Optimized with descriptive filenames + alt text. |

---

## 10. Migration SEO

### 10.1 Pre-Migration Data Collection (Before Old Site Offline)

| # | Task | Tool | RTM ID |
|---|---|---|---|
| 1 | Full crawl of current site: export all URLs, status codes, titles, meta descriptions | Screaming Frog | REQ-MIG-001 |
| 2 | Export all Google Search Console data (16 months): queries, pages, clicks, impressions, CTR, average position | GSC Export | REQ-MIG-002 |
| 3 | Document all backlinks | Ahrefs / Semrush / GSC | REQ-MIG-003 |
| 4 | Export Google Business Profile data: NAP, categories, reviews | GBP Dashboard | REQ-MIG-004 |
| 5 | Screenshot every current page for visual archive | Manual or automated browser screenshots | REQ-MIG-005 |

### 10.2 301 Redirect Map

See §2.4 for the complete redirect map.

**Verification procedure:**
1. Configure all redirects in Rank Math Pro Redirect Manager on staging.
2. Test each redirect URL via `httpstatus.io`.
3. Verify: returns correct HTTP status (301 or 410). Destination URL is correct. Zero redirect chains.
4. After deployment to production (Sprint 8): re-test all redirects on production domain.

### 10.3 Legacy URL Handling

| Old URL Type | Action |
|---|---|
| Working pages with identical URL | Preserved. No redirect needed. |
| Working pages with changed URL | 301 to new URL (`/vve` → `/vve-service/`). |
| Broken pages (404) restored at same URL | Page rebuilt. No redirect needed (`/reguliere-schoonmaak/`). |
| Broken pages (500) restored at same URL | Page rebuilt. No redirect needed (`/contact/`). |
| Old content permanently deleted | 410 Gone (`/2015/06/29/hallo-wereld/`). |
| HTTP URLs | 301 to HTTPS equivalent. |
| www URLs | 301 to non-www equivalent. |
| Trailing slash inconsistencies | 301 to consistent trailing-slash URL. |
| Legacy domain PDFs (`hds-onderhoudsdiensten.nl`) | Migrate PDFs to primary domain. 301 from old PDF URL to new PDF URL. |

### 10.4 Canonical Migration

- Old site canonicas are irrelevant (the old site is replaced).
- New site: all pages have self-referencing canonical URLs.
- No cross-domain canonicas (old site → new site). 301 redirects handle the migration.
- Verified via Screaming Frog crawl on production post-launch.

### 10.5 Post-Migration Monitoring Plan

| Timeframe | Task | Tool | Frequency |
|---|---|---|---|
| **Day 1** | Submit XML sitemap to GSC + Bing | GSC, Bing Webmaster Tools | Once |
| **Day 1** | Verify robots.txt accessible | Manual check | Once |
| **Day 1** | Verify 301 redirects on production | `httpstatus.io` | Once |
| **Day 1** | Screaming Frog crawl: zero 4xx/5xx on expected pages | Screaming Frog | Once |
| **Week 1** | Monitor GSC for crawl errors daily | GSC | Daily |
| **Week 1** | Monitor Core Web Vitals | GSC | Weekly |
| **Week 1** | Check form submissions flowing | GF Entries | Weekly |
| **Week 2** | Submit all new URLs for indexing | GSC URL Inspection | Once |
| **Week 2** | Compare indexed pages count to baseline | GSC | Once |
| **Week 2** | Compare search impressions to baseline | GSC | Once |
| **Week 4** | Full SEO audit vs baseline | Screaming Frog + GSC | Once |
| **Month 1–3** | Track keyword rankings vs baseline | GSC / Ahrefs / Semrush | Monthly |
| **Ongoing** | Monthly SEO report to client | Looker Studio / Manual | Monthly |

---

## 11. SEO QA Checklist

### 11.1 Technical SEO QA

- [ ] `/sitemap_index.xml` returns HTTP 200 with valid XML
- [ ] `/page-sitemap.xml` returns HTTP 200 (was 500 on current site)
- [ ] `/post-sitemap.xml` returns HTTP 200
- [ ] `/product-sitemap.xml` returns HTTP 200 (if WooCommerce active)
- [ ] Zero attachment pages in any sitemap
- [ ] `/robots.txt` returns HTTP 200 with correct content
- [ ] HTTPS enforced: all internal links use `https://`
- [ ] HTTP → 301 → HTTPS verified
- [ ] Non-www → 301 → www (or vice versa) verified
- [ ] Trailing slash consistency: no-slash → 301 → with-slash
- [ ] `/xmlrpc.php` returns 403
- [ ] Custom 404 page returns HTTP 404 (not 200)
- [ ] Zero redirect chains (A → B → C forbidden)
- [ ] All 301 redirects return correct status and destination
- [ ] 410 gone URLs return 410 (not 404)
- [ ] Canonical tags are self-referencing on all pages
- [ ] No cross-domain canonicas

### 11.2 On-Page SEO QA

- [ ] Every page has a unique `<title>` tag (50–60 characters)
- [ ] Every page has a unique `<meta name="description">` (150–160 characters)
- [ ] Zero empty titles or descriptions (Screaming Frog audit)
- [ ] Zero duplicate titles or descriptions (Screaming Frog audit)
- [ ] Every page has exactly 1 H1 tag
- [ ] H1 content matches or closely relates to the `<title>`
- [ ] Service pages have ≥ 2 H2 sections
- [ ] No skipped heading levels (H1 → H3 without H2)
- [ ] OpenGraph tags present and correct on all pages
- [ ] Twitter Card tags present on all pages
- [ ] `og:image` tag resolves to a valid image URL
- [ ] Facebook Sharing Debugger: zero errors on all page templates
- [ ] Twitter Card Validator: zero errors
- [ ] `lang="nl-NL"` present on `<html>` element on all pages
- [ ] `hreflang="nl"` + `x-default` present on homepage

### 11.3 Structured Data QA

- [ ] Organization schema valid (Google Rich Results Test) — all pages
- [ ] LocalBusiness schema valid (Google Rich Results Test) — Home, Contact, Over HDS
- [ ] Service schema valid (Google Rich Results Test) — each service page (P02–P08)
- [ ] FAQPage schema valid (Google Rich Results Test) — P18
- [ ] Product schema valid (Google Rich Results Test) — all 14 product pages
- [ ] JobPosting schema valid (Google Rich Results Test) — each active vacancy
- [ ] BreadcrumbList schema valid — all inner pages
- [ ] No duplicate schema output (e.g., two LocalBusiness schemas on the same page)

### 11.4 Content SEO QA

- [ ] All service pages ≥ 300 words Dutch
- [ ] All category landings ≥ 500 words Dutch
- [ ] No lorem ipsum or placeholder text
- [ ] Images have descriptive Dutch alt text (zero missing)
- [ ] Image filenames use lowercase-hyphens-Dutch-keywords format
- [ ] Internal links: zero broken (Screaming Frog crawl)
- [ ] Internal links: zero orphan pages (all pages reachable from ≥ 2 locations)
- [ ] Cross-link rules verified for each service page (see §5.1 matrix)
- [ ] CTA on every service page links to `/offerte-aanvragen/`

### 11.5 Performance SEO QA

- [ ] PSI Mobile score ≥ 90 on Home, 1 service page, 1 product page
- [ ] PSI Desktop score ≥ 95 on same pages
- [ ] LCP < 2.5 seconds
- [ ] CLS < 0.1
- [ ] TTFB < 600ms (WebPageTest: Amsterdam, Moto G4, 3G Fast)
- [ ] Critical CSS inlined in `<head>`
- [ ] No render-blocking JavaScript (PSI "Eliminate render-blocking resources" = 0)
- [ ] Images use WebP format with `<picture>` fallback
- [ ] Images have explicit `width`/`height` attributes
- [ ] Images below fold use `loading="lazy"`
- [ ] LCP image uses `fetchpriority="high"`

### 11.6 Local SEO QA

- [ ] NAP identical across footer, Contact page, and LocalBusiness schema
- [ ] Phone number clickable (`tel:0164-652846`)
- [ ] Email clickable (`mailto:info@helderduidelijkschoon.nl`)
- [ ] KVK and BTW in footer (if provided — MI-02, MI-03)
- [ ] Google Business Profile linked from footer or Contact page (if available)
- [ ] Service area mentioned on Homepage and in LocalBusiness schema

---

## 12. Acceptance Criteria

### 12.1 Technical SEO Acceptance

| # | Criterion | Pass Condition | RTM ID |
|---|---|---|---|
| AC-SEO01 | XML Sitemap accessible at `/sitemap_index.xml` | HTTP 200 with valid XML. Zero broken pages. Zero attachment pages. | REQ-SEO-022 |
| AC-SEO02 | `/page-sitemap.xml` returns HTTP 200 | Was 500 on current site — must be 200. | REQ-SEO-022 |
| AC-SEO03 | `robots.txt` returns HTTP 200 | Correct disallow rules. Sitemap URL included. | REQ-SEO-024 |
| AC-SEO04 | HTTPS enforced site-wide | HTTP → 301. HSTS header present. | REQ-SEO-028 |
| AC-SEO05 | Non-www redirects to non-www canonical | www → 301 to non-www. | REQ-SEO-028 |
| AC-SEO06 | All 301 redirects return correct status + destination | Each old URL tested manually. Zero redirect chains. | REQ-SEO-028 |
| AC-SEO07 | 404 page returns HTTP 404 (not 200) | `curl -I /non-existent-page` → HTTP 404. | — |
| AC-SEO08 | Canonical URLs self-referencing on all pages | Screaming Frog canonical audit. Zero unexpected canonicalization. | REQ-SEO-024 |

### 12.2 On-Page SEO Acceptance

| # | Criterion | Pass Condition | RTM ID |
|---|---|---|---|
| AC-SEO09 | Every page has a unique title tag (50–60 chars) | Screaming Frog: zero empty, zero duplicate. | REQ-SEO-001..021 |
| AC-SEO10 | Every page has a unique meta description (150–160 chars) | Screaming Frog: zero empty, zero duplicate. | REQ-SEO-001..021 |
| AC-SEO11 | Every page has exactly 1 H1 | Screaming Frog: H1 count = 1 per page. | — |
| AC-SEO12 | Service pages have ≥ 2 H2 sections | Screaming Frog: H2 count ≥ 2 per service page. | — |
| AC-SEO13 | OpenGraph tags complete on all pages | Facebook Sharing Debugger: zero errors on all page templates. | — |
| AC-SEO14 | Twitter Card tags complete on all pages | Twitter Card Validator: zero errors. | — |

### 12.3 Structured Data Acceptance

| # | Criterion | Pass Condition | RTM ID |
|---|---|---|---|
| AC-SEO15 | LocalBusiness schema valid | Google Rich Results Test: zero errors on Home, Contact, Over HDS. | REQ-SEO-025 |
| AC-SEO16 | Service schema valid per service page | Google Rich Results Test: zero errors on each of P02–P08. | REQ-SEO-026 |
| AC-SEO17 | FAQPage schema valid on P18 | Google Rich Results Test: zero errors. | REQ-SEO-027 |
| AC-SEO18 | Product schema valid on all 14 products | Google Rich Results Test: zero errors. | — |
| AC-SEO19 | JobPosting schema valid per vacancy | Google Rich Results Test: zero errors per vacancy. | — |
| AC-SEO20 | BreadcrumbList schema valid on all inner pages | Google Rich Results Test: zero errors. | — |

### 12.4 Content SEO Acceptance

| # | Criterion | Pass Condition | RTM ID |
|---|---|---|---|
| AC-SEO21 | All service pages ≥ 300 words Dutch | Word count check. | REQ-CON-002..008 |
| AC-SEO22 | All category landings ≥ 500 words Dutch | Word count check. | REQ-CON-009..010 |
| AC-SEO23 | No lorem ipsum or placeholder text | Full site text crawl. | — |
| AC-SEO24 | Images have alt text | Screaming Frog: zero missing alt text (decorative excluded). | REQ-ACC-006 |
| AC-SEO25 | Internal links: zero broken | Screaming Frog: zero 4xx on internal links. | REQ-SEO-010 |
| AC-SEO26 | Internal links: zero orphan pages | Screaming Frog: every page has ≥ 1 inbound internal link. | REQ-SEO-010 |
| AC-SEO27 | Cross-link rules verified per service page | Manual audit of cross-sell sections. | — |

### 12.5 Performance SEO Acceptance

| # | Criterion | Pass Condition | RTM ID |
|---|---|---|---|
| AC-SEO28 | PSI Mobile ≥ 90 on Home, 1 service page, 1 product page | PSI. | REQ-PERF-001 |
| AC-SEO29 | PSI Desktop ≥ 95 on same pages | PSI. | REQ-PERF-002 |
| AC-SEO30 | LCP < 2.5s | PSI / Lighthouse. | REQ-PERF-003 |
| AC-SEO31 | CLS < 0.1 | PSI / Lighthouse. | REQ-PERF-005 |

### 12.6 Migration SEO Acceptance

| # | Criterion | Pass Condition | RTM ID |
|---|---|---|---|
| AC-SEO32 | GSC sitemap submitted and status = "Success" | GSC → Sitemaps. | — |
| AC-SEO33 | Zero crawl errors in GSC (Day 7) | GSC → Pages → "Page indexing" report. | — |
| AC-SEO34 | Indexed pages count ≥ pre-migration baseline | GSC comparison (Day 14). | REQ-MIG-002 |
| AC-SEO35 | Daily GSC monitoring for 30 days | Zero critical crawl errors. | — |

---

## 13. Traceability

### 13.1 SEO Implementation → RTM Mapping

| SEO § | RTM Requirement IDs | Count |
|---|---|---|
| 2. Technical SEO | REQ-SEO-022, REQ-SEO-023, REQ-SEO-024, REQ-SEO-028 | 4 |
| 3. Metadata | REQ-SEO-001..021 | 21 |
| 4. Structured Data | REQ-SEO-025, REQ-SEO-026, REQ-SEO-027 | 3 |
| 5. Information Architecture | REQ-SEO-010 | 1 |
| 6. Content SEO | REQ-SEO-001..021 (indirect), REQ-CON-001..029 | 29+ |
| 7. Local SEO | REQ-SEO-025 (LocalBusiness), BR-002 (regional leadership) | 2 |
| 8. Performance SEO | REQ-PERF-001..003, REQ-PERF-005, REQ-PERF-007..011 | 8 |
| 9. Indexation | REQ-SEO-022..023, REQ-SEO-024 | 3 |
| 10. Migration SEO | REQ-MIG-001..011, REQ-SEO-028 | 14 |
| 11. SEO QA | All REQ-SEO, REQ-PERF, REQ-CON, REQ-ACC | Full coverage |
| **Total** | **All 28 REQ-SEO-001..028 + related REQ-PERF, REQ-CON, REQ-MIG** | **100%** |

### 13.2 SEO Implementation → Functional Specification Mapping

| SEO § | FS Section |
|---|---|
| 2. Technical SEO | FS §11.2 (Canonicals), FS §11.5 (Sitemap), FS §11.6 (robots.txt), FS §11.7 (Redirects) |
| 3. Metadata | FS §11.1 (Metadata), FS §11.3 (OpenGraph/Twitter) |
| 4. Structured Data | FS §11.4 (Schema) |
| 5. Information Architecture | FS §4.2 (Cross-links), FS §4.12 (Navigation), FS §4.14 (Footer) |
| 6. Content SEO | FS §4.1–4.20 (all page content specifications) |
| 8. Performance SEO | FS §15 (Performance Behaviour) |
| 10. Migration SEO | FS §11.7 (Redirects) |

### 13.3 SEO Implementation → NFR Mapping

| SEO § | NFR Section |
|---|---|
| 2. Technical SEO | NFR §9.2 (Canonical), NFR §9.4 (Sitemap), NFR §9.5 (robots.txt), NFR §9.7 (Redirects), NFR §9.9 (URL Consistency) |
| 3. Metadata | NFR §9.1 (Metadata), NFR §9.6 (OpenGraph) |
| 4. Structured Data | NFR §9.3 (Schema) |
| 5. Information Architecture | NFR §9.8 (Internal Linking) |
| 8. Performance SEO | NFR §3 (Performance Requirements) |

### 13.4 SEO Implementation → Solution Architecture Mapping

| SEO § | SA Section |
|---|---|
| All sections | SA §14 (SEO Architecture) |
| 8. Performance SEO | SA §12 (Performance Architecture) |

### 13.5 SEO Implementation → WordPress Technical Architecture Mapping

| SEO § | WTA Section |
|---|---|
| 2. Technical SEO | WTA §12.5 (Sitemap), WTA §12.6 (Redirects), WTA §12.7 (robots.txt), WTA §12.8 (Canonicals) |
| 3. Metadata | WTA §12.2 (Per-Page Metadata) |
| 4. Structured Data | WTA §12.3 (Rank Math Auto Schema), WTA §12.4 (Theme Custom Schema) |
| All sections (implementation) | WTA §12 (SEO Integration) |

## 14. Additional SEO Requirements (Post Final Architecture Review)

### 14.1 Image SEO

| Requirement | Implementation |
|---|---|
| **Image Sitemap** | Enable in Rank Math Pro → Sitemap Settings → Images. Include all images with `alt` text. Submit to GSC. |
| **Image Filename SEO** | Pattern: `[subject]-[context]-[location].webp`. Lowercase, hyphens, Dutch keywords. |
| **ImageObject Schema** | Auto-generated by Rank Math Pro for images with alt text on key pages. |

### 14.2 Social Media Integration

| Requirement | Implementation |
|---|---|
| **Facebook Domain Verification** | Add `fb:pages` meta tag via Rank Math Pro → Social Meta. |
| **Social Share Image** | 1200×630px branded graphic. Set in Rank Math Pro → Global Meta → OpenGraph Thumbnail. |

### 14.3 Single-Language hreflang

| Requirement | Implementation |
|---|---|
| **Homepage hreflang** | `<link rel="alternate" hreflang="nl" href="https://helderduidelijkschoon.nl/">` + `hreflang="x-default"`. Rank Math Pro auto-generates. |
| **Other pages** | No hreflang needed (single-language). Homepage x-default suffices. |

### 14.4 Analytics — Additional GA4 Events

| Event | Trigger | GA4 Event Name | Parameter |
|---|---|---|---|
| Scroll Depth | 25/50/75/100% page scroll | `scroll_depth` | `scroll_percentage` |
| Site Search | Internal search submitted | `search` | `search_term` |
| File Download | PDF link clicked | `file_download` | `file_name`, `file_extension` |

### 14.5 404 Monitoring Strategy

| Requirement | Implementation |
|---|---|
| **404 Monitor** | Rank Math Pro 404 Monitor. All hits logged. |
| **Review Cadence** | Weekly. Create 301 redirects for URLs with > 5 hits/week. |
| **GSC 404 Monitoring** | Weekly review of GSC Coverage → Not Found (404) report. |

---

**This SEO Implementation Specification is the definitive guide for developers, content editors, and QA engineers. Every page title, meta description, schema type, redirect rule, and QA check is specified. The document is fully traceable to RTM-001 (all 28 REQ-SEO requirements), FS-001, NFR-001, SA-001, and WTA-001.**

**END OF SEO IMPLEMENTATION SPECIFICATION — Version 1.0.0**
