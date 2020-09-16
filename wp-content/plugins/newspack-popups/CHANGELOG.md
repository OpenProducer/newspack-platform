# [1.11.0](https://github.com/Automattic/newspack-popups/compare/v1.10.0...v1.11.0) (2020-09-15)


### Bug Fixes

* handle false get_transient return value ([#211](https://github.com/Automattic/newspack-popups/issues/211)) ([11c9f3b](https://github.com/Automattic/newspack-popups/commit/11c9f3b17bcc125e9a87dfce942f03194e9c5657))


### Features

* lightweight GET API for campaigns ([#208](https://github.com/Automattic/newspack-popups/issues/208)) ([3bae873](https://github.com/Automattic/newspack-popups/commit/3bae8733a17e58db779c595ea2c2c82c9beb92f4))

# [1.10.0](https://github.com/Automattic/newspack-popups/compare/v1.9.4...v1.10.0) (2020-08-26)


### Features

* add interoperation features ([#201](https://github.com/Automattic/newspack-popups/issues/201)) ([7b5e941](https://github.com/Automattic/newspack-popups/commit/7b5e941975b63cc06b6a962063ad742049b11cf1))

## [1.9.4](https://github.com/Automattic/newspack-popups/compare/v1.9.3...v1.9.4) (2020-08-18)


### Bug Fixes

* dismissal events as non-interaction ([164ce5f](https://github.com/Automattic/newspack-popups/commit/164ce5fb91900c670a5ced2f632d7449d11538e0))

## [1.9.3](https://github.com/Automattic/newspack-popups/compare/v1.9.2...v1.9.3) (2020-08-11)


### Bug Fixes

* add permission_callback to REST route defn ([2626bb6](https://github.com/Automattic/newspack-popups/commit/2626bb6c70592fac741f0723a85f221c47782673))

## [1.9.2](https://github.com/Automattic/newspack-popups/compare/v1.9.1...v1.9.2) (2020-07-22)


### Bug Fixes

* a typo in one of the campaign settings options ([7ddd1c8](https://github.com/Automattic/newspack-popups/commit/7ddd1c8976c4583a44f7af6c984708dc8c83b57c))
* js error on non-AMP pages ([81e01fc](https://github.com/Automattic/newspack-popups/commit/81e01fceec66fa3d83c0d6b4559bc85ffb5b45f9))

## [1.9.1](https://github.com/Automattic/newspack-popups/compare/v1.9.0...v1.9.1) (2020-07-15)


### Bug Fixes

* utm paramers suppression conflict ([b2c577c](https://github.com/Automattic/newspack-popups/commit/b2c577c71fd910c3be6e7c0e621de34fcceaaaa2))

# [1.9.0](https://github.com/Automattic/newspack-popups/compare/v1.8.0...v1.9.0) (2020-07-14)


### Bug Fixes

* decode URL when checking utm_source suppression; fix transient ([#178](https://github.com/Automattic/newspack-popups/issues/178)) ([cd55311](https://github.com/Automattic/newspack-popups/commit/cd553112312c9f8974354193a4e025f0969848a1)), closes [#177](https://github.com/Automattic/newspack-popups/issues/177)
* **settings:** styling ([2c77bd4](https://github.com/Automattic/newspack-popups/commit/2c77bd4a03f369d2337533de249b890f50a111ce))


### Features

* hide non-test campagins for logged-in users ([#169](https://github.com/Automattic/newspack-popups/issues/169)) ([476e5c0](https://github.com/Automattic/newspack-popups/commit/476e5c030b10417d45bc3abc33925681669a2d24))
* **settings:** suppress all newsletter campaigns if one was dismissed ([#175](https://github.com/Automattic/newspack-popups/issues/175)) ([ed91a73](https://github.com/Automattic/newspack-popups/commit/ed91a7357ac94a0c82c3beda622230d99e2f8fd9))
* add option to suppress newsletter campaigns if visiting from email ([e1371f5](https://github.com/Automattic/newspack-popups/commit/e1371f58dee5331210009207ab59cfd5e2eee959))

# [1.8.0](https://github.com/Automattic/newspack-popups/compare/v1.7.2...v1.8.0) (2020-07-09)


### Bug Fixes

* make popupseen non-interactive event ([4278a17](https://github.com/Automattic/newspack-popups/commit/4278a17b948958b8f07ea3214c681fa449fb4bd9))


### Features

* mark load event as non interaction ([9abff5d](https://github.com/Automattic/newspack-popups/commit/9abff5d35666134e66b54cc81b33bfbccf86c2f2))

## [1.7.2](https://github.com/Automattic/newspack-popups/compare/v1.7.1...v1.7.2) (2020-07-07)


### Bug Fixes

* duplicate execution of the_content filter ([2692867](https://github.com/Automattic/newspack-popups/commit/26928678c00fb9993785ef049154579382387b1d))

## [1.7.1](https://github.com/Automattic/newspack-popups/compare/v1.7.0...v1.7.1) (2020-06-30)


### Bug Fixes

* ad insertion in overlay campaigns ([d036de1](https://github.com/Automattic/newspack-popups/commit/d036de19d81fd8f03b0a15263f0905258214852d)), closes [#158](https://github.com/Automattic/newspack-popups/issues/158)

# [1.7.0](https://github.com/Automattic/newspack-popups/compare/v1.6.0...v1.7.0) (2020-06-23)


### Bug Fixes

* insert all campaigns into content in one pass ([#150](https://github.com/Automattic/newspack-popups/issues/150)) ([3c30b8e](https://github.com/Automattic/newspack-popups/commit/3c30b8e9a4907ba08ab315538eed35f2990d2cde))
* non-amp form handling for inline campaigns ([#154](https://github.com/Automattic/newspack-popups/issues/154)) ([12b27b4](https://github.com/Automattic/newspack-popups/commit/12b27b48bf57e067c3372aa7c4b44652adbabe78))


### Features

* **analytics:** use newspack-plugin's filter where possible ([#155](https://github.com/Automattic/newspack-popups/issues/155)) ([0ee60bc](https://github.com/Automattic/newspack-popups/commit/0ee60bccbc1c7548927b4f44db1b0a884837c609))
* add class names for newsletter prompts, for analytics tracking ([9ca1f83](https://github.com/Automattic/newspack-popups/commit/9ca1f83c66755e868d537a95da65786ce405ffbe))

# [1.6.0](https://github.com/Automattic/newspack-popups/compare/v1.5.3...v1.6.0) (2020-06-18)


### Bug Fixes

* namespace use in register_rest_route ([5c69bd7](https://github.com/Automattic/newspack-popups/commit/5c69bd76f6f57661b6cdebb40889549cbae24635))
* related posts in campaigns ([#144](https://github.com/Automattic/newspack-popups/issues/144)) ([caef323](https://github.com/Automattic/newspack-popups/commit/caef323482cdea8e100681ddd028aa45dfc6ab45))


### Features

* clean up release zip ([bafe05e](https://github.com/Automattic/newspack-popups/commit/bafe05ed3dea557fcf10cf4b7d119ed564ce4766))

## [1.5.3](https://github.com/Automattic/newspack-popups/compare/v1.5.2...v1.5.3) (2020-06-09)


### Bug Fixes

* handle default background and overlay colors in the editor ([e51c5d8](https://github.com/Automattic/newspack-popups/commit/e51c5d840aa47b13020f2a1794c73b6218acddac)), closes [#56](https://github.com/Automattic/newspack-popups/issues/56)
* remove amp-form in non-amp requests ([#127](https://github.com/Automattic/newspack-popups/issues/127)) ([77c2f2f](https://github.com/Automattic/newspack-popups/commit/77c2f2fa656811c1edd4701011b9ff2ff3c70a5a))

## [1.5.2](https://github.com/Automattic/newspack-popups/compare/v1.5.1...v1.5.2) (2020-05-15)


### Bug Fixes

* **insertion:** fix insertion of overlay scroll-triggered popups ([6dc4427](https://github.com/Automattic/newspack-popups/commit/6dc4427e40d2722277d91ee1e11dd92c1fd7dd57)), closes [#124](https://github.com/Automattic/newspack-popups/issues/124)
* dont load popups on products because of amp-form ([8eb5ebc](https://github.com/Automattic/newspack-popups/commit/8eb5ebcc643df4d40f800af2402e70c906becb3d))

## [1.5.1](https://github.com/Automattic/newspack-popups/compare/v1.5.0...v1.5.1) (2020-05-12)


### Bug Fixes

* popup insertion logic ([#94](https://github.com/Automattic/newspack-popups/issues/94)) ([79ef273](https://github.com/Automattic/newspack-popups/commit/79ef2730d5930183b98c2f68d1dfa21bb32cf5a2)), closes [#92](https://github.com/Automattic/newspack-popups/issues/92)

# [1.5.0](https://github.com/Automattic/newspack-popups/compare/v1.4.1...v1.5.0) (2020-05-07)


### Bug Fixes

* fix couple issues with popup utm suppression ([c4f5a1b](https://github.com/Automattic/newspack-popups/commit/c4f5a1ba0ecb62c03bf3ef27c49954302ff0a1fe))
* resolve issue causing admin bar to display incorrectly ([#116](https://github.com/Automattic/newspack-popups/issues/116)) ([97270ea](https://github.com/Automattic/newspack-popups/commit/97270ea4fb2e1952bc9fcc502370efdd057c6ebc))


### Features

* convenience method for updating pop-up options ([#112](https://github.com/Automattic/newspack-popups/issues/112)) ([4f14604](https://github.com/Automattic/newspack-popups/commit/4f146041067aed7f50033fa2849040dd42ae1ada))
* support for draft popups ([c7c7fe5](https://github.com/Automattic/newspack-popups/commit/c7c7fe5e7cd7bdc27bcd0db60eba32b1611ada45))

## [1.4.1](https://github.com/Automattic/newspack-popups/compare/v1.4.0...v1.4.1) (2020-04-29)


### Bug Fixes

* prevent infinite loop with inline popups and paywalled content ([83633ee](https://github.com/Automattic/newspack-popups/commit/83633ee6ab04b5c773bfe482848688d5d803010a))

# [1.4.0](https://github.com/Automattic/newspack-popups/compare/v1.3.1...v1.4.0) (2020-04-24)


### Bug Fixes

* check if meta exists ([37786da](https://github.com/Automattic/newspack-popups/commit/37786daa4ce92d1b82b3d5938270d82c140ea719))


### Features

* add as filter ([9f74644](https://github.com/Automattic/newspack-popups/commit/9f74644494cc98813e6429e5c9b6333c0f059725))
* add assessment in insert_popups_amp_access ([09c9d5d](https://github.com/Automattic/newspack-popups/commit/09c9d5dcc464765c05003064f36e09cdc3d8b6a1))
* change settings UI ([e5bef8f](https://github.com/Automattic/newspack-popups/commit/e5bef8f67a2b6485648ddc024942accbc738ae71))
* enable disabling popups for specific posts and pages ([2cdaf31](https://github.com/Automattic/newspack-popups/commit/2cdaf316755e3f7f4e76650b8863b7026523d20b))
* enqueue script for posts and pages only ([4d15744](https://github.com/Automattic/newspack-popups/commit/4d1574409e2251afee3703ebce848a7cc5b6340a))

## [1.3.1](https://github.com/Automattic/newspack-popups/compare/v1.3.0...v1.3.1) (2020-04-22)


### Bug Fixes

* **amp:** disable popups on non-AMP pages with POST form elements ([366407d](https://github.com/Automattic/newspack-popups/commit/366407d4cebaede14c7bb10d0e3a8509fd86ab15))

# [1.3.0](https://github.com/Automattic/newspack-popups/compare/v1.2.0...v1.3.0) (2020-04-21)


### Bug Fixes

* dont count pageviews when initializing popup analytics ([969d3b6](https://github.com/Automattic/newspack-popups/commit/969d3b6c4b1e7d23ea49a02d25f49bfbbef61f85))
* prevent reporting analytics data for popup previews ([5eaec91](https://github.com/Automattic/newspack-popups/commit/5eaec9147eac9f3a5cc12c62ee3f05ddff061de7))


### Features

* add class to pop-up if display title is enabled ([#98](https://github.com/Automattic/newspack-popups/issues/98)) ([ee37d73](https://github.com/Automattic/newspack-popups/commit/ee37d7379c92fd0b40d5ca6bec2505940c673e73))

# [1.2.0](https://github.com/Automattic/newspack-popups/compare/v1.1.1...v1.2.0) (2020-04-01)


### Bug Fixes

* correct query reset that can reset the loop ([#91](https://github.com/Automattic/newspack-popups/issues/91)) ([66b6389](https://github.com/Automattic/newspack-popups/commit/66b6389b1e8d4478395c18fa0df9f0ca0f364e83))


### Features

* add style to subscribe pattern 1 ([#93](https://github.com/Automattic/newspack-popups/issues/93)) ([98ddaa1](https://github.com/Automattic/newspack-popups/commit/98ddaa1c8d984045302134dfc099e1baa3ae0723))
* handle MC4WP forms submissions ([95c8073](https://github.com/Automattic/newspack-popups/commit/95c807363a063759acc9ef96fffda196c7e30adc))
* handle multiple inline popups ([#89](https://github.com/Automattic/newspack-popups/issues/89)) ([6c303ca](https://github.com/Automattic/newspack-popups/commit/6c303ca02f047e870b10d3b5a32f344c49fbdcf8))

## [1.1.1](https://github.com/Automattic/newspack-popups/compare/v1.1.0...v1.1.1) (2020-03-24)


### Bug Fixes

* clear floats when using inline popup ([#87](https://github.com/Automattic/newspack-popups/issues/87)) ([6e7991f](https://github.com/Automattic/newspack-popups/commit/6e7991faf44d81b9db61d008e108a6a295dfaeb6))
* resolve inline popup collisions with super cool ad inserter content ([#86](https://github.com/Automattic/newspack-popups/issues/86)) ([2fbd806](https://github.com/Automattic/newspack-popups/commit/2fbd806b916d294c3557bc2325bab1465fc64928))
* **analytics:** handle non-AMP pages and Mailchimp ([#83](https://github.com/Automattic/newspack-popups/issues/83)) ([d53bb02](https://github.com/Automattic/newspack-popups/commit/d53bb02e5ada8188382e58bd97859f8496457e0d))
