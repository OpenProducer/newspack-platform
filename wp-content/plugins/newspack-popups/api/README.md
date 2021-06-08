# Lightweight API

The lightweight API is a stripped-down alternative to the WordPress REST API. Prompts will often be present on every page of a site, which can lead to performance issues as a result of a large number of uncacheable API requests.

## Configuration

The feature requires a custom config file (`wp-content/newspack-popups-config.php`). See the main README for more information.

## API Overview

| Endpoint | Frequency | Purpose | In codebase
| :------------- | :------------- | :------------- | :------------- |
| `GET /api/campaigns/index.php` | Page load | Tells the page which prompts should be displayed. This is the [authorization endpoint](https://amp.dev/documentation/components/amp-access/#authorization-endpoint) of the `amp-access` AMP component. | `Newspack_Popups_Inserter::insert_popups_amp_access`
| `GET /api/segmentation/index.php` | Page load | An analytics [config rewriter](https://amp.dev/documentation/components/amp-analytics/?format=websites#dynamically-rewrite-a-configuration), which inserts segmentation-derived custom dimensions. This feature has to be [additionally configured in Newspack Plugin's Analytics Wizard](https://newspack.pub/support/analytics/#a-custom-dimensions). | `Newspack_Popups_Segmentation::insert_gtag_amp_analytics`
| `POST /api/campaigns/index.php` | Interaction event | Reporting prompt-related events, such as user seeing or suppressing a prompt. | `Newspack_Popups_Model::insert_event_tracking`
| `POST /api/segmentation/index.php` | Page load or event | Reporting client segmentation-related data, such as a donation or newsletter subscription status. | `Newspack_Popups_Segmentation::insert_amp_analytics`

## Disabling the API calls

In high-traffic periods or for debugging purposes, it's possible to disable any calls to the API with the ["non-interactive mode" setting](https://newspack.pub/support/campaigns/settings/).
