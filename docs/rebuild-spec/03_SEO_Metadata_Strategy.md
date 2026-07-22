# Part 3: Metadata, Structured Data, Internal Linking, Technical SEO, Local SEO

**HDS Onderhoudsdiensten — Production Build Specification — Part 3 of 8**

---

## 12. Metadata Strategy

### 12.1 Title Tag Template

```
[Page Title] — HDS Onderhoudsdiensten | [Primary Location]
```

**Examples:**
- Home: `Schoonmaakbedrijf Regio Bergen op Zoom — HDS Onderhoudsdiensten`
- Glasbewassing: `Glasbewassing — HDS Onderhoudsdiensten | Bergen op Zoom`
- VVE Service: `VvE Schoonmaak & Onderhoud — HDS Onderhoudsdiensten | Zeeland & West-Brabant`

**Rules:** 50-60 characters, primary keyword first, brand name included, location included for local SEO, separator: em dash or pipe.

### 12.2 Meta Description Template

Every page must have a custom meta description (zero currently exist).

**Format:** 150-160 characters, primary keyword, location, value proposition, call to action.

**Examples:**
- Home: `Professioneel schoonmaakbedrijf in Bergen op Zoom, Zeeland & West-Brabant. Vaste gediplomeerde medewerkers, vrijblijvende offerte. Bel 0164-652846.`
- Glasbewassing: `Glasbewassing voor kantoren, VvE's en bedrijfspanden in Zeeland & West-Brabant. Gecertificeerde glasbewassers, veiligheidspaspoort. Vrijblijvende offerte aanvragen.`
- VVE Service: `VvE schoonmaak en onderhoud voor wooncomplexen. Trappenhuizen, galerijen, garages en klein technisch onderhoud. Een aanspreekpunt. Vraag een offerte aan.`

### 12.3 Open Graph Tags (Social Sharing)

Every page must include:

| Tag | Content |
|---|---|
| `og:locale` | `nl_NL` |
| `og:type` | `website` (pages), `article` (blog), `product` (products) |
| `og:title` | Same as SEO title |
| `og:description` | Same as meta description |
| `og:url` | Full canonical URL |
| `og:image` | 1200x630 px branded image |
| `og:image:width` | `1200` |
| `og:image:height` | `630` |
| `twitter:card` | `summary_large_image` |
| `twitter:title` | Same as og:title |
| `twitter:description` | Same as og:description |
| `twitter:image` | Same as og:image |

**MISSING INFORMATION:** A branded 1200x630 px social share image must be created before launch.

---

## 13. Structured Data Strategy

### 13.1 Required Schema Types

| Schema Type | Pages | Priority | Implementation |
|---|---|---|---|
| `Organization` / `LocalBusiness` | Home, Contact, Over HDS | P0 — CRITICAL | Yoast/Rank Math OR custom JSON-LD |
| `WebSite` (with SearchAction) | All pages | P0 | Auto-generated |
| `WebPage` | All pages | P0 | Auto-generated |
| `BreadcrumbList` | All inner pages | P0 | Theme + Yoast/Rank Math |
| `Service` | Each service page (7) | P1 — HIGH | Custom JSON-LD per service page |
| `FAQPage` | Veelgestelde Vragen | P2 — MEDIUM | Yoast FAQ block |
| `Product` (WooCommerce) | Each product (14) | P1 | WooCommerce structured data |
| `JobPosting` | Each vacancy | P2 | Custom or plugin |
| `Review` | Testimonials with stars | P2 | Custom |

### 13.2 LocalBusiness Schema (JSON-LD)

```json
{
  "@context": "https://schema.org",
  "@type": "HomeAndConstructionBusiness",
  "name": "HDS Onderhoudsdiensten",
  "description": "Schoonmaak- en onderhoudsdiensten voor bedrijven, VvE's, scholen, zorginstellingen en industrie in Zeeland en West-Brabant.",
  "url": "https://helderduidelijkschoon.nl/",
  "telephone": "+31164652846",
  "email": "info@helderduidelijkschoon.nl",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "[MISSING]",
    "addressLocality": "[MISSING]",
    "addressRegion": "[MISSING]",
    "postalCode": "[MISSING]",
    "addressCountry": "NL"
  },
  "geo": {
    "@type": "GeoCoordinates",
    "latitude": "[MISSING]",
    "longitude": "[MISSING]"
  },
  "openingHoursSpecification": {
    "@type": "OpeningHoursSpecification",
    "dayOfWeek": ["[MISSING]"],
    "opens": "[MISSING]",
    "closes": "[MISSING]"
  },
  "areaServed": {
    "@type": "GeoCircle",
    "geoMidpoint": {
      "@type": "GeoCoordinates",
      "latitude": "[MISSING]",
      "longitude": "[MISSING]"
    },
    "geoRadius": "[MISSING]"
  },
  "priceRange": "Op aanvraag",
  "image": "https://helderduidelijkschoon.nl/wp-content/uploads/hds-og-image.jpg",
  "sameAs": [
    "https://www.facebook.com/helderduidelijkschoon/",
    "https://www.instagram.com/hds_schoonmaakdiensten/"
  ]
}
```

**All [MISSING] fields require client-provided information.**

### 13.3 Service Schema (Per Service Page)

```json
{
  "@context": "https://schema.org",
  "@type": "Service",
  "name": "Glasbewassing",
  "description": "[150-char description]",
  "provider": {
    "@type": "HomeAndConstructionBusiness",
    "name": "HDS Onderhoudsdiensten",
    "telephone": "+31164652846",
    "url": "https://helderduidelijkschoon.nl/"
  },
  "areaServed": { "@type": "City", "name": "Bergen op Zoom" },
  "serviceType": "Glasbewassing"
}
```

### 13.4 BreadcrumbList

Every inner page must have both schema AND visible breadcrumbs:
```
Home > [Parent Category] > [Page Name]
```

---

## 14. Internal Linking Strategy

### 14.1 Cross-Link Rules

**Every service page must link to:**
- Contact page or Offerte Aanvragen page (CTA)
- 2-3 related service pages (contextual cross-sell)
- Veelgestelde Vragen page
- Kwaliteit & Veiligheid page

**Every service page must be linked FROM:**
- Homepage (service overview grid)
- Category landing page
- At least 2 other service pages ("Gerelateerde diensten")
- Footer (all services listed)
- Navigation (all services listed)

### 14.2 Anchor Text Rules

| Use | Avoid |
|---|---|
| Descriptive Dutch: "onze glasbewassing diensten" | Generic: "klik hier", "lees meer" |
| Natural language | Exact match spam in every link |
| Varied anchor text across pages | Same anchor text for every link |

### 14.3 Contextual Cross-Links Between Services

| Service Page | Should Link To |
|---|---|
| Glasbewassing | Gevelreiniging, Reguliere Schoonmaak, Oplevering Schoonmaak |
| Gevelreiniging | Glasbewassing, Industriele Schoonmaak |
| Reguliere Schoonmaak | Vloeronderhoud, Glasbewassing, VVE Service |
| Vloeronderhoud | Reguliere Schoonmaak, Oplevering Schoonmaak |
| VVE Service | Reguliere Schoonmaak, Glasbewassing |
| Oplevering Schoonmaak | Reguliere Schoonmaak, Glasbewassing, Vloeronderhoud |
| Industriele Schoonmaak | Reguliere Schoonmaak, Gevelreiniging |

### 14.4 Orphan Page Prevention

**No page may be orphaned** (zero incoming internal links). Every page must be reachable from at least main navigation OR footer AND at least one other content page via contextual link.

**Test:** Run Screaming Frog post-launch. Any page with zero inlinks fails.

---

## 15. Technical SEO Requirements

### 15.1 HTTP Status Codes

| Resource | Expected Status |
|---|---|
| All public pages | 200 |
| 301 redirects (old-new URLs) | 301 |
| `/xmlrpc.php` | 403 (blocked at server) |
| `/wp-admin/` (non-whitelisted) | 403 |
| Blog "Hallo wereld!" post | 410 |
| Old `/?page_id=318` URL | 301 to `/reguliere-schoonmaak/` |
| Non-existent content | 404 (custom page with nav + search + contact) |
| Attachment page URLs | 301 to parent post/page |

### 15.2 Sitemap Requirements

| Sitemap | Content | Priority |
|---|---|---|
| `/sitemap_index.xml` | Sitemap index | P0 |
| `/page-sitemap.xml` | All pages (currently broken - must work) | P0 |
| `/post-sitemap.xml` | Blog posts | P2 |
| `/product-sitemap.xml` | WooCommerce products | P1 |
| `/category-sitemap.xml` | Product/blog categories | P2 |

**Rules:**
- Attachment pages MUST NOT appear in sitemaps
- Author archive pages MUST NOT appear in sitemaps
- Noindex pages MUST NOT appear in sitemaps
- Submit to Google Search Console and Bing Webmaster Tools at launch

### 15.3 robots.txt

```
User-agent: *
Allow: /
Disallow: /wp-admin/
Disallow: /wp-includes/
Disallow: /wp-json/wp/v2/users
Disallow: /*?*
Disallow: /xmlrpc.php
Sitemap: https://helderduidelijkschoon.nl/sitemap_index.xml
```

### 15.4 Remaining Technical SEO Items

| Item | Specification |
|---|---|
| **Canonical tags** | Self-referencing on every page |
| **Hreflang** | Single language (nl-NL). No hreflang needed. |
| **Pagination** | WooCommerce: `rel="next"/"prev"`. Blog: same. |
| **Mobile SEO** | Responsive design. No separate mobile URL. Test with Google Mobile-Friendly Test. |
| **Crawl Budget** | No attachment pages in sitemap. No low-value pages indexed. |

---

## 16. Local SEO Requirements

### 16.1 Google Business Profile

| Action | Priority |
|---|---|
| Claim/verify existing GBP for HDS Onderhoudsdiensten | P0 — CRITICAL |
| Ensure NAP (Name, Address, Phone) is 100% identical to website footer | P0 — CRITICAL |
| Add all service categories (Schoonmaakbedrijf, Glazenwasser, etc.) | P1 |
| Add service area: all municipalities served | P1 |
| Add business hours | P1 |
| Upload 10+ photos: exterior, team, before/after, vehicles, logo | P1 |
| Enable messaging (if client wants GBP inquiries) | P2 |
| Post monthly updates/offers | P2 |
| Link GBP to website; link website to GBP | P0 |

### 16.2 NAP Consistency

The Name, Address, and Phone number must be **identical** across ALL platforms:

| Platform | Status |
|---|---|
| Website (footer + contact + LocalBusiness schema) | MUST match |
| Google Business Profile | MUST match |
| Facebook page | MUST match |
| Instagram profile | MUST match |
| VvE Belang listing | MUST match |
| OSB membership directory | MUST match (if listed) |
| Any other directories | MUST match |

**If the company has moved or changed phone number, update EVERY listing simultaneously.**

### 16.3 Location-Specific Landing Pages (Future — P3)

If the company serves multiple distinct cities (e.g., Bergen op Zoom, Roosendaal, Goes, Middelburg):

| Page | URL |
|---|---|
| Schoonmaakbedrijf Bergen op Zoom | `/schoonmaakbedrijf-bergen-op-zoom/` |
| Schoonmaakbedrijf Roosendaal | `/schoonmaakbedrijf-roosendaal/` |
| Schoonmaakbedrijf Goes | `/schoonmaakbedrijf-goes/` |

**Decision:** P3 — post-launch item. Launch with main service pages only. Add after client confirms served cities.

### 16.4 Local Citations

Submit to Dutch business directories:

- [ ] Bedrijvenpagina.nl
- [ ] MKB.nl
- [ ] Telefoonboek.nl (De Telefoongids)
- [ ] Detelefoongids.nl
- [ ] Drimble.nl
- [ ] NationaleBedrijvengids.nl
- [ ] VvE Belang (already listed — confirm consistency)
- [ ] OSB ledenlijst (if member — confirm)
