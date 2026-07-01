# MemoNetwork Web

MemoNetwork Web is the web control panel for MemoNetwork.

## Alpha 26.0-dev

Initial foundation:

- PHP 8+ MVC-style structure
- Installer foundation
- Login/session foundation
- Dashboard foundation
- REST API foundation
- Loading screen CMS preparation
- Dark orange MemoNetwork theme

## Quick start

1. Upload the repository to your web hosting.
2. Point the web root to `/public` when possible.
3. Copy `.env.example` to `.env`.
4. Open `/install.php` in your browser.
5. Enter your database details and create the owner account.

If your hosting cannot point to `/public`, keep the project in a protected folder and expose only the `public` directory.
