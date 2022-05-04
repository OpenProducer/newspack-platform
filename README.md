Newspack is an open-source publishing platform built on WordPress for small to medium sized news organizations. It is an “opinionated” platform that stakes out clear, best-practice positions on technology, design, and business practice for news publishers.

## How to install Newspack on your site

If you'd like to install Newspack on your self-hosted site or want to try Newspack out, the easiest way to do so is to [enable the Newspack plugin](https://github.com/Automattic/newspack-plugin/) and [the latest theme release](https://github.com/Automattic/newspack-theme/). To take full advantage of Newspack, the plugin and theme should be run together, but each should also work fine individually.

## Reporting Security Issues

To disclose a security issue to our team, [please submit a report via HackerOne here](https://hackerone.com/automattic/).

## Contributing to Newspack

If you have a patch or have stumbled upon an issue with the Newspack plugin/theme, you can contribute this back to the code. [Please read the Newspack contributor guidelines for more information on how you can do this.](https://github.com/Automattic/newspack-plugin/blob/master/.github/CONTRIBUTING.md)

### Development

- Run `npm start` to compile the SCSS and JS files, and start file watcher.
- Run `npm run build` to perform a single compilation run.

#### Environment variables

Some features require environment variables to be set (e.g. in `wp-config.php`):

```php
// Support
define( 'NEWSPACK_SUPPORT_API_URL', 'https://super-tech-support.zendesk.com/api/v2' );
define( 'NEWSPACK_SUPPORT_EMAIL', 'support@company.com' );
define( 'NEWSPACK_WPCOM_CLIENT_ID', '12345' );

// Optional
define( 'NEWSPACK_SUPPORT_IS_PRE_LAUNCH', true );
```

## Support or Questions

This repository is not suitable for support or general questions about Newspack. Please only use the Newspack issue trackers for bug reports and feature requests, following [the contribution guidelines](https://github.com/Automattic/newspack-plugin/blob/master/.github/CONTRIBUTING.md).

Support requests in issues on the Newspack repository will be closed on sight.

## Branches

The `default` branch of this repository is where PRs are merged, and has [CI](https://github.com/pantheon-systems/WordPress/tree/default/.circleci) that copies `default` to `master` after removing the CI directories. This allows customers to clone from `master` and implement their own CI without needing to worry about potential merge conflicts.

## Custom Upstreams

If you are using this repository as a starting point for a custom upstream, be sure to review the [documentation](https://pantheon.io/docs/create-custom-upstream#pull-in-core-from-pantheons-upstream) and pull the core files from the `master` branch.
