# [1.12.0](https://github.com/Automattic/newspack-plugin/compare/v1.11.0...v1.12.0) (2020-07-07)


### Features

* clean up release zip ([75d2167](https://github.com/Automattic/newspack-plugin/commit/75d2167f011d3fe7daed1bb67b25cd13f00ff568))
* ntg mailing list events in amp ([#575](https://github.com/Automattic/newspack-plugin/issues/575)) ([9017dda](https://github.com/Automattic/newspack-plugin/commit/9017ddab56cc45e982283d2421c9287605bcff5d))

# [1.11.0](https://github.com/Automattic/newspack-plugin/compare/v1.10.0...v1.11.0) (2020-06-30)


### Bug Fixes

* add non_interaction: true for scroll events ([a5660ae](https://github.com/Automattic/newspack-plugin/commit/a5660aecc425eaa5e3a4aaf82b0fde758e016742))
* add text domain to text strings ([159256f](https://github.com/Automattic/newspack-plugin/commit/159256f02a16183e34b767ed0d7434502fe35851))
* bug fixes from PR ([99dce9f](https://github.com/Automattic/newspack-plugin/commit/99dce9fb9eac85557d0b7c6088d9092b831e755c))
* cleaner handling of redirectURI without using props ([37c8893](https://github.com/Automattic/newspack-plugin/commit/37c88936a6da32b847f1cac3746291f000ae7f90))
* eslint error ([b2852ed](https://github.com/Automattic/newspack-plugin/commit/b2852ed206916110f03e2f7bffb064d16d9746e2))
* eslint errors ([2558b34](https://github.com/Automattic/newspack-plugin/commit/2558b347a2e8c0f42433a43e298411392f6d429e))
* explicitly set admin user ID when creating Salesforce webhooks ([7bb5246](https://github.com/Automattic/newspack-plugin/commit/7bb52462db0e1ea84388852412199606d61353d9))
* make gam toggle work correctly ([7467c88](https://github.com/Automattic/newspack-plugin/commit/7467c88c2700b04b7723763db53b05cb00281350))
* missing close quote ([d373f27](https://github.com/Automattic/newspack-plugin/commit/d373f27724a3ede863c4dd680da11da934f99717))
* mop up a couple of remain instances of "lead" in text ([0340cc8](https://github.com/Automattic/newspack-plugin/commit/0340cc8eec2abdd8bf3dc15fa8b54dd0e320aa90))
* move getTokens call into lifecycle function instead of render ([22bddef](https://github.com/Automattic/newspack-plugin/commit/22bddefeb7eea5e464b6ce2de4b631fdb891e5b0))
* only create webhooks when SF is connected (and delete when reset) ([5ac347c](https://github.com/Automattic/newspack-plugin/commit/5ac347c351aa6d79148dba19459ec546833e102e))
* properly map WC fields to SF fields on webhook delivery ([0af5581](https://github.com/Automattic/newspack-plugin/commit/0af558132312fd80e49f784351fc4771e8f3d30a))
* remove extra translation wrapper ([85ed3ae](https://github.com/Automattic/newspack-plugin/commit/85ed3ae83597553f0bc9c56448ff82f4ffe19dfc))
* remove unused var to fix lint error ([118eff7](https://github.com/Automattic/newspack-plugin/commit/118eff7c0040bafc3a6cd4436fdffcab45085997))
* sync to Contact, not Lead, and format donation line items ([6970926](https://github.com/Automattic/newspack-plugin/commit/6970926e09aa9d3c5ce50fa2143f164675cdf0da))
* update help copy; await update promise to resolve before validating ([c4edac0](https://github.com/Automattic/newspack-plugin/commit/c4edac0325a7cbb04f639321d6d2044035ea2be5))


### Features

* add ActionCardSections component (from PopupGroup) ([#563](https://github.com/Automattic/newspack-plugin/issues/563)) ([883f22e](https://github.com/Automattic/newspack-plugin/commit/883f22e473995f943fdc15c05f05f9f5b8e0f5f4))
* add request handlers for updating/adding Leads via Salesforce API ([2f7ae6b](https://github.com/Automattic/newspack-plugin/commit/2f7ae6b95992f0e23ebe3bcc12e57b2fa96ef276))
* add some handling for invalid client id/secret and error responses ([a930f7c](https://github.com/Automattic/newspack-plugin/commit/a930f7c1f80f12ee7fc5c596a1e67b1a8c6f312f))
* check refresh token before assuming connection is valid ([a5976e6](https://github.com/Automattic/newspack-plugin/commit/a5976e6e1487b92a855b3f8f5074c59e85e3c14d))
* finish SalesForce OAuth flow in admin dashboard ([92e5805](https://github.com/Automattic/newspack-plugin/commit/92e58051160616bf372924fd688fdfba65c41b87))
* re-enable disabling of text field sonce Salesforce is connected ([d279790](https://github.com/Automattic/newspack-plugin/commit/d279790dcfc1c172d84b400c236b8009398e0372))
* start SalesForce admin UI ([a349b37](https://github.com/Automattic/newspack-plugin/commit/a349b375a7b681c29306740a06a48bc49a9c09e1))
* update SalesForce settings page, store settings as options ([2658022](https://github.com/Automattic/newspack-plugin/commit/26580229117eefbcd2d6536e27f13854361effba))

# [1.10.0](https://github.com/Automattic/newspack-plugin/compare/v1.9.0...v1.10.0) (2020-06-23)


### Features

* **analytics:** add non-AMP submit, ini-load events handling ([#558](https://github.com/Automattic/newspack-plugin/issues/558)) ([fd3edc4](https://github.com/Automattic/newspack-plugin/commit/fd3edc4633351af2735f21601e6e3f892a39adac))

# [1.9.0](https://github.com/Automattic/newspack-plugin/compare/v1.8.0...v1.9.0) (2020-06-18)


### Features

* **campaign-analytics:** better error handling ([900644b](https://github.com/Automattic/newspack-plugin/commit/900644b40eebf95d17fb9dfbb780102225b4ee9b))

# [1.8.0](https://github.com/Automattic/newspack-plugin/compare/v1.7.0...v1.8.0) (2020-06-09)


### Bug Fixes

* add non-interactive event handling ([c0763b1](https://github.com/Automattic/newspack-plugin/commit/c0763b1c3079771315ffe80d98dd97caa78150c0))
* determine non-interactive based on ga event type ([90655bb](https://github.com/Automattic/newspack-plugin/commit/90655bb9c2ab9c8e152324076b027292cda8c9eb))
* ie11 compatibility ([20eab91](https://github.com/Automattic/newspack-plugin/commit/20eab910ada47fc6d38ed4b575f32321731d2e4c))
* non-AMP events ([3a6981d](https://github.com/Automattic/newspack-plugin/commit/3a6981d6c88bf5d5eed60ba504a3e064c0ff66c7))
* remove short-circuit ([6cc0ed7](https://github.com/Automattic/newspack-plugin/commit/6cc0ed7807ebf01c99667e1f7bb5021042fbf8f5))
* scroll event reporting ([3b095b9](https://github.com/Automattic/newspack-plugin/commit/3b095b9e8eabb71ef1d782a95c27fcc271d82a27))


### Features

* add up Campaigns Analytics view ([#516](https://github.com/Automattic/newspack-plugin/issues/516)) ([dd0608d](https://github.com/Automattic/newspack-plugin/commit/dd0608d7d2f6a01c9e94cd75ee6fcd2fca842a1a))
* add value to NTG scroll events ([0a365d6](https://github.com/Automattic/newspack-plugin/commit/0a365d6f4b5d317514efb3d9523e5c78e0c9699c))
* analytics events framework ([4b876a4](https://github.com/Automattic/newspack-plugin/commit/4b876a495f97760f1f6e0a6c174ec2feea087c78))

# [1.7.0](https://github.com/Automattic/newspack-plugin/compare/v1.6.0...v1.7.0) (2020-06-04)


### Bug Fixes

* duplicate key ([f18c0ae](https://github.com/Automattic/newspack-plugin/commit/f18c0ae17a93f811102f853cffabbb2dbf291cd2))
* **support:** validate token before starting chat ([#520](https://github.com/Automattic/newspack-plugin/issues/520)) ([1112600](https://github.com/Automattic/newspack-plugin/commit/1112600549128e01b6314a82866efbe499c6f99e))
* handle empty title for campaigns ([3d4f6df](https://github.com/Automattic/newspack-plugin/commit/3d4f6df817e615e24beaf319854afa1c0ed333b6))
* plugin messages in wizard pluralization ([#518](https://github.com/Automattic/newspack-plugin/issues/518)) ([5661b0a](https://github.com/Automattic/newspack-plugin/commit/5661b0aec5d17ea41147fdf804eab940de553be1)), closes [#453](https://github.com/Automattic/newspack-plugin/issues/453)


### Features

* **support:** enable removing WPCOM token in settings; change auth scope to global ([#546](https://github.com/Automattic/newspack-plugin/issues/546)) ([1210967](https://github.com/Automattic/newspack-plugin/commit/1210967c1f49f3c91603b95e2c462986f044a550))
* **support:** list user's support tickets ([#534](https://github.com/Automattic/newspack-plugin/issues/534)) ([dca3497](https://github.com/Automattic/newspack-plugin/commit/dca34970c207d276f72d7b54293f678659b2e176))
* add small action card and plugin installer ([cb08ab0](https://github.com/Automattic/newspack-plugin/commit/cb08ab0c95570cb2ab793be03267481266bea743))

# [1.6.0](https://github.com/Automattic/newspack-plugin/compare/v1.5.0...v1.6.0) (2020-05-06)


### Bug Fixes

* previewing for inline popups in wizard ([e909372](https://github.com/Automattic/newspack-plugin/commit/e909372d2014e636112faf7241a7c5f2566f8fe4))
* remove pop-ups ui from engagement wizard ([#517](https://github.com/Automattic/newspack-plugin/issues/517)) ([68367b5](https://github.com/Automattic/newspack-plugin/commit/68367b585f9fecab96dc4adec1f82d65b4991ab9))


### Features

* pop-ups wizard ([ae033d0](https://github.com/Automattic/newspack-plugin/commit/ae033d0d0a6a5ebd61abdef1fb70e702bf6abe81))
* popup wizard ui for draft popups ([8095ba4](https://github.com/Automattic/newspack-plugin/commit/8095ba4b32f01cff13768e57e1a2e4b38ebffe92))
* whitelist newspack newsletters plugin ([f1d1ff6](https://github.com/Automattic/newspack-plugin/commit/f1d1ff681855bf1d145275bb529599e9b2fd9203))

# [1.5.0](https://github.com/Automattic/newspack-plugin/compare/v1.4.0...v1.5.0) (2020-04-21)


### Bug Fixes

* admin color scheme conflict with newspack buttons ([#503](https://github.com/Automattic/newspack-plugin/issues/503)) ([c248078](https://github.com/Automattic/newspack-plugin/commit/c248078899a3f4f1c3bf5c829ecde9197aefd07d))


### Features

* **components:**  change mobile preset width of WebPreview ([#509](https://github.com/Automattic/newspack-plugin/issues/509)) ([29e0877](https://github.com/Automattic/newspack-plugin/commit/29e08770120fdf2fa9ad5ed9cd739ae1d2d70dd0))

# [1.4.0](https://github.com/Automattic/newspack-plugin/compare/v1.3.0...v1.4.0) (2020-04-01)


### Features

* add grid view option to the dashboard ([#496](https://github.com/Automattic/newspack-plugin/issues/496)) ([03b0646](https://github.com/Automattic/newspack-plugin/commit/03b06467a7c3d5a7e503c232b1a3dff749c2d6aa))

# [1.3.0](https://github.com/Automattic/newspack-plugin/compare/v1.2.1...v1.3.0) (2020-03-24)


### Features

* **health-check:** expose Health Check to external PHP code ([62cc8e0](https://github.com/Automattic/newspack-plugin/commit/62cc8e0d77ccb42c9d4a63d8cd082a0f1e2afa67))
* **starter content:** parallelise post creation; tweaks for handling E2E ([#489](https://github.com/Automattic/newspack-plugin/issues/489)) ([62974b2](https://github.com/Automattic/newspack-plugin/commit/62974b2090d5ef8fdcddc500ae5b1505ddaa8efc))

## [1.2.1](https://github.com/Automattic/newspack-plugin/compare/v1.2.0...v1.2.1) (2020-03-20)


### Bug Fixes

* **handoff:** enqueue shared JS & CSS in handoff ([#492](https://github.com/Automattic/newspack-plugin/issues/492)) ([a7b8b8f](https://github.com/Automattic/newspack-plugin/commit/a7b8b8f3d5105ea772abe1def0e67d93a7a26ea3))
* **wizards:** broken REST URL in SEO wizard ([21779bd](https://github.com/Automattic/newspack-plugin/commit/21779bd57d20f85ed0eb6af4c25f7e67a25b19ce))

# [1.2.0](https://github.com/Automattic/newspack-plugin/compare/v1.1.0...v1.2.0) (2020-03-17)


### Bug Fixes

* button box-shadow since Gutenberg 7.7 ([#483](https://github.com/Automattic/newspack-plugin/issues/483)) ([0b4e70a](https://github.com/Automattic/newspack-plugin/commit/0b4e70ab71c14c7dd2eb94106f63931b8aad93f6))
* prevent errors if ads plugin out of date with this plugin ([f4943f0](https://github.com/Automattic/newspack-plugin/commit/f4943f07a0976380ca1a142c9aabd8f1c272b3c3))
* use https in author/plugin uri ([fbf7088](https://github.com/Automattic/newspack-plugin/commit/fbf7088e21eb1749d3ebc448c654da7bf608a336))


### Features

* ability to suppress ads on posts and pages ([86b08c2](https://github.com/Automattic/newspack-plugin/commit/86b08c20631e8368ea8f2d589dbd26b38a38f5e1))
* non-random starter content for e2e testing ([b35e749](https://github.com/Automattic/newspack-plugin/commit/b35e749746f05b972c21ff2f2dada04ce5746df9))


### Performance Improvements

* reduce deliverable zip size by 70% ([#481](https://github.com/Automattic/newspack-plugin/issues/481)) ([6a10fb5](https://github.com/Automattic/newspack-plugin/commit/6a10fb55106053265598fa4495ce715a91562d3a))

# [1.1.0](https://github.com/Automattic/newspack-plugin/compare/v1.0.0...v1.1.0) (2020-03-10)


### Bug Fixes

* fix Site Kit module deactivation ([96787e9](https://github.com/Automattic/newspack-plugin/commit/96787e971ef08ce3a6df1cdd715a20d804bb317b))
* standardize REST API namespace usage ([44d15b2](https://github.com/Automattic/newspack-plugin/commit/44d15b20fd113277cd43a9113aabbea963b0c696))


### Features

* **support:** add a link to support docs ([#470](https://github.com/Automattic/newspack-plugin/issues/470)) ([af4754d](https://github.com/Automattic/newspack-plugin/commit/af4754d1856294c73cad429d33c686aac756c7c8))
* **support:** send initial info on chat start ([#471](https://github.com/Automattic/newspack-plugin/issues/471)) ([1392452](https://github.com/Automattic/newspack-plugin/commit/13924525f889de4509f0501e39dd2fec6703e2ba))
* **support:** update subject string for support tickets ([aca9963](https://github.com/Automattic/newspack-plugin/commit/aca9963a778855ee7a9d656605958ac7255450ab))

# 1.0.0 (2020-03-03)


### Bug Fixes

* action card right region text align ([168c64d](https://github.com/Automattic/newspack-plugin/commit/168c64da8c618b33dd1bc950ec596109b48fc364))
* Better population of countries for ones with no states defined ([314d6a2](https://github.com/Automattic/newspack-plugin/commit/314d6a20016a1c5429ceeae3483b67d45adb7f5e))
* linting and formatting js ([#383](https://github.com/Automattic/newspack-plugin/issues/383)) ([b4828c8](https://github.com/Automattic/newspack-plugin/commit/b4828c83a625fd5fe011bb55097b7587714f453c))
* more reliable Site Kit connection check ([05a66f2](https://github.com/Automattic/newspack-plugin/commit/05a66f2269e4fc33c8e43842bcf43604f7ed9869))
* move author bio length setting inside togglegroup ([a374d96](https://github.com/Automattic/newspack-plugin/commit/a374d962f9c59267e5bf601d10c0e0381e60480d))
* newspack logo in starter content ([#465](https://github.com/Automattic/newspack-plugin/issues/465)) ([ace948e](https://github.com/Automattic/newspack-plugin/commit/ace948e56ca4c91026a6edfdd365bdb11a547561))
* npm dev script ([4ec6fcb](https://github.com/Automattic/newspack-plugin/commit/4ec6fcbc5958c9ff53d475e5a472a7f74e128c45))
* plugins-screen script and stylesheet paths ([#445](https://github.com/Automattic/newspack-plugin/issues/445)) ([fb98e3a](https://github.com/Automattic/newspack-plugin/commit/fb98e3a7f6c35b4b28029182e7c2ef9d32c2308f))
* remove calc() style for column blocks in starter content homepage. fixes [#393](https://github.com/Automattic/newspack-plugin/issues/393). ([#394](https://github.com/Automattic/newspack-plugin/issues/394)) ([71a7bd7](https://github.com/Automattic/newspack-plugin/commit/71a7bd733621e5bfb4ca252d0ae1210908a7cebe))
* store support token per-user instead of globally ([2b4af08](https://github.com/Automattic/newspack-plugin/commit/2b4af08d6ad8b4803a11f2557f869454b7dbeace))
* update Site Kit connection check for latest SK version ([6ffd884](https://github.com/Automattic/newspack-plugin/commit/6ffd884e9e8bccfef66353315b8e6c31de5a698d))
* when preview is waiting vertically align items to the center ([0f2c48a](https://github.com/Automattic/newspack-plugin/commit/0f2c48ae371c865ad6eb980e0cded3859401654c))
* wpcom  endpoints for stripe activity ([#458](https://github.com/Automattic/newspack-plugin/issues/458)) ([7230ac5](https://github.com/Automattic/newspack-plugin/commit/7230ac5c6053c903b0836f309ca5ee6365142123))


### Features

* adjust happychat style ([a6d501c](https://github.com/Automattic/newspack-plugin/commit/a6d501c4c5a5b5745498ae6492a0bd29b41c00d0))
* **support:** add Happychat ([f483f84](https://github.com/Automattic/newspack-plugin/commit/f483f8409eaac6379bca8bafabeee08a6568cc6c))
* Add MC4WP to supported plugins. ([68b1476](https://github.com/Automattic/newspack-plugin/commit/68b1476e407518e484deacc7a8373e991c2a0243))
* add support screen ([#451](https://github.com/Automattic/newspack-plugin/issues/451)) ([c76cb72](https://github.com/Automattic/newspack-plugin/commit/c76cb727bea7bea4f75843941f5e1b7d298f8fef))
* move proxied-imports ([d6973e3](https://github.com/Automattic/newspack-plugin/commit/d6973e3de97bf00055048aa9a1a12c3165656bc6))
* remove tabbed navigation on Donations/Memberships screens, add back buttons. ([20678b7](https://github.com/Automattic/newspack-plugin/commit/20678b77fc9778330e7fa3915ada97808a345b75))
* whitelist plugin - Password Protected. ([#398](https://github.com/Automattic/newspack-plugin/issues/398)) ([71febf4](https://github.com/Automattic/newspack-plugin/commit/71febf432db13aafad20556b9e4e1c0eadb6c361))


### Reverts

* Revert "Handoff Banner: Update style to visually match the rest of the plugin" ([3b9243c](https://github.com/Automattic/newspack-plugin/commit/3b9243cf3e95180d2ab0b157be1685ad3c06a992))
