# SEO Audit — HDS Onderhoudsdiensten

## Executive Summary

**Overall SEO Health: POOR (35/100)**

The site has fundamental technical SEO infrastructure (Yoast, sitemaps, canonical URLs) but fails on content, on-page optimization, performance, and local SEO. Two critical pages are broken (contact 500, regular cleaning 404), which severely impacts conversions and rankings.

---

## 1. Technical SEO

### 1.1 Indexability

| Factor | Status | Detail |
|---|---|---|
| robots.txt | Pass | Allowed all, crawl-delay: 5 |
| XML Sitemap | Partial Fail | Sitemap index works. Page sitemap returns **500 error**. Product/post/attachment sitemaps work. |
| Canonical URLs | Pass | Self-referencing canonicals on all accessible pages |
| HTTPS | Pass | HTTPS enforced |
| www/non-www | Pass | non-www canonical |
| Pagination | Unknown | Shop page has pagination (page/2/), canonical handling unverified |
| Mobile-Friendly | Unknown | Not tested; Divi is responsive by default |
| Crawl Budget | Warn | 50+ attachment pages in sitemap waste crawl budget |

### 1.2 Status Codes

| URL | Status | Impact |
|---|---|---|
| / | 200 | OK |
| /contact/ | **500** | CRITICAL — Primary conversion page broken |
| /reguliere-schoonmaak/ | **404** | CRITICAL — Primary service page broken |
| /?page_id=318 | **404** | CRITICAL — Same page, different URL |
| /page-sitemap.xml | **500** | HIGH — Search engines can't discover pages via sitemap |

### 1.3 Structured Data

| Schema Type | Present? | Detail |
|---|---|---|
| WebPage | Yes | Yoast auto-generated |
| BreadcrumbList | Yes | Home only (single item) |
| WebSite | Yes | With SearchAction |
| Organization | **No** | **MISSING** — No company schema |
| LocalBusiness | **No** | **MISSING** — Critical for local SEO |
| Service | **No** | **MISSING** — No service schema on any service page |
| Product | Unknown | WooCommerce may add, not verified |
| FAQ | **No** | No FAQ page exists |
| Review | **No** | No reviews implemented |

### 1.4 Open Graph / Social

| Tag | Status | Notes |
|---|---|---|
| og:locale | Pass | nl_NL |
| og:type | Pass | website |
| og:title | Pass | Site title only |
| og:description | **Fail** | **MISSING** on all pages |
| og:image | **Fail** | **MISSING** on all pages |
| twitter:card | Pass | summary_large_image |

---

## 2. On-Page SEO

### 2.1 Title Tags

| Assessment | Score |
|---|---|
| All pages follow "[Page Name] - HDS Onderhoudsdiensten" | **Adequate but generic** |
| No keyword optimization evident | Fail |
| Homepage title is "HOME" (should be descriptive) | Fail |
| Missing brand USP in titles | Fail |

### 2.2 Meta Descriptions

| Assessment | Score |
|---|---|
| **No custom meta descriptions found on any page** | **CRITICAL FAIL** |
| Yoast generates default (no text = no description) | Fail |

### 2.3 Headings (H1-H6)

| Page | H1 | H2 | H3 | Assessment |
|---|---|---|---|---|
| Home | "Helder en Duidelijk..." | None detected | None detected | Poor hierarchy |
| Over HDS | "OVER HDS SCHOONMAAKDIENSTEN" | None | None | Single heading |
| Service pages | Each has H1 | None | Subtitle styling | Only 1 heading per page |

### 2.4 Content Length

| Page | Word Count | Assessment |
|---|---|---|
| Home | ~30 | **CRITICAL FAIL** — Far below 300-word minimum |
| Over HDS | ~120 | Poor |
| Referenties | ~25 | **CRITICAL FAIL** |
| Vacatures | ~5 | **CRITICAL FAIL** |
| Kwaliteit | ~150 | Below average |
| Downloads | ~10 | **CRITICAL FAIL** |
| Glasbewassing | ~180 | Below average |
| Gevelonderhoud | ~130 | Below average |
| Vloeronderhoud | ~140 | Below average |
| VVE Service | ~100 | Below average |
| Oplevering | ~90 | Below average |
| Industrieel | ~60 | Poor |

### 2.5 Keywords

| Service | Primary KW (NL) | On Page? | Notes |
|---|---|---|---|
| Regular cleaning | schoonmaak | Partial | Page broken |
| Window cleaning | glasbewassing, glazenwasser | Yes | Good |
| Facade cleaning | gevelreiniging, gevelonderhoud | Yes | Good |
| Floor maintenance | vloeronderhoud | Yes | Good |
| HOA cleaning | VvE schoonmaak | Partial | Only VVE SERVICE |
| Delivery cleaning | oplevering schoonmaak | Yes | Good |
| Industrial cleaning | industriële schoonmaak | Yes | Thin content |

---

## 3. Local SEO

| Factor | Status | Detail |
|---|---|---|
| Google Business Profile | **Unknown** | No link from site |
| NAP Consistency | **FAIL** | Physical address not on site |
| Local Keywords | **FAIL** | No region/city mentioned on site |
| LocalBusiness Schema | **FAIL** | Not implemented |
| Area Code (0164) | Pass | Phone number visible, implies Bergen op Zoom region |
| Local Citations | **FAIL** | Only VvE Belang, no other directories |

---

## 4. Content SEO

| Factor | Status |
|---|---|
| Blog | **FAIL** — Only default "Hello World" post from 2015 |
| Internal Linking | **FAIL** — Multiple broken links, inconsistent URL patterns |
| External Linking | Pass — Minimal, relevant (VvE Belang, Pi-Apps) |
| Image Alt Text | Unknown — HTML source not fully available |
| Content Freshness | **FAIL** — Latest content January 2021 |
| Duplicate Content | Warn — Attachment pages duplicate image content |
| Multilingual | **FAIL** — Dutch only, no hreflang tags |
| FAQ Content | **FAIL** — No FAQ page, no FAQ structured data |

---

## 5. URL Structure

| Assessment | Detail |
|---|---|
| URL Pattern | Clean, readable slugs (glasbewassing, vloeronderhoud, etc.) |
| Subdirectory Depth | Maximum 1 level — good |
| Trailing Slash | Inconsistent: homepage icons use /glasbewassing (no slash), nav uses /glasbewassing/ (with slash) |
| URL Consistency | **FAIL**: /vve vs /vve-service/, /reguliere-schoonmaak/ vs /?page_id=318 |
| Legacy Domain | PDFs linked from www.hds-onderhoudsdiensten.nl (not redirecting) |

---

## 6. Performance Indicators

| Factor | Inference |
|---|---|
| Divi Theme | Known for large CSS/JS payloads |
| Google Fonts | Self-hosted (good) but Open Sans includes all weights (300-800 italic/normal) |
| jQuery | Custom jQuery shim for Divi — non-standard loading |
| jQuery Migrate | Likely loaded (WordPress core dependency) |
| Page Speed | Likely POOR — Divi + WooCommerce + Formidable + unoptimized images |
| Caching | Unknown |
| Image Optimization | Unknown — PNG icons appear unoptimized |

---

## 7. SEO Priority Issues (Ranked)

| Rank | Issue | Severity | Impact |
|---|---|---|---|
| 1 | Contact page returns 500 | CRITICAL | Conversion impossible |
| 2 | Regular cleaning page returns 404 | CRITICAL | Primary service not indexed |
| 3 | Page sitemap returns 500 | CRITICAL | Search engines can't discover pages |
| 4 | No meta descriptions on any page | HIGH | Zero click-through optimization |
| 5 | No LocalBusiness/Organization schema | HIGH | Missing rich results in SERP |
| 6 | Homepage content <30 words | HIGH | Cannot rank for brand terms |
| 7 | No Google Business Profile link | HIGH | Missing local SEO foundation |
| 8 | No physical address on site | HIGH | Trust signal missing |
| 9 | Multiple broken internal links | MEDIUM | Poor user experience, crawl waste |
| 10 | No blog content since 2015 | MEDIUM | No fresh content signal |
| 11 | No service schema markup | MEDIUM | Missing rich snippet opportunities |
| 12 | Open Graph images missing | MEDIUM | Poor social sharing preview |
| 13 | Thin content on 9 of 12 pages | MEDIUM | Cannot rank for competitive terms |
| 14 | Vacancies as images (not text) | LOW | Accessibility + SEO failure |
| 15 | Instagram widget broken | LOW | Looks unprofessional |
| 16 | Divi theme performance overhead | LOW | Page speed impacted |
| 17 | Default WordPress post still live | LOW | Looks unprofessional |
| 18 | No hreflang/multilingual | LOW | Not needed if only NL customers |
