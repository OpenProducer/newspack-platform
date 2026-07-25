> Newspack Platform – Architecture and Development Flow
> Modeled on Newspack Radio Pro's architecture doc. Status: in progress.

## Progress log

- **2026-07-24**: `sync-plugins.sh` written, tested, and debugged against real Terminus/Pantheon on all three environments (`dev`, `radio`, `podcast`). All three are currently synced and clean (`connection_mode: git`). Two real bugs found and fixed along the way (see "Known issues fixed" below) — read that section before touching `sync-plugins.sh` again, it explains why the retry/backoff logic exists.
- ~~`master`'s `dev` environment has NOT yet been promoted to `test`/`live`~~ — resolved 2026-07-25: promoted via `terminus env:deploy newspack.test` then `terminus env:deploy newspack.live` (see entry below).
- **2026-07-24**: Theme divergence investigated and resolved (see "Custom theme repos" below) — `radio` confirmed as the canonical source for `newspack-radio-theme`. Subtree-split extraction commands validated in a scratch clone (both extractions succeed cleanly, real history intact).
- **2026-07-24**: `OpenProducer/newspack-theme-child` and `OpenProducer/newspack-radio-theme` created on GitHub (empty, no auto-init) and the validated extraction run for real from this repo on the `radio` branch. `newspack-radio-theme` pushed as 6 commits (`4559684`), `newspack-theme-child` pushed as 10 commits (`ab6627e`) — both match the scratch-clone dry run exactly (same file sets, same commit counts). Both repos are currently **private** and their default branch is `main` (not the extraction branch names — those were pushed as `extract-*:main` then deleted locally). Next: clone both locally as siblings of this repo and wire the Studio symlinks (Step 3 onward below).
- **2026-07-24**: Both new repos flipped to **public** (`gh repo edit --visibility public`), matching `newspack-platform`'s own visibility. Both cloned locally as siblings of this repo (`~/Dev/projects/newspack-theme-child`, `~/Dev/projects/newspack-radio-theme`) and re-verified post-clone: 10 commits / 4 files (`functions.php`, `style.css`, `screenshot.png`, `style.ctcbackup.css`) for theme-child, 6 commits / 3 files (no `style.ctcbackup.css`) for radio-theme — no `wp-content/` contamination in either.
- **2026-07-24**: Two blockers found while continuing the rollout — **open question #3 (Studio path) is not actually resolved yet, and `sync-themes.sh` cannot currently run on this machine.** Neither is fixed; both need a decision before proceeding. Details:
  - No Studio site is registered for `newspack-platform` at all. `~/.studio/cli.json` lists exactly three sites — `angelcityjazz`, `Newspack Radio Pro` (path `~/Dev/local/wordpress/studio/newspack-radio-pro`), and `test-site` — and none of the three is a git checkout of this repo (`git remote -v` inside each errors with "not a git repository"). "Newspack Radio Pro" is the separate reference site this doc's `functions.php` divergence check cross-referenced (see "Custom theme repos" above), not a Studio instance of `newspack-platform`. The "Local rapid development" section's Step 1 (clone this repo, check out a branch, open it as a Studio site) has never actually been done.
  - `sync-themes.sh` failed immediately on `--dry-run`: `./sync-themes.sh: line 37: master: unbound variable`. Root cause: it used bash associative arrays (`declare -A`, lines 37 and 97), which require bash ≥4, but the script's `#!/usr/bin/env bash` shebang resolves to macOS's stock `/bin/bash` (3.2.57) on this machine — there's no Homebrew `bash` installed to shadow it. `sync-plugins.sh` doesn't use associative arrays and is unaffected. This is presumably why the doc already flagged `sync-themes.sh` as "still completely untested" — it had never actually been run here. **Fixed**: rewrote `BRANCH_THEMES`/`THEME_HASHES` to use a `themes_for_branch()` case-statement and plain per-theme variables instead of associative arrays — `bash -n` passes and `--dry-run` now runs cleanly under bash 3.2. Both fix and the docs themselves plus `sync-plugins.sh` were committed to `master` (`9545634`, not yet pushed) — the pre-flight check requires a clean `git status`, and these files had been untracked since the earlier session.
- **2026-07-24**: `sync-themes.sh --branch master --dry-run` run for real (first real run of this script, ever): transfers `newspack-theme-child@ab6627e` (5 files: `functions.php`, `screenshot.png`, `style.css`, `style.ctcbackup.css`) into `wp-content/themes/newspack-theme-child`, `--delete` flags no extraneous files to remove. Clean dry-run, no unexpected deletions. Studio symlink step (open question #3) intentionally skipped for now per decision — `sync-themes.sh` doesn't depend on Studio, so this doesn't block theme syncing itself.
- **2026-07-25**: `sync-themes.sh --branch master` run for real (not `--dry-run`). Result: **"No changes to commit -- themes already up to date on master."** — verified independently with `diff -rq` between `~/Dev/projects/newspack-theme-child` and `wp-content/themes/newspack-theme-child` (zero differences). This is expected, not a bug: `newspack-theme-child` was subtree-split *out of* this exact branch a day earlier, so day-one content is byte-identical by construction. No theme commit, no push — the script correctly did nothing.
  - Separately, `docs/`, `sync-plugins.sh`, and `sync-themes.sh` (untracked since the previous session, committed locally to satisfy `sync-themes.sh`'s clean-tree pre-flight check) were pushed to `origin`. The push was initially **rejected** — `origin/master` had one commit (`880a81198c`, "Sync plugins (dev): 2026-07-24") that this clone never had, from an earlier `sync-plugins.sh --env dev` run that commits directly on Pantheon via `terminus env:commit` without ever pushing back to GitHub or being pulled locally (this is exactly open question #2, "GitHub sync for `sync-plugins.sh`" — still unresolved, and this divergence is a direct symptom of it). Confirmed no file overlap (`880a81198c` only touches `wp-content/plugins/gutenberg/...`), merged cleanly (`a05d5a7164`), pushed to both `origin` and `github` — both remotes now fully in sync with local `master` (`0`/`0` ahead-behind both ways).
  - Verified on Pantheon: `terminus env:diffstat newspack.dev` → "No changes on server," `connection_mode: git` confirmed, `terminus env:code-log newspack.dev` shows the merge commit `a05d5a7164` as the latest entry on `dev`. Code is confirmed live on Pantheon.
  - Visual check attempted via `WebFetch` against `dev-newspack.pantheonsite.io` — **inconclusive**, not a real check. The response was Pantheon's own sandbox interstitial ("This website is hosted in a sandbox environment" / Continue button), which `WebFetch` can't click through (no JS execution, can't submit the accept). This is standard Pantheon behavior for any unlaunched `*.pantheonsite.io` domain, not a sign of breakage, but it means **no actual visual verification of `dev` has happened yet** — needs a real browser (manual, or a future browser-automation tool) to confirm.
  - Net effect on `master`/`dev` theme content: **none** — no theme files changed. The only things that landed were the tooling commits (scripts, docs) and the previously-orphaned plugin sync from Pantheon.
- **`sync-themes.sh`** has now been run for real once (`master`, no-op as expected) — no longer "completely untested," but `radio`/`podcast` (which sync both themes, not just `newspack-theme-child`) remain unexercised.
- **2026-07-25**: Brought the tooling (`docs/`, `sync-plugins.sh`, `sync-themes.sh`) onto `radio` and `podcast` — both branches were missing these files entirely (never existed there). Before touching either, fetched `origin` and found both had the same orphaned-commit pattern seen on `master`: `origin/radio` had `689e4ee8d9` ("Sync plugins (radio)") and `origin/podcast` had `144cd9a180` ("Sync plugins (podcast)") that the local clone lacked — in both cases a clean fast-forward (0 local divergence), unlike `master` which needed a real merge. Fast-forwarded both, then cherry-picked the two `master` tooling commits (`9545634d7f` "Add sync-plugins.sh, sync-themes.sh, and architecture docs", `299184cb66` "Update progress log: sync-themes.sh bash-3.2 fix and clean dry-run") onto each — clean cherry-picks, no conflicts (net-new files).
  - `radio --dry-run`: no-op for both themes, verified independently with `diff -rq` (zero differences, not just trusting rsync's summary) — `radio` was the canonical extraction source for both, so this is expected.
  - `podcast --dry-run`: no-op for `newspack-theme-child` (verified, zero diff), but **`newspack-radio-theme` has a real, substantial change queued**. Only `functions.php` differs (`style.css`/`screenshot.png` identical, nothing extraneous to delete). Podcast's current `functions.php` (47 lines) is the old Child Theme Configurator boilerplate plus a basic TEC event-ordering filter; syncing would replace it with the canonical 157-line version carrying the carousel/venue/date-injection logic (`inject_event_categories_date_and_venue_into_carousel()`, TEC venue ID lookup with legacy-meta fallback) — exactly the divergence flagged in "Theme repo extraction" above. New behavior, not a regression, but a genuine visible/functional change (the event carousel will start showing dates/venues it currently doesn't).
- **2026-07-25**: Tooling catch-up commits pushed for real to both `origin` and `github` on `radio` and `podcast` — all four remote refs (`origin/radio`, `github/radio`, `origin/podcast`, `github/podcast`) confirmed `0`/`0` ahead-behind against local afterward. `radio` was **not** re-run through `sync-themes.sh` for real (dry-run + independent `diff -rq` already proved it a no-op — nothing to gain from repeating it).
- **2026-07-25**: `sync-themes.sh --branch podcast` run for real. Confirmed the commit prompt, result: `Sync themes: newspack-theme-child@ab6627e newspack-radio-theme@4559684` (`6945a36a1e`), 1 file changed (`wp-content/themes/newspack-radio-theme/functions.php`, +144/-34), exactly the change previewed in the dry-run above. Pushed cleanly to both `origin` and `github` in the same run — `podcast` confirmed `0`/`0` ahead-behind both remotes afterward. Verified landed on Pantheon: `terminus env:diffstat newspack.podcast` → "No changes on server"; `connection_mode: git`; `terminus env:code-log newspack.podcast` shows `6945a36a1e` as the latest entry. **This is the carousel/venue/date-injection logic going live on podcast for the first time** — visual verification of the events carousel on `podcast-newspack.pantheonsite.io` still needed.
- **2026-07-25**: `docs/ARCHITECTURE.md` reconciled across all three branches (`master`, `radio`, `podcast`) so the Progress log reads identically everywhere — previously each branch only carried the entries for events that happened on that branch. Verified via pairwise `git diff <branch> <branch> -- docs/ARCHITECTURE.md` after reconciling.
- **2026-07-25**: `master` promoted through the full pipeline: `dev` → `test` → `live`, via `terminus env:deploy newspack.test` then `terminus env:deploy newspack.live` (Terminus flag note: `--message` doesn't exist on this Terminus version, use `--note`). Each hop verified before proceeding to the next, same pattern as the theme syncs:
  - `test`: `terminus env:diffstat newspack.test` → "No changes on server"; `env:code-log` shows `6a95f080d0` (the reconciled progress-log commit, i.e. everything current on `dev`) at the top, labeled `test, dev`; `connection_mode: git`. Owner visually confirmed `test-newspack.pantheonsite.io` — homepage, carousel, all rendering fine, matches `dev`'s confirmed-good state.
  - `live`: same verification, same result — `terminus env:diffstat newspack.live` → "No changes on server"; `env:code-log` shows `6a95f080d0` at the top, labeled `test, live, dev`; `connection_mode: git`. Owner doing the final visual check on `live-newspack.pantheonsite.io`.
  - This carries the plugin updates (`gutenberg`, `newspack-newsletters`, `the-events-calendar`, `wordpress-seo`) and all tooling/docs commits from `dev` through to production.
- **2026-07-25**: Investigated why `newspack-theme`/`newspack-block-theme` weren't covered by `sync-plugins.sh` (neither is on WordPress.org, confirmed via Theme URI + no Update URI header + 404s on `wordpress.org/themes/{slug}/`) and found something that needed explaining first: `dev`'s installed `newspack-block-theme` read `1.28.1-alpha.1` even though a stable `v1.28.1` had existed for weeks. Root-caused before building anything (see "GitHub-Releases-sourced themes" under `sync-plugins.sh` above for full detail): this is an **upstream Automattic packaging bug**, not a real problem here — their release build bumps a version constant in `functions.php` correctly but leaves `style.css`'s `Version:` header one release behind, confirmed by diffing the actual `v1.28.1-alpha.1` and `v1.28.1` release zips directly. `dev`'s git-committed `functions.php` already read `1.28.1` (correct) before any of this session's work — only the cosmetic `style.css` string was stale. Opened an SFTP session on `dev` to test-install the "fix" anyway to be certain, confirmed via `wp theme install --force` that the resulting diff was just 2 meaningless dev-tooling files (`CLAUDE.md`, `.hooks/pre-push` — leaked from Automattic's own repo, zero functional relevance), and discarded it (`terminus connection:set newspack.dev git --yes`) rather than commit a no-op. `dev` confirmed back to clean, `connection_mode: git`, byte-identical to HEAD.
- **2026-07-25**: Built the general mechanism: extended `sync-plugins.sh` (not a new script — the "same commit" requirement with wp.org plugin updates ruled out a cleanly-separable script; see full rationale in the `sync-plugins.sh` doc section above) to check `newspack-theme` (+ 5 style variants) and `newspack-block-theme` via the GitHub Releases API and fold any updates into the same SFTP session/commit. Resolves open question #1 (theme sourcing mechanism). `bash -n` passes under this machine's stock bash 3.2 (no associative arrays, same lesson as `sync-themes.sh`).
- **2026-07-25**: Ran `--dry-run` on all three environments to see real output plus the natural per-environment discovery of which `newspack-theme` variants exist where (rather than a separate audit pass). Result: **identical across all three** — no per-environment variant drift to worry about.
  - `dev`, `radio`, `podcast` (all three, identical): no wp.org plugin/theme updates pending (recently synced). GitHub-release themes: `newspack-theme` + all 5 variants (`newspack-joseph`, `newspack-katharine`, `newspack-nelson`, `newspack-sacha`, `newspack-scott`) report `up to date` at `2.22.3` on every environment. `newspack-block-theme` reports `update 1.28.1-alpha.1 -> 1.28.1` on every environment — the documented false-positive reproducing exactly as expected, not a new/different problem, and consistent with `dev`'s content already being correct underneath the label.
  - None of the three run for real — dry-run only, as requested. No plugin/theme installs, no commits, no pushes to Pantheon happened as part of this entry.

- **2026-07-25**: Fixed the `newspack-block-theme` commit message before running for real — it previously used the generic `slug@version` format for every GitHub-release theme, which would have implied a real version change happened. Special-cased it to `reinstall newspack-block-theme (no functional change; corrects leaked dev-tooling files from release zip, see docs/ARCHITECTURE.md for the upstream style.css version-lag quirk)` since we know for certain this specific reinstall changes nothing functional.
- **2026-07-25**: Found and fixed a real bug while attempting the first real (non-`--dry-run`) `sync-plugins.sh` run: it died silently (exit 1, no error text) right at the first `read -r -p` confirm prompt when fed piped answers. Root cause, confirmed by direct test: `terminus wp` (SSH under the hood) silently drains whatever is sitting on the script's own stdin even though it never asks for input itself. This was already a latent risk with the original 2 `terminus wp` calls before the first prompt, but the new GitHub-release theme check added ~7 more, which reliably starved the prompt of its piped input. Fixed by closing stdin (`</dev/null`) on every `terminus` call that doesn't need input — same defensive pattern the script already used for the git-mode-switch call, just not applied consistently everywhere else. Verified the fix directly (`bash -x` trace reaching and correctly answering the prompt) before running for real.
- **2026-07-25**: `sync-plugins.sh --env dev` run for real (first real run of the extended script). Result: no wp.org plugin/theme updates pending; `newspack-block-theme` reinstalled as expected (the documented false-positive), commit message read exactly as fixed above. Diffstat briefly showed "No changes on server" immediately after the SFTP write (the documented Pantheon lag), then the commit's own retry/backoff correctly caught the real change on attempt 2/3 ("Your code was committed"). Verified: `terminus env:diffstat newspack.dev` → "No changes on server" (clean); `connection_mode: git`; `terminus env:code-log newspack.dev` shows the new commit (`d5e8baaca0`) at the top; `git show --stat` on the fetched commit confirms **only** `wp-content/themes/newspack-block-theme/CLAUDE.md` (+1 line) and `.hooks/pre-push` (mode change only) — nothing else touched, exactly as predicted. Pulled into local `master` (fast-forward) and pushed to `github` (`origin` already had it from the Pantheon-side commit) — both remotes back in sync. Owner doing the visual check on `dev` next; promotion to `test`/`live` and the `radio`/`podcast` real runs intentionally on hold until that's confirmed.

- **Remaining for full rollout**: visual confirmation of podcast's new carousel behavior; Studio symlink setup (open question #3, still unresolved — no Studio site exists for this repo on any branch); final owner visual check on `live-newspack.pantheonsite.io` (in progress); owner visual check on `dev` after today's `newspack-block-theme` reinstall (in progress) before promoting to `test`/`live` or running `radio`/`podcast` for real.

## Known issues fixed in sync-plugins.sh (read before modifying)

1. Used `terminus connection:info --field=connection_mode`, which doesn't expose that field at all (only SFTP/Git/MySQL connection strings). Fixed to use `terminus env:info --field=connection_mode` instead (matches the "Connection Mode" column in `terminus env:list`).
2. `terminus env:commit` and the subsequent `terminus connection:set ... git` can both report false negatives ("no code to commit" / "uncommitted changes") for several seconds after a real change lands, due to Pantheon-side propagation lag — this actually caused a full silent failure on `podcast` once (update applied, never committed, script claimed success anyway). Fixed with explicit retry/backoff loops around both the commit and the mode-switch steps, checking Terminus's actual output text rather than just exit codes, and refusing to proceed past an ambiguous state. The mode-switch call also runs with stdin closed so an unexpected "are you sure, this discards your work" prompt fails safe (defaults to no) instead of hanging or getting blindly confirmed.

## Guiding principle

- **Site repo (`newspack-platform`) = deployment vehicle.** Three branches, one Pantheon site (`newspack`), three multidev environments. Never hand-edited for plugin/theme code.
- **Custom theme layer = controlled iteration layer.** `newspack-theme-child` and `newspack-radio-theme` are the only code we author. They live in their own repos and are synced in, never edited inside the site repo.
- **Everything else = automated.** Newspack's own plugins, `newspack-theme`, `newspack-block-theme`, the radio/podcast add-ons, and every WordPress.org-hosted plugin Newspack recommends (Jetpack, Yoast SEO, Site Kit, Co-Authors Plus, etc.) are pulled and updated by script — no dashboard SFTP, no manual downloads.

## Branches and environments

Confirmed via `terminus site:info newspack` and `terminus env:list newspack` (2026-07-24). Full environment list on the site: `dev`, `test`, `live`, `radio`, `podcast`, `donate`.

| Branch | Pantheon env(s) | URL | Plugins/themes beyond core Newspack |
|---|---|---|---|
| `master` | `dev` → `test` → `live` (promotion pipeline) | dev/test/live-newspack.pantheonsite.io | `newspack-theme-child` |
| `radio` | `radio` (flat, no test/live tier) | radio-newspack.pantheonsite.io | `radio-station`, `mp3-music-player-by-sonaar`, `newspack-theme-child`, `newspack-radio-theme` |
| `podcast` | `podcast` (flat, no test/live tier) | podcast-newspack.pantheonsite.io | `simple-podcasting`, `newspack-theme-child`, `newspack-radio-theme` |
| — | `donate` | donate-newspack.pantheonsite.io | Out of scope for this automation for now |

Remotes on the site repo: `origin` → Pantheon (drush.in), `github` → `OpenProducer/newspack-platform`. Each environment is updated independently — never merge plugin/theme updates between branches.

### Promotion pipeline (master branch only)

`master` is the only branch with a `dev`/`test`/`live` tier. `sync-plugins.sh` and `sync-themes.sh` only ever act on `dev` — they hard-refuse `test`/`live` as targets. Getting a validated change to production is a separate, manual promotion step:

```
terminus env:deploy newspack.test --message="Promote plugin/theme updates"
# verify test
terminus env:deploy newspack.live --message="Promote plugin/theme updates"
```

`radio` and `podcast` have no test/live tier — their multidev *is* the live site for that variant, so a sync there is immediately public. Treat those runs with the same caution you'd give a production deploy.

## Custom theme repos (new)

| Theme | Used on | Repo (proposed) |
|---|---|---|
| `newspack-theme-child` | master, radio, podcast | `OpenProducer/newspack-theme-child` |
| `newspack-radio-theme` | radio, podcast, Radio Pro site | `OpenProducer/newspack-radio-theme` |

Today both live as plain, historyless directories baked into all three branches. The change: give each its own repo with real history, and stop editing them inside `newspack-platform`. Local edits happen in the theme repos themselves; `sync-themes.sh` pushes the current state into whichever branches use them.

**Extraction source, confirmed 2026-07-24:**
- `newspack-theme-child` is byte-identical across `master`/`radio`/`podcast` — no conflict, extract from any (used `radio`).
- `newspack-radio-theme` had **diverged** between `radio` and `podcast` — `radio`'s `functions.php` carries active 2025-12-05 development (carousel/venue/date injection logic); `podcast`'s copy hasn't been touched since 2024-12-11 and lacks that logic entirely, carrying different TEC boilerplate instead. Confirmed via git log dates and cross-checked byte-for-byte against the live Radio Pro Studio install (`newspack-radio-pro/wp-content/themes/newspack-radio-theme`), which matches `radio` exactly. **`radio` is the canonical source.** Extracting it means `podcast` will pick up the carousel logic it's currently missing the first time `sync-themes.sh --branch podcast` runs — expected, not a regression, but worth a visual check on the podcast site afterward since it's new behavior there.

## Local rapid development — WordPress Studio

Single Studio site, branch-switched (not one Studio site per branch):

1. Clone `newspack-platform`, check out the branch you're working on (`master`, `radio`, or `podcast`) as a Studio site.
2. Clone `newspack-theme-child` and `newspack-radio-theme` locally, outside the Studio site directory.
3. Symlink them into the Studio site's `wp-content/themes/`:
   ```
   ln -s ~/Dev/projects/newspack-theme-child   wp-content/themes/newspack-theme-child
   ln -s ~/Dev/projects/newspack-radio-theme   wp-content/themes/newspack-radio-theme
   ```
4. Edits in the theme repos reflect instantly in Studio — no copy step, same benefit the symlink approach gave Radio Pro's enhancements plugin.

Switching branches: check out the new branch in `newspack-platform`, confirm the symlinks still resolve (they will, since the theme repos live outside the site repo), restart Studio's site if needed.

*Note: confirm the exact local folder Studio uses for a given site (via its "Open in Terminal" / site settings) before wiring the symlinks — this can vary by Studio version and wasn't verified against your installed version.*

## Two-script automation architecture

Both scripts live at the site repo root, same guarded pattern as Radio Pro's three scripts. Safe test entry point for either:

```
./sync-[script].sh --dry-run
```

### `sync-plugins.sh` — WordPress.org plugins & themes via Terminus

```
./sync-plugins.sh --env dev      # master branch
./sync-plugins.sh --env radio    # radio branch
./sync-plugins.sh --env podcast  # podcast branch
./sync-plugins.sh --env dev --dry-run
```

What it automates:
- Authenticates with Terminus
- Queries available plugin **and** theme updates via WP-CLI (`--format=json`)
- Skips and flags pre-release versions (alpha/beta/rc) automatically
- Always excludes the guarded custom themes (`newspack-theme-child`, `newspack-radio-theme`)
- Checks `newspack-theme` (+ its 5 named style variants) and `newspack-block-theme` via the **GitHub Releases API** (see below) — these are not on WordPress.org, so `wp theme update` can never see them
- Switches the target environment to SFTP mode
- Runs `wp plugin update --all` and `wp theme update` (guarded themes excluded), then installs any outdated GitHub-release theme zips in the same SFTP session
- Shows a diff/summary before committing
- Commits via `terminus env:commit` with an auto-generated message listing updated slugs + versions (wp.org packages and GitHub-release themes both, one commit)
- Switches the environment back to Git mode, verifies the switch
- Prints a structured summary report

This replaces the manual Pantheon Dashboard → SFTP → wp-admin update workflow entirely.

#### GitHub-Releases-sourced themes (added 2026-07-25)

`newspack-theme` and `newspack-block-theme` have no WordPress.org presence (confirmed: Theme URI points to Automattic's GitHub repos, no Update URI header) — `wp theme update` silently can't see updates for them, a real coverage gap `sync-plugins.sh` had from the start. Fixed by extending `sync-plugins.sh` itself (not a new script — see rationale below) with a second check phase:

- For each of `Automattic/newspack-theme` and `Automattic/newspack-block-theme`: `GET /repos/{owner}/{repo}/releases/latest`, trusting GitHub's own `prerelease`/`draft` filtering rather than reimplementing semver comparison locally.
- `newspack-theme`'s releases ship 6 separately-zipped variants per tag (the base theme plus 5 named style variants: `newspack-joseph`, `newspack-katharine`, `newspack-nelson`, `newspack-sacha`, `newspack-scott`) — one API call covers all 6 installed slugs. `newspack-block-theme` is 1:1.
- Per slug: compares `terminus wp $SITE_ENV -- theme get <slug> --field=version` against the release's `tag_name`. If the theme isn't installed on that environment at all, skips it with a note rather than force-installing something the site never had.
- If different: downloads the matching `<slug>.zip` release asset and `wp theme install <url> --force`s it in the same SFTP session as the wp.org plugin/theme updates, folded into the same commit.
- **Why extend `sync-plugins.sh` instead of a new script**: the requirement that these land in the *same commit* as the wp.org updates rules out a fully separate script (which would need its own commit, or an awkward `--skip-commit` handoff between two script invocations). Kept as a clearly-delineated section within the script rather than interleaved with the wp.org logic, to avoid blending two very different "sources of truth" (WP's own update API vs. manually polled GitHub Releases) into one tangle.
- Bash 3.2 compatible by construction (case-statement slug lookup, not `declare -A`) — this repo's stock macOS bash bit us once already on `sync-themes.sh`, not repeating that mistake here.
- No new dependency: plain `curl` against `api.github.com` (public repos, unauthenticated rate limit of 60/hr is ample for ~2 calls/run), no `gh` CLI requirement.

**Known upstream quirk (confirmed 2026-07-25, `newspack-block-theme` only):** its release build correctly bumps a version constant in `functions.php` but leaves `style.css`'s WP-standard `Version:` header one release behind — e.g. the official `v1.28.1` "stable" release zip's own `style.css` says `Version: 1.28.1-alpha.1`. Verified by downloading both the `v1.28.1-alpha.1` and `v1.28.1` release zips directly and diffing them: `functions.php`'s `NEWSPACK_BLOCK_THEME_VERSION` constant correctly reads `1.28.1` in the stable zip, `style.css` doesn't. `newspack-theme` does not have this bug (its `style.css` version matches its tag exactly). Since `wp theme get --field=version` (and WP core itself) only ever reads `style.css`, this means the version-compare step above will likely flag `newspack-block-theme` as needing an update on *every* run, even immediately after installing the true latest. This is expected and treated as harmless by design — a false-positive here just re-installs identical bytes, the resulting diff comes back empty, and nothing gets committed. The script deliberately does not re-check the version string after install to "confirm" success; `wp theme install`'s own exit code is the only success signal it trusts.

This was caught in practice: `dev` briefly appeared to be running a pre-release build of `newspack-block-theme` (`1.28.1-alpha.1`). Investigation (see Progress log) found the *actual* installed code was already the correct stable content — `functions.php` already read `1.28.1` — the alpha-looking version string was purely this upstream cosmetic bug. No fix was needed or applied; the SFTP session opened to investigate was discarded (`terminus connection:set newspack.dev git --yes`) rather than committing a no-op.

### `sync-themes.sh` — custom child themes

```
./sync-themes.sh --branch master    # newspack-theme-child only
./sync-themes.sh --branch radio     # both themes
./sync-themes.sh --branch podcast   # both themes
./sync-themes.sh --branch radio --dry-run
```

What it automates:
- Verifies the site repo is on the target branch and clean
- Verifies each source theme repo is clean and on its production branch
- Rsyncs the relevant theme(s) into `wp-content/themes/` in the site repo
- Shows `git diff --stat` before committing
- Auto-generates commit message: `Sync themes: newspack-theme-child@<hash> [newspack-radio-theme@<hash>]`
- Pushes to both `origin` (Pantheon) and `github`

This replaces the manual "download from GitHub, copy-paste locally" theme workflow.

## Guardrails

- Never edit plugin or theme code directly inside `wp-content/` in the site repo — all changes originate in `sync-plugins.sh` (upstream pulls) or the theme repos (via `sync-themes.sh`).
- `sync-plugins.sh` always excludes the two guarded custom themes.
- Pre-release plugin/theme versions are never auto-updated.
- Each branch/environment is updated independently; never merge plugin or theme updates between `master`, `radio`, and `podcast`.
- Run `sync-plugins.sh` before `sync-themes.sh` in the same session to avoid push conflicts, mirroring Radio Pro's script-ordering rule.
- Tag only after post-sync verification passes (site loads, no PHP warnings, affected features spot-checked).
- `sync-plugins.sh` and `sync-themes.sh` never target `test`, `live`, or `donate` — `test`/`live` are reached only via `terminus env:deploy` promotion from a verified `dev`; `donate` is out of scope for now.

## Open questions before this ships

~~1. Theme sourcing mechanism~~ — resolved 2026-07-25: directly from Automattic's individual GitHub repos via the Releases API, not `newspack-workspace`. Investigated `newspack-workspace`'s `bin/repos.sh` and its vendored `themes/newspack-theme`, `themes/newspack-block-theme` directories first — turned out those track `main` HEAD (unreleased dev trunk, confirmed ahead of even the newest alpha tag), not a pinned release, so they're the wrong reference for a production site. See "GitHub-Releases-sourced themes" under `sync-plugins.sh` above for the mechanism that got built instead.
2. **GitHub sync for `sync-plugins.sh`** — Radio Pro's plugin-update script only commits on Pantheon's internal git per environment; it doesn't push those commits to GitHub. Decide whether `sync-plugins.sh` should also pull-and-push to `github` after `terminus env:commit`, or whether GitHub stays theme-repo-only and gets periodic manual syncs from Pantheon.
3. **Studio local site path** — confirm the actual filesystem path Studio uses so the symlink step in the setup checklist is accurate.

~~4. History for the two extracted themes~~ — resolved 2026-07-24: preserve history via `git subtree split`, not a fresh start. See "Theme repo extraction" below for the validated commands.

~~5. Environment topology~~ — resolved 2026-07-24: `dev`/`test`/`live` promotion pipeline for `master`; `radio`/`podcast` are flat (no test/live); `donate` confirmed out of scope.

## Theme repo extraction (validated and executed 2026-07-24)

Commands below were dry-run in a disposable scratch clone (`git clone` to `/tmp`, never touched the real repo) and both extractions succeeded cleanly:
- `newspack-radio-theme` → 6 commits of real history (`functions.php`, `style.css`, `screenshot.png` only, no `wp-content/` path contamination). Carries the full carousel/venue feature evolution.
- `newspack-theme-child` → 10 commits (adds `style.ctcbackup.css`).

Both extracted from the `radio` branch (confirmed canonical source, see above) — not `master` or `podcast`.

**Step 1 — create two empty repos on GitHub**, no README/license/gitignore (an initial auto-commit would conflict with the extracted history): `OpenProducer/newspack-theme-child` and `OpenProducer/newspack-radio-theme`.

**Step 2 — from inside `newspack-platform`** (this repo, NOT the new theme repos):
```
git checkout radio

git subtree split --prefix=wp-content/themes/newspack-radio-theme -b extract-radio-theme
git subtree split --prefix=wp-content/themes/newspack-theme-child -b extract-theme-child

git push git@github.com:OpenProducer/newspack-radio-theme.git extract-radio-theme:main
git push git@github.com:OpenProducer/newspack-theme-child.git extract-theme-child:main

# clean up the local extraction branches once pushed
git branch -D extract-radio-theme extract-theme-child
git checkout master
```

**Step 3 — clone both new repos locally**, siblings of `newspack-platform` (matches the default `THEME_CHILD_REPO`/`RADIO_THEME_REPO` paths `sync-themes.sh` already expects, so no script edits needed):
```
git clone git@github.com:OpenProducer/newspack-theme-child.git ~/Dev/projects/newspack-theme-child
git clone git@github.com:OpenProducer/newspack-radio-theme.git ~/Dev/projects/newspack-radio-theme
```

**Step 4 — first real test**: `./sync-themes.sh --branch master --dry-run` (theme-child only, lowest risk), then a real run, then `radio`/`podcast`. Expect `podcast` to pick up the carousel/venue logic it's currently missing — that's expected new behavior, not a bug, so do a visual check on the podcast site's carousel after that specific run.

**Step 5 — Studio symlinks** (once repos are cloned locally):
```
ln -s ~/Dev/projects/newspack-theme-child   wp-content/themes/newspack-theme-child
ln -s ~/Dev/projects/newspack-radio-theme   wp-content/themes/newspack-radio-theme
```

## Rollout checklist

1. ~~Confirm Terminus auth and the Pantheon site/environment machine names~~ — done: site `newspack`; envs `dev`, `test`, `live`, `radio`, `podcast`, `donate`.
2. ~~Validate `sync-plugins.sh`~~ — done on all three environments (`dev`, `radio`, `podcast`); see Progress log and Known issues above.
3. ~~Create `OpenProducer/newspack-theme-child` and `OpenProducer/newspack-radio-theme` on GitHub and run the extraction~~ — done 2026-07-24, see Progress log and "Theme repo extraction" above.
4. **Current task**: clone both new theme repos locally; wire the WordPress Studio symlinks.
5. `sync-themes.sh --branch master --dry-run`, then a real run on `master` (theme-child only, lowest risk).
6. `sync-themes.sh --branch radio --dry-run`, then a real run; then same for `podcast` — expect podcast's carousel behavior to change, that's intentional (see above).
7. Promote `master`'s plugin + theme changes: `terminus env:deploy newspack.test`, verify, `terminus env:deploy newspack.live`.
8. Once plugins and themes are validated end to end on all three branches, retire the manual Pantheon Dashboard SFTP workflow and the manual theme download/copy-paste workflow.
