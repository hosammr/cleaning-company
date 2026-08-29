# Development Backlog

## Unresolved Project Decisions

### DEC-001 — P10 Schoonmaakdiensten card grid: 5 vs 7 services (UNRESOLVED)

**Origin:** F9-B category landing page implementation (P09/P10).

**Conflict:** The project specification requires 5 service cards on `/schoonmaakdiensten/`:

- FS-001 §4.3: "P10: Reguliere + Vloer + VVE + Oplevering + Industrieel cards" (5)
- Product Backlog E-CORE-08: "P04, P05, P06, P07, P08 (all 5)"
- SEO-001 §6.3: "P10: 5 cards (Reguliere, Vloer, VVE, Oplevering, Industrieel)"
- PCR-001 page list contains no entries for `kantoor-schoonmaak` and `specialistische-reiniging`

The current implementation (`inc/service-functions.php` → `hds_get_service_page_groups()`) returns 7 services, adding `kantoor-schoonmaak` and `specialistische-reiniging` (both published and present in the "Diensten" menu).

**Decision at F9-B:** keep the current 7-card implementation; do NOT modify `hds_get_service_page_groups()`. The two extra services remain published and linked.

**Update F9-D (client service portfolio change):**

- `specialistische-reiniging` removed by client request (service no longer offered).
- `scholen-en-kinderopvang-reiniging` added by client request.
- The new service **replaces** Specialistische reiniging in the P10 grid: the grid keeps 7 cards — Reguliere, Vloer, VVE, Oplevering, Industrieel, Kantoor, Scholen en Kinderopvang.
- The 5-vs-7 specification conflict itself remains **UNRESOLVED** (the implementation still has 7 cards; the frozen specification still says 5).

**To resolve:** align either the specification or the code.
- Option A (spec wins): restrict the grid to 5 and re-home the extra services elsewhere.
- Option B (code wins): update FS-001 §4.3, PB E-CORE-08, SEO-001 §6.3, and PCR-001 to document the new composition.

**Status:** OPEN — no owner assigned.

---

## Client Change Records

### DEC-002 — Service portfolio change (F9-D, client requested)

| Change | Detail | Implemented |
|---|---|---|
| Removed service | Specialistische reiniging — company no longer offers this service | Page trashed (ID 89), menu item removed, all code/content references removed |
| Scope change | Gevelreiniging — residential houses and low-rise / easily accessible façades only; no large commercial buildings, no high-rise façades | Service data, P09 landing copy, and P09 SEO description updated to state the limitation explicitly |
| Added service | Scholen en Kinderopvang reiniging (`/scholen-en-kinderopvang-reiniging/`) | Full service architecture entry (placeholder copy, marked in `inc/services.php`), published page with service template, homepage card, Diensten menu item, P10 card, quote-form option |

**Redirect note (unimplemented):** the retired `/specialistische-reiniging/` URL currently returns 404. Recommended future redirect target, if one is configured: `/schoonmaakdiensten/` (aggregate landing). No redirect mechanism exists in the theme; Rank Math absent.

### DEC-003 — Client identity update (F9-E, client requested)

| Item | Old | New | Status |
|---|---|---|---|
| Company name | HDS-schoonmaakdiensten / HDS Onderhoudsdiensten | **Hamdoun Schoonmaak** | Implemented |
| Service area | West-Brabant en Zeeland | **de hele provincie Groningen** | Implemented |
| Primary phone | 0164-652846 | **0622272811** (mobiel) | Implemented (fallback + content) |
| Secondary phone | — | **0502340009** (vast) | Implemented (`hds_get_phone_secondary()`, displayed on contact page, footer, downloads, privacyverklaring) |
| E-mail | info@helderduidelijkschoon.nl | **PENDING** — client will provide new domain/email after hosting account is set up | Intentionally unchanged (code fallback + all content) |
| Domain | (local: hds-schoonmaakdiensten.local) | **PENDING** — official domain to be supplied by client | Intentionally unchanged |
| Facebook URL fallback | facebook.com/helderduidelijkschoon/ | **PENDING** client confirmation | Intentionally unchanged |

**Remaining client dependencies:** official domain, e-mail address(es), Facebook URL, KVK/BTW/address values (not yet supplied).
