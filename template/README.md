# YouBee Social — HTML template

A responsive social-media front-end template built on **Bootstrap 5.3**.
Eight static pages, one small stylesheet, **no custom JavaScript**.

All eight pages pass the W3C validator with zero errors and zero warnings.

---

## Pages

| File | What it covers |
| --- | --- |
| `login.html` | Log in — email/username, password, keep me logged in, link to register |
| `register.html` | Create account — name, username, email, password, date of birth, terms |
| `index.html` | Feed — post composer, posts with a single photo, likes, comments, nested replies |
| `profile.html` | Profile — cover + avatar, stats, tabs for Posts / About / Friends / Photos, change picture & cover |
| `messages.html` | Messages — conversation list, chat thread, attachment, new-message dialog |
| `notifications.html` | Notifications — read/unread states, grouped New / Earlier, inline request actions |
| `friends.html` | Friends — people search, requests, suggestions, all friends, sent requests |
| `settings.html` | Settings — profile, account, privacy, notifications, blocking |

## Feature map

| Requirement | Where |
| --- | --- |
| Profiles | `profile.html` |
| Messages | `messages.html` |
| Notifications | Dropdown panel on the navbar bell (every page) + the full `notifications.html` |
| Add friend | `friends.html` (requests / suggestions / sent) + right-rail suggestions |
| User settings | `settings.html` |
| Uploading posts | "Create post" dialog — header, sidebar, mobile tab bar, and both composers |
| Uploading photos | "Upload a photo" dialog — Photo buttons and the profile Photos tab |
| Changing profile picture | Camera button on the profile avatar, and Settings → Profile |
| Register / log in | `register.html`, `login.html` |

### Notifications dropdown

The navbar bell opens a panel with the four most recent items, a
**Mark all as read** button in its header, and **View all notifications** in its
footer linking to `notifications.html`. It stays open when you click *Mark all
as read* (`data-bs-auto-close="outside"`).

### One photo per post

A post carries at most one image — there is no carousel or multi-photo grid.
The markup is a single `.post-photo` block, and both upload dialogs accept one
file. The profile Photos tab is still a gallery of many past photos.

## Layout

```
project/
├── login.html
├── register.html
├── index.html
├── profile.html
├── messages.html
├── notifications.html
├── friends.html
├── settings.html
├── assets/
│   ├── css/style.css     ← the only stylesheet
│   └── img/              ← SVG placeholders (avatars, photos, cover, logo)
└── .claude/launch.json   ← dev-server config, not part of the template
```

## Running it

Open any `.html` file directly, or serve the folder so relative paths resolve cleanly:

```bash
python3 -m http.server 4173
```

Then visit <http://localhost:4173>.

Forms all post to `#` — they are markup only, so submitting reloads the page.
The auth pages cross-link to each other, and their logo links into the app.

## About the JavaScript

There is **no hand-written JavaScript** — no inline `onclick`, no inline `style`,
no custom script file. The single `<script>` tag on each page is Bootstrap's own
`bootstrap.bundle.min.js`, which is what powers the dialogs, dropdowns, tabs and
mobile drawers through `data-bs-*` attributes alone. Remove it and those
components stop opening; everything else still renders.

CDN assets are pinned to exact versions with Subresource Integrity hashes.

## Responsive behaviour

| Breakpoint | Layout |
| --- | --- |
| `< 992px` | Single column · fixed bottom tab bar · nav in an offcanvas drawer · search in a top drawer |
| `≥ 992px` | Left navigation rail + main content |
| `≥ 1200px` | Left rail + main + right rail (suggestions, birthdays, contacts) |

Messages uses Bootstrap's responsive offcanvas: the conversation list is a
drawer on phones and a static column from `md` up. The auth pages stack the
pitch above the form below `lg` and sit side by side above it.

## Stylesheet conventions

`assets/css/style.css` is organised into 19 numbered sections listed at the top
of the file. It only contains what Bootstrap utilities cannot express — brand
colour overrides, the app shell, and component styling for posts, chat bubbles,
notifications, the auth pages and settings rows.

- **Design tokens** live in `:root` as `--yb-*` custom properties. Change
  `--yb-honey` to rebrand the whole template.
- **Components** use a `block__element` / `block--modifier` naming pattern
  (`.msg__bubble`, `.msg--out`, `.notification-item--unread`).
- Use `.btn-icon` — not `.icon-btn` — for a circular button that must keep its
  `.btn-*` colours; `.icon-btn` paints its own neutral background.

## Accessibility notes

- Landmarks throughout, plus a skip link to `#main` on every page.
- Every control has a visible or `visually-hidden` label; every icon is
  `aria-hidden` next to real text.
- Decorative images use empty `alt`; content images are described.
- `prefers-reduced-motion` disables the transitions and the typing animation.
- Focus is visible on every interactive element.

## Replacing the placeholders

`assets/img/` holds plain SVGs so the template works offline. Swap them for real
files and keep the names, or update the `src` attributes. Sizes assumed:

- `avatar-*.svg` — square
- `photo-*.svg` — 4:3 (posts crop to 16:10)
- `cover.svg` — 16:5 (1600 × 500 or larger)
