# Security TODO — xilin (site + Xilin_ns_admin)

Audit date: 2026-10-21. Same approach as wynn_rewards / bos / galeria.

## Done

- [x] **Environment not from the Host header.** `app/Config/xilin_environment.php`: server-only `environment.php`
      (gitignored) or the host `local.xilin...`; debug pages only for `local` *and* a request from 127.0.0.1/::1
      (`public/index.php`). Unknown Host names get 400 (production: xilinnschinese.org, www., xilinnuc.startlogic.com).
      `baseURL` only ever one of the site's own names. DB group no longer chosen from `HTTP_HOST`.
- [x] **Admin pages were open to anyone.** `_check_login()` returned a redirect from constructors, which CodeIgniter 4
      ignores. Now: route filter `admin` (`app/Filters/AdminOnly.php`) on every `Xilin_ns_admin/*` route, and
      `_check_login()` throws a `RedirectException`. Cross-site links into the admin only reach its home page.
- [x] **Routing.** `Config/Routes.php` is one table of controllers/methods (like galeria); routes that pointed to
      missing classes (member, semester, sponsor*, subjects, schooluser, roster, …) work again. Auto-routing off.
- [x] **CSRF** on every POST (`csrf` filter). The views needed no change: `public/common/js/csrf.js`, added to each
      page by `app/Filters/CsrfScript.php`, puts the token in forms and AJAX calls.
- [x] **SQL injection** throughout controllers and models (bound parameters, int casts, escaped LIKE).
- [x] **XSS** in ~100 views (`esc()`), `ShowMsg()` (JSON-encoded), JSON responses with the right content type.
- [x] **Passwords hashed** (`app/Libraries/Passwords.php`): parents and admins; plain-text ones and production's
      in-place hashes (also of the `[$!)` form) are accepted and re-hashed at sign-in. Teachers use Google only.
      Forgot password emails a one-time 1-hour link (`Controllers/Password.php`), same answer for unknown emails.
      Admin "Find Password" sets a new password instead of showing it. Failed admin sign-ins no longer log the password.
- [x] **Login throttling** (parents, admins, forgot password): 5 per account+IP, 20 per IP, per 10 minutes.
- [x] **Session fixation**: new session id at every sign-in. Sessions in a private 0700 folder. Cookies `Secure`
      over HTTPS, `SameSite=Lax`; session cookie `HttpOnly`.
- [x] **Security headers** (`secureheaders` filter): X-Frame-Options SAMEORIGIN, nosniff, Referrer-Policy, …
- [x] **Ownership checks**: invoices, homework, class registration for another parent (admin only).
- [x] **Jobs** (scheduler, pod_reminder, imap, teachers/rosterEmail, payment/read): CLI or token (`hash_equals`);
      Gmail OAuth pages admin-only.
- [x] **Secrets** can move to the server-only `app/Config/secrets.php` (`xilin_secret()`); Google client secret removed
      (not needed to verify sign-in tokens).
- [x] **Uploads folder** can't run scripts (`public/uploadfiles/.htaccess`); root `.htaccess` serves only `public/`.
- [x] CodeIgniter 4.7.3 → 4.7.5; `google/apiclient` moved to `require` (used in production).

## Deploying (in this order)

1. Run `app/Database/password_hashing.sql` on the production database (creates `password_resets`, widens columns,
   clears plain-text copies: `admin.pwd` unless it's a hash, teachers' passwords, `login_log.password`).
2. Upload `vendor/` from `composer install --no-dev` (google/apiclient is now a runtime dependency).
3. Optional, on the server only: `app/Config/environment.php` → `<?php return 'production';` and
   `app/Config/secrets.php` → `<?php return ['db_password' => '…', 'cron_token' => '…', 'imap_password' => '…',
   'smtp_payment_password' => '…', 'payment_read_token' => '…'];`
4. Check sign-in (parent, admin, teacher via Google), Find password, and an admin save (CSRF) after deploying.

## Left to do

- [ ] **Change the secrets that are in git history**: DB password, cron token, IMAP and SMTP mailbox passwords,
      Google client secret (rotate it in Google Cloud). Then put the new values only in `secrets.php`.
- [ ] **Payment emails are trusted by sender name** (`Imap.php`, `Payment.php`: From contains "chase") → check
      DKIM / the real sender before crediting a payment.
- [ ] **Admin changes data with GET links** (del, copy, late_fee, info/doaction, …): CSRF tokens don't cover them;
      the cross-site check in `AdminOnly` is the mitigation. Make them POST.
- [ ] **No admin roles**: `MY_Controller::dopower()` returns TRUE; every admin can do everything.
- [ ] **Old JavaScript**: jQuery 1.4.2 / 1.8.2 (admin), 1.11.1 (site), Bootstrap 3.0.3, deprecated Google
      platform.js sign-in, SWFUpload (Flash); `register/index.php` loads inputmask from a CodePen S3 bucket.
- [ ] **Dead code to delete** (doesn't parse or uses CodeIgniter 2/3 APIs; not routed): `Homework.php`, `Program.php`,
      `Xilin_ns_admin/column.php`, `system.php`, `ueditor.php`, `imgcheck.php`, `Models/Admin/ci_api.php`,
      `Dmp_model.php`, `authcode()` in `common_helper.php`. Broken but routed: `Info::recycling/dohtml/archives_do`,
      `Member::add` (fixed), `Payment::online/update_online`, `Podreports::detail`, `Account::online_payment`.
      `Xilin_ns_admin/info/index` fails on an undefined `$cname` (before these changes too).
- [ ] Teacher Google sign-in also accepts any Google address containing "rufeng" (`Tlogin.php`) — remove when done.
