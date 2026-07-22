# Feature List — HDS Onderhoudsdiensten

## Current Features (Extracted from Existing Site)

---

### 1. Content Features

| Feature | Implementation | Status |
|---|---|---|
| Service pages | 7 WordPress pages with Dutch content | 5 working, 1 thin, 1 broken |
| News/Blog | WordPress posts | 1 default post (2015), effectively unused |
| Job vacancies | WordPress page with image attachments | Content as scanned images, not text |
| Client references | Dedicated page + HMS Testimonials plugin | Only 1 logo, no testimonials displayed |
| Downloads | PDF links for terms & conditions | 2 PDFs on legacy domain |
| Company info | "Over HDS" page | Very thin (2 paragraphs) |
| Quality & Safety | Dedicated page | Decent content, no certification logos |

### 2. Commerce Features

| Feature | Implementation | Status |
|---|---|---|
| Product catalog | WooCommerce shop (/winkel/) | 14 Airfixr products |
| Shopping cart | WooCommerce cart (/winkelmand/) | Header cart icon with count |
| Pricing display | WooCommerce | All prices excl. BTW |
| Product variants | Unknown (not verified) | May exist for Airfixr products |
| Checkout | WooCommerce standard | Not verified |
| Payment processing | Unknown | Not verified |

### 3. Contact & Conversion Features

| Feature | Implementation | Status |
|---|---|---|
| Contact form | Formidable Forms on /contact/ | **BROKEN — 500 error** |
| Phone contact | Clickable tel: link in header | Working: 0164-652846 |
| Email contact | Clickable mailto: link in header | Working: info@helderduidelijkschoon.nl |
| Quote request CTA | Button on homepage → /contact/ | **BROKEN** |
| Testimonial submission | HMS Testimonials form | Rendered but empty |
| Blog comments | WordPress native | Working on blog post |

### 4. Navigation & UX Features

| Feature | Implementation | Status |
|---|---|---|
| Main navigation | Divi menu with dropdowns (3 columns) | Working |
| Homepage icon grid | 8 linked icons | 3 links broken/wrong |
| Search | WordPress search in footer | Working |
| Responsive design | Divi theme (responsive by default) | Not verified |
| Breadcrumbs | Partial (Yoast schema only, not visible) | Schema exists, UI absent |

### 5. Social & Integration Features

| Feature | Implementation | Status |
|---|---|---|
| Facebook link | Footer link | Working |
| Instagram widget | Divi/WordPress widget | **BROKEN** — "Instagram did not return a 200" |
| VvE Belang partnership | Link on VVE Service page | Working |

### 6. SEO & Technical Features

| Feature | Implementation | Status |
|---|---|---|
| SEO plugin | Yoast SEO 21.8.1 | Active |
| XML Sitemap | Yoast sitemap index | Partial — page sitemap returns 500 |
| robots.txt | Allow all, crawl-delay:5 | Present |
| Canonical URLs | Self-referencing | Present |
| Open Graph tags | Yoast auto-generated | Present but no images/descriptions |
| Structured data | Yoast basic schema (WebPage, WebSite) | Minimal — missing org/service/local |
| SSL/HTTPS | HTTPS enforced | Working |

### 7. Security Features

| Feature | Implementation | Status |
|---|---|---|
| SSL Certificate | HTTPS active | Working |
| XML-RPC | Enabled | **Security risk** — should be disabled or restricted |

---

## Feature Gap Analysis

### Missing Critical Features

| Feature | Priority | Business Impact |
|---|---|---|
| Working contact page | **CRITICAL** | No online lead capture |
| Working regular cleaning page | **CRITICAL** | Core service not visible |
| Privacy policy (GDPR/AVG) | **CRITICAL** | Legal non-compliance |
| Cookie consent banner | **CRITICAL** | Legal non-compliance (ePrivacy) |
| Meta descriptions | **HIGH** | Zero SERP click-through optimization |
| LocalBusiness schema | **HIGH** | Invisible in local search results |
| Google Business Profile link | **HIGH** | Missing local SEO foundation |
| Physical address on site | **HIGH** | Trust signal absent |
| KVK/BTW numbers | **HIGH** | Legal requirement for Dutch B2B |

### Missing Value-Add Features

| Feature | Priority | Business Impact |
|---|---|---|
| Online booking/appointment system | Medium | Competitive advantage |
| Quote request form (working) | Medium | Lead capture |
| Live chat or WhatsApp | Medium | Immediate contact option |
| FAQ section | Medium | Reduce phone inquiries |
| Client portal/login | Low | Service management |
| Newsletter signup | Low | Email marketing |
| Blog/case studies | Medium | SEO + credibility |
| Photo galleries of work | Medium | Visual proof of quality |
| Team member profiles | Low | Personal connection |
| Multilingual (EN) | Low | Not needed for domestic market |

---

## Plugin & Extension Inventory

Detected from HTML source:

| Plugin | Version | Purpose | Status |
|---|---|---|---|
| Divi (Elegant Themes) | 4.16.1 | Theme/Page Builder | Active |
| Yoast SEO | 21.8.1 | SEO management | Active |
| WooCommerce | 8.2.5 | eCommerce webshop | Active |
| Formidable Forms | Unknown | Contact forms | Active but form inaccessible |
| HMS Testimonials | Unknown | Client testimonials | Active but empty |
| WordPress Core | 6.2.9 | CMS | Outdated |

---

## Technical Debt Indicators

| Indicator | Detail |
|---|---|
| WordPress version | 6.2.9 (latest: 6.6.x) — 4+ major versions behind |
| Divi version | 4.16.1 (latest: 4.27.x) — multiple versions behind |
| WooCommerce version | 8.2.5 (latest: 9.x) — major version behind |
| Yoast SEO version | 21.8.1 (latest: 23.x) — multiple versions behind |
| PHP version | Unknown — likely outdated given WP version |
| jQuery loading | Custom shim for Divi — non-standard, potential conflicts |
| XML-RPC | Enabled — common attack vector for brute force |
| Legacy domain | hds-onderhoudsdiensten.nl used for PDF hosting |
