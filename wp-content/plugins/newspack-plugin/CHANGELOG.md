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
