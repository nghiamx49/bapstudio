# B.A.P Studio WordPress theme

A dark-and-gold showcase theme for a wedding, event and prom photo studio, built from the "Wedding Studio Website" design. Photos, albums, the home banner, carousel, pinned albums and pricing packages are all managed in wp-admin. Motion is done with GSAP.

The theme lives in [`bap-studio/`](bap-studio/). The built CSS/JS in `bap-studio/dist/` is committed, so the folder can be uploaded to WordPress as is.

## Requirements

- WordPress 6.6+ and PHP 8.1+
- **Secure Custom Fields** (free, on WordPress.org) for the Studio settings page, the carousel gallery and the package bullet list. ACF PRO also works if you already have it.
- **Contact Form 7** (booking form) and **Flamingo** (keeps a copy of each message in wp-admin)
- Recommended: an SMTP plugin such as WP Mail SMTP so form emails are delivered, plus an SEO plugin (Yoast or Rank Math) and a caching plugin

## Install

1. Zip the `bap-studio` folder (or run `npm run zip` inside it) and upload it in **Appearance → Themes → Add New → Upload**, then activate it.
2. In **Plugins → Add New**, install and activate **Secure Custom Fields**, **Contact Form 7** and **Flamingo**.
3. Go to **Settings → Permalinks** and click Save once.

## Managing content

| Task | Where in wp-admin |
| --- | --- |
| Create, rename or reorder albums (Cưới, Sự kiện, Prom…) | **Media → Albums** (the *Order* field sets the menu order) |
| Upload photos | **Media → Add New**. Give each photo a *Title* and *Description*: they show beside the photo when it is opened. |
| Put photos in an album | Tick the album on the photo, or in **Media → Library** (list view) select many photos and use the bulk action *Add to album: …* |
| Banner text, image and buttons | **Studio settings → Banner**. Wrap words in `*stars*` to show them in gold italics. |
| Home carousel photos | **Studio settings → Home page → Carousel photos** (drag to reorder) |
| Pin 3 albums to the home page | **Studio settings → Home page → Pinned albums** |
| Pricing packages | **Pricing packages** menu. Title, price, price note, summary, included items, highlight, button label. *Order* (Page Attributes box) sets the position. |
| Pricing page | Create a page and choose the template **Bảng giá (Pricing)**, then pick it in **Studio settings → Home page → Pricing page** so it appears in the header. |
| Contact info, social links, footer | **Studio settings → Contact & footer**. Form messages go to the email set here. |
| Read form messages | **Flamingo → Inbound messages** |

Albums appear in the header navigation automatically. Extra links can be added with a menu assigned to **Header: extra links**. A **Studio editor** user role can manage photos, albums and packages without access to plugins or theme settings.

## Second look: B.A.P Studio Blush

[`bap-studio-blush/`](bap-studio-blush/) is a light "editorial romance" look (blush paper, rose ink, mauve buttons, oversized Cormorant headlines with italic emphasis, hairline-divided columns, cinematic night-plum photo bands). It has the same features as the dark theme: album links in the header, the photo dialog, banner text, carousel, 3 pinned albums, pricing packages, contact form and footer.

It is a **child theme** of `bap-studio`, so all content (albums, photos, packages, Studio settings, the contact form) is shared. You can switch between the two looks in **Appearance → Themes** at any time without re-entering anything.

- Install: upload both `bap-studio` and `bap-studio-blush` (or `npm run zip` in each folder), then activate **B.A.P Studio Blush**.
- Headline fields accept `*stars*` for italic words, e.g. Pinned albums heading `Tác phẩm *tiêu biểu*`.
- The design uses Manrope and DM Sans, which have no Vietnamese letters, so Be Vietnam Pro takes both roles. The grey text colours are slightly darker than the design so small text stays readable.
- Templates, styles and animations live in the child; the dialog, carousel, menu and "load more" scripts are imported from `bap-studio/src/js` at build time.

```bash
cd bap-studio-blush
npm install
npm run build   # or npm run dev / npm run zip
```

## Development

```bash
cd bap-studio
npm install
npm run dev     # rebuild dist/ on change
npm run build   # production build
npm run zip     # build and create ../bap-studio.zip for upload
```

A local site can be started with [`wp-env`](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/) (Docker) from the repo root: `npx @wordpress/env start`.

### Structure

```
bap-studio/
  functions.php            loads inc/*
  inc/                     taxonomies, post types, custom fields (Secure Custom Fields), admin tools, REST "load more", contact form
  front-page.php           banner, carousel, pinned albums, pricing, contact
  taxonomy-bap_album.php   album page with tabs, grid and "load more"
  page-pricing.php         pricing page template
  template-parts/          sections and the shared image dialog
  src/js, src/scss         sources (GSAP animations in src/js/animations.js)
  dist/                    built CSS, JS and fonts
```
