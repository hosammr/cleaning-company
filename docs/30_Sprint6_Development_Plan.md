# HDS Onderhoudsdiensten — Sprint 6 Development Plan

**Document ID:** SP6-001 | **Version:** 1.0.0 | **Date:** 2026-07-25
**Status:** Implementation-Ready | **Decision:** GO (92/100 Readiness Score)
**Predecessor:** Sprint 5.6 — Readiness Closure | **Successor:** Sprint 7 — Launch & Handover

---

## Table of Contents

1. [Development Roadmap](#1-development-roadmap)
2. [Module Implementation Order](#2-module-implementation-order)
3. [Dependency Graph](#3-dependency-graph)
4. [Critical Path](#4-critical-path)
5. [Repository Workflow](#5-repository-workflow)
6. [Branch Strategy](#6-branch-strategy)
7. [Merge Strategy](#7-merge-strategy)
8. [Definition of Ready](#8-definition-of-ready)
9. [Definition of Done](#9-definition-of-done)
10. [Coding Standards Enforcement](#10-coding-standards-enforcement)
11. [Quality Gates](#11-quality-gates)
12. [CI/CD Gates](#12-cicd-gates)
13. [Testing Sequence](#13-testing-sequence)
14. [Feature Implementation Sequence](#14-feature-implementation-sequence)
15. [Risk Matrix](#15-risk-matrix)
16. [Rollback Strategy](#16-rollback-strategy)
17. [Sprint Checkpoints](#17-sprint-checkpoints)
18. [Acceptance Criteria for Every Module](#18-acceptance-criteria-for-every-module)
19. [Deliverables of Every Epic](#19-deliverables-of-every-epic)
20. [Success Metrics](#20-success-metrics)

---

## 1. Development Roadmap

### 1.1 Sprint 6 Scope

Sprint 6 is the **primary content development and integration sprint**. It builds on the completed theme foundation (Sprint 5) and delivers all 32 pages, 3 forms, WooCommerce configuration, SEO metadata, compliance measures, accessibility validation, and performance optimization through to staging acceptance.

### 1.2 Sprint Timeline

| Metric | Value |
|---|---|
| **Sprint Duration** | 4 calendar weeks (20 working days) |
| **Start Date** | Week 1 Day 1 — immediately after Sprint 5.6 closure |
| **End Date** | Week 4 Day 5 — staging acceptance complete |
| **Total Epics** | 8 |
| **Total Stories** | 54 (Sprint 2–8 stories from backlog, consolidated) |
| **Total Points** | 326 |
| **Team Composition** | 2 developers + 1 content writer/QA |

### 1.3 Week-by-Week Roadmap

```
WEEK 1: Core Pages & Conversion
  Day 1-2:  Service Page Template finalization + Home Page content
  Day 3-4:  All 7 service pages (P02-P08) — content population
  Day 4-5:  Contact Page + Contact Form (GF-1), Offerte Page + Quote Form (GF-2)
  Day 5:    Bedankt page (P32), 404 page validation, cross-links audit
  Gate:     All 12 core pages return HTTP 200 with 300+ words Dutch

WEEK 2: Supporting Pages & WooCommerce
  Day 1-2:  About pages (P11, P12), Category landings (P09, P10)
  Day 2-3:  Referenties (P13), Vacatures (P14), Downloads (P15)
  Day 3-4:  Legal pages (P19-P22) — content draft
  Day 4-5:  WooCommerce: settings, product import, payment, shipping, emails
  Day 5:    FAQ page (P18), Luchtreiniging landing (P23), Shop intro (P24)
  Gate:     All 32 pages exist. WooCommerce purchase flow tested end-to-end.

WEEK 3: SEO, Compliance & Hardening
  Day 1-2:  Rank Math Pro configuration, meta titles/descriptions for all 32 pages
  Day 2-3:  Structured data: LocalBusiness, Service, FAQPage, BreadcrumbList
  Day 3-4:  301 redirects, XML sitemap, robots.txt, GSC verification
  Day 4:    GA4 + GTM + conversion events + consent mode v2
  Day 5:    Complianz cookie consent, Wordfence security, 2FA, XML-RPC disable
  Gate:     All SEO metadata unique. All schema validated. Cookie consent active.

WEEK 4: Testing, QA & Staging Acceptance
  Day 1:    Full functional QA — all pages, forms, links, WC flow
  Day 2:    Performance testing (PSI, WebPageTest, GTmetrix)
  Day 3:    Accessibility audit (axe, WAVE, Lighthouse, keyboard, screen reader)
  Day 4:    Cross-browser + mobile testing, fix all issues
  Day 5:    Client review on staging, staging acceptance sign-off
  Gate:     ALL 25 launch readiness criteria met. Client sign-off obtained.
```

### 1.4 Post-Sprint 6: Sprint 7 (Launch & Handover)

Sprint 7 is a separate 1-week sprint that handles:
- Production deployment
- Post-launch verification (Section J3 of MPS-001)
- 301 redirect validation on production
- Sitemap submission to GSC + Bing
- Client handover + 1-hour training + Beheergids delivery
- 30-day monitoring kickoff

**Sprint 7 is NOT in scope of this Sprint 6 plan.** Sprint 6 ends at staging acceptance.

---

## 2. Module Implementation Order

### 2.1 Implementation Phases

The modules are organized into 4 implementation phases, each with a clear gate. Modules within a phase can run in parallel. Phases execute sequentially.

```
PHASE 1: Page Templates & Content Population (Week 1-2)
  → Gate: All 32 pages return HTTP 200

PHASE 2: Forms, eCommerce, Navigation (Week 1-2)
  → Gate: Contact form submits. WC purchase flow completes.

PHASE 3: SEO, Schema, Analytics, Redirects (Week 3)
  → Gate: All meta unique. All schema validated. GA4 active.

PHASE 4: Compliance, Security, Performance, QA (Week 3-4)
  → Gate: All 25 launch readiness criteria met.
```

### 2.2 Detailed Module Implementation Order

| Order | Module ID | Module Name | Type | Phase | Depends On | Est. Hours |
|---|---|---|---|---|---|---|
| 1 | M-TPL-SERVICE | Service Page Template finalization | Template | 1 | Foundation (SP5) | 4 |
| 2 | M-PAGE-HOME | Home Page (P01) — content population | Content | 1 | M-TPL-SERVICE | 4 |
| 3 | M-PAGE-GLAS | Glasbewassing (P02) | Content | 1 | M-TPL-SERVICE | 2 |
| 4 | M-PAGE-GEVEL | Gevelreiniging (P03) | Content | 1 | M-TPL-SERVICE | 2 |
| 5 | M-PAGE-REGULIER | Reguliere Schoonmaak (P04) | Content | 1 | M-TPL-SERVICE | 3 |
| 6 | M-PAGE-VLOER | Vloeronderhoud (P05) | Content | 1 | M-TPL-SERVICE | 2 |
| 7 | M-PAGE-VVE | VVE Service (P06) | Content | 1 | M-TPL-SERVICE | 2 |
| 8 | M-PAGE-OPLEVERING | Oplevering Schoonmaak (P07) | Content | 1 | M-TPL-SERVICE | 2 |
| 9 | M-PAGE-INDUSTRIEEL | Industriele Schoonmaak (P08) | Content | 1 | M-TPL-SERVICE | 2 |
| 10 | M-TPL-CONTACT | Contact Page Template finalization | Template | 1 | Foundation | 2 |
| 11 | M-FORM-CONTACT | Gravity Forms Contact Form (GF-1) | Form | 2 | SMTP (SP5), M-TPL-CONTACT | 4 |
| 12 | M-PAGE-CONTACT | Contact Page (P16) — with form | Content | 2 | M-FORM-CONTACT | 2 |
| 13 | M-FORM-QUOTE | Gravity Forms Quote Form (GF-2) | Form | 2 | M-FORM-CONTACT | 3 |
| 14 | M-PAGE-QUOTE | Offerte Aanvragen (P17) | Content | 2 | M-FORM-QUOTE | 2 |
| 15 | M-PAGE-BEDANKT | Bedankt Page (P32) | Content | 2 | M-FORM-CONTACT | 1 |
| 16 | M-TPL-ABOUT | About Page Template finalization | Template | 1 | Foundation | 2 |
| 17 | M-PAGE-OVERHDS | Over HDS (P11) | Content | 1 | M-TPL-ABOUT | 3 |
| 18 | M-PAGE-KWALITEIT | Kwaliteit & Veiligheid (P12) | Content | 1 | M-TPL-ABOUT | 2 |
| 19 | M-TPL-LANDING | Category Landing Template finalization | Template | 1 | Foundation | 2 |
| 20 | M-PAGE-GLASGEVEL | Glas & Gevel Landing (P09) | Content | 1 | M-TPL-LANDING | 2 |
| 21 | M-PAGE-SCHOONMAAK | Schoonmaakdiensten Landing (P10) | Content | 1 | M-TPL-LANDING | 2 |
| 22 | M-CPT-TESTIMONIAL | Testimonial CPT + queries | Backend | 2 | Foundation | 2 |
| 23 | M-PAGE-REFERENTIES | Referenties (P13) | Content | 2 | M-CPT-TESTIMONIAL | 2 |
| 24 | M-CPT-VACANCY | Vacancy CPT + JobPosting block | Backend | 2 | Foundation | 3 |
| 25 | M-FORM-VACATURE | Vacature Application Form (GF-3) | Form | 2 | M-CPT-VACANCY | 2 |
| 26 | M-PAGE-VACATURES | Vacatures (P14) — rebuild from images | Content | 2 | M-CPT-VACANCY, M-FORM-VACATURE | 3 |
| 27 | M-PAGE-DOWNLOADS | Downloads (P15) — PDF migration | Content | 2 | Legacy domain access | 3 |
| 28 | M-TPL-LEGAL | Legal Page Template finalization | Template | 1 | Foundation | 1 |
| 29 | M-PAGE-PRIVACY | Privacyverklaring (P19) | Content | 2 | M-TPL-LEGAL | 2 |
| 30 | M-PAGE-COOKIE | Cookiebeleid (P20) — shell | Content | 2 | M-TPL-LEGAL | 1 |
| 31 | M-PAGE-VOORWAARDEN | Algemene Voorwaarden (P21) | Content | 2 | M-TPL-LEGAL | 2 |
| 32 | M-PAGE-DISCLAIMER | Disclaimer (P22) | Content | 2 | M-TPL-LEGAL | 1 |
| 33 | M-PAGE-FAQ | Veelgestelde Vragen (P18) | Content | 2 | Foundation | 2 |
| 34 | M-WC-CONFIG | WooCommerce Core Settings | eCommerce | 2 | Foundation | 2 |
| 35 | M-WC-IMPORT | WooCommerce Product Import (14 SKUs) | eCommerce | 2 | M-WC-CONFIG | 2 |
| 36 | M-WC-PAYMENT | Mollie Payment Gateway | eCommerce | 2 | M-WC-CONFIG | 2 |
| 37 | M-WC-SHIPPING | Shipping Zones & Classes | eCommerce | 2 | M-WC-CONFIG | 1 |
| 38 | M-WC-EMAILS | WooCommerce Email Notifications | eCommerce | 2 | SMTP (SP5), M-WC-CONFIG | 2 |
| 39 | M-WC-FLOW | WC Purchase Flow E2E Test | eCommerce | 2 | M-WC-IMPORT..M-WC-EMAILS | 2 |
| 40 | M-PAGE-LUCHT | Luchtreiniging Landing (P23) | Content | 2 | M-WC-IMPORT | 2 |
| 41 | M-PAGE-WINKEL | Winkel intro text (P24) | Content | 2 | M-WC-CONFIG | 1 |
| 42 | M-NAV-MENUS | Navigation Menus (primary + footer) | Navigation | 2 | All page modules | 3 |
| 43 | M-SEO-CONFIG | Rank Math Pro Configuration | SEO | 3 | All page modules | 2 |
| 44 | M-SEO-META | Meta Titles & Descriptions (32 pages) | SEO | 3 | M-SEO-CONFIG | 4 |
| 45 | M-SCHEMA-LOCAL | LocalBusiness JSON-LD Schema | SEO | 3 | All page modules | 2 |
| 46 | M-SCHEMA-SERVICE | Service Schema (P02-P08) | SEO | 3 | Service pages | 1 |
| 47 | M-SCHEMA-FAQ | FAQPage Schema | SEO | 3 | M-PAGE-FAQ | 1 |
| 48 | M-SCHEMA-BREADCRUMB | BreadcrumbList Schema | SEO | 3 | All inner pages | 1 |
| 49 | M-REDIRECTS | 301 Redirect Configuration | SEO | 3 | All page URLs known | 2 |
| 50 | M-SITEMAP | XML Sitemap Generation & Validation | SEO | 3 | All pages published | 1 |
| 51 | M-ROBOTS | robots.txt Configuration | SEO | 3 | M-SITEMAP | 1 |
| 52 | M-GA4-GTM | GA4 + GTM + Consent Mode v2 | Analytics | 3 | All pages + forms | 3 |
| 53 | M-CONV-EVENTS | Conversion Event Tracking | Analytics | 3 | M-FORM-CONTACT, M-FORM-QUOTE, M-WC-FLOW | 2 |
| 54 | M-GSC | Google Search Console Verification | SEO | 3 | Domain verified | 1 |
| 55 | M-COMPLIANZ | Complianz Cookie Consent | Compliance | 4 | M-PAGE-PRIVACY, M-PAGE-COOKIE | 2 |
| 56 | M-GDPR-FORMS | GDPR Form Consent Verification | Compliance | 4 | All forms | 1 |
| 57 | M-WORDFENCE | Wordfence Security Configuration | Security | 4 | Foundation | 3 |
| 58 | M-SECURITY-HARDEN | XML-RPC, Custom Login, 2FA Verify | Security | 4 | Foundation | 2 |
| 59 | M-UPTIME | UptimeRobot Monitoring | Monitoring | 4 | Staging URL | 1 |
| 60 | M-PERF-TEST | Performance Testing (PSI/WebPageTest) | QA | 4 | All pages built | 4 |
| 61 | M-PERF-FIX | Performance Remediation | QA | 4 | M-PERF-TEST | 3 |
| 62 | M-A11Y-AUDIT | Accessibility Audit (axe/WAVE/Lighthouse) | QA | 4 | All pages built | 3 |
| 63 | M-A11Y-FIX | Accessibility Remediation | QA | 4 | M-A11Y-AUDIT | 3 |
| 64 | M-CROSS-BROWSER | Cross-Browser Testing | QA | 4 | All pages built | 2 |
| 65 | M-MOBILE-TEST | Mobile/Tablet Testing (real devices) | QA | 4 | All pages built | 2 |
| 66 | M-LINK-AUDIT | Internal Link Audit (Screaming Frog) | QA | 4 | M-NAV-MENUS | 2 |
| 67 | M-IMAGE-OPT | Image Optimization (WebP, alt text) | QA | 4 | All media in place | 2 |
| 68 | M-CLIENT-REVIEW | Client Review & Staging Acceptance | Governance | 4 | All modules | 2 |

### 2.3 Module Categories

| Category | Module Count | Modules |
|---|---|---|
| Templates | 5 | M-TPL-SERVICE, M-TPL-CONTACT, M-TPL-ABOUT, M-TPL-LANDING, M-TPL-LEGAL |
| Content Pages | 22 | M-PAGE-* (all page content population modules) |
| Forms | 3 | M-FORM-CONTACT, M-FORM-QUOTE, M-FORM-VACATURE |
| Backend/CPT | 2 | M-CPT-TESTIMONIAL, M-CPT-VACANCY |
| eCommerce | 7 | M-WC-CONFIG, M-WC-IMPORT, M-WC-PAYMENT, M-WC-SHIPPING, M-WC-EMAILS, M-WC-FLOW, M-PAGE-LUCHT, M-PAGE-WINKEL |
| Navigation | 1 | M-NAV-MENUS |
| SEO | 9 | M-SEO-CONFIG, M-SEO-META, M-SCHEMA-*, M-REDIRECTS, M-SITEMAP, M-ROBOTS, M-GSC |
| Analytics | 2 | M-GA4-GTM, M-CONV-EVENTS |
| Compliance | 2 | M-COMPLIANZ, M-GDPR-FORMS |
| Security | 2 | M-WORDFENCE, M-SECURITY-HARDEN |
| Monitoring | 1 | M-UPTIME |
| QA | 9 | M-PERF-TEST, M-PERF-FIX, M-A11Y-AUDIT, M-A11Y-FIX, M-CROSS-BROWSER, M-MOBILE-TEST, M-LINK-AUDIT, M-IMAGE-OPT, M-CLIENT-REVIEW |

---

## 3. Dependency Graph

### 3.1 Module Dependency Graph

```
Foundation (Sprint 5 — COMPLETE)
  |
  ├──> M-TPL-SERVICE ──> M-PAGE-HOME
  │    ├──> M-PAGE-GLAS
  │    ├──> M-PAGE-GEVEL
  │    ├──> M-PAGE-REGULIER   ★ CRITICAL
  │    ├──> M-PAGE-VLOER
  │    ├──> M-PAGE-VVE
  │    ├──> M-PAGE-OPLEVERING
  │    └──> M-PAGE-INDUSTRIEEL
  │
  ├──> M-TPL-CONTACT ──> M-FORM-CONTACT ★ CRITICAL
  │    ├──> M-PAGE-CONTACT
  │    ├──> M-FORM-QUOTE ──> M-PAGE-QUOTE
  │    └──> M-PAGE-BEDANKT
  │
  ├──> M-TPL-ABOUT
  │    ├──> M-PAGE-OVERHDS
  │    └──> M-PAGE-KWALITEIT
  │
  ├──> M-TPL-LANDING
  │    ├──> M-PAGE-GLASGEVEL
  │    └──> M-PAGE-SCHOONMAAK
  │
  ├──> M-TPL-LEGAL
  │    ├──> M-PAGE-PRIVACY
  │    ├──> M-PAGE-COOKIE
  │    ├──> M-PAGE-VOORWAARDEN
  │    └──> M-PAGE-DISCLAIMER
  │
  ├──> M-CPT-TESTIMONIAL ──> M-PAGE-REFERENTIES
  ├──> M-CPT-VACANCY
  │    ├──> M-FORM-VACATURE
  │    └──> M-PAGE-VACATURES
  │
  ├──> M-PAGE-DOWNLOADS
  ├──> M-PAGE-FAQ
  │
  ├──> M-WC-CONFIG
  │    ├──> M-WC-IMPORT
  │    ├──> M-WC-PAYMENT
  │    ├──> M-WC-SHIPPING
  │    ├──> M-WC-EMAILS
  │    └──> M-WC-FLOW ──────────────┐
  │    ├──> M-PAGE-LUCHT            │
  │    └──> M-PAGE-WINKEL           │
  │                                  │
  ├──> M-NAV-MENUS ──────────────┐  │
  │                               │  │
  │   [ALL PAGES COMPLETE]        │  │
  │       │                       │  │
  ├───────┴───────────────────────┤  │
  │                               │  │
  ├──> M-SEO-CONFIG               │  │
  │    ├──> M-SEO-META            │  │
  │    ├──> M-SCHEMA-LOCAL        │  │
  │    ├──> M-SCHEMA-SERVICE      │  │
  │    ├──> M-SCHEMA-FAQ          │  │
  │    ├──> M-SCHEMA-BREADCRUMB   │  │
  │    ├──> M-REDIRECTS           │  │
  │    ├──> M-SITEMAP             │  │
  │    ├──> M-ROBOTS              │  │
  │    └──> M-GSC                 │  │
  │                               │  │
  ├──> M-GA4-GTM                  │  │
  │    └──> M-CONV-EVENTS ────────┘  │
  │                                  │
  ├──> M-COMPLIANZ ──────────────────┤
  ├──> M-GDPR-FORMS                  │
  ├──> M-WORDFENCE                   │
  ├──> M-SECURITY-HARDEN             │
  └──> M-UPTIME                      │
      │                              │
      │   [ALL MODULES COMPLETE]     │
      │       │                      │
      └───────┴──────────────────────┘
              │
              ├──> M-PERF-TEST ──> M-PERF-FIX
              ├──> M-A11Y-AUDIT ──> M-A11Y-FIX
              ├──> M-CROSS-BROWSER
              ├──> M-MOBILE-TEST
              ├──> M-LINK-AUDIT
              ├──> M-IMAGE-OPT
              └──> M-CLIENT-REVIEW
```

### 3.2 Epic-Level Dependency Flow

```
EPIC 1: Core Pages ──────> EPIC 5: SEO & Analytics
EPIC 2: Conversion Forms    EPIC 6: Compliance
EPIC 3: Supporting Pages    EPIC 7: Security
EPIC 4: WooCommerce          EPIC 8: QA & Acceptance
```

Epics 1-4 execute in parallel (Week 1-2). Epics 5-7 execute in parallel after Epics 1-4 complete (Week 3). Epic 8 is the final sequential phase (Week 4).

---

## 4. Critical Path

### 4.1 Critical Path Modules

The following modules form the **critical path**. Any delay to these modules delays the entire sprint.

```
M-TPL-SERVICE → M-PAGE-REGULIER → ALL PAGES → M-NAV-MENUS
                                              → M-SEO-META → M-GA4-GTM
                                              → M-FORM-CONTACT → M-FORM-QUOTE
                                              → M-WC-CONFIG → M-WC-FLOW
                                              → M-CLIENT-REVIEW
```

### 4.2 Critical Path Timeline

| Day | Critical Milestone | Dependency |
|---|---|---|
| Day 1 | M-TPL-SERVICE complete | Sprint 5 Foundation |
| Day 2 | M-PAGE-REGULIER complete (highest business impact fix) | M-TPL-SERVICE |
| Day 5 | All core pages + Contact form operational | Multiple |
| Day 10 | All 32 pages exist. WC purchase flow tested. | Multiple |
| Day 13 | All SEO metadata written. All schema validated. | All pages complete |
| Day 15 | Cookie consent active. Security hardened. | Legal pages + Complianz |
| Day 18 | Performance + Accessibility audits complete | All pages + optimization |
| Day 20 | Client acceptance on staging | All modules |

### 4.3 Critical Path Risk Buffer

- Days 1-5: **1-day buffer** (pages can slip into Day 6)
- Days 6-10: **1-day buffer** (WC config can slip into Day 11)
- Days 11-15: **1-day buffer** (SEO/compliance can slip into Day 16)
- Days 16-20: **FIXED** — no buffer. Client review on Day 20 is a hard gate.

### 4.4 Non-Critical (Parallel) Modules

These modules are NOT on the critical path and can be deferred or parallelized:

| Module | Can Be Deferred To | Reason |
|---|---|---|
| M-PAGE-DISCLAIMER | Week 3 | Low priority legal page |
| M-PAGE-COOKIE (shell) | Week 3 | Populated by Complianz in Week 3 |
| M-PAGE-VOORWAARDEN | Week 3 | Requires MI-16 client input |
| M-PAGE-REFERENTIES (logos) | Week 3 | Requires MI-10 client input |
| M-PAGE-VACATURES (full text) | Week 3 | Requires MI-12 client input |
| M-IMAGE-OPT | Week 4 | Parallel with testing |
| M-UPTIME | Week 4 | Low complexity, immediate setup |

---

## 5. Repository Workflow

### 5.1 Repository Structure

```
cleaning-company/
├── .github/
│   └── workflows/
│       ├── lint.yml              # ESLint + Stylelint on PR
│       └── build-verify.yml      # Build verification
├── wp-content/
│   └── themes/
│       └── hds/                  # Theme root (version controlled)
│           ├── assets/
│           ├── inc/
│           ├── parts/
│           ├── page-templates/
│           ├── languages/
│           ├── theme.json
│           ├── style.css
│           └── functions.php
├── docs/                         # All documentation
├── Docker/                       # Docker configuration
├── package.json
├── .editorconfig
├── .prettierrc
├── eslint.config.js
├── stylelintrc.json
├── phpcs.xml
├── phpstan.neon
├── phpunit.xml
├── .commitlintrc.json
└── Makefile
```

### 5.2 What IS Version-Controlled

| Artifact | Version Control | Reason |
|---|---|---|
| Theme files (PHP, CSS, JS, JSON) | YES — full | Source code |
| Build output (main.min.css, main.min.js) | YES | Deployable artifact (no build step on server) |
| package.json, package-lock.json | YES | Dependency lock |
| Documentation (docs/) | YES | Project knowledge base |
| Docker configuration | YES | Reproducible environment |
| Lint/format configs | YES | Consistent tooling |
| wp-content/uploads/ | NO | User-generated content |
| wp-config.php | NO | Contains secrets |
| node_modules/ | NO | Installed via `npm ci` |
| .env files | NO | Contains secrets |

### 5.3 Daily Workflow

```
START OF DAY
  1. git checkout develop
  2. git pull origin develop
  3. Create feature branch from develop
  4. Implement module
  5. npm run lint (local)
  6. npm run build (local)
  7. git add, git commit (conventional commit)
  8. git push origin feature/<branch>
  9. Create PR to develop
 10. PR review (if team > 1)

END OF DAY
  - All work committed and pushed
  - No uncommitted changes on developer machine
  - Staging environment reflects develop branch
```

### 5.4 Git Hooks

| Hook | Action | Enforced By |
|---|---|---|
| pre-commit | `npm run lint` (JS + CSS) | Husky |
| commit-msg | Conventional commit validation | Commitlint |
| pre-push | `npm run build` verification | Husky |

---

## 6. Branch Strategy

### 6.1 Branch Model: Trunk-Based (Modified)

```
main           ──────────────────────────────────────► Production
  │
  └── develop  ──────────────────────────────────────► Staging (integration)
        │
        ├── feature/service-page-content
        ├── feature/contact-form
        ├── feature/woocommerce-config
        ├── feature/seo-metadata
        ├── feature/compliance-cookies
        ├── fix/accessibility-nav
        └── chore/update-docs
```

### 6.2 Branch Types

| Branch Type | Pattern | Purpose | Lifetime | Merges To |
|---|---|---|---|---|
| `main` | `main` | Production-ready code | Permanent | — |
| `develop` | `develop` | Integration branch, auto-deploys to staging | Permanent | — |
| `feature/*` | `feature/<module-name>` | One module per branch | Hours to 2 days | `develop` |
| `fix/*` | `fix/<issue-description>` | Bug fixes found in testing | Hours | `develop` |
| `chore/*` | `chore/<description>` | Docs, config, cleanup | Hours | `develop` |
| `release/*` | `release/sprint-6` | Sprint 6 release candidate | Days (Week 4) | `main` |

### 6.3 Branch Naming Convention

```
feature/<epic>-<module>   e.g., feature/core-contact-form
fix/<issue-id>-<desc>     e.g., fix/M-CONTACT-recaptcha
chore/<desc>              e.g., chore/update-readme
```

### 6.4 Branch Rules

| Rule | Enforcement |
|---|---|
| `main` is protected — no direct pushes | GitHub branch protection |
| `main` requires 1 approving review + passing CI | GitHub branch protection |
| `develop` requires passing lint before merge | CI workflow |
| Feature branches MUST be created from `develop` | Process convention |
| Feature branches MUST be merged via PR (no direct push) | Process convention |
| Feature branches MUST be deleted after merge | Process convention |
| Branch name MUST match pattern | Process convention |
| Maximum branch lifetime: 2 working days | Process convention |

---

## 7. Merge Strategy

### 7.1 Merge Method: Squash Merge

**All feature branches → develop use squash merge.**

```
feature/my-module:  commits A, B, C
                        │
                        ▼  squash merge
develop:            commit D (single squashed commit)
```

### 7.2 Rationale

| Reason | Explanation |
|---|---|
| **Clean history** | One commit per module on develop |
| **Atomic rollback** | Reverting one commit reverts the entire feature |
| **Conventional commits** | Squashed commit message follows conventional commit format |
| **No merge commits** | Develop branch history is linear and readable |

### 7.3 Merge Commit Message Format

Every squashed merge commit MUST follow:

```
<type>(<scope>): <description>

<module ID> — <module name>

Closes #<issue-number>
```

**Examples:**
```
feat(content): Build Reguliere Schoonmaak page with 300+ words

M-PAGE-REGULIER — Service page content population

Closes #42
```

```
feat(forms): Configure Gravity Forms Contact Form with reCAPTCHA v3

M-FORM-CONTACT — Contact form configuration

Closes #43
```

### 7.4 Merge Flow

```
Feature Branch           Develop Branch           Main Branch
─────────────────       ─────────────────       ─────────────────
1. Implement module
2. npm run lint
3. npm run build
4. git commit
5. git push
6. Create PR ──────────► 7. CI runs (lint + build)
                         8. Code review (if team > 1)
                         9. Squash merge ◄────── 10. Auto-deploy to staging
                         11. Delete feature branch
                         12. Verify on staging ──► 13. Continue next module
                                                   ...
                                                   (Week 4: Release)
                         14. Create release/sprint-6 from develop
                         15. Final QA on release branch
                         16. Create PR release → main
                         17. Merge commit (NOT squash) to main ──► 18. Tag v1.0.0
                         19. Deploy to production
```

### 7.5 Release Branch (Sprint 6 → Main)

The `release/sprint-6` branch merges to `main` using a **regular merge commit** (not squash). This preserves the full history on main while keeping develop clean.

```
develop ──────────► release/sprint-6 ──► main (merge commit, tag v1.0.0)
```

### 7.6 Prohibited Actions

| Action | Why Prohibited |
|---|---|
| Direct push to `main` | Protected branch |
| Direct push to `develop` | Must go through PR |
| Force push to any shared branch | Destroys history |
| Merge `main` into feature branch | Wrong integration direction |
| Rebase shared branches | Destroys shared history |

---

## 8. Definition of Ready

### 8.1 DoR for Every Module

Before a module can be picked up for implementation, ALL of the following MUST be true:

| # | Criterion | Verified By |
|---|---|---|
| DOR-01 | Module has a unique ID (M-*) from this plan | Lead Developer |
| DOR-02 | Module has a clearly defined deliverable | Lead Developer |
| DOR-03 | Acceptance criteria are documented (Section 18) | Lead Developer |
| DOR-04 | All dependencies are resolved (Dependency Graph, Section 3) | Developer |
| DOR-05 | Required client data (MI-*) is available OR graceful degradation is defined | Lead Developer |
| DOR-06 | Required assets (images, logos, text) are available OR placeholder strategy is defined | Developer |
| DOR-07 | Module is estimated (hours from Section 2.2) | Lead Developer |
| DOR-08 | Module is assigned to a specific developer | Lead Developer |
| DOR-09 | Prerequisite modules have passed their Definition of Done | Lead Developer |
| DOR-10 | Feature branch is created from latest `develop` | Developer |

### 8.2 Client Data Gate (DoR for MI-Dependent Modules)

The following modules are BLOCKED until client provides specific information (MI-* items from MPS-001 Section A4). If client data is unavailable, the module proceeds with **graceful degradation** (placeholder values + conditional display).

| Module | Required MI | Graceful Degradation |
|---|---|---|
| M-PAGE-CONTACT (address block) | MI-01 (address) | Hide address block; show phone + email only |
| M-PAGE-OVERHDS (history) | MI-19 (founding year) | Show "Al meer dan X jaar" generic text |
| M-PAGE-REFERENTIES (logos) | MI-10 (client logos) | Hide logo section; show testimonials only |
| M-PAGE-REFERENTIES (testimonials) | MI-11 (testimonial text) | Show "Wij horen graag uw ervaring!" empty state |
| M-PAGE-VACATURES (text content) | MI-12 (vacancy text as HTML) | Show "Momenteel geen openstaande vacatures" |
| M-PAGE-VOORWAARDEN | MI-16 (terms text) | Show page with "Binnenkort beschikbaar" |
| M-WC-SHIPPING | MI-14 (shipping costs) | Flat rate placeholder; note for client review |
| M-WC-PAYMENT | MI-15 (payment gateway) | Default to Mollie test mode |
| M-SCHEMA-LOCAL | MI-01..04 (address, KVK, BTW, hours) | Omit missing fields from schema |

### 8.3 DoR Gate Check

At the start of each week, the Lead Developer runs the DoR Gate Check:

```
CHECK: All modules planned for this week
   FOR EACH module:
     IF DOR-01 through DOR-10 all pass → READY
     IF any MI dependency missing → ESCALATE to client
     IF prerequisite module not done → RE-PLAN to next week
```

---

## 9. Definition of Done

### 9.1 DoD for Every Module

Before a module is marked complete, ALL of the following MUST be true:

| # | Criterion | Verified By |
|---|---|---|
| DOD-01 | Module deliverable is deployed to staging and verified | Developer + QA |
| DOD-02 | All acceptance criteria (Section 18) are demonstrably met | Developer |
| DOD-03 | `npm run lint` passes with 0 errors (JS + CSS) | CI |
| DOD-04 | `npm run build` succeeds (CSS minification + JS minification) | CI |
| DOD-05 | No hardcoded values (phone, email, address) — uses theme functions | Code review |
| DOD-06 | All PHP output is escaped; all inputs are sanitized | Code review |
| DOD-07 | No `console.log()` in production code | Code review |
| DOD-08 | Responsive at mobile (320px), tablet (768px), desktop (1200px) | Developer |
| DOD-09 | Cross-browser verified: Chrome, Firefox, Safari, Edge (latest) | Developer |
| DOD-10 | No JavaScript errors in browser console | Developer |
| DOD-11 | Feature branch is merged to `develop` (squash merge) | Developer |
| DOD-12 | Feature branch is deleted after successful merge | Developer |
| DOD-13 | Git commit follows conventional commit format | Commitlint |
| DOD-14 | Documentation updated if behavior changed (rare — docs are frozen) | Developer |

### 9.2 DoD for Content Pages (Additional)

| # | Criterion |
|---|---|
| DOD-C01 | Page returns HTTP 200 at correct URL |
| DOD-C02 | Content meets minimum word count (300 for service, 500 for landing, 150 for contact/legal) |
| DOD-C03 | H1 present exactly once |
| DOD-C04 | H2/H3 hierarchy logical (no skipped levels) |
| DOD-C05 | All links functional (internal + external) |
| DOD-C06 | CTA present and links to correct URL |
| DOD-C07 | Content reviewed by native Dutch speaker |
| DOD-C08 | Cross-links to related service pages present (service pages) |
| DOD-C09 | Title tag and meta description set |
| DOD-C10 | Featured image or hero image present (where applicable) |

### 9.3 DoD for Forms (Additional)

| # | Criterion |
|---|---|
| DOD-F01 | Form submits successfully without errors |
| DOD-F02 | Confirmation email delivered to test inbox within 2 minutes |
| DOD-F03 | Notification email delivered to info@ within 2 minutes |
| DOD-F04 | Entry stored in Gravity Forms database |
| DOD-F05 | reCAPTCHA v3 active (badge visible) |
| DOD-F06 | Honeypot field present and functional |
| DOD-F07 | Privacy checkbox unchecked by default |
| DOD-F08 | Privacy checkbox links to /privacyverklaring/ |
| DOD-F09 | All validation errors display inline (Dutch) |
| DOD-F10 | All fields have `<label>` elements |
| DOD-F11 | Required fields marked with text and `aria-required` |
| DOD-F12 | Error messages programmatically associated via `aria-describedby` |
| DOD-F13 | Form keyboard-navigable |
| DOD-F14 | File upload: server-side MIME validation, file rename, size check |

### 9.4 DoD for WooCommerce (Additional)

| # | Criterion |
|---|---|
| DOD-W01 | All 14 products visible at /winkel/ |
| DOD-W02 | Product images display correctly |
| DOD-W03 | Prices match source data |
| DOD-W04 | Add to cart, update quantity, remove item all work |
| DOD-W05 | Checkout completes with test payment |
| DOD-W06 | All email notifications delivered |
| DOD-W07 | Order visible in WooCommerce admin |
| DOD-W08 | Guest checkout functional |
| DOD-W09 | WooCommerce pages (cart, checkout, account) excluded from cache |

### 9.5 DoD for SEO (Additional)

| # | Criterion |
|---|---|
| DOD-S01 | Schema validates via Google Rich Results Test |
| DOD-S02 | Zero empty meta descriptions (Screaming Frog) |
| DOD-S03 | Zero duplicate meta descriptions |
| DOD-S04 | All meta descriptions 150-160 characters |
| DOD-S05 | Open Graph tags complete on all pages |
| DOD-S06 | Self-referencing canonicals on all pages |
| DOD-S07 | XML sitemap returns 200 with valid XML |
| DOD-S08 | robots.txt returns 200 with correct rules |
| DOD-S09 | All 301 redirects return 301 (not 302/307) |
| DOD-S10 | Zero redirect chains |

### 9.6 Module Completion Checklist

When a developer marks a module as Done, they fill this checklist:

```
MODULE: [ID] — [Name]
DEVELOPER: [Name]
DATE: [YYYY-MM-DD]

[ ] DOD-01 — Deployed to staging + verified
[ ] DOD-02 — All acceptance criteria met
[ ] DOD-03 — npm run lint: 0 errors
[ ] DOD-04 — npm run build: success
[ ] DOD-05 — No hardcoded values
[ ] DOD-06 — Output escaped, input sanitized
[ ] DOD-07 — No console.log()
[ ] DOD-08 — Responsive (320/768/1200)
[ ] DOD-09 — Cross-browser verified
[ ] DOD-10 — No JS console errors
[ ] DOD-11 — Merged to develop (squash)
[ ] DOD-12 — Feature branch deleted
[ ] DOD-13 — Conventional commit
[ ] DOD-14 — Docs updated if needed
[ ] [Additional category-specific DOD items]

STATUS: [ ] DONE  [ ] BLOCKED — Reason: _______
```

---

## 10. Coding Standards Enforcement

### 10.1 Enforced Standards

| Language | Standard | Tool | Config File | Run On |
|---|---|---|---|---|
| PHP | WordPress Coding Standards | PHP_CodeSniffer | `phpcs.xml` | Manual (requires Docker runtime) |
| PHP | Static Analysis (Level 6) | PHPStan | `phpstan.neon` | Manual (requires Docker runtime) |
| CSS | Stylelint Standard | Stylelint | `.stylelintrc.json` | pre-commit hook + CI |
| JavaScript | ESLint flat config | ESLint | `eslint.config.js` | pre-commit hook + CI |
| All | EditorConfig | EditorConfig | `.editorconfig` | IDE plugin |
| All | Prettier | Prettier | `.prettierrc` | pre-commit hook |
| Git | Conventional Commits | Commitlint | `.commitlintrc.json` | commit-msg hook |

### 10.2 PHP Coding Standards (Enforced via phpcs.xml)

| Rule | Requirement |
|---|---|
| Naming | Functions: `hds_` prefix. Classes: `HDS_` prefix. snake_case for functions, PascalCase for classes. |
| Escaping | All output: `esc_html()`, `esc_attr()`, `esc_url()`, `wp_kses()` |
| Sanitization | All inputs: `sanitize_text_field()`, `sanitize_email()`, etc. |
| Nonces | All custom forms: `wp_nonce_field()` + `check_admin_referer()` or `wp_verify_nonce()` |
| SQL | Prepared statements via `$wpdb->prepare()`. No direct string interpolation. |
| Banned | No `eval()`. No `base64_decode()`. No `extract()`. No `$_GET`/`$_POST` raw use. |
| PHP Version | PHP 8.2+ syntax. Typed properties. Match expressions where appropriate. |
| i18n | `__()` and `_e()` for all user-facing strings with textdomain `hds` |

### 10.3 JavaScript Coding Standards (Enforced via ESLint)

| Rule | Requirement |
|---|---|
| Syntax | ES6+. `const`/`let` only (no `var`). Arrow functions. Template literals. |
| jQuery | No jQuery dependency (vanilla JS unless WooCommerce requires it) |
| DOM | Event delegation. `data-*` attributes for JS hooks. No inline event handlers. |
| Progressive Enhancement | Navigation, forms must work without JavaScript |
| Console | No `console.log()` in production code. `console.error()` for error reporting only. |
| Naming | camelCase. Event handlers: `on` prefix (`onMenuToggle`). |

### 10.4 CSS Coding Standards (Enforced via Stylelint)

| Rule | Requirement |
|---|---|
| Naming | BEM-like: `.hds-[block]__[element]--[modifier]` |
| Custom Properties | All design tokens via `var(--hds-*)` |
| Specificity | Max nesting depth: 3 levels |
| No ID selectors | IDs reserved for JS hooks (`data-*` preferred) |
| No `!important` | Except for utility classes (`.hds-sr-only`, `.hds-hidden`) |
| Mobile-First | `min-width` media queries. No `max-width` breakpoints. |

### 10.5 Automated Enforcement Pipeline

```
Developer writes code
       │
       ▼
┌──────────────────┐
│  pre-commit hook │  ← Husky
│  npm run lint    │  ESLint + Stylelint
│  commitlint      │  Conventional commit message
└──────┬───────────┘
       │ PASS
       ▼
┌──────────────────┐
│  git push        │
└──────┬───────────┘
       │
       ▼
┌──────────────────┐
│  CI: lint.yml    │  ← GitHub Actions
│  npm ci          │
│  npm run lint    │  ESLint + Stylelint (same checks, CI guarantee)
│  npm run build   │  CSS minification + JS minification (verify succeeds)
└──────┬───────────┘
       │ PASS
       ▼
┌──────────────────┐
│  Code Review     │  ← Manual (if team > 1)
│  PHPCS (manual)  │  ← Manual (Docker not in CI)
└──────┬───────────┘
       │ APPROVED
       ▼
   Merge to develop
```

### 10.6 PHP Linting (Manual Gate)

PHP linting (PHPCS + PHPStan) cannot run in CI due to the Docker/Composer dependency being external (EXT-01). The developer runs these manually before PR:

```bash
# Requires Docker runtime
docker compose run --rm php composer phpcs
docker compose run --rm php composer phpstan
```

The results are posted as a comment on the PR. This is a **manual gate** — the reviewer verifies the PHPCS/PHPStan output before approving.

---

## 11. Quality Gates

### 11.1 Quality Gate Framework

A Quality Gate is a **go/no-go checkpoint**. If the gate fails, work stops until the failure is resolved. Gates are sequential — Gate N must pass before work on Gate N+1's scope begins.

### 11.2 Gate Definitions

| Gate | Name | When | Pass Criteria | Owner |
|---|---|---|---|---|
| **QG-0** | Sprint 5 Foundation Verified | Pre-Sprint 6 (already passed) | See Readiness Closure Report (Section 5) | Lead Dev |
| **QG-1** | Core Pages Live | End of Week 1, Day 5 | All 12 core pages return HTTP 200 with 300+ words. Contact form submits and delivers email. | Lead Dev |
| **QG-2** | All Pages + WC | End of Week 2, Day 5 | All 32 pages exist. WC purchase flow tested end-to-end. All 3 forms functional. | Lead Dev |
| **QG-3** | SEO + Compliance Complete | End of Week 3, Day 5 | All SEO metadata unique. All schema validated. Cookie consent active. Security hardened. GA4 active. | Lead Dev |
| **QG-4** | Staging Acceptance | End of Week 4, Day 5 | ALL 25 launch readiness criteria met. Client sign-off obtained. | Lead Dev + Client |

### 11.3 Gate QG-1: Core Pages Live (Week 1)

| # | Check | Tool | Threshold |
|---|---|---|---|
| QG1-01 | Home page returns HTTP 200 | Browser | `/` = 200 |
| QG1-02 | All 7 service pages return HTTP 200 | Browser | P02-P08 all 200 |
| QG1-03 | Category landings return HTTP 200 | Browser | P09, P10 = 200 |
| QG1-04 | All core pages >= 300 words Dutch | Manual count | >= 300 per page |
| QG1-05 | Contact page returns HTTP 200 (was 500) | Browser | `/contact/` = 200 |
| QG1-06 | Contact form submits and delivers email | Manual test | Email in inbox < 2 min |
| QG1-07 | Quote form submits and delivers email | Manual test | Email with file download link |
| QG1-08 | Bedankt page returns HTTP 200 | Browser | `/bedankt/` = 200 |
| QG1-09 | 404 page returns HTTP 404 | Browser | `/nonexistent` = 404 |
| QG1-10 | No broken internal links | Screaming Frog | 0 broken |
| QG1-11 | Lint passes | `npm run lint` | 0 JS errors, 0 CSS errors |
| QG1-12 | Build succeeds | `npm run build` | CSS + JS minified |

**Gate Decision:** [ ] GO / [ ] NO-GO — If NO-GO, Week 2 does NOT start.

### 11.4 Gate QG-2: All Pages + WC (Week 2)

| # | Check | Tool | Threshold |
|---|---|---|---|
| QG2-01 | All 32 pages return HTTP 200 | Screaming Frog | 32 pages, 0 unexpected 4xx |
| QG2-02 | All service pages >= 300 words | Manual count | 7 pages >= 300 |
| QG2-03 | Both category landings >= 500 words | Manual count | 2 pages >= 500 |
| QG2-04 | All legal pages published | Browser | P19-P22 rendered |
| QG2-05 | Vacatures page: ZERO JPG images | Manual | All text in HTML |
| QG2-06 | PDFs accessible from primary domain | Browser | Download links work |
| QG2-07 | Navigation: all pages reachable | Manual | Primary nav + footer nav |
| QG2-08 | WC: all 14 products visible at /winkel/ | Browser | 14 products |
| QG2-09 | WC: test purchase completes end-to-end | Manual test | Browse→Cart→Checkout→Payment→Email |
| QG2-10 | WC: all email notifications delivered | Manual test | Order confirm + processing emails |
| QG2-11 | FAQ page: 10-15 items with accordion | Browser + axe | Scroll, expand, collapse all work |
| QG2-12 | All forms (3) submit and deliver email | Manual test | GF-1, GF-2, GF-3 all work |
| QG2-13 | Lint passes | `npm run lint` | 0 errors |
| QG2-14 | Build succeeds | `npm run build` | Success |

**Gate Decision:** [ ] GO / [ ] NO-GO — If NO-GO, Week 3 does NOT start.

### 11.5 Gate QG-3: SEO + Compliance + Security (Week 3)

| # | Check | Tool | Threshold |
|---|---|---|---|
| QG3-01 | All 32 pages have unique meta titles | Screaming Frog | 0 empty, 0 duplicate |
| QG3-02 | All 32 pages have unique meta descriptions (150-160 chars) | Screaming Frog | 0 empty, 0 duplicate |
| QG3-03 | Open Graph tags complete on all pages | Screaming Frog | og:title, og:description, og:image, og:url |
| QG3-04 | Self-referencing canonicals on all pages | Screaming Frog | 32/32 correct |
| QG3-05 | LocalBusiness schema: present + valid | Google Rich Results Test | Valid |
| QG3-06 | Service schema: valid on all 7 service pages | Google Rich Results Test | 7/7 valid |
| QG3-07 | FAQPage schema: valid | Google Rich Results Test | Valid |
| QG3-08 | BreadcrumbList schema on all inner pages | Manual | Present |
| QG3-09 | XML Sitemap: returns 200, valid XML | Browser + validator | 200 + valid |
| QG3-10 | robots.txt: returns 200, correct rules | Browser | 200 + rules correct |
| QG3-11 | All 301 redirects return 301 (not 302/307) | httpstatus.io | 100% 301 |
| QG3-12 | Zero redirect chains | Screaming Frog | 0 chains |
| QG3-13 | GA4 real-time shows page views | GA4 dashboard | Active |
| QG3-14 | Cookie consent banner appears on fresh browser | Manual test | Banner visible |
| QG3-15 | No GA/Facebook cookies before consent | Chrome DevTools > Application > Cookies | 0 non-functional before consent |
| QG3-16 | Consent logged (Complianz scan log) | Complianz dashboard | Entries logged |
| QG3-17 | 2FA active on all admin accounts | Wordfence dashboard | All accounts 2FA |
| QG3-18 | XML-RPC returns 403 | Browser + `curl` | `/xmlrpc.php` = 403 |
| QG3-19 | Custom login URL active | Browser | `/wp-admin` redirects to custom |
| QG3-20 | Daily backups configured and verified | Backup plugin | Last backup < 24h |
| QG3-21 | UptimeRobot monitoring active | UptimeRobot dashboard | Monitor active |
| QG3-22 | Lint passes | `npm run lint` | 0 errors |
| QG3-23 | Build succeeds | `npm run build` | Success |

**Gate Decision:** [ ] GO / [ ] NO-GO — If NO-GO, Week 4 does NOT start.

### 11.6 Gate QG-4: Staging Acceptance (Week 4)

| # | Check | Tool | Threshold |
|---|---|---|---|
| QG4-01 | PSI Mobile >= 90 | PageSpeed Insights | Score >= 90 (all templates) |
| QG4-02 | PSI Desktop >= 95 | PageSpeed Insights | Score >= 95 (all templates) |
| QG4-03 | LCP < 2.5s | PSI / WebPageTest | < 2.5s |
| QG4-04 | CLS < 0.1 | PSI | < 0.1 |
| QG4-05 | axe DevTools: zero critical + serious issues | axe DevTools | 0 critical, 0 serious |
| QG4-06 | WAVE: zero errors | WAVE | 0 errors |
| QG4-07 | Lighthouse Accessibility = 100 (all templates) | Lighthouse | Score = 100 |
| QG4-08 | Keyboard navigation: all elements reachable | Manual | Complete navigation possible |
| QG4-09 | Screen reader: forms usable | NVDA / VoiceOver | All forms operable |
| QG4-10 | Color contrast: all elements pass AA | WebAIM / axe | All pass |
| QG4-11 | 200% zoom: no content loss, no horizontal scroll | Browser zoom | No issues |
| QG4-12 | Touch targets >= 44x44px | Manual | All interactive elements |
| QG4-13 | Cross-browser: Chrome, Firefox, Safari, Edge | Manual + BrowserStack | Consistent rendering |
| QG4-14 | Mobile/tablet: real devices | Manual (iPhone + Android + iPad) | Responsive, usable |
| QG4-15 | Screaming Frog: zero broken internal links | Screaming Frog | 0 broken |
| QG4-16 | Screaming Frog: zero orphan pages | Screaming Frog | 0 orphans |
| QG4-17 | All images have alt text | Screaming Frog | 0 missing |
| QG4-18 | All images optimized (WebP) | Manual | WebP format, < 150KB |
| QG4-19 | All content reviewed by native Dutch speaker | Manual review | No errors |
| QG4-20 | All 3 forms submit and deliver email on staging | Manual test | 3/3 working |
| QG4-21 | WC purchase flow complete end-to-end (test mode) | Manual test | Pass |
| QG4-22 | Cookie consent: banner appears, no cookies before consent | Manual test | Pass |
| QG4-23 | Legal pages published and linked from footer | Manual | All 4 linked |
| QG4-24 | No lorem ipsum or placeholder text | Manual | 0 occurrences |
| QG4-25 | Client sign-off obtained | Client meeting | Written approval |

**Gate Decision:** [ ] GO / [ ] NO-GO — If GO, Sprint 7 (Launch) may begin. If NO-GO, fix issues and re-submit for review.

---

## 12. CI/CD Gates

### 12.1 CI Pipeline (GitHub Actions)

```yaml
# .github/workflows/lint.yml
name: Lint
on:
  pull_request:
    branches: [develop, main]
  push:
    branches: [develop]

jobs:
  lint:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: actions/setup-node@v4
        with:
          node-version: '20'
          cache: 'npm'
      - run: npm ci
      - run: npm run lint:css
      - run: npm run lint:js
```

```yaml
# .github/workflows/build-verify.yml
name: Build Verify
on:
  pull_request:
    branches: [develop, main]

jobs:
  build:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: actions/setup-node@v4
        with:
          node-version: '20'
          cache: 'npm'
      - run: npm ci
      - run: npm run build
      - name: Verify build output exists
        run: |
          test -f wp-content/themes/hds/assets/css/main.min.css
          test -f wp-content/themes/hds/assets/js/main.min.js
```

### 12.2 CI Gate Rules

| Gate | Trigger | Pass Condition | Fail Action |
|---|---|---|---|
| CI-GATE-LINT | Every PR to `develop` or `main` | ESLint 0 errors, Stylelint 0 errors | PR blocked from merge |
| CI-GATE-BUILD | Every PR to `develop` or `main` | Build succeeds, output files exist | PR blocked from merge |
| CI-GATE-MAIN | Every PR to `main` | Lint + Build + 1 approving review | PR blocked from merge |

### 12.3 CI/CD — What is NOT in CI

| Activity | Why Not in CI | How It's Done |
|---|---|---|
| PHPCS / PHPStan | Requires Docker + PHP + Composer (EXT-01) | Manual on developer workstation before PR |
| PHPUnit | No PHP tests exist at this stage | N/A — deferred to post-launch |
| End-to-end tests | Playwright not yet configured | Manual testing on staging |
| Deployment | Hosting uses custom deployment (DeployHQ / WP Engine Git Push) | Manual trigger or auto-deploy on merge to develop |
| Performance testing | Requires running WordPress instance + external tools | Manual (PSI, WebPageTest) during QA week |

### 12.4 Deployment Model

```
Developer git push ──► GitHub
                          │
                          ▼
                    PR created to develop
                          │
                    ┌─────┴─────┐
                    │ CI: lint  │ (automatic)
                    │ CI: build │ (automatic)
                    └─────┬─────┘
                          │ PASS
                          ▼
                    Code review (if team > 1)
                          │ APPROVED
                          ▼
                    Squash merge to develop
                          │
                          ▼
                    ┌──────────────┐
                    │ DeployHQ or  │ (automatic on merge)
                    │ WP Engine    │
                    │ Git Push     │
                    └──────┬───────┘
                           │
                           ▼
                    Staging Environment
                    staging.helderduidelijkschoon.nl
                           │
                    ┌──────┴───────┐
                    │ Clear caches │ (manual or API)
                    │ WP Rocket    │
                    │ Cloudflare   │
                    │ Redis        │
                    └──────────────┘
```

---

## 13. Testing Sequence

### 13.1 Testing Layers

Testing executes in 4 layers, from innermost (fastest) to outermost (most comprehensive):

```
LAYER 1: Continuous (every commit)
  ├── ESLint (JS)
  ├── Stylelint (CSS)
  └── Build verification (CSS + JS minification output exists)

LAYER 2: Per-Module (every module completion)
  ├── PHPCS (manual, per module)
  ├── PHPStan (manual, per module)
  ├── Visual verification (staging browser check)
  ├── Responsive check (320/768/1200)
  └── Cross-browser quick check (Chrome + Firefox + Safari + Edge)

LAYER 3: Per-Phase (end of each week)
  ├── Screaming Frog crawl (broken links, status codes, metadata)
  ├── Form submission tests (all 3 forms, email delivery)
  ├── WC purchase flow test (end-to-end)
  └── Navigation audit (all pages reachable from nav + footer)

LAYER 4: Comprehensive (Week 4 — QA Sprint)
  ├── Performance: PSI, WebPageTest, GTmetrix, DebugBear
  ├── Accessibility: axe DevTools, WAVE, Lighthouse, keyboard, screen reader
  ├── Cross-browser: Chrome, Firefox, Safari, Edge (latest 2 versions each)
  ├── Mobile/tablet: real iPhone, Android, iPad
  ├── SEO: full Screaming Frog audit, schema validation, redirect testing
  ├── Security: Wordfence scan, XML-RPC test, 2FA verification
  ├── GDPR: cookie consent, form consent checkboxes, legal page links
  └── Content: Dutch language review, word count check, alt text completeness
```

### 13.2 Testing Sequence by Day (Week 4)

```
DAY 1 — Functional QA
  ┌─────────────────────────────────────────────┐
  │ 09:00  Screaming Frog: crawl all 32 pages   │
  │        Verify: zero broken links             │
  │        Verify: all status codes correct      │
  │        Verify: zero orphan pages              │
  │ 11:00  Form testing: GF-1 (Contact)          │
  │        Form testing: GF-2 (Quote) with file  │
  │        Form testing: GF-3 (Vacature)         │
  │ 14:00  WC: full purchase flow test           │
  │        WC: guest + logged-in checkout         │
  │        WC: email notification verification    │
  │ 16:00  Navigation: every link in primary nav  │
  │        Navigation: every link in footer nav   │
  │        Search: test 5 search queries          │
  │        Cookie consent: fresh browser test     │
  └─────────────────────────────────────────────┘

DAY 2 — Performance Testing
  ┌─────────────────────────────────────────────┐
  │ 09:00  PSI mobile: Home, Service, Contact    │
  │        PSI desktop: Home, Service, Contact   │
  │ 11:00  WebPageTest (Amsterdam, Moto G4, 3G)  │
  │        Home, Glasbewassing, Contact          │
  │ 14:00  GTmetrix: all page templates          │
  │ 15:00  DebugBear: Core Web Vitals lab test   │
  │ 16:00  Chrome Coverage: unused CSS/JS audit  │
  │        Chrome Performance: long tasks audit   │
  │ 17:00  Fix performance issues found          │
  └─────────────────────────────────────────────┘

DAY 3 — Accessibility Audit
  ┌─────────────────────────────────────────────┐
  │ 09:00  axe DevTools: all page templates      │
  │        Every page: zero critical + serious   │
  │ 11:00  WAVE: all page templates              │
  │ 13:00  Lighthouse Accessibility: all pages   │
  │ 14:00  Keyboard-only: tab through every page │
  │        Verify: all elements reachable         │
  │        Verify: focus visible everywhere       │
  │ 15:00  Screen reader: NVDA (Windows)         │
  │        Test: Home, Service, Contact, WC       │
  │ 16:00  Color contrast: WebAIM checker         │
  │ 17:00  Fix accessibility issues found        │
  └─────────────────────────────────────────────┘

DAY 4 — Cross-Browser + Mobile + Remediation
  ┌─────────────────────────────────────────────┐
  │ 09:00  Chrome: all pages (latest + previous) │
  │ 10:00  Firefox: all pages (latest + prev)    │
  │ 11:00  Safari: macOS (latest + previous)     │
  │ 12:00  Edge: all pages (latest + previous)   │
  │ 14:00  iPhone 14+: Safari iOS (real device)  │
  │        Android Chrome (real device)           │
  │        iPad: Safari (real device)             │
  │ 16:00  Fix all cross-browser issues          │
  │ 17:00  Final regression: contact form, WC    │
  └─────────────────────────────────────────────┘

DAY 5 — Client Review + Acceptance
  ┌─────────────────────────────────────────────┐
  │ 09:00  Final Screaming Frog crawl (verify)   │
  │ 10:00  Client walkthrough on staging         │
  │        Homepage, Service pages, Contact      │
  │        Offerte, WC purchase, Legal pages     │
  │ 14:00  Client feedback collection            │
  │ 15:00  Final fixes (if any)                  │
  │ 17:00  Staging acceptance sign-off           │
  └─────────────────────────────────────────────┘
```

### 13.3 Test Environment

| Test Type | Environment | Notes |
|---|---|---|
| Lint + Build | GitHub Actions CI | Automated |
| PHPCS + PHPStan | Developer workstation (Docker) | Manual |
| Functional QA | Staging | All forms + WC in test mode |
| Performance | Staging + PSI/WebPageTest URLs | Staging must have CDN active |
| Accessibility | Staging + local tools | axe DevTools browser extension |
| Cross-browser | Staging + BrowserStack (or local) | Real devices for mobile |
| Client review | Staging | Password-protected, noindex |
| Screaming Frog | Staging | Crawl with basic auth if needed |

---

## 14. Feature Implementation Sequence

### 14.1 Epic 1: Core Pages (Week 1, Days 1-5)

**Goal:** All core service pages, home page, category landings, and content populated.

| Story ID | Module ID | Story Name | Points | Owner | Parallel |
|---|---|---|---|---|---|
| E-CORE-01 | M-TPL-SERVICE, M-PAGE-HOME | Home Page Template + Content | 8 | Dev A | No — foundational |
| E-CORE-02 | M-TPL-SERVICE | Service Page Template Finalization | 8 | Dev A | After E-CORE-01 |
| E-CORE-03 | M-PAGE-GLAS | Glasbewassing Page (P02) | 5 | Dev B | With E-CORE-04..08 |
| E-CORE-04 | M-PAGE-GEVEL | Gevelreiniging Page (P03) | 5 | Dev B | With E-CORE-03..08 |
| E-CORE-05 | M-PAGE-REGULIER | Reguliere Schoonmaak (P04) — CRITICAL | 8 | Dev B | With E-CORE-03..08 |
| E-CORE-06 | M-PAGE-VLOER, M-PAGE-VVE, M-PAGE-OPLEVERING | Vloer + VVE + Oplevering (P05-P07) | 8 | Dev B | With E-CORE-03..08 |
| E-CORE-07 | M-PAGE-INDUSTRIEEL | Industriele Schoonmaak (P08) | 5 | Dev B | With E-CORE-03..08 |
| E-CORE-08 | M-TPL-LANDING, M-PAGE-GLASGEVEL, M-PAGE-SCHOONMAAK | Category Landings (P09, P10) | 5 | Dev B | With E-CORE-03..08 |
| E-CORE-09 | M-TPL-CONTACT, M-FORM-CONTACT, M-PAGE-CONTACT | Contact Page + Form (P16) — CRITICAL | 13 | Dev A | After E-CORE-01 |
| E-CORE-10 | M-FORM-QUOTE, M-PAGE-QUOTE | Offerte Aanvragen + Form (P17) | 8 | Dev A | After E-CORE-09 |
| E-CORE-11 | M-PAGE-BEDANKT | Bedankt Page (P32) | 2 | Dev A | With E-CORE-10 |

**Week 1 Day-by-Day (from SPRINT2_EXECUTION_PLAN.md):**

```
DAY 1:  E-CORE-01 (Home template + content)     [Dev A]
        E-CORE-02 (Service template finalize)    [Dev A]

DAY 2:  E-CORE-03 (P02 Glasbewassing)           [Dev B]
        E-CORE-04 (P03 Gevelreiniging)          [Dev B]
        E-CORE-05 (P04 Reguliere — CRITICAL)     [Dev B]
        E-CORE-09 (Contact page + GF-1)          [Dev A]

DAY 3:  E-CORE-06 (P05 Vloer + P06 VVE + P07 Oplevering) [Dev B]
        E-CORE-09 (Contact form testing + Info Block)     [Dev A]

DAY 4:  E-CORE-07 (P08 Industriele)              [Dev B]
        E-CORE-08 (P09 Glas&Gevel + P10 Schoonmaak)      [Dev B]
        E-CORE-10 (Quote page + GF-2)             [Dev A]

DAY 5:  E-CORE-11 (P32 Bedankt)                  [Dev A]
        Cross-links audit + nav wiring           [Both]
        GATE QG-1: Core Pages Live               [Lead Dev]
```

**Week 1 Completion Deliverables:**
- [ ] Home page (P01): 300+ words, all 8 content blocks
- [ ] Service pages (P02-P08): all 7 pages >= 300 words Dutch
- [ ] Category landings (P09, P10): both >= 500 words
- [ ] Contact page (P16): GF-1 form submits, email delivered
- [ ] Quote page (P17): GF-2 form submits with file upload
- [ ] Bedankt page (P32): dynamic message based on ?type=

### 14.2 Epic 2: Supporting Pages (Week 2, Days 1-3)

**Goal:** About, trust, legal, FAQ, downloads, vacancies pages built.

| Story ID | Module ID | Story Name | Points | Owner | Parallel |
|---|---|---|---|---|---|
| E-SUPPORT-01 | M-TPL-ABOUT, M-PAGE-OVERHDS | About Template + Over HDS (P11) | 5 | Dev A | With E-SUPPORT-02 |
| E-SUPPORT-02 | M-PAGE-KWALITEIT | Kwaliteit & Veiligheid (P12) | 3 | Dev A | With E-SUPPORT-01 |
| E-SUPPORT-03 | M-CPT-TESTIMONIAL, M-PAGE-REFERENTIES | Referenties (P13) + CPT | 8 | Dev B | With others |
| E-SUPPORT-04 | M-CPT-VACANCY, M-FORM-VACATURE, M-PAGE-VACATURES | Vacatures Rebuild (P14) | 8 | Dev B | With others |
| E-SUPPORT-05 | M-TPL-LEGAL, M-PAGE-PRIVACY, M-PAGE-COOKIE, M-PAGE-VOORWAARDEN, M-PAGE-DISCLAIMER | Legal Pages (P19-P22) | 8 | Dev A | With others |
| E-SUPPORT-06 | M-PAGE-DOWNLOADS | Downloads + PDF Migration (P15) | 5 | Dev B | With others |
| E-SUPPORT-07 | M-PAGE-FAQ | FAQ Page (P18) | 5 | Dev B | With others |

```
DAY 6:  E-SUPPORT-01 (Over HDS — 500+ words)    [Dev A]
        E-SUPPORT-02 (Kwaliteit — 300+ words)    [Dev A]
        E-SUPPORT-03 (Referenties CPT + page)     [Dev B]

DAY 7:  E-SUPPORT-04 (Vacatures CPT + GF-3)      [Dev B]
        E-SUPPORT-05 (Legal pages — 4 pages)      [Dev A]
        E-SUPPORT-07 (FAQ page)                   [Dev B]

DAY 8:  E-SUPPORT-06 (Downloads + PDF migration)  [Dev B]
        Legal pages content completion            [Dev A]
        M-NAV-MENUS (Navigation wiring)           [Both]
```

### 14.3 Epic 3: WooCommerce (Week 2, Days 3-5)

**Goal:** Webshop configured, products imported, purchase flow tested.

| Story ID | Module ID | Story Name | Points | Owner | Parallel |
|---|---|---|---|---|---|
| E-COMM-01 | M-WC-CONFIG | WC Core Settings | 3 | Dev A | No — foundational |
| E-COMM-02 | M-WC-IMPORT, M-PAGE-WINKEL | Product Import + Shop Intro | 5 | Dev A | After E-COMM-01 |
| E-COMM-03 | M-WC-PAYMENT | Mollie Payment Gateway | 5 | Dev A | After E-COMM-01 |
| E-COMM-04 | M-WC-SHIPPING | Shipping Configuration | 3 | Dev A | After E-COMM-01 |
| E-COMM-05 | M-WC-EMAILS | WC Email Notifications | 3 | Dev A | After E-COMM-01 |
| E-COMM-06 | M-PAGE-LUCHT | Luchtreiniging Landing (P23) | 3 | Dev B | Parallel |
| E-COMM-07 | M-WC-FLOW | WC Purchase Flow E2E Test | 5 | Dev A | After all above |

```
DAY 8 PM: E-COMM-01 (WC settings)                 [Dev A]
          E-COMM-06 (Luchtreiniging page)          [Dev B]

DAY 9:    E-COMM-02 (Product import)               [Dev A]
          E-COMM-03 (Mollie payment)               [Dev A]
          E-COMM-04 (Shipping)                     [Dev A]
          E-COMM-05 (WC emails)                    [Dev A]

DAY 10:   E-COMM-07 (WC E2E test)                  [Dev A]
          M-PAGE-WINKEL (Shop intro text)          [Dev A]
          M-NAV-MENUS (Final navigation pass)       [Both]
          GATE QG-2: All Pages + WC                [Lead Dev]
```

### 14.4 Epic 4: SEO & Analytics (Week 3, Days 1-3)

**Goal:** Metadata, schema, sitemaps, redirects, GA4, GTM, conversion tracking.

| Story ID | Module ID | Story Name | Points | Owner | Parallel |
|---|---|---|---|---|---|
| E-SEO-01 | M-SEO-CONFIG | Rank Math Pro Configuration | 3 | Dev A | No |
| E-SEO-02 | M-SEO-META | Meta Titles + Descriptions (32 pages) | 8 | Both | After E-SEO-01 |
| E-SEO-03 | M-SCHEMA-LOCAL, M-SCHEMA-SERVICE, M-SCHEMA-FAQ, M-SCHEMA-BREADCRUMB | All Structured Data | 8 | Dev A | After E-SEO-01 |
| E-SEO-04 | M-REDIRECTS | 301 Redirects | 5 | Dev A | After E-SEO-01 |
| E-SEO-05 | M-SITEMAP, M-ROBOTS | Sitemap + robots.txt | 3 | Dev A | After all pages |
| E-SEO-06 | M-GSC | GSC Verification | 1 | Dev A | Domain access |
| E-SEO-07 | M-GA4-GTM, M-CONV-EVENTS | GA4 + GTM + Events | 8 | Dev A | After E-SEO-01 |

```
DAY 11:   E-SEO-01 (Rank Math config)              [Dev A]
          E-SEO-02 (Meta descriptions — batch 1)   [Both]

DAY 12:   E-SEO-02 (Meta descriptions — batch 2)   [Both]
          E-SEO-03 (All schema implemented)         [Dev A]
          E-SEO-04 (301 redirects configured)       [Dev A]

DAY 13:   E-SEO-05 (Sitemap + robots.txt)          [Dev A]
          E-SEO-06 (GSC verification)               [Dev A]
          E-SEO-07 (GA4 + GTM + events)             [Dev A]
```

### 14.5 Epic 5: Compliance & Security (Week 3, Days 3-5)

**Goal:** GDPR compliance, cookie consent, security hardening.

| Story ID | Module ID | Story Name | Points | Owner | Parallel |
|---|---|---|---|---|---|
| E-COMPLY-01 | M-COMPLIANZ | Complianz Cookie Consent | 5 | Dev A | With others |
| E-COMPLY-02 | M-GDPR-FORMS | GDPR Form Consent Verification | 2 | Dev A | With others |
| E-COMPLY-03 | M-WORDFENCE | Wordfence Security Configuration | 5 | Dev A | With others |
| E-COMPLY-04 | M-SECURITY-HARDEN | Security Hardening Verification | 3 | Dev A | With others |
| E-COMPLY-05 | M-UPTIME | UptimeRobot Monitoring | 1 | Dev A | With others |

```
DAY 13 PM: M-COMPLIANZ (Cookie consent setup)      [Dev A]
           M-WORDFENCE (Security configuration)     [Dev A]

DAY 14:    M-COMPLIANZ (Consent logging verify)    [Dev A]
           M-GDPR-FORMS (Form consent verify)       [Dev A]
           M-SECURITY-HARDEN (XML-RPC, 2FA, login) [Dev A]

DAY 15:    M-UPTIME (Monitoring setup)              [Dev A]
           Final compliance sweep                  [Dev A]
           GATE QG-3: SEO + Compliance              [Lead Dev]
```

### 14.6 Epic 6: Testing & QA (Week 4, Days 1-4)

See Section 13.2 for day-by-day testing sequence.

| Story ID | Module ID | Story Name | Points | Owner | Parallel |
|---|---|---|---|---|---|
| E-QA-01 | M-LINK-AUDIT | Full Functional QA | 5 | QA | No |
| E-QA-02 | M-PERF-TEST, M-PERF-FIX | Performance Testing + Remediation | 8 | QA | After E-QA-01 |
| E-QA-03 | M-A11Y-AUDIT, M-A11Y-FIX | Accessibility Audit + Remediation | 8 | QA | After E-QA-01 |
| E-QA-04 | M-CROSS-BROWSER | Cross-Browser Testing | 3 | QA | After E-QA-01 |
| E-QA-05 | M-MOBILE-TEST | Mobile/Tablet Testing | 3 | QA | After E-QA-01 |
| E-QA-06 | M-IMAGE-OPT | Image Optimization | 3 | QA | Parallel |
| E-QA-07 | — | Content Review (native Dutch speaker) | 3 | Content | Parallel |

### 14.7 Epic 7: Client Acceptance (Week 4, Day 5)

| Story ID | Module ID | Story Name | Points | Owner |
|---|---|---|---|---|
| E-ACCEPT-01 | M-CLIENT-REVIEW | Client Walkthrough on Staging | 5 | Lead Dev |
| E-ACCEPT-02 | — | Final Fixes from Client Feedback | 3 | Dev |
| E-ACCEPT-03 | — | Staging Acceptance Sign-Off | 2 | Client + Lead Dev |
| E-ACCEPT-04 | — | Sprint 6 Closure Report | 2 | Lead Dev |

---

## 15. Risk Matrix

### 15.1 Risk Register (Sprint 6 Specific)

| ID | Risk | Probability | Impact | Risk Score (P×I) | Mitigation | Owner | Trigger |
|---|---|---|---|---|---|---|---|
| R-S6-01 | Client data (MI-01..25) not provided | High (60%) | High (4) | **24** | Graceful degradation for all MI-dependent modules. Conditional display. Placeholder strategy documented. | Client + Lead Dev | Day 5: MI data still missing |
| R-S6-02 | Contact form email delivery fails | Medium (30%) | Critical (5) | **15** | SMTP verified in Sprint 5. Test daily during Week 1. Fallback: hosting-provided SMTP. | Dev | Form test: email not delivered in 2 min |
| R-S6-03 | WooCommerce plugin conflict | Medium (30%) | High (4) | **12** | Identical staging stack. Test E2E flow before gate. Rollback to clean state. | Dev | WC admin page throws error |
| R-S6-04 | Gravity Forms license expired or missing | Medium (30%) | High (4) | **12** | Verify license active before Week 1. Fallback: use WPForms Lite temporarily. | Lead Dev | GF settings inaccessible |
| R-S6-05 | Performance regression from plugin load | Medium (30%) | Medium (3) | **9** | Weekly PSI check. WP Rocket + Redis + Cloudflare configured in Sprint 5. Defer non-critical plugins. | Dev | PSI mobile drops below 85 |
| R-S6-06 | Content writer unavailable for Dutch content | Medium (30%) | Medium (3) | **9** | Developer can produce initial Dutch content (with Dutch speaker review later). AI-assisted draft + human review. | Lead Dev | Content backlog exceeds 5 pages |
| R-S6-07 | Client review delayed beyond Day 20 | Medium (30%) | High (4) | **12** | Book client review slot at Sprint start. Send staging link + test credentials Day 18. Daily reminders Day 18-20. | Lead Dev | No client response by Day 18 |
| R-S6-08 | Legacy domain (hds-onderhoudsdiensten.nl) inaccessible | Medium (30%) | Low (2) | **6** | Screen-scrape PDFs from current live site if still up. Contact client for domain access credentials Day 1. | Dev + Client | PDF download attempts fail |
| R-S6-09 | Rank Math Pro / Yoast Premium license missing | Low (20%) | Medium (3) | **6** | Free version covers 90% of needs. Schema, sitemaps, redirects all in free tier. Premium adds redirect manager only. | Dev | Premium features locked |
| R-S6-10 | Complianz configuration complexity | Low (20%) | Medium (3) | **6** | Use Complianz wizard (automated setup). Test on staging before production. Documentation available. | Dev | Cookie banner not appearing |
| R-S6-11 | Staging environment downtime | Low (20%) | High (4) | **8** | Managed hosting with 99.9% SLA. Local development environment available as backup. | Hosting | Staging returns 5xx |
| R-S6-12 | Scope creep (new features requested mid-sprint) | Medium (30%) | Medium (3) | **9** | Frozen documentation. All change requests go to post-launch backlog. Sprint scope is locked. | Lead Dev | Client requests new feature |
| R-S6-13 | Accessibility remediation overruns | Low (20%) | Medium (3) | **6** | Foundation theme already WCAG-compliant at code level (Sprint 5). Content additions unlikely to introduce violations. | Dev | axe finds >5 critical issues |
| R-S6-14 | WooCommerce payment gateway not available | Medium (30%) | High (4) | **12** | Mollie test mode available immediately. Production activation requires client dashboard access (MI-15). | Client + Dev | Mollie dashboard inaccessible |
| R-S6-15 | Single developer bottleneck | Medium (30%) | Medium (3) | **9** | Parallel execution plan assumes 2 developers. If 1 developer, extend timeline by 50% (3 extra weeks). | Lead Dev | Only 1 developer available |

### 15.2 Risk Score Matrix

```
Impact
  5 (Critical)   │  R-S6-02 (15)
  4 (High)       │  R-S6-01 (24)  R-S6-03 (12)  R-S6-07 (12)
                 │  R-S6-04 (12)  R-S6-11 (8)   R-S6-14 (12)
  3 (Medium)     │  R-S6-05 (9)   R-S6-06 (9)   R-S6-09 (6)
                 │  R-S6-10 (6)   R-S6-12 (9)   R-S6-13 (6)
                 │  R-S6-15 (9)
  2 (Low)        │  R-S6-08 (6)
  1 (Negligible) │
                 └────────────────────────────────────────────────
                   1 (Very Low)  2 (Low)   3 (Medium)   4 (High)   5 (Very High)
                                              Probability
```

### 15.3 Top 5 Risks (by Risk Score)

| Rank | Risk | Score | Status |
|---|---|---|---|
| 1 | R-S6-01: Client data not provided | 24 | **ACTIVE** — monitor daily, escalate Day 5 |
| 2 | R-S6-02: Contact form email delivery fails | 15 | **MONITORED** — SMTP verified in Sprint 5 |
| 3 | R-S6-03: WooCommerce plugin conflict | 12 | **MONITORED** — test on staging |
| 4 | R-S6-04: Gravity Forms license missing | 12 | **MONITORED** — verify Day 1 |
| 5 | R-S6-07: Client review delayed | 12 | **MONITORED** — book slot at Sprint start |

---

## 16. Rollback Strategy

### 16.1 Rollback Principles

1. **Every deployment is reversible.** No change goes to staging that cannot be undone.
2. **Rollback is faster than fix-forward.** If a module breaks staging, revert immediately and fix in isolation.
3. **Rollback is tested.** The restore-from-backup procedure is verified before any risky change.

### 16.2 Rollback Levels

| Level | Scope | Trigger | Rollback Action | RTO |
|---|---|---|---|---|
| **L1: Code Rollback** | Single module | Module merge causes regression on staging | `git revert <squash-commit>` on develop, push, auto-deploy to staging | < 10 min |
| **L2: Feature Rollback** | Multiple related modules | Feature (e.g., entire WooCommerce config) is broken | Revert all module commits in feature scope | < 30 min |
| **L3: Staging Reset** | Entire staging environment | Staging corrupted or unrecoverable | Restore latest daily backup of staging | < 1 hour |
| **L4: Production Rollback** | Production environment | Critical issue within 24h of launch (Sprint 7) | Restore pre-deployment backup to production; revert DNS if needed | < 2 hours |

### 16.3 L1: Code Rollback Procedure

```bash
# 1. Identify the squash commit that introduced the regression
git log --oneline -20

# 2. Revert the commit on develop
git checkout develop
git revert <commit-hash> --no-edit

# 3. Push to trigger auto-deploy
git push origin develop

# 4. Verify staging is restored to working state
# Open staging.helderduidelijkschoon.nl and confirm fix

# 5. Fix the issue in a new feature branch (do NOT re-merge the broken code)
git checkout -b fix/<issue-description>
```

### 16.4 L3: Staging Reset Procedure

```bash
# 1. Stop auto-deploy to prevent new code reaching staging
# (Disable DeployHQ auto-deploy or pause GitHub webhook)

# 2. Restore latest daily backup to staging environment
# Use hosting provider's backup restore tool
# OR: BlogVault/UpdraftPlus restore function

# 3. Verify restore: homepage loads, admin login works, forms submit

# 4. Re-apply working commits since last backup
# git log --oneline --since="<backup-timestamp>"
# Cherry-pick each known-good commit

# 5. Re-enable auto-deploy
```

### 16.5 Rollback Decision Matrix

| Situation | Decision | Action |
|---|---|---|
| Merge causes lint/build failure | Block PR | CI catches this — PR cannot be merged |
| Merge causes staging 500 error | ROLLBACK | L1 rollback immediately |
| Merge causes contact form to break | ROLLBACK | L1 rollback immediately |
| Merge causes visual regression on 1 page | ROLLBACK | L1 rollback + fix in isolation |
| Merge causes performance degradation (PSI < 85) | ROLLBACK | L1 rollback + investigate |
| Staging database corrupted | ROLLBACK | L3 staging reset |
| Plugin update causes conflict | ROLLBACK | L1 rollback plugin update. Test update in isolation. |
| Client rejects a page design | FIX FORWARD | Do NOT rollback. Fix in new feature branch. |

### 16.6 Backup Verification Schedule

| When | What | Verify |
|---|---|---|
| End of every day | Daily backup completed | Backup plugin log shows success |
| Every Friday | Restore backup to test environment | Homepage + contact form + admin login work |
| Before any major plugin update | Pre-update backup | Snapshot saved |
| Before production deployment (Sprint 7) | Full backup + test restore | Entire site functional on test environment |

---

## 17. Sprint Checkpoints

### 17.1 Checkpoint Cadence

| Checkpoint | When | Type | Participants |
|---|---|---|---|
| CP-0 | Sprint 6 Kickoff (Day 0) | Planning | Lead Dev + Team |
| CP-1 | End of Week 1 (Day 5) | Gate QG-1 | Lead Dev |
| CP-2 | Middle of Week 2 (Day 8) | Progress + Risk Review | Lead Dev + Team |
| CP-3 | End of Week 2 (Day 10) | Gate QG-2 | Lead Dev |
| CP-4 | Middle of Week 3 (Day 13) | Progress + Risk Review | Lead Dev + Team |
| CP-5 | End of Week 3 (Day 15) | Gate QG-3 | Lead Dev |
| CP-6 | Middle of Week 4 (Day 18) | QA Progress Review | Lead Dev + QA |
| CP-7 | End of Week 4 (Day 20) | Gate QG-4 — Staging Acceptance | Lead Dev + Client |
| CP-8 | Day 20 + 1 | Sprint 6 Retrospective | Full Team |

### 17.2 Checkpoint Details

#### CP-0: Sprint 6 Kickoff (Day 0)

| Agenda Item | Duration | Owner |
|---|---|---|
| Review Sprint 5.6 Readiness Closure Report | 10 min | Lead Dev |
| Confirm GO decision (92/100) | 5 min | Lead Dev |
| Walk through Sprint 6 Development Plan | 20 min | Lead Dev |
| Assign Week 1 modules to developers | 10 min | Lead Dev |
| Verify all tools: Git, npm, lint, build working | 10 min | Dev |
| Verify staging environment: accessible, up-to-date with develop | 10 min | Dev |
| Confirm client data availability (MI-01..25) | 10 min | Lead Dev + Client |
| Set up communication channel (daily standup) | 5 min | Lead Dev |

#### CP-1: Gate QG-1 — Core Pages Live (Day 5)

| Agenda Item | Duration | Owner |
|---|---|---|
| Run QG-1 checklist (12 items) | 30 min | Lead Dev |
| Review any failed checks | 15 min | Lead Dev |
| GO / NO-GO decision | 5 min | Lead Dev |
| If GO: Plan Week 2 assignments | 15 min | Lead Dev |
| If NO-GO: Identify blocking issues, assign fixes | 15 min | Lead Dev |

#### CP-2: Mid-Week 2 Check (Day 8)

| Agenda Item | Duration | Owner |
|---|---|---|
| Module completion status (Epics 2-3) | 10 min | Dev |
| Risk review: R-S6-01 (client data), R-S6-03 (WC conflict) | 10 min | Lead Dev |
| On-track for QG-2 on Day 10? | 5 min | Lead Dev |
| Adjust assignments if behind schedule | 10 min | Lead Dev |

#### CP-3: Gate QG-2 — All Pages + WC (Day 10)

| Agenda Item | Duration | Owner |
|---|---|---|
| Run QG-2 checklist (14 items) | 30 min | Lead Dev |
| Review any failed checks | 15 min | Lead Dev |
| GO / NO-GO decision | 5 min | Lead Dev |
| If GO: Plan Week 3 assignments | 15 min | Lead Dev |

#### CP-4: Mid-Week 3 Check (Day 13)

| Agenda Item | Duration | Owner |
|---|---|---|
| SEO metadata progress (32 pages) | 10 min | Dev |
| Schema implementation status | 5 min | Dev |
| Complianz + Wordfence progress | 5 min | Dev |
| On-track for QG-3 on Day 15? | 5 min | Lead Dev |

#### CP-5: Gate QG-3 — SEO + Compliance (Day 15)

| Agenda Item | Duration | Owner |
|---|---|---|
| Run QG-3 checklist (23 items) | 45 min | Lead Dev |
| Review any failed checks | 15 min | Lead Dev |
| GO / NO-GO decision | 5 min | Lead Dev |
| If GO: Plan Week 4 QA assignments | 15 min | Lead Dev |

#### CP-6: Mid-Week 4 QA Check (Day 18)

| Agenda Item | Duration | Owner |
|---|---|---|
| Performance: PSI scores, remediation status | 10 min | QA |
| Accessibility: axe findings, remediation status | 10 min | QA |
| Cross-browser/mobile: issues found | 10 min | QA |
| On-track for QG-4 on Day 20? | 5 min | Lead Dev |
| Client review: confirmed for Day 20? | 5 min | Lead Dev |

#### CP-7: Gate QG-4 — Staging Acceptance (Day 20)

| Agenda Item | Duration | Owner |
|---|---|---|
| Run QG-4 checklist (25 items) | 60 min | Lead Dev |
| Client walkthrough of staging site | 60 min | Lead Dev + Client |
| Client feedback collection | 30 min | Client |
| Final fixes (if applicable) | 90 min | Dev |
| GO / NO-GO decision | 15 min | Lead Dev + Client |
| If GO: Sign-off. Sprint 7 may begin. | — | — |
| If NO-GO: Document remaining issues. Plan remediation sprint. | 30 min | Lead Dev |

#### CP-8: Sprint 6 Retrospective (Day 20 + 1)

| Agenda Item | Duration | Owner |
|---|---|---|
| What went well? | 15 min | All |
| What went wrong? | 15 min | All |
| What should we do differently? | 15 min | All |
| Action items for Sprint 7 | 15 min | Lead Dev |

### 17.3 Checkpoint Escalation

If any Gate checkpoint fails (CP-1, CP-3, CP-5, CP-7):

1. **Document the failure:** Which checks failed? Why?
2. **Identify blockers:** What must be fixed to pass the gate?
3. **Assign owner + deadline:** Who fixes it, by when?
4. **Re-schedule the gate:** Gate re-runs after fixes applied.
5. **Escalate to Solution Architect if:** Gate fails twice in a row OR blocking issue is architectural.

---

## 18. Acceptance Criteria for Every Module

### 18.1 Module AC Index

| Module ID | Module Name | Acceptance Criteria Summary |
|---|---|---|
| M-TPL-SERVICE | Service Page Template | Selectable in Page editor. Hero + Content + Cross-Sell + CTA sections render. Custom fields save/display. Breadcrumbs visible. Responsive. |
| M-PAGE-HOME | Home Page (P01) | HTTP 200. All 8 content blocks present. Service Card Grid links correct. CTA → /offerte-aanvragen/. Empty states hidden. >= 300 words Dutch. |
| M-PAGE-GLAS | Glasbewassing (P02) | HTTP 200. >= 300 words. H2: Veiligheid, Samenwerking, Technieken. Cross-links: Gevelreiniging, Reguliere Schoonmaak, Oplevering. CTA present. |
| M-PAGE-GEVEL | Gevelreiniging (P03) | HTTP 200. >= 300 words. H1 = "Gevelreiniging" (NOT "Gevelonderhoud"). Bullet list of 5+ services. Cross-links: Glasbewassing, Industriele. |
| M-PAGE-REGULIER | Reguliere Schoonmaak (P04) | **CRITICAL.** HTTP 200 (was 404). >= 300 words. Covers: audience, frequency, process, quality. Linked from nav, homepage, footer. |
| M-PAGE-VLOER | Vloeronderhoud (P05) | HTTP 200. >= 300 words. 7 floor services bullet list. Holiday scheduling mention. Cross-links present. |
| M-PAGE-VVE | VVE Service (P06) | HTTP 200. >= 300 words. Services: stairwells, halls, garages, maintenance, outdoor. VvE Belang link. Cross-links present. |
| M-PAGE-OPLEVERING | Oplevering Schoonmaak (P07) | HTTP 200. >= 300 words. "0-beurt" concept. 5 task types bullet list. Cross-links present. |
| M-PAGE-INDUSTRIEEL | Industriele Schoonmaak (P08) | HTTP 200. >= 300 words (was ~60). Bullet list: leidingen, vloeren, stellingen, machines, vet/olie. Safety section. |
| M-TPL-CONTACT | Contact Page Template | Two-column layout renders (60/40). Contact Info Block renders with conditional address/KVK/BTW/hours. Responsive. |
| M-FORM-CONTACT | Contact Form (GF-1) | All 9 fields functional. reCAPTCHA v3 active. Privacy checkbox unchecked, links to /privacyverklaring/. Submit → redirect to /bedankt/?type=contact. Confirmation + notification emails delivered < 2 min. Entry in DB. |
| M-PAGE-CONTACT | Contact Page (P16) | HTTP 200 (was 500). Form + Contact Info render. Phone clickable tel:. Email clickable mailto:. |
| M-FORM-QUOTE | Quote Form (GF-2) | All 12 fields + file upload. Postcode Dutch format validation (NNNN AA). File upload: PDF/JPG/PNG/DOCX max 5MB. Submit → /bedankt/?type=offerte. Confirmation email with summary. Notification with file download link. |
| M-PAGE-QUOTE | Offerte Aanvragen (P17) | HTTP 200. Form renders with all fields. File upload field present. |
| M-PAGE-BEDANKT | Bedankt (P32) | HTTP 200. Dynamic message based on ?type=. Phone fallback. Noindex meta. Excluded from sitemap. |
| M-TPL-ABOUT | About Page Template | Content with image sections render. Responsive. |
| M-PAGE-OVERHDS | Over HDS (P11) | HTTP 200. >= 500 words. Values + USPs preserved. OSB link (if MI-25). |
| M-PAGE-KWALITEIT | Kwaliteit & Veiligheid (P12) | HTTP 200. >= 300 words. H2: Kwaliteit, Veiligheid, MVO. Cert logos (if provided). |
| M-TPL-LANDING | Category Landing Template | Hero + Intro + Service Card Grid + CTA. Responsive. |
| M-PAGE-GLASGEVEL | Glas & Gevel (P09) | HTTP 200. >= 500 words. 2 service cards (Glasbewassing + Gevelreiniging). Links correct. |
| M-PAGE-SCHOONMAAK | Schoonmaakdiensten (P10) | HTTP 200. >= 500 words. 5 service cards (all sub-services). Links correct. |
| M-CPT-TESTIMONIAL | Testimonial CPT | Registered with public=false. No URL conflict with /referenties/. Custom fields: author, company, rating, service. |
| M-PAGE-REFERENTIES | Referenties (P13) | HTTP 200. >= 300 words. Client logos (conditional). Testimonials (conditional). Empty state: "Wij horen graag uw ervaring!" |
| M-CPT-VACANCY | Vacancy CPT | Registered with public=true, rewrite=vacatures. Fields: hours, location, start, email, deadline, active toggle. |
| M-FORM-VACATURE | Vacature Form (GF-3) | 5 fields + CV upload. CV: PDF/DOCX max 5MB. Privacy checkbox. Submit → email notification. |
| M-PAGE-VACATURES | Vacatures (P14) | HTTP 200. **ZERO** JPG images. All text in HTML. Toggle-to-expand cards. Application form per vacancy. Screen-reader accessible. |
| M-PAGE-DOWNLOADS | Downloads (P15) | HTTP 200. >= 150 words. PDFs accessible from primary domain. Download cards: name, description, type icon, file size. |
| M-TPL-LEGAL | Legal Page Template | H1 + Content + Last Updated date. Responsive. |
| M-PAGE-PRIVACY | Privacyverklaring (P19) | HTTP 200. Full privacy policy content. Linked from footer + all form consent checkboxes. Ready for legal review. |
| M-PAGE-COOKIE | Cookiebeleid (P20) | HTTP 200. Page shell exists. Complianz populates in Week 3. Linked from footer + cookie banner. |
| M-PAGE-VOORWAARDEN | Algemene Voorwaarden (P21) | HTTP 200. Terms content (if MI-16). Placeholder if not. Linked from footer + WC checkout. |
| M-PAGE-DISCLAIMER | Disclaimer (P22) | HTTP 200. >= 200 words. Liability, IP, external links, applicable law. Linked from footer. |
| M-PAGE-FAQ | FAQ (P18) | HTTP 200. 10-15 items with accordion. FAQPage schema auto-generated. >= 300 words combined. Keyboard-navigable. |
| M-WC-CONFIG | WC Core Settings | Shop/cart/checkout/account pages assigned. EUR, Dutch separators, excl. BTW, 21% tax. Guest checkout enabled. |
| M-WC-IMPORT | WC Product Import | 14 products visible at /winkel/. Images display. Prices correct. Stock status correct. |
| M-WC-PAYMENT | Mollie Payment | Payment gateway installed. Test mode working. Webhook configured. iDEAL test payment successful. |
| M-WC-SHIPPING | Shipping Config | Zone Nederland. Classes: Klein/Groot pakket. Rates display at checkout. |
| M-WC-EMAILS | WC Email Notifications | 10 notifications enabled. Branded (logo). Dutch. From info@... via SMTP. Test email delivered. |
| M-WC-FLOW | WC E2E Test | Browse→Product→Cart→Checkout→Payment→Confirmation→Email. Guest + logged-in. Mobile checkout usable. |
| M-PAGE-LUCHT | Luchtreiniging (P23) | HTTP 200. >= 300 words. Product highlights with shop links. CTA → /winkel/. Cross-links from service pages. |
| M-PAGE-WINKEL | Winkel intro (P24) | HTTP 200. >= 100 words intro text. Products visible. |
| M-NAV-MENUS | Navigation Menus | Primary nav: DIENSTEN v, OVER HDS v, LUCHTREINIGING v, CONTACT. Footer: 5 columns. All pages reachable. Mobile: hamburger + accordion. |
| M-SEO-CONFIG | Rank Math Pro | Plugin configured. XML sitemaps enabled. robots.txt managed. Open Graph + Twitter Cards enabled. |
| M-SEO-META | Meta Data (32 pages) | 32 unique titles (50-60 chars). 32 unique meta descriptions (150-160 chars). Zero empty. Zero duplicate. OG tags complete. |
| M-SCHEMA-LOCAL | LocalBusiness Schema | Valid JSON-LD. On Home, Contact, Over HDS. name, telephone, email. Address/geo/hours if MI-01..04 provided. |
| M-SCHEMA-SERVICE | Service Schema (7) | Valid JSON-LD on P02-P08. name, description, provider, areaServed, serviceType. 7/7 valid via Rich Results Test. |
| M-SCHEMA-FAQ | FAQPage Schema | Valid JSON-LD on P18. Auto-generated from FAQ Block. |
| M-SCHEMA-BREADCRUMB | BreadcrumbList | Valid on all inner pages. Visible breadcrumbs + schema. |
| M-REDIRECTS | 301 Redirects | All 7 redirection rules active. All return 301 (not 302). Zero redirect chains. |
| M-SITEMAP | XML Sitemap | /sitemap_index.xml returns 200. Valid XML. Zero attachment pages. Zero noindex pages. |
| M-ROBOTS | robots.txt | Returns 200. Correct disallow rules. Sitemap URL present. |
| M-GA4-GTM | GA4 + GTM | GA4 real-time shows page views. GTM snippet in head. Consent Mode v2 active. |
| M-CONV-EVENTS | Conversion Events | phone_click, email_click, form_submission, quote_request, add_to_cart, purchase all fire in GA4. |
| M-GSC | GSC Verification | Domain property verified. |
| M-COMPLIANZ | Cookie Consent | Banner appears on fresh browser. No GA/Facebook cookies before consent (DevTools verified). Consent logged (Complianz scan). Three options: Accepteren, Weigeren, Instellingen. |
| M-GDPR-FORMS | GDPR Form Consent | All 3 forms: privacy checkbox unchecked. All checkboxes link to /privacyverklaring/. |
| M-WORDFENCE | Wordfence Security | Firewall active. 2FA on all admin accounts. Brute force protection (max 3 attempts). Malware scan daily. |
| M-SECURITY-HARDEN | Security Hardening | XML-RPC returns 403. Custom login URL active. DISALLOW_FILE_EDIT=true. /wp-json/wp/v2/users blocked. |
| M-UPTIME | UptimeRobot | Monitor active for staging URL. Alerts configured (email). |
| M-PERF-TEST | Performance Test | PSI mobile >= 90 on all templates. PSI desktop >= 95. LCP < 2.5s. CLS < 0.1. |
| M-PERF-FIX | Performance Fix | All PSI recommendations addressed. No render-blocking JS. Images optimized. Fonts self-hosted. |
| M-A11Y-AUDIT | Accessibility Audit | axe: zero critical + serious. WAVE: zero errors. Lighthouse: 100. Keyboard: all reachable. Screen reader: forms usable. |
| M-A11Y-FIX | Accessibility Fix | All audit findings resolved. Re-audit confirms zero issues. |
| M-CROSS-BROWSER | Cross-Browser | Chrome, Firefox, Safari, Edge: consistent rendering. All functions work. |
| M-MOBILE-TEST | Mobile/Tablet | iPhone, Android, iPad: responsive. No horizontal scroll. Touch >= 44px. Forms usable. |
| M-LINK-AUDIT | Link Audit | Screaming Frog: zero broken internal links. Zero orphan pages. |
| M-IMAGE-OPT | Image Optimization | All images WebP. Alt text in Dutch. Filenames: lowercase-hyphens. < 150KB. Explicit dimensions. |
| M-CLIENT-REVIEW | Client Acceptance | Client walkthrough completed. Feedback documented. Fixes applied (if any). Written sign-off obtained. |

### 18.2 Critical Module AC (Must Pass First)

| Priority | Module | Why Critical |
|---|---|---|
| P0 | M-PAGE-REGULIER | Primary service line. Was 404. Highest business impact fix. |
| P0 | M-FORM-CONTACT | Primary conversion path. Was 500. Blocks all online lead capture. |
| P0 | M-PAGE-PRIVACY | Legal requirement. Cannot launch without it. |
| P0 | M-SCHEMA-LOCAL | SEO foundation. Required for local search ranking. |
| P0 | M-WC-FLOW | Revenue path. Must work end-to-end before acceptance. |

---

## 19. Deliverables of Every Epic

### 19.1 Epic 1: Core Pages & Conversion

| Deliverable ID | Deliverable | Format | Owner | Acceptance |
|---|---|---|---|---|
| DEL-EP1-01 | Home page (P01) on staging, 300+ words, all 8 blocks | Live page | Dev A | QG-1 |
| DEL-EP1-02 | Service Page Template (page-service.php) finalized | PHP file | Dev A | QG-1 |
| DEL-EP1-03 | 7 service pages (P02-P08) on staging, all >= 300 words | Live pages | Dev B | QG-1 |
| DEL-EP1-04 | Category Landing Template (page-category-landing.php) | PHP file | Dev B | QG-1 |
| DEL-EP1-05 | 2 category landings (P09, P10) on staging, both >= 500 words | Live pages | Dev B | QG-1 |
| DEL-EP1-06 | Contact Page Template (page-contact.php) | PHP file | Dev A | QG-1 |
| DEL-EP1-07 | Gravity Forms Contact Form (GF-1) — configured + tested | GF form | Dev A | QG-1 |
| DEL-EP1-08 | Contact page (P16) on staging — form working | Live page | Dev A | QG-1 |
| DEL-EP1-09 | Gravity Forms Quote Form (GF-2) — configured + tested | GF form | Dev A | QG-1 |
| DEL-EP1-10 | Offerte Aanvragen page (P17) on staging | Live page | Dev A | QG-1 |
| DEL-EP1-11 | Bedankt page (P32) on staging | Live page | Dev A | QG-1 |

### 19.2 Epic 2: Supporting Pages

| Deliverable ID | Deliverable | Format | Owner | Acceptance |
|---|---|---|---|---|
| DEL-EP2-01 | About Page Template (page-about.php) | PHP file | Dev A | QG-2 |
| DEL-EP2-02 | Over HDS page (P11) on staging, >= 500 words | Live page | Dev A | QG-2 |
| DEL-EP2-03 | Kwaliteit & Veiligheid page (P12) on staging, >= 300 words | Live page | Dev A | QG-2 |
| DEL-EP2-04 | hds_testimonial CPT registered (public=false) | PHP code | Dev B | QG-2 |
| DEL-EP2-05 | Referenties page (P13) on staging with conditional content | Live page | Dev B | QG-2 |
| DEL-EP2-06 | hds_vacancy CPT registered with custom fields | PHP code | Dev B | QG-2 |
| DEL-EP2-07 | Gravity Forms Vacature Form (GF-3) — configured + tested | GF form | Dev B | QG-2 |
| DEL-EP2-08 | Vacatures page (P14) on staging — all text in HTML, zero JPGs | Live page | Dev B | QG-2 |
| DEL-EP2-09 | Legal Page Template (page-legal.php) | PHP file | Dev A | QG-2 |
| DEL-EP2-10 | Privacyverklaring (P19) — content drafted | Live page | Dev A | QG-2 |
| DEL-EP2-11 | Cookiebeleid (P20) — page shell | Live page | Dev A | QG-2 |
| DEL-EP2-12 | Algemene Voorwaarden (P21) — content or placeholder | Live page | Dev A | QG-2 |
| DEL-EP2-13 | Disclaimer (P22) — content published | Live page | Dev A | QG-2 |
| DEL-EP2-14 | Downloads page (P15) with PDFs on primary domain | Live page | Dev B | QG-2 |
| DEL-EP2-15 | FAQ page (P18) with 10-15 items + accordion | Live page | Dev B | QG-2 |
| DEL-EP2-16 | All navigation menus (primary + footer) wired | WP Menus | Both | QG-2 |

### 19.3 Epic 3: WooCommerce

| Deliverable ID | Deliverable | Format | Owner | Acceptance |
|---|---|---|---|---|
| DEL-EP3-01 | WooCommerce core settings configured | WC settings | Dev A | QG-2 |
| DEL-EP3-02 | 14 Airfixr products imported to staging | WC products | Dev A | QG-2 |
| DEL-EP3-03 | Mollie payment gateway configured (test mode) | WC payment | Dev A | QG-2 |
| DEL-EP3-04 | Shipping zones + classes + rates configured | WC shipping | Dev A | QG-2 |
| DEL-EP3-05 | WooCommerce email notifications branded + tested | WC emails | Dev A | QG-2 |
| DEL-EP3-06 | Luchtreiniging landing page (P23) on staging | Live page | Dev B | QG-2 |
| DEL-EP3-07 | Winkel intro text (P24) on staging | Live page | Dev A | QG-2 |
| DEL-EP3-08 | WC purchase flow E2E test report | Document | Dev A | QG-2 |

### 19.4 Epic 4: SEO & Analytics

| Deliverable ID | Deliverable | Format | Owner | Acceptance |
|---|---|---|---|---|
| DEL-EP4-01 | Rank Math Pro fully configured | Plugin config | Dev A | QG-3 |
| DEL-EP4-02 | 32 unique meta titles (spreadsheet + applied) | Rank Math fields | Both | QG-3 |
| DEL-EP4-03 | 32 unique meta descriptions (spreadsheet + applied) | Rank Math fields | Both | QG-3 |
| DEL-EP4-04 | LocalBusiness JSON-LD schema — validated | Code + Rich Results | Dev A | QG-3 |
| DEL-EP4-05 | Service schema on all 7 service pages — validated | Code + Rich Results | Dev A | QG-3 |
| DEL-EP4-06 | FAQPage schema — auto-generated + validated | Rich Results Test | Dev A | QG-3 |
| DEL-EP4-07 | BreadcrumbList schema on all inner pages | Code + manual | Dev A | QG-3 |
| DEL-EP4-08 | All 301 redirects configured + tested | Rank Math redirects | Dev A | QG-3 |
| DEL-EP4-09 | XML Sitemap — valid, zero errors | /sitemap_index.xml | Dev A | QG-3 |
| DEL-EP4-10 | robots.txt — correct rules | /robots.txt | Dev A | QG-3 |
| DEL-EP4-11 | GA4 property active + GTM container configured | GA4 + GTM dashboards | Dev A | QG-3 |
| DEL-EP4-12 | All 7 conversion events firing in GA4 | GA4 real-time | Dev A | QG-3 |
| DEL-EP4-13 | Google Search Console verified | GSC dashboard | Dev A | QG-3 |

### 19.5 Epic 5: Compliance & Security

| Deliverable ID | Deliverable | Format | Owner | Acceptance |
|---|---|---|---|---|
| DEL-EP5-01 | Complianz cookie consent configured + tested | Plugin config | Dev A | QG-3 |
| DEL-EP5-02 | Consent logging verified | Complianz log | Dev A | QG-3 |
| DEL-EP5-03 | GDPR form consent: all checkboxes verified | Manual check | Dev A | QG-3 |
| DEL-EP5-04 | Wordfence firewall + 2FA + brute force active | Plugin config | Dev A | QG-3 |
| DEL-EP5-05 | XML-RPC disabled (verified 403) | Manual test | Dev A | QG-3 |
| DEL-EP5-06 | Custom login URL active | Manual test | Dev A | QG-3 |
| DEL-EP5-07 | UptimeRobot monitoring active | Dashboard | Dev A | QG-3 |
| DEL-EP5-08 | Security hardening verification report | Document | Dev A | QG-3 |

### 19.6 Epic 6: Testing & QA

| Deliverable ID | Deliverable | Format | Owner | Acceptance |
|---|---|---|---|---|
| DEL-EP6-01 | Functional QA report (Screaming Frog + forms + WC) | Document | QA | QG-4 |
| DEL-EP6-02 | Performance test report (PSI + WebPageTest + GTmetrix) | Document | QA | QG-4 |
| DEL-EP6-03 | Accessibility audit report (axe + WAVE + Lighthouse) | Document | QA | QG-4 |
| DEL-EP6-04 | Cross-browser test report | Document | QA | QG-4 |
| DEL-EP6-05 | Mobile/tablet test report | Document | QA | QG-4 |
| DEL-EP6-06 | Image optimization report | Document | QA | QG-4 |
| DEL-EP6-07 | All QA findings resolved or documented as known issues | Issue tracker | Dev + QA | QG-4 |

### 19.7 Epic 7: Client Acceptance

| Deliverable ID | Deliverable | Format | Owner | Acceptance |
|---|---|---|---|---|
| DEL-EP7-01 | Client walkthrough completed | Meeting | Lead Dev | QG-4 |
| DEL-EP7-02 | Client feedback documented | Document | Lead Dev | QG-4 |
| DEL-EP7-03 | All client-requested fixes applied (or deferred to Sprint 7) | Staging | Dev | QG-4 |
| DEL-EP7-04 | Written client sign-off on staging acceptance | Email or document | Client | QG-4 |
| DEL-EP7-05 | Sprint 6 Closure Report | Document | Lead Dev | Sprint 7 kickoff |

---

## 20. Success Metrics

### 20.1 Sprint-Level Success Metrics

| # | Metric | Target | Measurement | Frequency |
|---|---|---|---|---|
| SM-01 | Sprint completion rate | 100% of 68 modules completed | Module checklist (DoD) | Weekly |
| SM-02 | Gate pass rate (first attempt) | 4/4 gates passed first attempt | Gate checklists | Per gate |
| SM-03 | On-time delivery | Day 20 staging acceptance | Calendar check | End of Sprint |
| SM-04 | Lint compliance | 0 JS errors, 0 CSS errors on `develop` | CI pipeline | Every commit |
| SM-05 | Build pipeline success | 100% build success rate | CI pipeline | Every commit |
| SM-06 | Code review turnaround | < 4 hours per PR | GitHub PR timeline | Per PR |
| SM-07 | Client feedback cycle | < 24 hours from request to response | Email/chat timeline | Per request |
| SM-08 | Rollback count | 0 rollbacks required | Incident log | Sprint |
| SM-09 | Critical bugs found in QA | < 5 critical bugs | QA report (Week 4) | End of Sprint |
| SM-10 | Zero-hardcoded-values | 0 hardcoded phone/email/address values | Code audit | Weekly |

### 20.2 Page-Level Success Metrics

| # | Metric | Target | Tool |
|---|---|---|---|
| SM-11 | All pages return HTTP 200 | 32/32 pages | Screaming Frog |
| SM-12 | Service pages meet minimum word count | 7/7 pages >= 300 words | Manual count |
| SM-13 | Category landings meet minimum word count | 2/2 pages >= 500 words | Manual count |
| SM-14 | Contact page working (was 500) | HTTP 200 + form submits | Manual test |
| SM-15 | Reguliere Schoonmaak working (was 404) | HTTP 200 + >= 300 words | Manual test |
| SM-16 | Zero broken internal links | 0 broken | Screaming Frog |
| SM-17 | Zero orphan pages | 0 orphan | Screaming Frog |
| SM-18 | All forms submit and deliver email | 3/3 forms working | Manual test |

### 20.3 SEO Success Metrics

| # | Metric | Target | Tool |
|---|---|---|---|
| SM-19 | Unique meta titles | 32/32 unique, 50-60 chars | Screaming Frog |
| SM-20 | Unique meta descriptions | 32/32 unique, 150-160 chars | Screaming Frog |
| SM-21 | Zero empty meta descriptions | 0 empty | Screaming Frog |
| SM-22 | Schema validated | 9/9 schema types valid | Google Rich Results Test |
| SM-23 | XML Sitemap working | /sitemap_index.xml = 200 + valid XML | Browser + validator |
| SM-24 | robots.txt correct | 200 + correct rules | Browser |
| SM-25 | 301 redirects functional | 7/7 rules return 301 | httpstatus.io |
| SM-26 | Zero redirect chains | 0 chains | Screaming Frog |
| SM-27 | Open Graph tags complete | 32/32 pages | Screaming Frog |
| SM-28 | Self-referencing canonicals | 32/32 pages | Screaming Frog |

### 20.4 Performance Success Metrics

| # | Metric | Target | Tool |
|---|---|---|---|
| SM-29 | PSI Mobile Score | >= 90 (all templates) | PageSpeed Insights |
| SM-30 | PSI Desktop Score | >= 95 (all templates) | PageSpeed Insights |
| SM-31 | Largest Contentful Paint (LCP) | < 2.5 seconds | PSI / WebPageTest |
| SM-32 | Cumulative Layout Shift (CLS) | < 0.1 | PSI |
| SM-33 | Time to First Byte (TTFB) | < 600ms | WebPageTest |
| SM-34 | Total Page Weight (Mobile) | < 1.5 MB | WebPageTest |
| SM-35 | Speed Index | < 3.4 seconds | Lighthouse |

### 20.5 Accessibility Success Metrics

| # | Metric | Target | Tool |
|---|---|---|---|
| SM-36 | axe DevTools critical issues | 0 | axe DevTools |
| SM-37 | axe DevTools serious issues | 0 | axe DevTools |
| SM-38 | WAVE errors | 0 | WAVE |
| SM-39 | Lighthouse Accessibility score | 100 (all templates) | Lighthouse |
| SM-40 | Keyboard navigation | All elements reachable + operable | Manual |
| SM-41 | Screen reader usability | Forms + navigation operable | NVDA / VoiceOver |
| SM-42 | Color contrast AA compliance | All elements pass | WebAIM / axe |
| SM-43 | 200% zoom | No content loss, no horizontal scroll | Browser |
| SM-44 | Touch targets | All >= 44x44px | Manual measurement |

### 20.6 Compliance & Security Success Metrics

| # | Metric | Target | Tool |
|---|---|---|---|
| SM-45 | Cookie consent banner appears | Visible on fresh browser | Manual test |
| SM-46 | No tracking cookies before consent | 0 non-functional cookies | Chrome DevTools |
| SM-47 | Consent logged | Entries in Complianz log | Complianz dashboard |
| SM-48 | Form consent checkboxes unchecked | All unchecked by default | Manual check |
| SM-49 | Form consent links to privacy policy | All link to /privacyverklaring/ | Manual check |
| SM-50 | XML-RPC disabled | /xmlrpc.php returns 403 | curl + browser |
| SM-51 | 2FA active on all admin accounts | All accounts | Wordfence dashboard |
| SM-52 | Custom login URL active | /wp-admin redirects | Browser |
| SM-53 | Daily backup verified | Last backup < 24 hours | Backup plugin |
| SM-54 | GA4 tracking active | Real-time shows traffic | GA4 dashboard |
| SM-55 | GSC verified | Property verified | GSC dashboard |

### 20.7 Quality Success Metrics

| # | Metric | Target | Tool |
|---|---|---|---|
| SM-56 | All images have alt text | 0 missing | Screaming Frog |
| SM-57 | All images optimized (WebP) | 100% WebP | Manual |
| SM-58 | No lorem ipsum or placeholder text | 0 occurrences | Manual sweep |
| SM-59 | Cross-browser consistency | Chrome, FF, Safari, Edge all pass | Manual + BrowserStack |
| SM-60 | Mobile responsiveness | iPhone, Android, iPad all pass | Real devices |
| SM-61 | Dutch content reviewed | All pages reviewed by native speaker | Manual review |
| SM-62 | All links functional | 0 broken internal + external | Screaming Frog |
| SM-63 | Contact info consistent | Phone + email identical on all pages | Manual |

### 20.8 Business Success Metrics (Post-Launch — Sprint 7)

| # | Metric | Target | Measurement |
|---|---|---|---|
| SM-64 | Contact form submissions (first week) | > 0 (was 0 — HTTP 500) | Gravity Forms entries |
| SM-65 | Reguliere Schoonmaak page visits | Page is discoverable (was 404) | GA4 page views |
| SM-66 | WooCommerce orders (first month) | > 0 (if shop kept) | WC orders |
| SM-67 | Page index rate in GSC | All 32 pages indexed | GSC coverage report |
| SM-68 | Bounce rate | < 60% | GA4 |
| SM-69 | Average session duration | > 2 minutes | GA4 |
| SM-70 | 30-day post-launch: zero critical bugs | 0 critical | Issue tracker |

### 20.9 Sprint 6 Exit Criteria

Sprint 6 is complete when:

1. ALL 4 Quality Gates (QG-1 through QG-4) have passed
2. ALL 68 module DoD checklists are signed off
3. ALL 25 Launch Readiness Criteria (QG-4) are met
4. Client has provided written staging acceptance
5. Sprint 7 (Launch & Handover) is ready to begin
6. ALL 63 success metrics (SM-01 through SM-63) are verified

---

## Appendix A: Module-to-Story Mapping

| Module | Story(s) | Epic |
|---|---|---|
| M-TPL-SERVICE | E-CORE-01, E-CORE-02 | Epic 1 |
| M-PAGE-HOME | E-CORE-01 | Epic 1 |
| M-PAGE-GLAS | E-CORE-03 | Epic 1 |
| M-PAGE-GEVEL | E-CORE-04 | Epic 1 |
| M-PAGE-REGULIER | E-CORE-05 | Epic 1 |
| M-PAGE-VLOER | E-CORE-06 | Epic 1 |
| M-PAGE-VVE | E-CORE-06 | Epic 1 |
| M-PAGE-OPLEVERING | E-CORE-06 | Epic 1 |
| M-PAGE-INDUSTRIEEL | E-CORE-07 | Epic 1 |
| M-TPL-CONTACT | E-CORE-09 | Epic 1 |
| M-FORM-CONTACT | E-CORE-09 | Epic 1 |
| M-PAGE-CONTACT | E-CORE-09 | Epic 1 |
| M-FORM-QUOTE | E-CORE-10 | Epic 1 |
| M-PAGE-QUOTE | E-CORE-10 | Epic 1 |
| M-PAGE-BEDANKT | E-CORE-11 | Epic 1 |
| M-TPL-ABOUT | E-SUPPORT-01 | Epic 2 |
| M-PAGE-OVERHDS | E-SUPPORT-01 | Epic 2 |
| M-PAGE-KWALITEIT | E-SUPPORT-02 | Epic 2 |
| M-TPL-LANDING | E-CORE-08 | Epic 1 |
| M-PAGE-GLASGEVEL | E-CORE-08 | Epic 1 |
| M-PAGE-SCHOONMAAK | E-CORE-08 | Epic 1 |
| M-CPT-TESTIMONIAL | E-SUPPORT-03 | Epic 2 |
| M-PAGE-REFERENTIES | E-SUPPORT-03 | Epic 2 |
| M-CPT-VACANCY | E-SUPPORT-04 | Epic 2 |
| M-FORM-VACATURE | E-SUPPORT-04 | Epic 2 |
| M-PAGE-VACATURES | E-SUPPORT-04 | Epic 2 |
| M-PAGE-DOWNLOADS | E-SUPPORT-06 | Epic 2 |
| M-TPL-LEGAL | E-SUPPORT-05 | Epic 2 |
| M-PAGE-PRIVACY | E-SUPPORT-05 | Epic 2 |
| M-PAGE-COOKIE | E-SUPPORT-05 | Epic 2 |
| M-PAGE-VOORWAARDEN | E-SUPPORT-05 | Epic 2 |
| M-PAGE-DISCLAIMER | E-SUPPORT-05 | Epic 2 |
| M-PAGE-FAQ | E-SUPPORT-07 | Epic 2 |
| M-WC-CONFIG | E-COMM-01 | Epic 3 |
| M-WC-IMPORT | E-COMM-02 | Epic 3 |
| M-WC-PAYMENT | E-COMM-03 | Epic 3 |
| M-WC-SHIPPING | E-COMM-04 | Epic 3 |
| M-WC-EMAILS | E-COMM-05 | Epic 3 |
| M-WC-FLOW | E-COMM-07 | Epic 3 |
| M-PAGE-LUCHT | E-COMM-06 | Epic 3 |
| M-PAGE-WINKEL | E-COMM-02 | Epic 3 |
| M-NAV-MENUS | Cross-cutting | Epic 2 |
| All SEO modules | E-SEO-01 through E-SEO-07 | Epic 4 |
| All Compliance modules | E-COMPLY-01 through E-COMPLY-05 | Epic 5 |
| All Security modules | E-COMPLY-03, E-COMPLY-04 | Epic 5 |
| All QA modules | E-QA-01 through E-QA-07 | Epic 6 |
| Client Acceptance | E-ACCEPT-01 through E-ACCEPT-04 | Epic 7 |

---

## Appendix B: Reference Documents

| Document ID | Document Name | Location |
|---|---|---|
| MPS-001 | Master Project Specification | `docs/MASTER_PROJECT_SPECIFICATION.md` |
| SAD-001 | Solution Architecture Document | `docs/architecture/SOLUTION_ARCHITECTURE.md` |
| WTA-001 | WordPress Technical Architecture | `docs/architecture/wordpress-technical-architecture.md` |
| ADR-001 | Architecture Decision Records | `docs/architecture/ADR.md` |
| FS-001 | Functional Specification | `docs/specifications/functional-specification.md` |
| NFR-001 | Non-Functional Requirements | `docs/specifications/non-functional-requirements.md` |
| SEO-001 | SEO Implementation Specification | `docs/seo/seo-implementation-specification.md` |
| DS-001 | Design System Specification | `docs/design/design-system-specification.md` |
| RTM-001 | Requirements Traceability Matrix | `docs/REQUIREMENTS_TRACEABILITY_MATRIX.md` |
| BKLG-001 | Development Backlog | `docs/DEVELOPMENT_BACKLOG.md` |
| DEP-001 | Development Execution Plan | `docs/planning/development-execution-plan.md` |
| SP2-001 | Sprint 2 Execution Plan (template) | `docs/SPRINT2_EXECUTION_PLAN.md` |
| RCR-001 | Readiness Closure Report | `docs/Readiness_Closure_Report.md` |
| IA-001 | Implementation Audit | `docs/Implementation_Audit.md` |
| RS-01..08 | Rebuild Specifications (8 documents) | `docs/rebuild-spec/` |

---

**END OF SPRINT 6 DEVELOPMENT PLAN — Version 1.0.0**

**This document is implementation-ready. All 20 sections are complete. Sprint 6 may begin upon approval.**

**Cross-reference:** This plan is consistent with MPS-001 (Master Project Specification), SAD-001 (Solution Architecture), BKLG-001 (Development Backlog), DEP-001 (Development Execution Plan), and the Sprint 5.6 Readiness Closure Report (RCR-001). Zero deviations from frozen architecture (16/16 ADR).

**Next Document:** Sprint 7 — Launch & Handover Plan (to be created after QG-4 Staging Acceptance).
