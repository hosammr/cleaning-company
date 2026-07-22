# SiteMap — HDS Onderhoudsdiensten

## Current Site Architecture

```
helderduidelijkschoon.nl/
│
├── /                                                [HOME]
│   ├── Icon: Over HDS ──────────────────────────► /over-hds/
│   ├── Icon: Schoonmaak ─────────────────────────► /reguliere-schoonmaak/    ← 404
│   ├── Icon: Glas ───────────────────────────────► /glasbewassing            ← no trailing /
│   ├── Icon: VVE ────────────────────────────────► /vve                      ← alt URL
│   ├── Icon: Gevel ──────────────────────────────► /gevelreiniging/
│   ├── Icon: Kwaliteit ──────────────────────────► /kwaliteit-veiligheid/
│   ├── Icon: Vloer ──────────────────────────────► /vloeronderhoud/
│   ├── Icon: Contact ────────────────────────────► /contact/                 ← 500
│   └── CTA Button: Contact ──────────────────────► /contact/                 ← 500
│
├── /over-hds/                                      [OVER HDS]
├── /referenties/                                    [REFERENTIES]
│   └── Contains: Testimonials form + 1 client logo
├── /vacatures/                                      [VACATURES]
│   └── Contains: 2 vacancy images (scanned Word docs)
├── /kwaliteit-veiligheid/                           [KWALITEIT & VEILIGHEID]
│   └── Sections: Kwaliteit, Veiligheid, MVO
├── /downloads/                                      [DOWNLOADS]
│   ├── PDF: Algemene voorwaarden schoonmaak
│   └── PDF: Algemene voorwaarden gevelreiniging
│       └── Hosted on: hds-onderhoudsdiensten.nl
│
├── /glasbewassing/                                  [GLASBEWASSING]
│   └── Sections: Veiligheid, Samenwerking, Technieken
├── /gevelreiniging/                                 [GEVELONDERHOUD]
│   └── Services: Impregneren, Graffiti, Daken/Goten/Gevel/Zonnepanelen/Reclameborden
│
├── /vloeronderhoud/                                 [VLOERONDERHOUD]
│   └── Services: 7 floor service types
├── /vve-service/                                    [VVE SERVICE]
│   └── External link: vvebelang.nl
├── /oplevering-schoonmaak/                          [OPLEVERING SCHOONMAAK]
│   └── Services: 5 task types
├── /?page_id=318                                    [REGULIERE SCHOONMAAK]   ← 404
├── /industriele-schoonmaak/                         [INDUSTRIELE SCHOONMAAK]
│
├── /contact/                                        [CONTACT]               ← 500
├── /winkel/                                         [WINKEL — WooCommerce]
│   ├── /product/airfixr-150/                        €795,00
│   ├── /product/airfixr-60/                         €325,00
│   ├── /product/airfixr-75/                         €595,00
│   ├── /product/airfixr-ionisator-220v/             €95,00
│   ├── /product/airfixr-panel/                      €395,00
│   ├── /product/airfixr-panel-rvs/                  €425,00
│   ├── /product/airfixr-panel-silent/               €395,00
│   ├── /product/f7-filter-150/                      €49,00
│   ├── /product/f7-filter-75/                       €35,00
│   ├── /product/ophangsysteem-airfixr-panel-rvs/    —
│   ├── /product/servicepakket-150/                  —
│   ├── /product/servicepakket-75/                   —
│   ├── /product/uv-c-lamp-16w/                      —
│   └── /product/uv-c-lamp-40w/                      —
│   └── /winkel/page/2/                              Pagination
│
├── /winkelmand/                                     [CART]
├── /2015/06/29/hallo-wereld/                        [BLOG: Hello World]
├── /2015/08/25/kwaliteit-veiligheid/                [BLOG: redirect?]
│
├── /robots.txt                                      [Allow all, crawl-delay:5]
├── /sitemap.xml                                     [Sitemap index]
├── /page-sitemap.xml                                [500 ERROR]
├── /post-sitemap.xml                                [2 posts]
├── /product-sitemap.xml                             [18 products]
├── /attachment-sitemap.xml                          [~50 attachment pages]
├── /category-sitemap.xml                            [Categories]
├── /product_cat-sitemap.xml                         [Product categories]
├── /author-sitemap.xml                              [Authors]
│
└── /xmlrpc.php                                      [XML-RPC enabled — security risk]
```

## URL Inconsistencies Map

| Link Source | URL Used | Actual Page | Status |
|---|---|---|---|
| Homepage icon "Schoonmaak" | /reguliere-schoonmaak/ | /?page_id=318 | 404 |
| Navigation "Reguliere Schoonmaak" | /?page_id=318 | — | 404 |
| Homepage icon "Glas" | /glasbewassing | /glasbewassing/ | 200 (no slash) |
| Navigation "Glasbewassing" | /glasbewassing/ | /glasbewassing/ | 200 |
| Homepage icon "VVE" | /vve | /vve-service/ | 200 (different) |
| Navigation "VVE Service" | /vve-service/ | /vve-service/ | 200 |
| Homepage icon "Gevel" | /gevelreiniging/ | /gevelreiniging/ | 200 |
| Navigation "Gevelonderhoud" | /gevelreiniging/ | /gevelreiniging/ | 200 (label differs) |

## Legend

```
[BRACKETS]    = Page/Resource type
►              = Link to
← STATUS       = Broken link indicator
—              = Price unknown/not listed on shop page
```

## Pages NOT Found (404)

| URL | Expected Content |
|---|---|
| /reguliere-schoonmaak/ | Regular cleaning services |
| /?page_id=318 | Same page (ID-based URL) |
| /privacyverklaring/ | Privacy policy |
| /privacy-policy/ | Privacy policy (English) |
| /algemene-voorwaarden/ | Terms and conditions |
| /faq/ | Frequently asked questions |

## Server Errors (500)

| URL | Expected Content |
|---|---|
| /contact/ | Contact page with form |
| /page-sitemap.xml | Page listing for search engines |
