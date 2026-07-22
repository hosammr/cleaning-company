# Part 1: Executive Summary, Architecture, Information Architecture, Sitemap

**HDS Onderhoudsdiensten — Production Build Specification**
**Version:** 1.0.0 | **Target:** helderduidelijkschoon.nl | **Language:** Nederlands (nl-NL) single-language | **Date:** July 2026

---

## 1. Executive Summary

**Current State:** The existing website is a 2015-era WordPress 6.2.9 / Divi 4.16.1 / WooCommerce 8.2.5 site with critical failures: the contact page returns HTTP 500, the primary cleaning service page returns HTTP 404, the page sitemap returns HTTP 500, zero meta descriptions exist, no privacy policy or cookie consent is present, and all core software is 4+ major versions behind. The site cannot receive web-originated contact-form inquiries.

**Target State:** A modern, performant, accessible (WCAG 2.2 AA), SEO-optimized, legally compliant website rebuilt from scratch that serves as the primary digital acquisition channel for B2B cleaning service inquiries, with a secondary eCommerce function for Airfixr air purification products.

**Decision Point:** This specification assumes a **complete rebuild** on a modern WordPress stack. If the client prefers to repair the existing site, only P0 items from the original Improvement Suggestions apply, and the remainder of this specification must be discarded.

---

## 2. Document Cross-Reference and Conflict Resolution

### 2.1 Source Documents

| # | Document | File |
|---|---|---|
| D1 | Project Analysis | `ProjectAnalysis.md` |
| D2 | Content Inventory | `ContentInventory.md` |
| D3 | Business Requirements | `BusinessRequirements.md` |
| D4 | Improvement Suggestions | `ImprovementSuggestions.md` |
| D5 | Feature List | `FeatureList.md` |
| D6 | Sitemap | `SiteMap.md` |
| D7 | SEO Audit | `SEOAudit.md` |
| D8 | User Journey | `UserJourney.md` |

### 2.2 Detected Inconsistencies — Resolved

| # | Inconsistency | Documents | Resolution |
|---|---|---|---|
| I1 | Page label "Gevelonderhoud" in nav vs "GEVEL" on icon vs URL `gevelreiniging` vs page title "GEVELONDERHOUD" | D1, D2, D6, D8 | **Standardize as "Gevelreiniging"** — the URL slug `gevelreiniging` is the search-indexed term. Page title and nav label become "Gevelreiniging". Icon label becomes "Gevel". If both "onderhoud" and "reiniging" are distinct services, create two pages; otherwise consolidate under `gevelreiniging`. |
| I2 | URL `/vve` (icon) vs `/vve-service/` (nav) for same page | D1, D2, D6, D8 | **Standardize as `/vve-service/`** (canonical). `/vve` becomes a 301 redirect. |
| I3 | Trailing slash: `/glasbewassing` (icon) vs `/glasbewassing/` (nav) | D1, D2, D6 | **Standardize with trailing slash**: `/glasbewassing/` canonical. No-slash variant issues 301. |
| I4 | Icon "SCHOONMAAK" vs nav "Reguliere Schoonmaak" | D2, D6, D8 | **Standardize as "Reguliere Schoonmaak"**. Icon abbreviated to "Schoonmaak". |
| I5 | Two broken URLs for Reguliere Schoonmaak: `/reguliere-schoonmaak/` (icon) and `/?page_id=318` (nav) | D1, D2, D6 | **Build new page at `/reguliere-schoonmaak/`**. `/?page_id=318` returns 301 to it. |
| I6 | Menu groups "GLAS & GEVEL" and "SCHOONMAAKDIENSTEN" have no landing pages | D1, D2, D6 | **Create landing pages** at `/glas-en-gevel/` and `/schoonmaakdiensten/` as parent overview pages. |
| I7 | Airfixr shop unrelated to core business with no cross-linking | D1, D2, D3, D8 | **Cross-link** from relevant service pages. Add shop introduction page at `/luchtreiniging/`. |

### 2.3 Duplicated Information — Consolidated

The following appeared across multiple source documents and is consolidated into this single specification:

- **Service portfolio** (D1, D2, D3, D5, D7, D8) → Consolidated in Section 4 and page templates
- **Navigation structure** (D1, D2, D6) → Consolidated in Section 6 (Part 2)
- **URL inconsistencies** (D2, D6) → Consolidated in URL Strategy and Redirect Strategy (Part 2)
- **Contact information** (D1, D2) → Consolidated in Section 4.4
- **Critical issues** (all 8 docs) → Consolidated in Gap Analysis (Part 6)
- **Technical stack** (D1, D5) → Consolidated in Section 3

### 2.4 Missing Dependencies — Now Addressed

| Missing Item | Addressed In |
|---|---|
| Formal technical architecture specification | Section 3 |
| CMS platform decision (WordPress vs alternative) | Section 28 (Part 5) |
| Page template specifications | Section 29 (Part 5) |
| Form field specifications | Section 24 (Part 4) |
| Performance budgets | Section 17 (Part 4) |
| Accessibility compliance level (WCAG version) | Section 18 (Part 4) |
| Security hardening specification | Section 19 (Part 4) |
| Backup and disaster recovery specification | Section 35 (Part 6) |
| QA testing plan | Section 43 (Part 7) |
| Content migration procedures | Part 2 and Section 45 (Part 7) |
| Deployment pipeline specification | Section 36 (Part 6) |
| Error handling and monitoring strategy | Sections 33, 34 (Part 5) |
| Design system and component inventory | Sections 25, 26, 27 (Part 5) |

---

## 3. Architecture Overview

### 3.1 Technology Stack (Target)

| Component | Selection | Rationale |
|---|---|---|
| **CMS** | WordPress 6.7+ (latest stable) | Client familiar with WordPress; WooCommerce compatibility required |
| **PHP** | PHP 8.2+ | Required by modern WordPress; performance improvements |
| **Database** | MySQL 8.0+ or MariaDB 10.6+ | Standard WordPress backend |
| **Theme** | Custom block-based theme (FSE-compatible) OR GeneratePress Pro / Kadence Pro | Replaces outdated Divi; Divi MUST NOT be used due to performance overhead and lock-in risk |
| **Page Builder** | Native WordPress Block Editor (Gutenberg) ONLY | No third-party page builder (no Divi, no Elementor, no WPBakery) |
| **SEO** | Yoast SEO Premium OR Rank Math Pro | Meta management, sitemaps, schema, redirects |
| **eCommerce** | WooCommerce 9.x+ (latest stable) | Required for Airfixr product sales |
| **Forms** | Gravity Forms (recommended) OR WS Form | Replaces broken Formidable Forms |
| **Caching** | WP Rocket OR FlyingPress + server-side Redis object cache | Critical for performance |
| **CDN** | Cloudflare (free tier minimum) OR BunnyCDN | Global asset delivery, DDoS protection, SSL |
| **Hosting** | Managed WordPress (Kinsta, WP Engine, or Cloud86.nl for Dutch hosting) | Must support PHP 8.2+, Redis, daily backups, staging |
| **SSL** | HTTPS enforced (Let's Encrypt or Cloudflare) | Standard |
| **Version Control** | Git (GitHub / GitLab) | All custom theme and plugin code |
| **Deployment** | DeployHQ, GitHub Actions, or WP Engine Git Push | Automated deployment from Git |
| **Analytics** | Google Analytics 4 (via Google Site Kit or GTM) | Traffic and conversion measurement |
| **Tag Manager** | Google Tag Manager | Centralized script management |
| **Backups** | BlogVault OR UpdraftPlus Premium OR host-managed nightly backups | Automated daily offsite backups |
| **Security** | Wordfence Premium OR Solid Security Pro | Firewall, malware scanning, 2FA |
| **Cookie Consent** | Complianz Premium (Dutch-specific) OR Cookiebot | GDPR/AVG-compliant cookie banner |
| **Image Optimization** | ShortPixel OR Imagify OR converter for WebP/AVIF | Automated WebP conversion |
| **Search** | Relevanssi OR native WordPress search | Improved site search |
| **Monitoring** | UptimeRobot (free) + Query Monitor (dev) | Uptime alerts + performance debugging |

### 3.2 Technology Stack (What MUST NOT Be Used)

| Banned Component | Reason |
|---|---|
| Divi Theme | Performance overhead, lock-in risk, outdated |
| Elementor | Page-builder lock-in risk |
| Any page builder storing content as shortcodes | Migration nightmare, trapped content |
| Formidable Forms | Current version broken (causing 500 error) |
| Outdated PHP (<8.0) | Security risk, EOL |
| XML-RPC enabled | Brute-force attack vector |

### 3.3 Infrastructure Diagram

```
[Cloudflare CDN (SSL, caching, WAF)]
          |
          v
[Managed WordPress Host]
    |-- Production Environment
    |-- Staging Environment
    |-- Offsite Backup Storage (daily)
          |
          v
[WordPress 6.7+]
    |-- Custom Block Theme (native Block Editor)
    |-- WooCommerce 9.x+, Gravity Forms
    |-- Yoast SEO / Rank Math, WP Rocket
    |-- Complianz (cookie consent), Wordfence (security)
          |
          v
[MySQL 8.0+ / MariaDB 10.6+]
```

---

## 4. Information Architecture

### 4.1 Content Hierarchy

```
Helder en Duidelijk voor het Schoonste resultaat!
|
|-- Diensten
|   |-- Glasbewassing
|   |-- Gevelreiniging
|   |-- Reguliere Schoonmaak
|   |-- Vloeronderhoud
|   |-- VVE Service
|   |-- Oplevering Schoonmaak
|   |-- Industriele Schoonmaak
|
|-- Over HDS
|   |-- Kwaliteit & Veiligheid
|   |-- Referenties
|   |-- Vacatures
|   |-- Downloads
|
|-- Luchtreiniging (Airfixr)
|   |-- Winkel (WooCommerce)
|
|-- Contact
|   |-- Offerte Aanvragen
|
|-- Kennisbank (Blog)
|   |-- Blog artikelen
|   |-- Veelgestelde Vragen (FAQ)
```

### 4.2 Page Inventory (Target — All Pages)

| # | Page Title (NL) | URL Slug | Template | Priority |
|---|---|---|---|---|
| P01 | Home | `/` | Home | P0 |
| P02 | Glasbewassing | `/glasbewassing/` | Service | P0 |
| P03 | Gevelreiniging | `/gevelreiniging/` | Service | P0 |
| P04 | Reguliere Schoonmaak | `/reguliere-schoonmaak/` | Service | P0 — **NEW (currently 404)** |
| P05 | Vloeronderhoud | `/vloeronderhoud/` | Service | P0 |
| P06 | VVE Service | `/vve-service/` | Service | P0 |
| P07 | Oplevering Schoonmaak | `/oplevering-schoonmaak/` | Service | P0 |
| P08 | Industriele Schoonmaak | `/industriele-schoonmaak/` | Service | P0 |
| P09 | Glas & Gevel (landing) | `/glas-en-gevel/` | Category Landing | P1 — **NEW** |
| P10 | Schoonmaakdiensten (landing) | `/schoonmaakdiensten/` | Category Landing | P1 — **NEW** |
| P11 | Over HDS | `/over-hds/` | About | P0 |
| P12 | Kwaliteit & Veiligheid | `/kwaliteit-veiligheid/` | About | P0 |
| P13 | Referenties | `/referenties/` | About | P1 |
| P14 | Vacatures | `/vacatures/` | About | P1 |
| P15 | Downloads | `/downloads/` | About | P1 |
| P16 | Contact | `/contact/` | Contact | P0 — **REBUILD (currently 500)** |
| P17 | Offerte Aanvragen | `/offerte-aanvragen/` | Quote Request | P1 — **NEW** |
| P18 | Veelgestelde Vragen | `/veelgestelde-vragen/` | FAQ | P2 — **NEW** |
| P19 | Privacyverklaring | `/privacyverklaring/` | Legal | P0 — **NEW (legal requirement)** |
| P20 | Cookiebeleid | `/cookiebeleid/` | Legal | P0 — **NEW (legal requirement)** |
| P21 | Algemene Voorwaarden | `/algemene-voorwaarden/` | Legal | P0 — **NEW (currently PDF only)** |
| P22 | Disclaimer | `/disclaimer/` | Legal | P2 — **NEW** |
| P23 | Luchtreiniging | `/luchtreiniging/` | Product Landing | P1 — **NEW** |
| P24 | Winkel | `/winkel/` | Shop | P1 |
| P25 | Product detail pages (14) | `/product/{slug}/` | Product | P1 |
| P26 | Winkelmand | `/winkelmand/` | Cart | P1 |
| P27 | Afrekenen | `/afrekenen/` | Checkout | P1 |
| P28 | Mijn Account | `/mijn-account/` | Account | P1 |
| P29 | Blog (index) | `/kennisbank/` | Blog Index | P2 — **NEW** |
| P30 | Blog posts (5-10 initial) | `/kennisbank/{slug}/` | Blog Post | P2 — **NEW** |
| P31 | 404 Page | — | 404 | P0 — **NEW** |
| P32 | Bedankt (post-form) | `/bedankt/` | Thank You | P0 — **NEW** |

### 4.3 Page Groupings by Content Type

| Group | Pages | Primary Audience |
|---|---|---|
| **Service Pages** | P02–P08 | B2B — facility managers, VvE boards, construction PMs, school admins, factory managers |
| **Category Landings** | P09–P10 | B2B — same; SEO landing pages for broader search terms |
| **Company/Trust** | P11–P15 | All visitors — credibility, recruitment, compliance |
| **Conversion** | P16–P17 | All prospects — lead capture |
| **Legal** | P19–P22 | All visitors — legal compliance, trust |
| **eCommerce** | P23–P28 | B2B/B2C — Airfixr product buyers |
| **Content/SEO** | P18, P29–P30 | All visitors — organic traffic acquisition |

### 4.4 Company Information Block (Appears in Footer on Every Page)

```
HDS Onderhoudsdiensten
[Straat + Huisnummer]           -- MISSING INFORMATION REQUIRED
[Postcode] [Plaats]             -- MISSING INFORMATION REQUIRED
Telefoon: 0164-652846
E-mail: info@helderduidelijkschoon.nl
KVK: [XXXXXXXX]                 -- MISSING INFORMATION REQUIRED
BTW: [NLXXXXXXXXXB01]           -- MISSING INFORMATION REQUIRED
```

---

## 5. Final Sitemap

### 5.1 URL Hierarchy (flat — maximum depth 2)

```
helderduidelijkschoon.nl/
|-- /                                              [HOME]
|-- /glasbewassing/                                [GLASBEWASSING]
|-- /gevelreiniging/                               [GEVELREINIGING]
|-- /reguliere-schoonmaak/                         [REGULIERE SCHOONMAAK]
|-- /vloeronderhoud/                               [VLOERONDERHOUD]
|-- /vve-service/                                  [VVE SERVICE]
|-- /oplevering-schoonmaak/                        [OPLEVERING SCHOONMAAK]
|-- /industriele-schoonmaak/                       [INDUSTRIELE SCHOONMAAK]
|-- /glas-en-gevel/                                [GLAS & GEVEL landing]
|-- /schoonmaakdiensten/                           [SCHOONMAAKDIENSTEN landing]
|-- /over-hds/                                     [OVER HDS]
|-- /kwaliteit-veiligheid/                         [KWALITEIT & VEILIGHEID]
|-- /referenties/                                  [REFERENTIES]
|-- /vacatures/                                    [VACATURES]
|-- /downloads/                                    [DOWNLOADS]
|-- /contact/                                      [CONTACT]
|-- /offerte-aanvragen/                            [OFFERTE AANVRAGEN]
|-- /bedankt/                                      [THANK YOU]
|-- /veelgestelde-vragen/                          [FAQ]
|-- /privacyverklaring/                            [PRIVACY POLICY]
|-- /cookiebeleid/                                 [COOKIE POLICY]
|-- /algemene-voorwaarden/                         [TERMS]
|-- /disclaimer/                                   [DISCLAIMER]
|-- /luchtreiniging/                               [AIRFIXR LANDING]
|-- /winkel/                                       [SHOP]
|-- /product/{slug}/                               [PRODUCT x14]
|-- /winkelmand/                                   [CART]
|-- /afrekenen/                                    [CHECKOUT]
|-- /mijn-account/                                 [MY ACCOUNT]
|-- /kennisbank/                                   [BLOG INDEX]
|-- /kennisbank/{slug}/                            [BLOG POSTS]
```

### 5.2 External URLs (not part of site hierarchy)

| Label | URL | Location |
|---|---|---|
| Facebook | https://www.facebook.com/helderduidelijkschoon/ | Footer, Contact |
| Instagram | https://www.instagram.com/hds_schoonmaakdiensten/ | Footer, Contact |
| VvE Belang | https://www.vvebelang.nl/ | VVE Service page |
| OSB | MISSING — link URL unknown | Kwaliteit & Veiligheid page |
| Pi-Apps | https://www.api-apps.nl/ | Remove from new site unless still active developer |

### 5.3 Pages NOT Migrated (Content Removed)

| Current URL | Reason | Action |
|---|---|---|
| `/2015/06/29/hallo-wereld/` | Default WordPress post from 2015 | Delete. 410 Gone. |
| `/2015/08/25/kwaliteit-veiligheid/` | Suspected redirect post | Delete. 410 Gone. |
| `/?page_id=318` | Broken secondary URL for Reguliere Schoonmaak | 301 to `/reguliere-schoonmaak/` |
| `/vve` (no trailing slash) | Duplicate for VVE Service | 301 to `/vve-service/` |
| All attachment pages (~50) | WordPress auto-generated for images | Redirect to parent page. Remove from sitemap. |
| `/xmlrpc.php` | XML-RPC endpoint | Disable at server level. 403 Forbidden. |
