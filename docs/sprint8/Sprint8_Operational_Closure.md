# Sprint 8 — Operational Execution & Project Closure

**Document ID:** SP8-001 | **Version:** 1.0.0 | **Date:** 2026-07-26
**Predecessor:** Sprint 7 (Launch & Handover, 95/100, GO)
**Scope:** Operational execution, launch validation, hypercare, client acceptance, project closure

---

## Table of Contents

1. [Phase 1 — Master Operational Checklist](#phase-1--master-operational-checklist)
2. [Phase 2 — Launch Validation](#phase-2--launch-validation)
3. [Phase 3 — Hypercare (30-Day Post-Launch Monitoring)](#phase-3--hypercare-30-day-post-launch-monitoring)
4. [Phase 4 — Client Acceptance](#phase-4--client-acceptance)

---

## Phase 1 — Master Operational Checklist

This is the single source of truth for all remaining operational work. Every task required to go from current state (code complete, documentation frozen) to live production is tracked here.

### Legend

| Symbol | Meaning |
|---|---|
| ☐ | Not started |
| 🔄 | In progress |
| ✅ | Completed |
| ⚠️ | Blocked / At risk |
| ❌ | Cannot complete (dependency) |

| Priority | Meaning |
|---|---|
| P0 | Critical — blocks launch |
| P1 | High — must complete within 7 days of launch |
| P2 | Medium — must complete within 30 days of launch |
| P3 | Low — nice to have, post-launch |

---

### 1.1 Hosting

| # | Task | Status | Priority | Owner | Dependencies | Est. Duration | Validation Method |
|---|---|---|---|---|---|---|---|
| H01 | Select managed WordPress hosting provider (Kinsta/WP Engine/Cloud86) | ☐ | P0 | Client + Developer | MI-20 (client budget) | 3 days | Provider selected; account created |
| H02 | Provision production hosting plan | ☐ | P0 | Client | H01 | 1 day | Hosting dashboard shows active plan |
| H03 | Provision staging environment (staging.helderduidelijkschoon.nl) | ☐ | P0 | Developer | H02 | 1 hour | Staging URL resolves, loads WordPress |
| H04 | Provision production environment (helderduidelijkschoon.nl) | ☐ | P0 | Developer | H02 | 1 hour | Production URL reserved, accessible |
| H05 | Verify server meets minimum requirements (2 vCPU, 2 GB RAM, 20 GB SSD) | ☐ | P0 | Developer | H02 | 15 min | Hosting dashboard confirms specs |
| H06 | Verify PHP 8.2+ installed with required extensions | ☐ | P0 | Developer | H02 | 15 min | `php -m` shows: mysqli, redis, imagick, curl, mbstring, intl, zip, gd, exif, opcache |
| H07 | Verify MySQL 8.0+ or MariaDB 10.6+ | ☐ | P0 | Developer | H02 | 10 min | `mysql --version` |
| H08 | Configure hosting-level daily backups (platform-native) | ☐ | P0 | Developer | H02 | 30 min | Hosting backup dashboard shows active schedule |
| H09 | Enable hosting-level staging → production push (if available) | ☐ | P1 | Developer | H03, H04 | 30 min | Push workflow tested |
| H10 | Configure hosting-level SSH/SFTP access | ☐ | P1 | Developer | H02 | 15 min | SSH connection successful |

---

### 1.2 Server

| # | Task | Status | Priority | Owner | Dependencies | Est. Duration | Validation Method |
|---|---|---|---|---|---|---|---|
| S01 | Configure php.ini for production (memory 256M, execution 300s, upload 64M) | ☐ | P0 | Developer | H05 | 15 min | `phpinfo()` or `wp server-info` confirms values |
| S02 | Enable OPcache with production settings (256MB, validate_timestamps=1) | ☐ | P0 | Developer | S01 | 15 min | `phpinfo()` shows OPcache active |
| S03 | Configure Nginx/Apache for production (gzip, expires headers, security headers) | ☐ | P0 | Developer | H04 | 30 min | Securityheaders.com grade A+; headers present in response |
| S04 | Set file permissions: dirs 755, files 644, wp-config.php 400 | ☐ | P0 | Developer | H04 | 10 min | `ls -la` confirms permissions |
| S05 | Disable directory listing (Options -Indexes) | ☐ | P0 | Developer | S03 | 5 min | `curl -I /wp-content/uploads/` returns 403 |
| S06 | Block PHP execution in /wp-content/uploads/ | ☐ | P0 | Developer | S03 | 5 min | `.php` file in uploads returns 403 |
| S07 | Configure cron jobs (WP cron, daily backup, weekly plugin updates, monthly DB optimize) | ☐ | P1 | Developer | H02 | 30 min | `crontab -l` shows configured entries; test run verifies execution |
| S08 | Configure UFW/iptables (allow 80, 443, 22; deny all others) | ☐ | P0 | Developer | H02 | 15 min | `ufw status` or `iptables -L` confirms rules |
| S09 | Bind MySQL to localhost only (no remote access) | ☐ | P0 | Developer | H02 | 10 min | `mysql -h <public-ip>` fails; `mysql -h 127.0.0.1` succeeds |
| S10 | Configure log rotation (PHP, Nginx, MySQL — 30 day retention) | ☐ | P1 | Developer | S03 | 15 min | Log files present and rotating |

---

### 1.3 WordPress

| # | Task | Status | Priority | Owner | Dependencies | Est. Duration | Validation Method |
|---|---|---|---|---|---|---|---|
| W01 | Install WordPress 6.7+ (nl_NL locale) via WP-CLI or hosting panel | ☐ | P0 | Developer | H04, S01 | 15 min | Homepage loads at production URL |
| W02 | Generate wp-config.php from wp-config-env.php template | ☐ | P0 | Developer | W01 | 10 min | wp-config.php exists with correct DB, salts, env constants |
| W03 | Set WP_HOME and WP_SITEURL to https://helderduidelijkschoon.nl | ☐ | P0 | Developer | W02 | 5 min | `wp option get home` and `wp option get siteurl` both return correct URL |
| W04 | Set DB_PREFIX to `hds_` | ☐ | P0 | Developer | W02 | 5 min | `wp db tables` shows `hds_` prefix |
| W05 | Generate and set all 8 security salts from api.wordpress.org | ☐ | P0 | Developer | W02 | 5 min | Each salt populated with unique 64-char string |
| W06 | Set WP_ENV to `production` | ☐ | P0 | Developer | W02 | 2 min | `wp config get WP_ENV` returns `production` |
| W07 | Set DISABLE_WP_CRON = true (rely on server cron) | ☐ | P1 | Developer | W02, S07 | 2 min | `wp config get DISABLE_WP_CRON` returns `true` |
| W08 | Set DISALLOW_FILE_EDIT = true | ☐ | P0 | Developer | W02 | 2 min | Appearance → Theme File Editor: not visible in admin |
| W09 | Set DISALLOW_FILE_MODS = false (allow plugin updates from admin) | ☐ | P2 | Developer | W02 | 2 min | Plugin update links visible in admin |
| W10 | Set WP_POST_REVISIONS = 10 (limit revisions) | ☐ | P2 | Developer | W02 | 2 min | `wp config get WP_POST_REVISIONS` returns `10` |
| W11 | Set WP_MEMORY_LIMIT = 256M | ☐ | P1 | Developer | W02 | 2 min | `wp config get WP_MEMORY_LIMIT` returns `256M` |
| W12 | Delete default content (Hello World post, Sample Page, default comment) | ☐ | P0 | Developer | W01 | 5 min | Posts, Pages, Comments all empty |
| W13 | Set permalink structure to `/%postname%/` | ☐ | P0 | Developer | W01 | 2 min | `wp rewrite structure` returns `/%postname%/` |
| W14 | Set timezone to Europe/Amsterdam | ☐ | P0 | Developer | W01 | 2 min | `wp option get timezone_string` returns `Europe/Amsterdam` |
| W15 | Set date format to `j F Y` (Dutch-friendly) | ☐ | P2 | Developer | W01 | 2 min | Blog posts show Dutch date format |
| W16 | Set site language to Nederlands | ☐ | P0 | Developer | W01 | 2 min | `wp option get WPLANG` returns `nl_NL` |
| W17 | Create admin account with unique username + strong password + 2FA | ☐ | P0 | Developer | W01 | 5 min | Login with credentials; 2FA prompt appears |
| W18 | Remove any admin accounts named `admin`, `administrator`, `root` | ☐ | P0 | Developer | W01 | 5 min | `wp user list` shows only custom-named admins |
| W19 | Create Editor account for client daily use (non-admin) | ☐ | P0 | Developer | W01 | 5 min | Client logs in as Editor; can edit pages, not settings |
| W20 | Configure WordPress auto-updates for minor releases only | ☐ | P1 | Developer | W01 | 5 min | `wp config get WP_AUTO_UPDATE_CORE` returns `minor` |

---

### 1.4 Plugins

| # | Task | Status | Priority | Owner | Dependencies | Est. Duration | Validation Method |
|---|---|---|---|---|---|---|---|
| P01 | Install + activate WooCommerce 9.x+ | ☐ | P0 | Developer | W01 | 10 min | WooCommerce menu visible in WP admin |
| P02 | Run WooCommerce setup wizard (EUR, NL separators, page assignments) | ☐ | P0 | Developer | P01 | 15 min | WC pages assigned; currency = EUR; decimal = comma; thousand = dot |
| P03 | Install + activate Mollie for WooCommerce | ☐ | P0 | Developer | P01 | 5 min | Mollie payment methods visible in WC → Settings → Payments |
| P04 | Configure Mollie API keys (live) + webhook URL | ☐ | P0 | Developer | P03 | 15 min | Test purchase succeeds with real iDEAL redirect |
| P05 | Install + activate Gravity Forms (premium license) | ☐ | P0 | Developer | W01 | 5 min | Gravity Forms menu visible in WP admin |
| P06 | Create GF-1: Contactformulier (7 fields + reCAPTCHA) | ☐ | P0 | Developer | P05 | 30 min | Form renders on /contact/; submit → redirect + email |
| P07 | Create GF-2: Offerteformulier (11 fields + file upload + reCAPTCHA) | ☐ | P0 | Developer | P05 | 30 min | Form renders on /offerte-aanvragen/; submit → redirect + email with attachment |
| P08 | Create GF-3: Sollicitatieformulier (6 fields + file upload + reCAPTCHA) | ☐ | P0 | Developer | P05 | 30 min | Form renders on vacancy pages; submit → email with CV |
| P09 | Configure GF notification emails (to info@ + confirmation to user) | ☐ | P0 | Developer | P06, P07, P08 | 15 min | Test submissions deliver emails to both addresses |
| P10 | Set GF entry retention to 12 months auto-delete | ☐ | P1 | Developer | P06, P07 | 5 min | GF → Settings → Personal Data: 12 months retention |
| P11 | Install + activate Rank Math Pro (premium license) | ☐ | P0 | Developer | W01 | 5 min | Rank Math menu visible in WP admin |
| P12 | Run Rank Math setup wizard | ☐ | P0 | Developer | P11 | 15 min | XML Sitemap enabled; Schema enabled; Redirections enabled |
| P13 | Configure global meta template: `%title% \| HDS Onderhoudsdiensten %sep% %sitename%` | ☐ | P0 | Developer | P11 | 5 min | Home page source shows correct title tag |
| P14 | Configure Local SEO (Organization: HomeAndConstructionBusiness) | ☐ | P1 | Developer | P11 | 10 min | Organization schema validates in Google Rich Results Test |
| P15 | Configure XML Sitemap (include pages, posts, products; exclude attachment, author) | ☐ | P0 | Developer | P11 | 10 min | `/sitemap_index.xml` returns 200; valid XML |
| P16 | Add 7 redirect rules (R01-R07 per Sprint 7 §3.4) | ☐ | P0 | Developer | P11 | 15 min | Each old URL → 301 → new URL confirmed via curl |
| P17 | Enable 404 Monitor | ☐ | P1 | Developer | P11 | 5 min | 404 Monitor dashboard shows data after launch |
| P18 | Install + activate FlyingPress (premium license) | ☐ | P0 | Developer | W01 | 5 min | FlyingPress menu visible in WP admin |
| P19 | Configure FlyingPress: Page Cache, CSS Minify, JS Defer, Critical CSS, Lazy Load, Remove Unused CSS | ☐ | P0 | Developer | P18 | 15 min | Response headers show cache HIT; CSS/JS minified in source |
| P20 | Configure cache bypass for WC pages: /winkelmand/, /afrekenen/, /mijn-account/ | ☐ | P0 | Developer | P18, P01 | 10 min | `CF-Cache-Status: BYPASS` on cart/checkout/account |
| P21 | Install + activate Redis Object Cache (free) | ☐ | P1 | Developer | W01 | 5 min | Redis Object Cache menu visible |
| P22 | Verify Redis connection (Settings → Redis → Enable) | ☐ | P1 | Developer | P21 | 5 min | Status indicator green; `redis-cli ping` → PONG |
| P23 | Install + activate Complianz Premium (premium license) | ☐ | P0 | Developer | W01 | 5 min | Complianz menu visible |
| P24 | Run Complianz wizard (NL market, GA4 + GTM, consent mode v2) | ☐ | P0 | Developer | P23 | 20 min | Cookie banner appears on fresh browser visit |
| P25 | Verify: zero non-functional cookies before consent | ☐ | P0 | Developer | P24 | 10 min | DevTools → Application → Cookies: only functional cookies until Accept |
| P26 | Assign auto-generated Cookiebeleid to correct page (P20) | ☐ | P1 | Developer | P24 | 5 min | /cookiebeleid/ page shows Complianz-generated content |
| P27 | Install + activate Wordfence Premium (premium license) | ☐ | P0 | Developer | W01 | 5 min | Wordfence menu visible |
| P28 | Configure Wordfence: Firewall enabled, Brute Force max 3, Custom Login URL | ☐ | P0 | Developer | P27 | 15 min | `/wp-admin` redirects; custom login URL works |
| P29 | Enable 2FA on all admin accounts | ☐ | P0 | Developer | W17, P27 | 10 min | Each admin login prompts for 2FA code |
| P30 | Run initial Wordfence malware scan — verify clean | ☐ | P0 | Developer | P27 | 15 min | Scan results: 0 critical, 0 high, 0 medium |
| P31 | Schedule daily Wordfence scans (03:00) | ☐ | P1 | Developer | P27 | 5 min | Scan log shows daily scheduled scans |
| P32 | Install + activate Post SMTP (free) | ☐ | P0 | Developer | W01 | 5 min | Post SMTP menu visible |
| P33 | Configure Post SMTP with SendGrid/Mailgun/SES credentials | ☐ | P0 | Developer | P32, D02 | 15 min | Send test email from Post SMTP → delivered to inbox |
| P34 | Set From Name: HDS Onderhoudsdiensten; From Email: info@helderduidelijkschoon.nl | ☐ | P0 | Developer | P32 | 5 min | Received email shows correct From name/address |
| P35 | Enable Post SMTP Email Log | ☐ | P1 | Developer | P32 | 5 min | Email log page shows sent emails |
| P36 | Install + activate BlogVault / UpdraftPlus Premium (backups) | ☐ | P0 | Developer | W01 | 5 min | Backup plugin menu visible |
| P37 | Configure daily backups at 03:00; retention: 30 daily + 4 weekly + 12 monthly | ☐ | P0 | Developer | P36 | 15 min | Backup log shows daily completions |
| P38 | Configure offsite destination (Google Drive/Dropbox/S3) | ☐ | P0 | Developer | P36 | 15 min | Offsite storage shows backup files |
| P39 | Take + verify first full backup | ☐ | P0 | Developer | P37, P38 | 15 min | Backup file present in offsite storage |
| P40 | Install + activate ShortPixel / Imagify (image optimization) | ☐ | P1 | Developer | W01 | 5 min | Plugin active; images optimized on upload |
| P41 | Configure auto-optimize on upload + WebP conversion | ☐ | P1 | Developer | P40 | 10 min | Upload test image → auto-optimized + WebP version generated |
| P42 | Install + activate Relevanssi (search, free) | ☐ | P1 | Developer | W01 | 5 min | Relevanssi menu visible |
| P43 | Build Relevanssi search index after all content created | ☐ | P1 | Developer | P42, C05 | 10 min | Search for "glasbewassing" returns correct page as #1 result |
| P44 | Enable auto-updates for all plugins (minor/patch releases only) | ☐ | P1 | Developer | All plugins | 10 min | Plugins page shows auto-updates enabled |

---

### 1.5 WooCommerce

| # | Task | Status | Priority | Owner | Dependencies | Est. Duration | Validation Method |
|---|---|---|---|---|---|---|---|
| WC01 | Set Shop page to `/winkel/`, Cart to `/winkelmand/`, Checkout to `/afrekenen/`, Account to `/mijn-account/` | ☐ | P0 | Developer | P01 | 5 min | WC → Settings → Advanced: page assignments correct |
| WC02 | Configure currency: EUR, thousand separator `.`, decimal separator `,`, decimals `2` | ☐ | P0 | Developer | P02 | 5 min | Product page shows price as `€ 129,95` |
| WC03 | Configure tax: prices entered excl. BTW, standard rate 21%, display suffix "excl. BTW" | ☐ | P0 | Developer | P02 | 10 min | Cart shows tax line; suffix visible |
| WC04 | Configure shipping zone: Nederland; classes: Klein pakket, Groot pakket | ☐ | P0 | Developer | WC01 | 15 min | Checkout shows shipping options for NL address |
| WC05 | Set shipping rates (per client data MI-14) | ☐ | P0 | Developer | WC04 | 10 min | Shipping cost calculated correctly at checkout |
| WC06 | Enable Mollie payment methods: iDEAL, Bancontact, Credit Card, PayPal, SEPA | ☐ | P0 | Developer | P04 | 10 min | All methods visible at checkout |
| WC07 | Enable Bank Transfer (BACS) for B2B invoice payments | ☐ | P1 | Developer | WC06 | 5 min | Bank Transfer option visible at checkout |
| WC08 | Configure all 10 WC email notifications (New Order, Processing, Completed, etc.) | ☐ | P0 | Developer | P33 | 15 min | WC → Settings → Emails: all enabled; From: HDS Onderhoudsdiensten |
| WC09 | Customize email templates with Dutch text + logo | ☐ | P1 | Developer | WC08 | 20 min | Order confirmation email: Dutch, branded, logo present |
| WC10 | Set Terms & Conditions page to `/algemene-voorwaarden/` | ☐ | P0 | Developer | WC01 | 5 min | Checkout shows T&C checkbox linking to correct page |
| WC11 | Set Privacy Policy page to `/privacyverklaring/` | ☐ | P0 | Developer | WC01 | 5 min | Checkout shows privacy checkbox linking to correct page |
| WC12 | Import 14 Airfixr products via CSV (WC → Import) | ☐ | P0 | Developer | WC01 | 20 min | /winkel/ shows 14 products with images, prices, descriptions |
| WC13 | Verify product images, SKUs, prices, categories, short descriptions all correct | ☐ | P0 | Developer | WC12 | 15 min | Each product page: all fields populated, no broken images |
| WC14 | Test purchase flow: browse → product → add to cart → checkout → iDEAL → confirmation | ☐ | P0 | Developer | WC12, P04 | 15 min | Order appears in WC → Orders; confirmation email received |
| WC15 | Test guest checkout (no account creation required) | ☐ | P1 | Developer | WC14 | 10 min | Guest can complete purchase without registration |
| WC16 | Test My Account: login, order history, address management | ☐ | P1 | Developer | WC01 | 10 min | My Account pages functional |
| WC17 | Add test product to cart → navigate away → return: cart preserved | ☐ | P1 | Developer | WC14 | 5 min | Cart items persist across navigation |
| WC18 | Test empty cart behavior: /winkelmand/ shows "Je winkelmand is leeg" with terug naar winkel link | ☐ | P2 | Developer | WC01 | 5 min | Empty cart message in Dutch |

---

### 1.6 Forms

| # | Task | Status | Priority | Owner | Dependencies | Est. Duration | Validation Method |
|---|---|---|---|---|---|---|---|
| F01 | Place GF-1 Contactformulier on /contact/ page | ☐ | P0 | Developer | P06, C05 | 5 min | /contact/ loads with form visible |
| F02 | Place GF-2 Offerteformulier on /offerte-aanvragen/ page | ☐ | P0 | Developer | P07, C05 | 5 min | /offerte-aanvragen/ loads with form visible |
| F03 | Place GF-3 Sollicitatieformulier on each vacature page (via block/pattern) | ☐ | P0 | Developer | P08, C05 | 10 min | Vacature page shows application form |
| F04 | Test GF-1: submit → redirect to /bedankt/?type=contact → email to info@ delivered within 2 min | ☐ | P0 | Developer | F01, P09 | 5 min | Email in inbox; subject correct; all field values present |
| F05 | Test GF-1: confirmation email delivered to user's email | ☐ | P0 | Developer | F04, P09 | 5 min | User receives branded confirmation email |
| F06 | Test GF-2: submit with file upload (PDF < 5MB) → redirect + email with attachment link | ☐ | P0 | Developer | F02, P09 | 5 min | Email received with file link; file downloadable |
| F07 | Test GF-2: reject file > 5MB or non-PDF/JPG/PNG/DOCX | ☐ | P1 | Developer | F06 | 5 min | Form shows validation error in Dutch |
| F08 | Test GF-3: submit with CV → email notification to info@ | ☐ | P0 | Developer | F03, P09 | 5 min | Email received with CV attachment link |
| F09 | Verify reCAPTCHA v3 badge visible on all 3 forms | ☐ | P0 | Developer | F04, F06, F08 | 5 min | reCAPTCHA badge in bottom-right corner |
| F10 | Verify privacy checkbox unchecked by default on all forms | ☐ | P0 | Developer | F04, F06, F08 | 3 min | Each form: privacy checkbox unchecked on page load |
| F11 | Verify privacy checkbox link goes to /privacyverklaring/ | ☐ | P0 | Developer | F04 | 3 min | Clicking link opens privacy policy page |
| F12 | Verify all form validation errors display in Dutch | ☐ | P1 | Developer | F04, F06, F08 | 5 min | Submit empty form: error messages in Dutch |
| F13 | Verify inline validation: required fields show error on blur | ☐ | P2 | Developer | F12 | 5 min | Tab through fields: errors appear inline |
| F14 | Verify form styling matches design system (colors, typography, spacing) | ☐ | P1 | Developer | F01, F02, F03 | 10 min | Visual check: forms match approved design |

---

### 1.7 SEO

| # | Task | Status | Priority | Owner | Dependencies | Est. Duration | Validation Method |
|---|---|---|---|---|---|---|---|
| SEO01 | Write 32 unique meta titles (50-60 chars, keyword + brand + location) | ☐ | P0 | Content Editor | C05 | 2 hours | Export all pages from Rank Math; zero empty, zero duplicate |
| SEO02 | Write 32 unique meta descriptions (150-160 chars, keyword + value + CTA) | ☐ | P0 | Content Editor | C05 | 2 hours | Export all pages; zero empty, zero duplicate |
| SEO03 | Apply meta titles + descriptions via Rank Math per-page fields | ☐ | P0 | Developer | P11, SEO01, SEO02 | 1 hour | View Source on each page: title + description present |
| SEO04 | Verify every page has exactly one H1 | ☐ | P0 | Developer | C05 | 15 min | Screaming Frog: zero missing H1, zero multiple H1 |
| SEO05 | Verify all images have Dutch alt text (zero missing) | ☐ | P0 | Developer | M07 | 30 min | Screaming Frog: zero missing alt text |
| SEO06 | Verify self-referencing canonicals on all pages | ☐ | P0 | Developer | P11 | 10 min | Each page `<link rel="canonical">` points to itself |
| SEO07 | Verify Open Graph tags complete on all pages | ☐ | P1 | Developer | P11 | 10 min | Facebook Sharing Debugger: no errors |
| SEO08 | Verify Twitter Card tags complete | ☐ | P1 | Developer | P11 | 10 min | Twitter Card Validator: card renders correctly |
| SEO09 | Generate + validate XML Sitemap | ☐ | P0 | Developer | P15 | 10 min | `/sitemap_index.xml` returns 200; W3C valid XML |
| SEO10 | Submit XML Sitemap to Google Search Console | ☐ | P0 | Developer | GSC01 | 5 min | GSC shows sitemap submitted; status "Success" |
| SEO11 | Submit XML Sitemap to Bing Webmaster Tools | ☐ | P2 | Developer | GSC01 | 5 min | Bing shows sitemap submitted |
| SEO12 | Verify robots.txt accessible at /robots.txt | ☐ | P0 | Developer | W01 | 5 min | `/robots.txt` returns 200 with correct rules |
| SEO13 | Verify all 7 redirect rules return 301 (not 302, not 307) | ☐ | P0 | Developer | P16 | 15 min | httpstatus.io: all old URLs → 301 → correct new URL |
| SEO14 | Verify zero redirect chains (each old URL → single 301 → new URL) | ☐ | P0 | Developer | SEO13 | 10 min | httpstatus.io: zero chains |
| SEO15 | Verify 2 x 410 Gone responses (2015 posts) | ☐ | P1 | Developer | P16 | 5 min | curl old URLs → 410 |
| SEO16 | Verify HTTPS enforced: HTTP → 301 → HTTPS + HSTS header | ☐ | P0 | Developer | SSL03 | 10 min | securityheaders.com: HSTS present; HTTP redirect works |
| SEO17 | Verify www → non-www redirect working | ☐ | P0 | Developer | P16 | 5 min | http://www.helderduidelijkschoon.nl → 301 → https://helderduidelijkschoon.nl |
| SEO18 | Verify zero broken internal links (Screaming Frog crawl) | ☐ | P0 | Developer | C05 | 20 min | Screaming Frog: zero 404 on internal links |
| SEO19 | Verify LocalBusiness schema present + valid | ☐ | P0 | Developer | P14 | 10 min | Google Rich Results Test: LocalBusiness → Valid |
| SEO20 | Verify Organization schema present + valid | ☐ | P1 | Developer | P14 | 5 min | Google Rich Results Test: Organization → Valid |
| SEO21 | Verify Service schema on each of 7 service pages | ☐ | P1 | Developer | C05 | 15 min | Each service page: Service schema valid |
| SEO22 | Verify FAQPage schema on FAQ page | ☐ | P1 | Developer | C05 | 5 min | Google Rich Results Test: FAQPage → Valid |
| SEO23 | Verify BreadcrumbList schema on all inner pages | ☐ | P1 | Developer | C05 | 10 min | Each inner page has valid BreadcrumbList |
| SEO24 | Verify mobile-friendly test passes (Google Mobile-Friendly Test) | ☐ | P1 | Developer | C05 | 10 min | Google Mobile-Friendly Test: "Page is mobile-friendly" |
| SEO25 | Run full Screaming Frog crawl: zero errors, zero warnings | ☐ | P0 | Developer | All content + SEO tasks | 30 min | Screaming Frog report: 0 errors, 0 warnings |

---

### 1.8 Analytics

| # | Task | Status | Priority | Owner | Dependencies | Est. Duration | Validation Method |
|---|---|---|---|---|---|---|---|
| A01 | Create Google Analytics 4 property (if not existing) | ☐ | P0 | Developer | None | 15 min | GA4 dashboard shows property |
| A02 | Enable Enhanced Measurement (all: page views, scrolls, outbound clicks, site search, video, file downloads) | ☐ | P0 | Developer | A01 | 5 min | GA4 → Admin → Data Streams → Enhanced Measurement: all toggles ON |
| A03 | Set data retention to 14 months | ☐ | P1 | Developer | A01 | 2 min | GA4 → Admin → Data Settings → Data Retention: 14 months |
| A04 | Add internal traffic filter (office IP — from client MI) | ☐ | P1 | Developer | A01 | 5 min | GA4 → Admin → Data Filters: internal traffic filter active |
| A05 | Create Google Tag Manager container (if not existing) | ☐ | P0 | Developer | None | 15 min | GTM dashboard shows container |
| A06 | Add GA4 Configuration tag in GTM | ☐ | P0 | Developer | A01, A05 | 10 min | GTM Preview: GA4 tag fires on all pages |
| A07 | Add conversion tags: phone_click, email_click, form_submission, quote_request | ☐ | P1 | Developer | A05 | 20 min | GTM Preview: each conversion event fires on corresponding action |
| A08 | Add conversion tags: add_to_cart, purchase (if Airfixr kept) | ☐ | P1 | Developer | A05, WC01 | 15 min | GTM Preview: WC events fire on cart/checkout actions |
| A09 | Set HDS_GTM_ID in wp-config.php | ☐ | P0 | Developer | A05 | 2 min | View Source: GTM snippet present with correct container ID |
| A10 | Set HDS_GA4_ID in wp-config.php | ☐ | P0 | Developer | A01 | 2 min | View Source: GA4 snippet present (via GTM) |
| A11 | Verify GA4 real-time report shows page views | ☐ | P0 | Developer | A09, A10 | 5 min | Visit site → GA4 Real-Time shows active user |
| A12 | Verify GTM snippet in page source (View Source: googletagmanager.com/gtm.js) | ☐ | P0 | Developer | A09 | 2 min | GTM container snippet present in `<head>` and `<body>` |
| A13 | Verify Complianz fires GA4 only after consent | ☐ | P0 | Developer | P25, A06 | 10 min | Before consent: zero GA4 cookies. After accept: GA4 cookies set. |
| A14 | Create Google Search Console domain property (helderduidelijkschoon.nl) | ☐ | P0 | Developer | None | 10 min | GSC dashboard shows domain property |
| A15 | Verify GSC domain via DNS TXT record or HTML tag | ☐ | P0 | Developer | D01, A14 | 15 min | GSC shows "Ownership verified" |
| A16 | Submit XML Sitemap to GSC | ☐ | P0 | Developer | SEO09, A14 | 5 min | GSC → Sitemaps: sitemap submitted, status "Success" |

---

### 1.9 Security

| # | Task | Status | Priority | Owner | Dependencies | Est. Duration | Validation Method |
|---|---|---|---|---|---|---|---|
| SEC01 | Verify XML-RPC returns 403 (curl -I /xmlrpc.php) | ☐ | P0 | Developer | P28 | 5 min | `curl -I https://helderduidelijkschoon.nl/xmlrpc.php` → HTTP/2 403 |
| SEC02 | Verify REST user endpoint returns 403 (curl /wp-json/wp/v2/users) | ☐ | P0 | Developer | W02 | 5 min | `curl https://helderduidelijkschoon.nl/wp-json/wp/v2/users` → 403 |
| SEC03 | Verify author enumeration blocked (/?author=1 returns 403 or redirects) | ☐ | P0 | Developer | W02 | 5 min | Browser: /?author=1 does not reveal username |
| SEC04 | Verify custom login URL active (/wp-admin redirects to 404) | ☐ | P0 | Developer | P28 | 5 min | `/wp-admin` → not accessible; custom URL shows login form |
| SEC05 | Verify 2FA prompt on all admin logins | ☐ | P0 | Developer | P29 | 5 min | Login → 2FA code prompt before dashboard access |
| SEC06 | Verify DISALLOW_FILE_EDIT active (no Theme/Plugin File Editor) | ☐ | P0 | Developer | W08 | 3 min | Appearance → no "Theme File Editor"; Plugins → no "Plugin File Editor" |
| SEC07 | Verify HSTS header present: `Strict-Transport-Security: max-age=31536000; includeSubDomains` | ☐ | P0 | Developer | SSL03 | 5 min | securityheaders.com → grade A+ |
| SEC08 | Verify security headers: X-Content-Type-Options, X-Frame-Options, Referrer-Policy, X-XSS-Protection, Permissions-Policy | ☐ | P0 | Developer | S03 | 5 min | securityheaders.com: all headers present |
| SEC09 | Run Wordfence scan — verify zero critical/high/medium issues | ☐ | P0 | Developer | P30 | 10 min | Wordfence scan report clean |
| SEC10 | Verify file permissions: dirs 755, files 644, wp-config.php 400 | ☐ | P0 | Developer | S04 | 10 min | `find /path -type d ! -perm 755` returns nothing critical |
| SEC11 | Verify no default plugins installed (Hello Dolly, Akismet) | ☐ | P1 | Developer | P01 | 3 min | Plugins list: only the 12 required plugins |
| SEC12 | Verify no unused themes installed (Twenty*) | ☐ | P1 | Developer | T01 | 3 min | Themes list: only HDS theme active |
| SEC13 | Verify wp-config.php is not web-accessible | ☐ | P0 | Developer | S04 | 3 min | Browser: /wp-config.php → 403 or redirect |
| SEC14 | Verify debug mode OFF: WP_DEBUG = false, WP_DEBUG_DISPLAY = false, WP_DEBUG_LOG = false | ☐ | P0 | Developer | W02 | 3 min | `wp config get WP_DEBUG` → false |
| SEC15 | Verify no PHP errors visible in frontend (white screen for fatal errors only) | ☐ | P0 | Developer | SEC14 | 5 min | Visit pages with query params: no PHP warnings/notices shown |

---

### 1.10 Performance

| # | Task | Status | Priority | Owner | Dependencies | Est. Duration | Validation Method |
|---|---|---|---|---|---|---|---|
| PERF01 | Run PageSpeed Insights Mobile on Home — target >= 90 | ☐ | P0 | Developer | P19, C05 | 5 min | PSI URL: home page; Mobile score >= 90 |
| PERF02 | Run PageSpeed Insights Mobile on Service page (glasbewassing) — target >= 90 | ☐ | P0 | Developer | P19, C05 | 5 min | PSI: service page; Mobile score >= 90 |
| PERF03 | Run PageSpeed Insights Mobile on Contact page — target >= 90 | ☐ | P0 | Developer | P19, C05 | 5 min | PSI: contact page; Mobile score >= 90 |
| PERF04 | Run PageSpeed Insights Desktop on Home — target >= 95 | ☐ | P1 | Developer | P19, C05 | 5 min | PSI: home page; Desktop score >= 95 |
| PERF05 | Verify LCP < 2.5s on all templates | ☐ | P0 | Developer | PERF01, PERF02, PERF03 | 5 min | PSI: LCP < 2500ms on each tested page |
| PERF06 | Verify CLS < 0.1 on all templates | ☐ | P0 | Developer | PERF01, PERF02, PERF03 | 5 min | PSI: CLS < 0.1 on each tested page |
| PERF07 | Verify TTFB < 600ms | ☐ | P1 | Developer | P19 | 5 min | GTmetrix or WebPageTest: TTFB < 600ms |
| PERF08 | Run WebPageTest (Amsterdam, Moto G4, 3G Fast) — pass all checks | ☐ | P1 | Developer | P19, C05 | 15 min | WebPageTest result: no critical warnings |
| PERF09 | Run GTmetrix — verify Grade A | ☐ | P1 | Developer | P19, C05 | 10 min | GTmetrix: Grade A on home + service + contact |
| PERF10 | Verify FlyingPress cache active (response headers show HIT) | ☐ | P0 | Developer | P19 | 5 min | `curl -I` → `X-Cache: HIT` or FlyingPress-specific header |
| PERF11 | Verify Redis object cache connected (Redis Object Cache plugin: status green) | ☐ | P1 | Developer | P22 | 5 min | WP Admin → Redis: status green; cache hit rate > 80% |
| PERF12 | Verify Cloudflare cache active (CF-Cache-Status: HIT on static pages) | ☐ | P1 | Developer | CDN01 | 5 min | `curl -I` → `CF-Cache-Status: HIT` |
| PERF13 | Verify CSS minified (production source: single minified CSS file) | ☐ | P0 | Developer | P19 | 3 min | View Source: CSS files minified (single line or few lines) |
| PERF14 | Verify JS deferred (production source: scripts have defer or are delayed) | ☐ | P0 | Developer | P19 | 3 min | View Source: script tags have `defer` attribute |
| PERF15 | Verify images served as WebP | ☐ | P1 | Developer | P41, M07 | 5 min | DevTools → Network → image requests: Content-Type: image/webp |
| PERF16 | Verify lazy loading active on below-fold images | ☐ | P1 | Developer | P19, W01 | 5 min | DevTools → Network: images below fold load on scroll |
| PERF17 | Verify cache bypass for WC pages (cart/checkout/account = BYPASS) | ☐ | P0 | Developer | P20 | 5 min | `curl -I /winkelmand/` → cache: BYPASS or MISS (not HIT) |

---

### 1.11 Migration

| # | Task | Status | Priority | Owner | Dependencies | Est. Duration | Validation Method |
|---|---|---|---|---|---|---|---|
| M01 | Full crawl of current live site with Screaming Frog (all URLs, status codes, titles, metas) | ☐ | P0 | Developer | Old site access | 30 min | CSV export saved; all 32 old URLs documented |
| M02 | Export 16 months of GSC data from old site | ☐ | P0 | Developer | GSC access to old site | 15 min | CSV exported with search queries, pages, positions, clicks |
| M03 | Document all backlinks (Ahrefs/Semrush/GSC) | ☐ | P1 | Developer | M01 | 30 min | Backlink CSV with URL, anchor text, domain authority |
| M04 | Export Google Business Profile data (NAP, categories, reviews) | ☐ | P0 | Developer | GBP access | 15 min | NAP values documented; reviews exported |
| M05 | Download all images from old site Media Library | ☐ | P0 | Developer | Old site access | 20 min | All media files in local folder |
| M06 | Download all PDFs from hds-onderhoudsdiensten.nl | ☐ | P0 | Developer | Legacy domain access | 10 min | All PDFs in local folder |
| M07 | Upload + optimize all images: WebP, quality 85, 1200px max width | ☐ | P0 | Developer | M05, P41 | 1 hour | Media Library: all images WebP; size < 150 KB each |
| M08 | Set Dutch alt text on all non-decorative images | ☐ | P0 | Developer | M07, SEO05 | 30 min | Media Library: alt text populated in Dutch |
| M09 | Upload all PDFs to new Media Library | ☐ | P0 | Developer | M06 | 10 min | Media Library: all PDFs accessible |
| M10 | Update internal links to new PDF URLs | ☐ | P0 | Developer | M09 | 15 min | No broken PDF links in Screaming Frog crawl |
| M11 | Lower DNS TTL to 300 seconds (24h before launch) | ☐ | P0 | Developer | D01 | 5 min | `dig helderduidelijkschoon.nl` shows TTL = 300 |
| M12 | Notify client of content freeze on old site | ☐ | P0 | Developer | M01 | 5 min | Email sent; client acknowledged |
| M13 | Take final full backup of old live site (files + DB) | ☐ | P0 | Developer | Old site access | 15 min | Backup file verified (restore test on separate env) |
| M14 | Verify old site backup restoration (test restore to separate environment) | ☐ | P0 | Developer | M13 | 20 min | Restored site: home page loads, admin login works, forms submit |
| M15 | Export WooCommerce data from old site (products, orders, customers) | ☐ | P0 | Developer | Old site access, WC01 | 15 min | CSV files with complete WC data |
| M16 | Export old site WordPress content XML (Tools → Export → All Content) | ☐ | P1 | Developer | M13 | 5 min | WXR file saved |
| M17 | Verify domain registrar login credentials available | ☐ | P0 | Client | MI-22 | 5 min | Client confirms access to domain registrar |
| M18 | Verify hosting control panel access (old + new) | ☐ | P0 | Client | MI-23 | 5 min | Client confirms access to both hosting panels |
| M19 | Document current DNS records (A, CNAME, MX, TXT) before any changes | ☐ | P0 | Developer | D01 | 10 min | Screenshot/CSV of all DNS records |

---

### 1.12 Client Data

| # | Task | Status | Priority | Owner | Dependencies | Est. Duration | Validation Method |
|---|---|---|---|---|---|---|---|
| CD01 | Collect client street address + postal code + city (MI-01) | ☐ | P0 | Client | None | N/A | Address data received |
| CD02 | Collect KVK registration number (MI-02) | ☐ | P0 | Client | None | N/A | KVK number received |
| CD03 | Collect BTW (VAT) number (MI-03) | ☐ | P0 | Client | None | N/A | BTW number received |
| CD04 | Collect business operating hours (MI-04) | ☐ | P0 | Client | None | N/A | Opening hours received |
| CD05 | Collect service area (municipalities/postcodes) (MI-05) | ☐ | P1 | Client | None | N/A | Service area list received |
| CD06 | Collect logo vector file (SVG/AI/EPS) (MI-06) | ☐ | P0 | Client | None | N/A | Logo file received |
| CD07 | Collect brand color palette confirmation (MI-07) | ☐ | P1 | Client | None | N/A | Colors confirmed or approved |
| CD08 | Collect typography preference (MI-08) | ☐ | P1 | Client | None | N/A | Font choice confirmed |
| CD09 | Collect project photos (MI-09) — optional, stock fallback exists | ☐ | P2 | Client | None | N/A | Photos received or stock confirmed |
| CD10 | Collect testimonial names/logos (5+) (MI-10) | ☐ | P1 | Client | None | N/A | Testimonials received |
| CD11 | Collect testimonial quotes (3+) (MI-11) | ☐ | P1 | Client | None | N/A | Quotes received |
| CD12 | Collect vacancy text as editable text (not JPG) (MI-12) | ☐ | P1 | Client | None | N/A | Vacancy text received |
| CD13 | Collect shipping costs/delivery policy (MI-14) — if Airfixr kept | ☐ | P0 | Client | None | N/A | Shipping info received |
| CD14 | Collect Airfixr keep/remove decision (MI-15) | ☐ | P0 | Client | None | N/A | Written decision received |
| CD15 | Collect Terms & Conditions text (MI-16) | ☐ | P0 | Client | None | N/A | AV text received |
| CD16 | Collect privacyverklaring content or engage lawyer (MI-17) | ☐ | P0 | Client | None | N/A | Privacy policy drafted + lawyer-reviewed |
| CD17 | Collect legal entity type (BV/eenmanszaak/VOF) (MI-18) | ☐ | P0 | Client | None | N/A | Entity type confirmed |
| CD18 | Collect social media URLs (Facebook, Instagram) (MI-19) | ☐ | P1 | Client | None | N/A | URLs received |
| CD19 | Collect Google Business Profile URL or access (MI-21) | ☐ | P0 | Client | None | N/A | GBP access or URL received |
| CD20 | Confirm domain registrar login available (MI-22) | ☐ | P0 | Client | None | N/A | Registrar access confirmed |
| CD21 | Confirm hosting control panel access (MI-23) | ☐ | P0 | Client | None | N/A | Hosting access confirmed |
| CD22 | Collect old developer contact for migration notification (MI-24) | ☐ | P1 | Client | None | N/A | Pi-Apps contact received |
| CD23 | Collect OSB membership link URL (MI-25) | ☐ | P2 | Client | None | N/A | OSB link received |
| CD24 | Populate Customizer: phone, email, address, KVK, BTW, hours, social URLs | ☐ | P0 | Developer | CD01-CD05, CD18, CD19 | 15 min | All Customizer fields populated; values render on frontend |
| CD25 | Upload logo to Customizer → Site Identity | ☐ | P0 | Developer | CD06 | 5 min | Logo visible in header on all pages |

---

### 1.13 DNS

| # | Task | Status | Priority | Owner | Dependencies | Est. Duration | Validation Method |
|---|---|---|---|---|---|---|---|
| D01 | Document all current DNS records (A, AAAA, CNAME, MX, TXT, NS) | ☐ | P0 | Developer | M17 | 15 min | Screenshot/export of all DNS records |
| D02 | Configure SPF record: `v=spf1 include:<smtp-provider>.net ~all` | ☐ | P0 | Developer | P33 | 10 min | `dig TXT helderduidelijkschoon.nl` shows SPF |
| D03 | Configure DKIM record (CNAME provided by SMTP service) | ☐ | P0 | Developer | P33 | 10 min | `dig CNAME <selector>._domainkey.helderduidelijkschoon.nl` returns value |
| D04 | Configure DMARC record: `v=DMARC1; p=none; rua=mailto:info@helderduidelijkschoon.nl` | ☐ | P0 | Developer | P33 | 10 min | `dig TXT _dmarc.helderduidelijkschoon.nl` shows DMARC |
| D05 | Verify SPF + DKIM + DMARC with mxtoolbox.com | ☐ | P0 | Developer | D02, D03, D04 | 10 min | MxToolbox: all checks pass |
| D06 | Verify MX records unchanged after DNS cutover (preserve email routing) | ☐ | P0 | Developer | D01 | 5 min | `dig MX helderduidelijkschoon.nl` matches pre-migration values |
| D07 | Update A record to point to production server IP | ☐ | P0 | Developer | H04 | 5 min | `dig A helderduidelijkschoon.nl` returns production IP |
| D08 | Add CNAME record for www → @ (or A record matching root) | ☐ | P0 | Developer | D07 | 5 min | `dig CNAME www.helderduidelijkschoon.nl` resolves correctly |
| D09 | Add GSC verification TXT record (if DNS method chosen) | ☐ | P0 | Developer | A15 | 5 min | GSC shows "Ownership verified" |
| D10 | Lower DNS TTL to 300s (at least 24h before launch) | ☐ | P0 | Developer | D07 | 5 min | `dig helderduidelijkschoon.nl` shows TTL = 300 |
| D11 | Verify DNS propagation after cutover (whatsmydns.net) | ☐ | P0 | Developer | D07, D10 | 10 min | All global resolvers return production IP |
| D12 | Verify legacy domain (hds-onderhoudsdiensten.nl) DNS still resolves or has 301 redirects configured | ☐ | P1 | Developer | M06 | 10 min | Legacy domain resolves → redirects to new domain, or PDF links updated |

---

### 1.14 SSL

| # | Task | Status | Priority | Owner | Dependencies | Est. Duration | Validation Method |
|---|---|---|---|---|---|---|---|
| SSL01 | Install SSL certificate (Let's Encrypt or Cloudflare Origin) | ☐ | P0 | Developer | H04 | 15 min | `curl -I https://helderduidelijkschoon.nl` → HTTP/2 200 |
| SSL02 | Configure Cloudflare SSL/TLS to "Full (Strict)" | ☐ | P0 | Developer | CDN01, SSL01 | 5 min | Cloudflare dashboard: SSL/TLS = Full (Strict) |
| SSL03 | Configure HSTS header: `Strict-Transport-Security: max-age=31536000; includeSubDomains` | ☐ | P0 | Developer | S03, SSL01 | 5 min | securityheaders.com → HSTS present |
| SSL04 | Configure HTTP → HTTPS redirect (Nginx/Apache) | ☐ | P0 | Developer | S03 | 5 min | `curl -I http://helderduidelijkschoon.nl` → 301 → https:// |
| SSL05 | Configure www → non-www redirect via HTTPS | ☐ | P0 | Developer | S03 | 5 min | `curl -I http://www.helderduidelijkschoon.nl` → 301 → https://helderduidelijkschoon.nl |
| SSL06 | Verify SSL certificate chain is complete (SSL Labs grade A+) | ☐ | P0 | Developer | SSL01 | 5 min | SSL Labs: grade A+; no chain issues |
| SSL07 | Add SSL expiry monitoring (UptimeRobot or Cloudflare notification) | ☐ | P1 | Developer | SSL01 | 5 min | Alert configured; test alert fires |
| SSL08 | Verify mixed content: zero HTTP resources on HTTPS pages | ☐ | P0 | Developer | SSL01 | 10 min | Browser console: no mixed content warnings; Screaming Frog: zero HTTP resources |

---

### 1.15 Email

| # | Task | Status | Priority | Owner | Dependencies | Est. Duration | Validation Method |
|---|---|---|---|---|---|---|---|
| E01 | Verify SPF record published and valid | ☐ | P0 | Developer | D02 | 5 min | MxToolbox SPF check: pass |
| E02 | Verify DKIM record published and valid | ☐ | P0 | Developer | D03 | 5 min | MxToolbox DKIM check: pass |
| E03 | Verify DMARC record published and valid | ☐ | P0 | Developer | D04 | 5 min | MxToolbox DMARC check: pass |
| E04 | Send test email from Post SMTP → verify inbox delivery < 2 min | ☐ | P0 | Developer | P33 | 5 min | Test email in info@ inbox within 2 minutes |
| E05 | Send test via Contact form (GF-1) → verify email delivered to info@ | ☐ | P0 | Developer | F04 | 5 min | Contact form email in inbox; all fields present |
| E06 | Send test via Quote form (GF-2) → verify email + attachment delivered | ☐ | P0 | Developer | F06 | 5 min | Quote form email in inbox; file link works |
| E07 | Send test via Sollicitatie form (GF-3) → verify email + CV delivered | ☐ | P0 | Developer | F08 | 5 min | Vacature email in inbox; CV attachment link works |
| E08 | Send test WC order → verify New Order email to info@ + confirmation to customer | ☐ | P0 | Developer | WC14 | 10 min | Both emails received; From: HDS Onderhoudsdiensten |
| E09 | Verify all emails branded: logo, Dutch, From: HDS Onderhoudsdiensten <info@...> | ☐ | P1 | Developer | E05, E06, E07, E08 | 10 min | Each email: correct branding, Dutch language, correct From address |
| E10 | Run mail-tester.com check → score >= 9/10 | ☐ | P0 | Developer | E04 | 5 min | mail-tester.com: score >= 9 |
| E11 | Verify no emails land in spam (Gmail, Outlook, Yahoo — test each) | ☐ | P0 | Developer | E04 | 10 min | Test with Gmail, Outlook, Yahoo: emails in inbox, not spam |
| E12 | Verify Post SMTP Email Log records all outgoing emails | ☐ | P1 | Developer | P35 | 5 min | Post SMTP → Email Log: test emails visible |
| E13 | Configure Post SMTP failure alert (> 5% failure → email developer) | ☐ | P1 | Developer | P32 | 5 min | Alert configured; test alert fires |
| E14 | Verify MX records unchanged from pre-migration state (email routing preserved) | ☐ | P0 | Developer | D06 | 5 min | MX records match D01 documentation |

---

### 1.16 Backups

| # | Task | Status | Priority | Owner | Dependencies | Est. Duration | Validation Method |
|---|---|---|---|---|---|---|---|
| B01 | Configure BlogVault/UpdraftPlus daily backup at 03:00 (files + database) | ☐ | P0 | Developer | P36, P37 | 10 min | Backup plugin: schedule active |
| B02 | Set retention: 30 daily + 4 weekly + 12 monthly | ☐ | P0 | Developer | B01 | 5 min | Plugin settings: retention configured |
| B03 | Configure offsite storage destination (Google Drive/Dropbox/S3) | ☐ | P0 | Developer | P38 | 10 min | Offsite destination connected; test file transfer succeeds |
| B04 | Take first full backup → verify completion | ☐ | P0 | Developer | B01, B03 | 15 min | Backup log: "Completed successfully"; offsite file present |
| B05 | Test restore from backup to staging environment | ☐ | P0 | Developer | B04 | 20 min | Staging: home page loads, admin login works, forms submit, WC checkout works |
| B06 | Configure backup failure alert → email developer | ☐ | P0 | Developer | B01 | 5 min | Alert configured; test alert fires |
| B07 | Configure database-only backup every 6 hours (7-day retention) | ☐ | P1 | Developer | B01 | 10 min | DB backup files present; 7-day rotation working |
| B08 | Configure monthly WooCommerce order CSV export → offsite (7-year retention) | ☐ | P0 | Developer | WC01 | 15 min | Monthly CSV export scheduled; stored offsite |
| B09 | Take pre-launch backup (full) — label with timestamp | ☐ | P0 | Developer | B04 | 15 min | Backup file with timestamp label in offsite storage |
| B10 | Take post-launch backup immediately after go-live verification | ☐ | P0 | Developer | All launch tasks | 15 min | Post-launch backup in offsite storage |
| B11 | Document backup restore procedure (step-by-step runbook) | ☐ | P1 | Developer | B05 | 30 min | Runbook tested: another person can follow steps to restore |

---

### 1.17 Monitoring

| # | Task | Status | Priority | Owner | Dependencies | Est. Duration | Validation Method |
|---|---|---|---|---|---|---|---|
| MON01 | Configure UptimeRobot: monitor https://helderduidelijkschoon.nl every 5 minutes | ☐ | P0 | Developer | H04, SSL01 | 10 min | UptimeRobot dashboard: site monitored; status UP |
| MON02 | Set UptimeRobot alert: < 99.9% over 24h → email developer + client | ☐ | P0 | Developer | MON01 | 5 min | Alert configured; test alert fires |
| MON03 | Configure UptimeRobot SSL expiry monitoring (< 30 days → alert) | ☐ | P1 | Developer | SSL01 | 5 min | SSL check active; expiry date tracked |
| MON04 | Configure Wordfence malware scan alert (any detection → email developer) | ☐ | P0 | Developer | P31 | 5 min | Alert configured; test alert fires |
| MON05 | Configure backup failure alert (any failure → email developer) | ☐ | P0 | Developer | B06 | 5 min | Alert configured; test alert fires |
| MON06 | Configure Post SMTP email delivery failure alert (> 5% failure → email developer) | ☐ | P1 | Developer | E13 | 5 min | Alert configured; test alert fires |
| MON07 | Configure disk usage monitoring (> 80% → email developer) | ☐ | P1 | Developer | H02 | 10 min | Hosting or server-level disk alert configured |
| MON08 | Configure GSC 404 error alert (> 10 new 404s/day → email developer) | ☐ | P1 | Developer | P17 | 5 min | GSC alert configured; test alert fires |
| MON09 | Configure WordPress auto-update notification (email on update) | ☐ | P2 | Developer | W20 | 5 min | WP admin → Settings → Email: update notification enabled |
| MON10 | Configure weekly PSI automated check (home + service + contact) | ☐ | P1 | Developer | PERF01, PERF02, PERF03 | 15 min | Weekly PSI report generated; alert if < 90 mobile |
| MON11 | Set up Google Analytics 4 custom alert: zero traffic for 24h | ☐ | P1 | Developer | A01 | 5 min | GA4 alert configured; test alert fires |

---

## Phase 2 — Launch Validation

This checklist is executed on **launch day and the 24 hours following**. Every item must pass before the site is considered successfully launched.

### 2.1 Pre-Launch (Staging)

| # | Check | Status | Method |
|---|---|---|---|
| LV-PRE-01 | All 32 pages published with final Dutch content on staging | ☐ | Manual browse each page |
| LV-PRE-02 | Zero lorem ipsum or placeholder text anywhere | ☐ | Screaming Frog custom search for "lorem", "ipsum", "placeholder" |
| LV-PRE-03 | All service pages >= 300 words | ☐ | Word count check on each service page |
| LV-PRE-04 | Phone 0164-652846 correct on all pages | ☐ | Screaming Frog custom search |
| LV-PRE-05 | Email info@helderduidelijkschoon.nl correct on all pages | ☐ | Screaming Frog custom search |
| LV-PRE-06 | All 3 forms submit successfully on staging | ☐ | Manual test each form |
| LV-PRE-07 | WC purchase flow works end-to-end on staging (test mode) | ☐ | Complete test purchase |
| LV-PRE-08 | All 7 redirect rules working on staging | ☐ | curl each old URL → 301 → correct new URL |
| LV-PRE-09 | XML Sitemap valid, zero errors | ☐ | Visit /sitemap_index.xml; W3C validator |
| LV-PRE-10 | robots.txt correct | ☐ | Visit /robots.txt |
| LV-PRE-11 | Screaming Frog crawl: 0 broken internal links | ☐ | Full crawl report |
| LV-PRE-12 | All schema types validate (Google Rich Results Test) | ☐ | Test each template |
| LV-PRE-13 | axe DevTools: zero critical + serious on all templates | ☐ | Run axe on each template |
| LV-PRE-14 | PSI Mobile >= 90 on Home, Service, Contact | ☐ | PageSpeed Insights |
| LV-PRE-15 | Cookie consent banner works: zero non-functional cookies before accept | ☐ | Fresh browser visit; DevTools → Cookies |
| LV-PRE-16 | Client written sign-off on staging acceptance | ☐ | Signed document or email confirmation |
| LV-PRE-17 | Final full backup of old live site taken + verified | ☐ | Backup file present; test restore succeeded |

### 2.2 Launch Day — Deployment

| # | Check | Status | Method |
|---|---|---|---|
| LV-DEP-01 | DNS TTL confirmed at 300s (lowered 24h ago) | ☐ | `dig helderduidelijkschoon.nl` → TTL = 300 |
| LV-DEP-02 | Deploy theme to production (Git push or manual upload) | ☐ | Production /wp-content/themes/hds/ has latest files |
| LV-DEP-03 | Run `npm run build` on production theme | ☐ | Build artifacts present in /dist/ |
| LV-DEP-04 | Import database from staging to production | ☐ | All pages, posts, products present on production |
| LV-DEP-05 | Run search-replace: staging URL → production URL | ☐ | `wp search-replace 'staging.helderduidelijkschoon.nl' 'helderduidelijkschoon.nl'` |
| LV-DEP-06 | Clear all caches: FlyingPress → Clear All | ☐ | Cache cleared |
| LV-DEP-07 | Clear Redis object cache | ☐ | Redis → Flush Cache |
| LV-DEP-08 | Purge Cloudflare cache (Purge Everything) | ☐ | Cloudflare dashboard: cache purged |
| LV-DEP-09 | Verify production homepage loads at https://helderduidelijkschoon.nl | ☐ | Browser: page loads correctly |
| LV-DEP-10 | Verify SSL: padlock icon, no mixed content warnings | ☐ | Browser: green padlock; zero console warnings |
| LV-DEP-11 | Update DNS A record to production IP (if not already) | ☐ | `dig A helderduidelijkschoon.nl` → production IP |
| LV-DEP-12 | Verify DNS propagation started (whatsmydns.net) | ☐ | Multiple global resolvers return production IP |
| LV-DEP-13 | Take post-deployment backup | ☐ | Backup log: "Completed successfully" |

### 2.3 Launch Day — Verification

| # | Check | Status | Method |
|---|---|---|---|
| LV-VER-01 | **Homepage:** loads, all content visible, navigation works, CTA buttons clickable | ☐ | Manual browse |
| LV-VER-02 | **Service pages (7):** each loads, content complete, cross-sell section present, CTA present | ☐ | Manual browse each of 7 service pages |
| LV-VER-03 | **Category landing pages (3):** Glas & Gevel, Schoonmaakdiensten, Luchtreiniging | ☐ | Manual browse each |
| LV-VER-04 | **Static pages:** Over HDS, Kwaliteit & Veiligheid, Referenties, Downloads, FAQ, Blog, 404 | ☐ | Manual browse each |
| LV-VER-05 | **Legal pages:** Privacyverklaring, Cookiebeleid, Algemene Voorwaarden, Disclaimer | ☐ | Manual browse each |
| LV-VER-06 | **Contact:** /contact/ loads, form visible, contact info block present | ☐ | Manual browse |
| LV-VER-07 | **Offerte:** /offerte-aanvragen/ loads, form visible | ☐ | Manual browse |
| LV-VER-08 | **Vacatures:** /vacatures/ loads, list of vacancies, each vacancy page shows form | ☐ | Manual browse |
| LV-VER-09 | **Navigation:** primary nav dropdowns work (desktop), hamburger menu works (mobile) | ☐ | Test on desktop + mobile |
| LV-VER-10 | **Search:** search for "glasbewassing" → correct page as #1 result | ☐ | On-site search |
| LV-VER-11 | **404 page:** visit /niet-bestaande-pagina → custom 404 page loads (not Apache default) | ☐ | Manual browse |
| LV-VER-12 | **Bedankt pages:** /bedankt/?type=contact and /bedankt/?type=offerte load correctly | ☐ | Manual browse |
| LV-VER-13 | **Forms — Contact (GF-1):** submit → redirect to /bedankt/?type=contact → email delivered to info@ within 2 min | ☐ | Full test submission |
| LV-VER-14 | **Forms — Quote (GF-2):** submit with file upload → redirect → email + attachment delivered | ☐ | Full test submission |
| LV-VER-15 | **Forms — Vacature (GF-3):** submit with CV → email notification delivered | ☐ | Full test submission |
| LV-VER-16 | **Forms — Confirmation emails:** user receives confirmation for GF-1 and GF-2 | ☐ | Check user email inbox |
| LV-VER-17 | **WooCommerce — Shop:** /winkel/ shows 14 products | ☐ | Manual browse |
| LV-VER-18 | **WooCommerce — Product page:** image, price, description, add-to-cart, tabs | ☐ | Manual check one product |
| LV-VER-19 | **WooCommerce — Cart:** add item, update quantity, remove item | ☐ | Complete cart operations |
| LV-VER-20 | **WooCommerce — Checkout:** billing fields, payment methods visible, T&C checkbox | ☐ | Manual browse checkout |
| LV-VER-21 | **WooCommerce — Purchase:** test purchase (real payment, minimum amount) → order confirmation → email to customer + info@ | ☐ | Complete purchase; verify both emails |
| LV-VER-22 | **WooCommerce — My Account:** login, order history visible | ☐ | Manual browse after purchase |
| LV-VER-23 | **WooCommerce — Guest checkout:** functional (no account required) | ☐ | Purchase as guest |
| LV-VER-24 | **Email — SPF/DKIM/DMARC:** all verified (mxtoolbox.com) | ☐ | MxToolbox: all pass |
| LV-VER-25 | **Email — Deliverability:** mail-tester.com >= 9/10 | ☐ | mail-tester.com score |
| LV-VER-26 | **Email — No spam:** test emails to Gmail + Outlook + Yahoo land in inbox | ☐ | Check each provider |
| LV-VER-27 | **Search — Relevanssi:** search returns relevant results, no errors | ☐ | Multiple test queries |
| LV-VER-28 | **Redirects — All 7:** each old URL → 301 → correct new URL | ☐ | httpstatus.io or curl each |
| LV-VER-29 | **Redirects — HTTPS:** HTTP → 301 → HTTPS with HSTS header | ☐ | securityheaders.com |
| LV-VER-30 | **Redirects — www:** www.helderduidelijkschoon.nl → 301 → https://helderduidelijkschoon.nl | ☐ | curl -I |
| LV-VER-31 | **Sitemap:** /sitemap_index.xml → 200, valid XML, submitted to GSC | ☐ | Browse sitemap; GSC → Sitemaps |
| LV-VER-32 | **Robots:** /robots.txt → 200, correct rules, Sitemap directive present | ☐ | Browse robots.txt |
| LV-VER-33 | **Schema — LocalBusiness:** present + valid | ☐ | Google Rich Results Test |
| LV-VER-34 | **Schema — Organization:** present + valid | ☐ | Google Rich Results Test |
| LV-VER-35 | **Schema — Service:** present + valid on each service page | ☐ | Google Rich Results Test |
| LV-VER-36 | **Schema — FAQPage:** present + valid on FAQ page | ☐ | Google Rich Results Test |
| LV-VER-37 | **Schema — BreadcrumbList:** present + valid on inner pages | ☐ | Google Rich Results Test |
| LV-VER-38 | **Analytics — GA4:** real-time report shows active user(s) | ☐ | GA4 Real-Time |
| LV-VER-39 | **Analytics — GTM:** snippet in page source; no console errors | ☐ | View Source + DevTools Console |
| LV-VER-40 | **Analytics — GSC:** domain verified; sitemap submitted | ☐ | GSC dashboard |
| LV-VER-41 | **Accessibility — axe DevTools:** zero critical + serious on all templates | ☐ | Run axe DevTools on each template type |
| LV-VER-42 | **Accessibility — Keyboard:** Tab through entire page; all interactive elements reachable + operable | ☐ | Keyboard-only navigation test |
| LV-VER-43 | **Accessibility — Screen reader:** NVDA test on Contact form + navigation | ☐ | NVDA test (Windows) |
| LV-VER-44 | **Accessibility — Zoom:** 200% zoom; no content loss, no horizontal scroll | ☐ | Browser zoom test |
| LV-VER-45 | **Performance — PSI Mobile:** Home >= 90 | ☐ | PageSpeed Insights |
| LV-VER-46 | **Performance — PSI Mobile:** Service page (glasbewassing) >= 90 | ☐ | PageSpeed Insights |
| LV-VER-47 | **Performance — PSI Mobile:** Contact page >= 90 | ☐ | PageSpeed Insights |
| LV-VER-48 | **Performance — PSI Desktop:** Home >= 95 | ☐ | PageSpeed Insights |
| LV-VER-49 | **Performance — LCP:** < 2.5s on all 3 tested pages | ☐ | PSI LCP metric |
| LV-VER-50 | **Performance — CLS:** < 0.1 on all 3 tested pages | ☐ | PSI CLS metric |
| LV-VER-51 | **Performance — Cache:** FlyingPress HIT on repeated visits | ☐ | `curl -I` → cache header |
| LV-VER-52 | **Performance — Cache bypass:** WC cart/checkout/account = BYPASS | ☐ | `curl -I /winkelmand/` → BYPASS |
| LV-VER-53 | **Security — XML-RPC:** returns 403 | ☐ | `curl -I /xmlrpc.php` |
| LV-VER-54 | **Security — User enumeration:** /?author=1 does not reveal username | ☐ | Browser test |
| LV-VER-55 | **Security — Custom login:** /wp-admin redirects; custom URL works | ☐ | Browser test |
| LV-VER-56 | **Security — 2FA:** prompt on admin login | ☐ | Login as admin |
| LV-VER-57 | **Security — Headers:** HSTS + security headers present (securityheaders.com A+) | ☐ | securityheaders.com |
| LV-VER-58 | **Security — Wordfence:** scan clean (zero critical/high/medium) | ☐ | Wordfence → Scan |
| LV-VER-59 | **Backups — First daily backup:** completed successfully | ☐ | Backup log |
| LV-VER-60 | **Backups — Offsite:** backup file present in Google Drive/Dropbox/S3 | ☐ | Check offsite storage |
| LV-VER-61 | **Monitoring — UptimeRobot:** site monitored, status UP | ☐ | UptimeRobot dashboard |
| LV-VER-62 | **Monitoring — Alerts:** all alerts configured and test-fired | ☐ | Check each monitoring tool |
| LV-VER-63 | **Responsive:** mobile (320px), tablet (768px), desktop (1024px), wide (1440px) — all templates | ☐ | Browser responsive mode or BrowserStack |
| LV-VER-64 | **Cookie consent:** banner appears on fresh browser; GA4 only after accept | ☐ | Fresh browser visit + DevTools → Cookies |

### 2.4 Launch Day — Client Communication

| # | Action | Status | Owner |
|---|---|---|---|
| LV-COM-01 | Notify client: site is live at https://helderduidelijkschoon.nl | ☐ | Developer |
| LV-COM-02 | Send client login credentials (admin URL, username, temp password) | ☐ | Developer |
| LV-COM-03 | Send client Beheergids (PDF) | ☐ | Developer |
| LV-COM-04 | Schedule 1-hour training session (within 7 days) | ☐ | Developer |
| LV-COM-05 | Provide emergency contact: developer phone + email | ☐ | Developer |
| LV-COM-06 | Provide hosting support contact | ☐ | Developer |
| LV-COM-07 | Notify old developer (Pi-Apps) of successful migration | ☐ | Client |
| LV-COM-08 | Update Google Business Profile website URL (if changed) | ☐ | Client |
| LV-COM-09 | Update social media profiles (website link) | ☐ | Client |

---

## Phase 3 — Hypercare (30-Day Post-Launch Monitoring)

### 3.1 Daily Monitoring (Days 1-30)

| Day | Task | Owner | Method | Expected Result |
|---|---|---|---|---|
| Daily 1-30 | Check UptimeRobot dashboard: zero downtime | Developer | UptimeRobot → Dashboard | 100% uptime reported |
| Daily 1-30 | Check GSC for crawl errors + new 404s | Developer | GSC → Pages → Not indexed | < 5 new errors/day |
| Daily 1-7 | Check Wordfence scan results | Developer | WP Admin → Wordfence → Scan | Zero critical/high/medium |
| Daily 1-7 | Check Post SMTP Email Log: all emails delivered | Developer | WP Admin → Post SMTP → Email Log | Zero failed deliveries |
| Daily 1-7 | Check Gravity Forms entries: no spam surge | Developer | WP Admin → Forms → Entries | < 10% spam rate |
| Daily 1-7 | Check WooCommerce orders: no anomalies | Developer | WP Admin → WC → Orders | Orders processing normally |
| Daily 1-30 | Check GA4 Real-Time: traffic flowing | Developer | GA4 → Reports → Real-Time | Active users visible |
| Daily 8-30 | Check Wordfence scan every 3 days | Developer | WP Admin → Wordfence → Scan | Zero critical/high/medium |
| Daily 8-30 | Check Post SMTP every 3 days | Developer | WP Admin → Post SMTP → Email Log | Zero failed deliveries |

### 3.2 Weekly Monitoring

| Week | Task | Owner | Method | Expected Result |
|---|---|---|---|---|
| Week 1 | Run full Screaming Frog crawl | Developer | Screaming Frog → full crawl | Zero 404 errors; zero broken links |
| Week 1 | Run PSI Mobile on Home, Service, Contact | Developer | PageSpeed Insights | Score >= 90 all pages |
| Week 1 | Run axe DevTools on all template types | Developer | axe DevTools browser extension | Zero critical + serious |
| Week 1 | Test all 3 forms (GF-1, GF-2, GF-3) — live submission | Developer | Manual form test on production | All deliver email within 2 minutes |
| Week 1 | Test WC purchase flow — test purchase with real payment | Developer | Complete purchase on production | Order confirmation + emails delivered |
| Week 1 | Verify backup: latest backup completed + offsite file present | Developer | Backup plugin + offsite storage | Backup successful; file present |
| Week 1 | Run mail-tester.com check | Developer | mail-tester.com | Score >= 9/10 |
| Week 1 | Check GBP + social media links correct | Developer | Manual check | All links working |
| Week 1 | Check all email notifications working (forms, WC, backup) | Developer | Review email inboxes | All notification types received |
| Week 2 | Compare GSC indexed pages vs baseline (old site) | Developer | GSC → Pages → Indexed | Indexed count increasing toward baseline |
| Week 2 | Compare GSC search impressions vs baseline | Developer | GSC → Performance → Compare 2 periods | Impressions trending toward baseline |
| Week 2 | Submit all new URLs for indexing (GSC URL Inspection) | Developer | GSC → URL Inspection → Request Indexing | URLs queued for indexing |
| Week 2 | Run PSI Mobile on Home, Service, Contact | Developer | PageSpeed Insights | Score >= 90 all pages |
| Week 2 | Verify backups: latest 7 daily + 1 weekly present offsite | Developer | Offsite storage | All expected backups present |
| Week 2 | Test backup restore to staging (monthly drill) | Developer | Restore latest backup to staging | Staging: home page loads, admin works |
| Week 3 | Run PSI Mobile on Home, Service, Contact | Developer | PageSpeed Insights | Score >= 90 all pages |
| Week 3 | Run full Screaming Frog crawl | Developer | Screaming Frog | Zero new errors vs week 1 baseline |
| Week 3 | Review GA4 conversion events (form submissions, phone clicks, email clicks) | Developer | GA4 → Engagement → Events | Conversion events firing correctly |
| Week 3 | Review GA4 traffic sources + user behavior | Developer | GA4 → Reports → Acquisition | Organic traffic growing; no anomalies |
| Week 3 | Verify backups: all daily + weekly present offsite | Developer | Offsite storage | All expected backups present |
| Week 4 | Full SEO audit: compare all metrics vs pre-launch baseline | Developer | GSC, GA4, Screaming Frog | Rankings stabilizing; traffic recovering |
| Week 4 | Run PSI Mobile on Home, Service, Contact | Developer | PageSpeed Insights | Score >= 90 all pages |
| Week 4 | Run security scan (Wordfence) + review logs | Developer | Wordfence + hosting logs | Clean scan; no suspicious activity |
| Week 4 | Review all form submissions (trends, spam rate, conversion) | Developer | Gravity Forms → Entries | Healthy form activity |
| Week 4 | Review WC orders: revenue, conversion rate, payment methods | Developer | WC → Reports | Normal order flow |
| Week 4 | Prepare Client 30-Day Report (traffic, conversions, rankings, uptime) | Developer | All monitoring data | Report ready for client |
| Week 4 | Client check-in meeting: review 30-day report, address questions | Developer | Scheduled meeting | Client satisfied |
| Week 4 | Verify backups: all daily + weekly + first monthly present offsite | Developer | Offsite storage | All expected backups present |
| Week 4 | Test complete disaster recovery: restore from backup to new server | Developer | Full DR test | Site fully restored and operational |

### 3.3 Escalation Protocol

| Severity | Definition | Response Time | Action |
|---|---|---|---|
| **P0 — Critical** | Site down, checkout broken, forms not delivering, security breach | < 1 hour | Immediate investigation. Rollback if > 2h to fix. Notify client. |
| **P1 — High** | Page not loading, email delay > 5 min, significant performance degradation | < 4 hours | Investigate and fix. Notify client if > 4h. |
| **P2 — Medium** | Minor display issue, cache miss, non-critical plugin warning | < 24 hours | Fix during business hours. |
| **P3 — Low** | Cosmetic issue, typos, minor SEO warning | < 7 days | Queue for next maintenance window. |

### 3.4 Rollback Decision Matrix

If critical issue (P0) and fix is not obvious within 1 hour:

1. **Restore from pre-launch backup** (or latest clean backup)
2. **Revert DNS** to old site (if old hosting still active)
3. **Communicate:** Email client — issue, action taken, ETA
4. **Document:** Issue + resolution in incident log
5. **Fix on staging → re-test → re-launch**

---

## Phase 4 — Client Acceptance

### 4.1 Client Acceptance Checklist

**Project:** HDS Onderhoudsdiensten — Ground-Up Rebuild
**Domain:** helderduidelijkschoon.nl
**Version:** 1.0.0

---

**Content Acceptance**

| # | Item | Client Sign-Off |
|---|---|---|
| CA-01 | All 32 pages published with final Dutch content | ☐ |
| CA-02 | All service pages >= 300 words | ☐ |
| CA-03 | All category landings >= 500 words | ☐ |
| CA-04 | Zero lorem ipsum or placeholder text | ☐ |
| CA-05 | Phone 0164-652846 correct on all pages | ☐ |
| CA-06 | Email info@helderduidelijkschoon.nl correct on all pages | ☐ |
| CA-07 | Address, KVK, BTW correct in footer + schema (if provided) | ☐ |
| CA-08 | Operating hours correct (if provided) | ☐ |
| CA-09 | All 14 Airfixr products present with correct data | ☐ |
| CA-10 | Legal pages present: Privacyverklaring, Cookiebeleid, Algemene Voorwaarden, Disclaimer | ☐ |

**Design Acceptance**

| # | Item | Client Sign-Off |
|---|---|---|
| CA-11 | Logo displayed correctly on all pages | ☐ |
| CA-12 | Color scheme matches approved design | ☐ |
| CA-13 | Typography matches approved design | ☐ |
| CA-14 | Responsive on mobile (320px), tablet (768px), desktop (1024px), wide (1440px) | ☐ |
| CA-15 | No broken images anywhere | ☐ |
| CA-16 | Consistent layout across all page types | ☐ |

**Functional Acceptance**

| # | Item | Client Sign-Off |
|---|---|---|
| CA-17 | Navigation works on all devices (desktop dropdown, mobile hamburger) | ☐ |
| CA-18 | Contact form (GF-1): submits → redirect to /bedankt/ → email delivered to info@ | ☐ |
| CA-19 | Quote form (GF-2): submits with file upload → redirect → email with file link | ☐ |
| CA-20 | Vacature form (GF-3): submits with CV → email notification | ☐ |
| CA-21 | Confirmation emails received by user for GF-1 and GF-2 | ☐ |
| CA-22 | WooCommerce: browse → cart → checkout → payment (iDEAL) → confirmation → email | ☐ |
| CA-23 | Search: returns relevant results | ☐ |
| CA-24 | 404 page: custom page shown (not Apache default) | ☐ |
| CA-25 | Cookie consent banner: appears on fresh browser; functional after accept | ☐ |
| CA-26 | All external links open correctly (social media, GBP, OSB) | ☐ |

**Performance Acceptance**

| # | Item | Client Sign-Off |
|---|---|---|
| CA-27 | Pages load in < 3 seconds (subjective user experience) | ☐ |
| CA-28 | Mobile experience acceptable (client tests on own phone) | ☐ |
| CA-29 | No visible layout shifts during page load | ☐ |

**Client Sign-Off**

| Field | Value |
|---|---|
| Client Name | ______________________ |
| Date | ______________________ |
| Signature | ______________________ |

---

### 4.2 Final QA Checklist

This is the developer's internal QA gate. All items must pass before the Client Acceptance Checklist is presented.

| # | Check | Status | Notes |
|---|---|---|---|
| QA-01 | All Sprint 7 Epic 4 validation items passed on production | ☐ | See §2.3 — all 64 items |
| QA-02 | Screaming Frog crawl: zero 404, zero broken links, zero missing H1 | ☐ | |
| QA-03 | Screaming Frog crawl: zero empty title tags, zero duplicate title tags | ☐ | |
| QA-04 | Screaming Frog crawl: zero empty meta descriptions, zero duplicate meta descriptions | ☐ | |
| QA-05 | Screaming Frog crawl: zero missing image alt text | ☐ | |
| QA-06 | Google Rich Results Test: all schema types validate | ☐ | |
| QA-07 | PSI Mobile >= 90 on Home, Service (glasbewassing), Contact | ☐ | |
| QA-08 | PSI Desktop >= 95 on Home | ☐ | |
| QA-09 | axe DevTools: zero critical + serious on all template types | ☐ | |
| QA-10 | WAVE: zero errors on all template types | ☐ | |
| QA-11 | Keyboard navigation: all interactive elements reachable + operable | ☐ | |
| QA-12 | NVDA screen reader: forms + navigation usable | ☐ | |
| QA-13 | 200% zoom: no content loss, no horizontal scroll | ☐ | |
| QA-14 | Color contrast: WCAG AA on all text/UI elements | ☐ | |
| QA-15 | securityheaders.com: grade A+ | ☐ | |
| QA-16 | SSL Labs: grade A+ | ☐ | |
| QA-17 | MxToolbox: SPF + DKIM + DMARC all pass | ☐ | |
| QA-18 | mail-tester.com: score >= 9/10 | ☐ | |
| QA-19 | Wordfence scan: zero critical/high/medium | ☐ | |
| QA-20 | Backup: completed + verified restore | ☐ | |
| QA-21 | All monitoring alerts configured + test-fired | ☐ | |
| QA-22 | Cookie consent: zero non-functional cookies before accept | ☐ | |
| QA-23 | GA4 real-time: traffic visible | ☐ | |
| QA-24 | GTM: snippet present; all conversion events firing | ☐ | |
| QA-25 | GSC: domain verified; sitemap submitted | ☐ | |
| QA-26 | Responsive: all breakpoints tested on all templates | ☐ | |
| QA-27 | Cross-browser tested: Chrome, Firefox, Safari, Edge (latest versions) | ☐ | |
| QA-28 | No console errors on any page (DevTools Console) | ☐ | |
| QA-29 | All forms tested: submission, validation, email delivery, file uploads | ☐ | |
| QA-30 | WC purchase flow tested: iDEAL, BACS, guest checkout, cart persistence | ☐ | |

**QA Sign-Off:**

| Field | Value |
|---|---|
| QA Engineer | ______________________ |
| Date | ______________________ |

---

### 4.3 Operational Handover Checklist

This checklist ensures the client (or their operations team) can independently operate the site.

| # | Item | Delivered? | Client Confirms? |
|---|---|---|---|
| OH-01 | Beheergids (Administrator Manual) — printed + PDF, Dutch, with screenshots | ☐ | ☐ |
| OH-02 | Editor Manual (Content Guide) — PDF, Dutch | ☐ | ☐ |
| OH-03 | Login credentials: custom admin URL, username, temporary password | ☐ | ☐ |
| OH-04 | 2FA setup instructions (Wordfence → Login Security) | ☐ | ☐ |
| OH-05 | Hosting provider name + support contact (phone + email) | ☐ | ☐ |
| OH-06 | Domain registrar name + support contact | ☐ | ☐ |
| OH-07 | SMTP provider name + support contact | ☐ | ☐ |
| OH-08 | Backup: how to take manual backup, where backups are stored | ☐ | ☐ |
| OH-09 | Backup: how to restore from backup (step-by-step with screenshots) | ☐ | ☐ |
| OH-10 | Recovery: what to do if site is down (runbook) | ☐ | ☐ |
| OH-11 | Recovery: what to do if email delivery fails | ☐ | ☐ |
| OH-12 | Recovery: what to do if malware is detected | ☐ | ☐ |
| OH-13 | Update procedure: test on staging before production (step-by-step) | ☐ | ☐ |
| OH-14 | Update procedure: how to update plugins/themes/WordPress safely | ☐ | ☐ |
| OH-15 | Content: how to edit pages (Block Editor basics) | ☐ | ☐ |
| OH-16 | Content: how to create new service pages using Service template | ☐ | ☐ |
| OH-17 | Content: how to add testimonials (CPT) | ☐ | ☐ |
| OH-18 | Content: how to manage vacancies (CPT) | ☐ | ☐ |
| OH-19 | Content: how to upload + optimize images | ☐ | ☐ |
| OH-20 | Content: SEO checklist per page (meta title, description, alt text) | ☐ | ☐ |
| OH-21 | WooCommerce: how to view orders, update status, process refunds | ☐ | ☐ |
| OH-22 | WooCommerce: how to update product stock/prices | ☐ | ☐ |
| OH-23 | Forms: how to view GF entries, export data, mark as read | ☐ | ☐ |
| OH-24 | Security: how to add/remove users, change passwords | ☐ | ☐ |
| OH-25 | Security: how to review Wordfence scan results | ☐ | ☐ |
| OH-26 | Compliance: how to view Complianz consent log | ☐ | ☐ |
| OH-27 | Compliance: how to update legal pages (privacyverklaring, AV) | ☐ | ☐ |
| OH-28 | Monitoring: how to check UptimeRobot dashboard | ☐ | ☐ |
| OH-29 | Developer emergency contact (phone + email) | ☐ | ☐ |
| OH-30 | 1-hour training session completed | ☐ | ☐ |
| OH-31 | Training recording/screen-capture provided (if done remotely) | ☐ | ☐ |
| OH-32 | Client confirms ability to perform all essential tasks independently | ☐ | ☐ |

**Handover Sign-Off:**

| Field | Value |
|---|---|
| Developer | ______________________ |
| Client | ______________________ |
| Date | ______________________ |

---

### 4.4 Warranty & Support Plan

**Warranty Period:** 30 days from launch date.

**Covered (No Additional Cost):**
- Production-critical defects (site down, checkout broken, forms not delivering email, security breach)
- Bug fixes for issues that existed at launch and were not identified during QA
- Configuration corrections for launch-critical settings (SMTP, DNS, SSL, cache)
- Emergency restore from backup if needed due to covered defect

**Not Covered (Separate Scope/Quote):**
- New feature requests
- Content changes or additions beyond launch scope
- Design changes or redesigns
- Plugin upgrades to new major versions
- Third-party service configuration changes
- Training beyond the included 1-hour session
- SEO optimization beyond launch configuration
- Performance optimization beyond launch targets

**Support Hours:**
- Business hours: Monday-Friday, 09:00-17:00 CET
- Emergency P0 issues: 24/7 during warranty period
- Response time: P0 < 1 hour, P1 < 4 hours, P2 < 24 hours, P3 < 7 days

**Post-Warranty Options (Recommended):**

| Option | Scope | Estimated Monthly |
|---|---|---|
| **Basic Maintenance** | WP core + plugin minor updates, daily backup monitoring, monthly Wordfence review, monthly PSI check | € 150-250 |
| **Standard Maintenance** | Basic + content updates (up to 2 hours), SEO monitoring, monthly GA4 report, priority support | € 350-500 |
| **Premium Maintenance** | Standard + content writing (1 blog post/month), SEO optimization, CRO recommendations, quarterly performance review, unlimited content edits | € 500-750 |

**Client Decision:**

| Field | Value |
|---|---|
| Post-warranty option selected | ☐ Basic ☐ Standard ☐ Premium ☐ None (ad-hoc) |
| Client Name | ______________________ |
| Date | ______________________ |

---

### 4.5 Maintenance Schedule

**Weekly Tasks (Client or Developer):**
| Task | Owner | Duration |
|---|---|---|
| Update WordPress core (minor), plugins, theme | Client | 15 min |
| Review Wordfence scan results | Client | 5 min |
| Check Gravity Forms entries (no spam surge) | Client | 5 min |
| Check WooCommerce orders (no anomalies) | Client | 5 min |
| Verify UptimeRobot: zero downtime | Client/Developer | 2 min |

**Monthly Tasks (Client or Developer):**
| Task | Owner | Duration |
|---|---|---|
| Test backup restore to staging | Developer | 20 min |
| Audit admin accounts (remove unused) | Client | 5 min |
| Review and clean post revisions (> 30 days) | Client | 10 min |
| Review GA4 traffic + conversions report | Developer | 15 min |
| Run PSI Mobile on Home, Service, Contact | Developer | 10 min |
| Run Screaming Frog crawl (check for new errors) | Developer | 15 min |
| Export WooCommerce orders CSV (financial archive) | Client | 10 min |
| Review Post SMTP email log | Developer | 5 min |

**Quarterly Tasks (Developer):**
| Task | Owner | Duration |
|---|---|---|
| Change all admin passwords | Client | 10 min |
| Review and update legal pages | Client | 30 min |
| Full performance re-test (PSI, WebPageTest, GTmetrix) | Developer | 30 min |
| Full accessibility re-audit (axe, WAVE, keyboard, screen reader) | Developer | 30 min |
| Security audit (Wordfence scan + server logs review) | Developer | 30 min |
| Plugin compatibility check (major version updates) | Developer | 30 min |
| Test full disaster recovery (restore to clean server) | Developer | 1 hour |

**Annual Tasks (Client + Developer):**
| Task | Owner | Duration |
|---|---|---|
| External security audit (third-party) | Client + Auditor | 2 hours |
| SSL certificate renewal verification | Developer | 5 min |
| Domain renewal verification | Client | 5 min |
| Hosting plan review (adequate resources?) | Developer | 15 min |
| Content audit (pages still accurate? any to add/remove?) | Client | 1 hour |
| SEO audit (rankings, traffic, backlinks vs previous year) | Developer | 2 hours |
| GDPR/AVG compliance review | Client + Lawyer | 1 hour |

---

*End of Sprint 8 — Operational Execution & Project Closure*

**Document Status:** READY FOR EXECUTION
**Next:** Project_Closure_Report.md
