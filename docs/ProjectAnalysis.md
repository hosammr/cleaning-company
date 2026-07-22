# Project Analysis — HDS Onderhoudsdiensten

## 1. Project Overview

| Attribute | Value |
|---|---|
| **Company Name** | HDS Onderhoudsdiensten |
| **Domain** | https://helderduidelijkschoon.nl/ |
| **Tagline** | "Helder en Duidelijk voor het Schoonste resultaat!" |
| **Industry** | Schoonmaak & Onderhoudsdiensten (Cleaning & Maintenance Services) |
| **Target Market** | Zakelijk (B2B) — bedrijfspanden, VvE's, industrie, scholen, zorginstellingen |
| **Service Area** | Regio Zeeland / West-Brabant (netnummer 0164 = Bergen op Zoom e.o.) |
| **Language** | Nederlands (nl-NL), single-language only |
| **Site First Published** | 29 juni 2015 |
| **Last Major Content Update** | Januari 2021 (Airfixr productlijn toegevoegd) |
| **Last Sitemap Update** | 22 januari 2024 (waarschijnlijk alleen Yoast plugin-update) |

## 2. Technical Stack

| Component | Detail |
|---|---|
| **CMS** | WordPress 6.2.9 |
| **Theme** | Divi 4.16.1 (Elegant Themes) |
| **SEO Plugin** | Yoast SEO 21.8.1 |
| **eCommerce** | WooCommerce 8.2.5 |
| **Forms** | Formidable Forms (formidableforms.css detected) |
| **Testimonials** | HMS Testimonials (shortcode detected) |
| **Hosting** | Unknown (no hosting header signature detected) |
| **SSL** | HTTPS enabled |
| **Web Server** | Apache (via WordPress default structure) |
| **jQuery** | Custom jQuery shim (Divi compatibility) |
| **Fonts** | Open Sans (Google Fonts, self-hosted via Divi) |
| **XML-RPC** | Enabled (/xmlrpc.php accessible) — security risk |

## 3. Page Inventory

### 3.1 Public Content Pages (15 total)

| # | Page | URL | Status | Content Quality |
|---|---|---|---|---|
| 1 | HOME | / | 200 | Medium — icon grid + single CTA |
| 2 | Over HDS | /over-hds/ | 200 | Low — 2 paragraphs only |
| 3 | Referenties | /referenties/ | 200 | Low — 1 image + empty form + empty testimonials |
| 4 | Vacatures | /vacatures/ | 200 | Low — 2 scanned vacancy images |
| 5 | Kwaliteit & Veiligheid | /kwaliteit-veiligheid/ | 200 | Medium — 3 sections |
| 6 | Downloads | /downloads/ | 200 | Low — 2 PDF links only |
| 7 | Glasbewassing | /glasbewassing/ | 200 | High — most detailed service page |
| 8 | Gevelonderhoud | /gevelreiniging/ | 200 | High — detailed with bullet points |
| 9 | Vloeronderhoud | /vloeronderhoud/ | 200 | High — detailed with bullet points |
| 10 | VVE Service | /vve-service/ | 200 | Medium — decent content |
| 11 | Oplevering Schoonmaak | /oplevering-schoonmaak/ | 200 | Medium — bullet list of tasks |
| 12 | Industriële Schoonmaak | /industriele-schoonmaak/ | 200 | Low — 1 paragraph only |
| 13 | **Reguliere Schoonmaak** | /reguliere-schoonmaak/ | **404** | **BROKEN — linked from homepage** |
| 14 | **Contact** | /contact/ | **500** | **BROKEN — PHP fatal error** |
| 15 | Winkel | /winkel/ | 200 | Medium — WooCommerce shop page |

### 3.2 Blog Posts (1 real post)

| # | Title | URL | Date | Note |
|---|---|---|---|---|
| 1 | Hallo wereld! | /2015/06/29/hallo-wereld/ | 2015-06-29 | Default WordPress post, never removed |
| 2 | Kwaliteit & Veiligheid | /2015/08/25/kwaliteit-veiligheid/ | 2015-08-25 | Redirects to page |

### 3.3 WooCommerce Products (14 products)

| # | Product | Price (excl. BTW) |
|---|---|---|
| 1 | Airfixr 60 | €325,00 |
| 2 | Airfixr 75 | €595,00 |
| 3 | Airfixr 150 | €795,00 |
| 4 | Airfixr Ionisator 220V | €95,00 |
| 5 | Airfixr Panel | €395,00 |
| 6 | Airfixr Panel RVS | €425,00 |
| 7 | Airfixr Panel Silent | €395,00 |
| 8 | F7 Filter 150 | €49,00 |
| 9 | F7 Filter 75 | €35,00 |
| 10 | Ophangsysteem Airfixr Panel RVS | Unknown |
| 11 | Servicepakket 150 | Unknown |
| 12 | Servicepakket 75 | Unknown |
| 13 | UV-C Lamp 16W | Unknown |
| 14 | UV-C Lamp 40W | Unknown |

### 3.4 Attachment Pages (50+ media attachment URLs)

WordPress creates separate attachment pages for each uploaded image. All detected via attachment-sitemap.xml. These dilute SEO value but are standard WordPress behavior.

## 4. Navigation Structure

```
HOME (/)
├── OVER HDS (dropdown)
│   ├── Over HDS (/over-hds/)
│   ├── Referenties (/referenties/)
│   ├── Vacatures (/vacatures/)
│   ├── Kwaliteit & Veiligheid (/kwaliteit-veiligheid/)
│   └── Downloads (/downloads/)
├── GLAS & GEVEL (dropdown)
│   ├── Glasbewassing (/glasbewassing/)
│   └── Gevelonderhoud (/gevelreiniging/)
├── SCHOONMAAKDIENSTEN (dropdown)
│   ├── Vloeronderhoud (/vloeronderhoud/)
│   ├── VVE Service (/vve-service/)
│   ├── Oplevering Schoonmaak (/oplevering-schoonmaak/)
│   ├── Reguliere Schoonmaak (/reguliere-schoonmaak/) ← 404
│   └── Industriële Schoonmaak (/industriele-schoonmaak/)
└── CONTACT (/contact/) ← 500
```

**Note:** Homepage icon grid links to different URLs than navigation:
- `/reguliere-schoonmaak/` → 404 (nav points to `/?page_id=318`)
- `/vve` → no trailing slash (nav points to `/vve-service/`)
- `/glasbewassing` → no trailing slash (nav points to `/glasbewassing/`)
- `/gevelreiniging/` matches nav
- `/vloeronderhoud/` matches nav
- `/kwaliteit-veiligheid/` matches nav
- `/over-hds/` matches nav

## 5. Contact Information

| Type | Detail |
|---|---|
| **Phone** | 0164-652846 (vast netnummer Bergen op Zoom) |
| **Email** | info@helderduidelijkschoon.nl |
| **Facebook** | https://www.facebook.com/helderduidelijkschoon/ |
| **Instagram** | hds_schoonmaakdiensten (Instagram widget broken on site) |
| **Physical Address** | NOT FOUND on website |
| **KVK Number** | NOT FOUND on website |
| **BTW Number** | NOT FOUND on website |
| **Contact Form** | NOT ACCESSIBLE (contact page returns 500 error) |

## 6. Social Media Presence

| Platform | Status | Notes |
|---|---|---|
| Facebook | Active link | facebook.com/helderduidelijkschoon/ |
| Instagram | Linked but broken | Widget returns "Instagram did not return a 200" |
| Twitter/X | None | Not present |
| LinkedIn | None | Not present |
| Google Business | Unknown | Not linked from site |

## 7. External Links & Partnerships

| Link | Purpose |
|---|---|
| www.vvebelang.nl | VvE Belang (HOA association, partnership reference) |
| www.api-apps.nl | Web developer (Pi-Apps, footer credit) |
| www.hds-onderhoudsdiensten.nl | Legacy domain (PDF documents hosted here) |
| enable-javascript.com | Comment system fallback link |

## 8. Key Findings Summary

The website is a 2015-era WordPress/Divi site with WooCommerce that has received minimal maintenance. Several critical infrastructure issues exist: the contact page (primary conversion point) returns a 500 error, a key service page returns 404, and legal compliance (GDPR/AVG) is missing entirely. Content is thin on most pages. The site was originally built by Pi-Apps and uses a legacy subdomain (hds-onderhoudsdiensten.nl) for PDF hosting.
