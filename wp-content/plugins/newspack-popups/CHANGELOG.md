# [1.22.0](https://github.com/Automattic/newspack-popups/compare/v1.21.0...v1.22.0) (2021-01-21)


### Bug Fixes

* corrected logic for above header display ([#403](https://github.com/Automattic/newspack-popups/issues/403)) ([3ca5d71](https://github.com/Automattic/newspack-popups/commit/3ca5d7115f75f31d733f5498caac78d4acdc1040))
* handle missing GA id ([f27f84a](https://github.com/Automattic/newspack-popups/commit/f27f84a39785030481a7c07b5190555fb3e01f73))
* in preview tab, allow previewing without choosing groups ([#399](https://github.com/Automattic/newspack-popups/issues/399)) ([c9de7e9](https://github.com/Automattic/newspack-popups/commit/c9de7e9b1067f8ca70bafdfd3853ab26b72c88d8))
* install composer dependencies on release job ([#368](https://github.com/Automattic/newspack-popups/issues/368)) ([f814d27](https://github.com/Automattic/newspack-popups/commit/f814d270d701dec2997981b1cf1da3f9adf66d3b))
* remove dismiss button preview when empty ([#402](https://github.com/Automattic/newspack-popups/issues/402)) ([688d575](https://github.com/Automattic/newspack-popups/commit/688d57572dfec80300bf0906eff766b6c1f72010))
* remove inline class for above header campaigns ([#401](https://github.com/Automattic/newspack-popups/issues/401)) ([212f39e](https://github.com/Automattic/newspack-popups/commit/212f39ea47ca77cb707926010445a543df0e6f51))
* remove trailing question mark from cleaned non-AMP URLs ([#376](https://github.com/Automattic/newspack-popups/issues/376)) ([25363ae](https://github.com/Automattic/newspack-popups/commit/25363aed2e61d349bc8eb6831a303fb3a03d70e2))
* segmentation and frequency conflict ([#383](https://github.com/Automattic/newspack-popups/issues/383)) ([47a5af0](https://github.com/Automattic/newspack-popups/commit/47a5af09ffd941780fc6f55a2e54f4c45246299f)), closes [#379](https://github.com/Automattic/newspack-popups/issues/379)
* segmentation category affinity fixes ([16a6500](https://github.com/Automattic/newspack-popups/commit/16a65003a50b2e411dcb7995152ea941ac86d0e3))
* session read count in view-as logic ([#362](https://github.com/Automattic/newspack-popups/issues/362)) ([33b65c7](https://github.com/Automattic/newspack-popups/commit/33b65c7d6cf1d8a379246bfbbc4ce41fdde22554))
* view-as-segment should always take min count values ([#404](https://github.com/Automattic/newspack-popups/issues/404)) ([e69a97a](https://github.com/Automattic/newspack-popups/commit/e69a97a698f9c76552b5aa70d671fb0dc435ac67))


### Features

* deprecate test mode and never frequency ([#390](https://github.com/Automattic/newspack-popups/issues/390)) ([cc04314](https://github.com/Automattic/newspack-popups/commit/cc04314c8986734e658613ef1f5120be6bfa2012))
* handle category affinity segment ([fb01269](https://github.com/Automattic/newspack-popups/commit/fb012699d7e81d04fbb70b3888de205626f7e112))
* initialize campaigns by placement ([#374](https://github.com/Automattic/newspack-popups/issues/374)) ([5db8e97](https://github.com/Automattic/newspack-popups/commit/5db8e97f339ac7061b16c76939f35c12a46b71b0))
* manual placement ([#373](https://github.com/Automattic/newspack-popups/issues/373)) ([45b0c67](https://github.com/Automattic/newspack-popups/commit/45b0c677a52c76a3622305a59eb185e316be636d))
* support for campaign groups in campaigns wizard ([#363](https://github.com/Automattic/newspack-popups/issues/363)) ([fdb12db](https://github.com/Automattic/newspack-popups/commit/fdb12db88cc8c534d25c7a6db430864c866a209a))
* **segmentation:** add session read count handling ([b1a76cb](https://github.com/Automattic/newspack-popups/commit/b1a76cb83b4a24a2c0626d1a6810cd21865d2c02))
* **view-as:** handle groups ([#349](https://github.com/Automattic/newspack-popups/issues/349)) ([563f3ce](https://github.com/Automattic/newspack-popups/commit/563f3cef14959b5ad26fdd7c5d2f204752fe1443))
* shortcode ([#321](https://github.com/Automattic/newspack-popups/issues/321)) ([6da09cf](https://github.com/Automattic/newspack-popups/commit/6da09cfcc19b6275f3aa5f7f3e5321cc2f52d178))
* view-as feature ([#345](https://github.com/Automattic/newspack-popups/issues/345)) ([632bec7](https://github.com/Automattic/newspack-popups/commit/632bec7e882f9ed7b99f83d24e689cbad278e131))

# [1.21.0](https://github.com/Automattic/newspack-popups/compare/v1.20.0...v1.21.0) (2020-12-15)


### Bug Fixes

* editor colors ([69472f3](https://github.com/Automattic/newspack-popups/commit/69472f342c5a58e7e6064cd87361cdb7d73c3997))
* insert time-triggered overlay campaigns above header ([#297](https://github.com/Automattic/newspack-popups/issues/297)) ([d2c473b](https://github.com/Automattic/newspack-popups/commit/d2c473bc3f610953183e8abd319edbea1133dbad)), closes [#4](https://github.com/Automattic/newspack-popups/issues/4)


### Features

* **editor:** hide post visibility selector ([ce7aa98](https://github.com/Automattic/newspack-popups/commit/ce7aa98a409d7b45a9100d5d3faa75e7c7a7b51b))
* add above header placement option ([#292](https://github.com/Automattic/newspack-popups/issues/292)) ([06a863e](https://github.com/Automattic/newspack-popups/commit/06a863efcab8c60e46015d0354c34deca77f48c8)), closes [#260](https://github.com/Automattic/newspack-popups/issues/260)
* make campaign groups a hierarchical taxonomy ([#344](https://github.com/Automattic/newspack-popups/issues/344)) ([da4b26a](https://github.com/Automattic/newspack-popups/commit/da4b26ae3e4b62e03ed7c22cd067e1e6f00383b6))
* render a preview of the dismiss button in the editor ([#336](https://github.com/Automattic/newspack-popups/issues/336)) ([fa7dc05](https://github.com/Automattic/newspack-popups/commit/fa7dc05649f9fcbf66cc95b51cafc34acb1ad8df))

# [1.20.0](https://github.com/Automattic/newspack-popups/compare/v1.19.0...v1.20.0) (2020-12-08)


### Bug Fixes

* do create config file on the atomic platform ([14120a8](https://github.com/Automattic/newspack-popups/commit/14120a8d677dea5912cc0a6224d0de5f623aae89))


### Features

* move custom GA config endpoint to lightweight API ([326fb10](https://github.com/Automattic/newspack-popups/commit/326fb103d0eb9f05321c5598eac47ced378dcc34))

# [1.19.0](https://github.com/Automattic/newspack-popups/compare/v1.18.0...v1.19.0) (2020-12-02)


### Bug Fixes

* do not render popups with 'never' frequency ([f1c80f3](https://github.com/Automattic/newspack-popups/commit/f1c80f30da73f40750aec54ba80b143899f47ae7))


### Features

* report segmentation-related data as custom dimension to GA ([#325](https://github.com/Automattic/newspack-popups/issues/325)) ([648b70b](https://github.com/Automattic/newspack-popups/commit/648b70ba4155dc7f74b7485c3d5fbf98b2e2a482)), closes [#259](https://github.com/Automattic/newspack-popups/issues/259)
* **segmentation:** segment size computation ([#324](https://github.com/Automattic/newspack-popups/issues/324)) ([c152cd9](https://github.com/Automattic/newspack-popups/commit/c152cd9889140e6bfd6731e14ba8a279e84087bc))
* prune segmentation data ([c11c686](https://github.com/Automattic/newspack-popups/commit/c11c686ffd30940d042f354576ec3bc160d20db1)), closes [#251](https://github.com/Automattic/newspack-popups/issues/251)

# [1.19.0](https://github.com/Automattic/newspack-popups/compare/v1.18.0...v1.19.0) (2020-12-02)


### Bug Fixes

* do not render popups with 'never' frequency ([f1c80f3](https://github.com/Automattic/newspack-popups/commit/f1c80f30da73f40750aec54ba80b143899f47ae7))


### Features

* report segmentation-related data as custom dimension to GA ([#325](https://github.com/Automattic/newspack-popups/issues/325)) ([648b70b](https://github.com/Automattic/newspack-popups/commit/648b70ba4155dc7f74b7485c3d5fbf98b2e2a482)), closes [#259](https://github.com/Automattic/newspack-popups/issues/259)
* **segmentation:** segment size computation ([#324](https://github.com/Automattic/newspack-popups/issues/324)) ([c152cd9](https://github.com/Automattic/newspack-popups/commit/c152cd9889140e6bfd6731e14ba8a279e84087bc))
* prune segmentation data ([c11c686](https://github.com/Automattic/newspack-popups/commit/c11c686ffd30940d042f354576ec3bc160d20db1)), closes [#251](https://github.com/Automattic/newspack-popups/issues/251)

# [1.18.0](https://github.com/Automattic/newspack-popups/compare/v1.17.0...v1.18.0) (2020-11-24)


### Bug Fixes

* handle legacy config file path ([#317](https://github.com/Automattic/newspack-popups/issues/317)) ([7770f6a](https://github.com/Automattic/newspack-popups/commit/7770f6aec53e1a97bb43665e51e534a1b96d0626))


### Features

* Improve configuration file handling ([#315](https://github.com/Automattic/newspack-popups/issues/315)) ([be40334](https://github.com/Automattic/newspack-popups/commit/be403343f00963b9b754554f48fd19a06e068e96))
* polyfill amp-analytics script ([#306](https://github.com/Automattic/newspack-popups/issues/306)) ([6066651](https://github.com/Automattic/newspack-popups/commit/6066651d75642967be52c0f6261c64fc49f68408)), closes [#193](https://github.com/Automattic/newspack-popups/issues/193)
* reorganize editor sidebar and add notice when test mode is enabled ([#307](https://github.com/Automattic/newspack-popups/issues/307)) ([59e522c](https://github.com/Automattic/newspack-popups/commit/59e522c9a241da4d2b2e3a202af5fa52b81ba8a2)), closes [#310](https://github.com/Automattic/newspack-popups/issues/310)
* retrieve client email and donor status from Mailchimp ([#305](https://github.com/Automattic/newspack-popups/issues/305)) ([4ffc44d](https://github.com/Automattic/newspack-popups/commit/4ffc44d112e4daf7b2a911a68fc0ccc2228bdd17)), closes [#304](https://github.com/Automattic/newspack-popups/issues/304)

# [1.17.0](https://github.com/Automattic/newspack-popups/compare/v1.16.0...v1.17.0) (2020-11-11)


### Bug Fixes

* do not enqueue scripts if post has Campaigns disabled ([af32c62](https://github.com/Automattic/newspack-popups/commit/af32c623ec32e2cf27f831b615f14d980061b053))
* handle MC4WP forms when assessing if campaign has newsletter form ([74cfbae](https://github.com/Automattic/newspack-popups/commit/74cfbaee27aa6e67a9ba523dc33123f2d39f1d81))
* inject campaigns into posts and pages only ([#296](https://github.com/Automattic/newspack-popups/issues/296)) ([c162379](https://github.com/Automattic/newspack-popups/commit/c162379826ebaf799883f2db370d5f18af72405a))


### Features

* handle posts read count segmentation ([#289](https://github.com/Automattic/newspack-popups/issues/289)) ([c6024d2](https://github.com/Automattic/newspack-popups/commit/c6024d23ad02f5b874f3fe43982c6b5dcb199daf)), closes [#271](https://github.com/Automattic/newspack-popups/issues/271)
* handle subscription, donation segmentation ([5fb405d](https://github.com/Automattic/newspack-popups/commit/5fb405dbd0326bef4c4c55902878dafe184ffae0)), closes [#249](https://github.com/Automattic/newspack-popups/issues/249) [#250](https://github.com/Automattic/newspack-popups/issues/250)
* improve preview post ([#291](https://github.com/Automattic/newspack-popups/issues/291)) ([f7a5ba1](https://github.com/Automattic/newspack-popups/commit/f7a5ba1be80d25d6af8724b2de7e5e553515341f))
* settings interop ([#206](https://github.com/Automattic/newspack-popups/issues/206)) ([466db2a](https://github.com/Automattic/newspack-popups/commit/466db2a21d758b378c0d510996f99959e0973910))

# [1.16.0](https://github.com/Automattic/newspack-popups/compare/v1.15.1...v1.16.0) (2020-10-29)


### Bug Fixes

* allow only one overlay campaign per page ([#286](https://github.com/Automattic/newspack-popups/issues/286)) ([231d862](https://github.com/Automattic/newspack-popups/commit/231d862554a64c895b4af7c3238d8da5034c4199))


### Features

* handle non-amp amp-analytics submission event ([#283](https://github.com/Automattic/newspack-popups/issues/283)) ([44e7587](https://github.com/Automattic/newspack-popups/commit/44e7587143ac9389df6d627acbaa37729fe802dc)), closes [#200](https://github.com/Automattic/newspack-popups/issues/200) [#257](https://github.com/Automattic/newspack-popups/issues/257)
* suppression donation campaigns for donors ([1f53b60](https://github.com/Automattic/newspack-popups/commit/1f53b60a94f7d5be095d5212a98c184de60ada5f)), closes [#141](https://github.com/Automattic/newspack-popups/issues/141)

## [1.15.1](https://github.com/Automattic/newspack-popups/compare/v1.15.0...v1.15.1) (2020-10-28)


### Bug Fixes

* add posts_read array to legacy client data ([#281](https://github.com/Automattic/newspack-popups/issues/281)) ([126a5f3](https://github.com/Automattic/newspack-popups/commit/126a5f330f133dc6446dda427b36851f054e726f))

# [1.15.0](https://github.com/Automattic/newspack-popups/compare/v1.14.0...v1.15.0) (2020-10-27)


### Bug Fixes

* handle no WooCommerce installed ([d6b7b7c](https://github.com/Automattic/newspack-popups/commit/d6b7b7c1dd24f822c872f8d2a2212d97c02d7c96))
* non-post visits; prevent overwriting client data ([#276](https://github.com/Automattic/newspack-popups/issues/276)) ([6f13857](https://github.com/Automattic/newspack-popups/commit/6f1385738554adf62d634e20ee9dff7a9a29f771))
* **editor:** disable "Every page" frequency for overlay campaigns ([#268](https://github.com/Automattic/newspack-popups/issues/268)) ([700c07e](https://github.com/Automattic/newspack-popups/commit/700c07e97e23bd2671362119f29ce7118b1bceac)), closes [#105](https://github.com/Automattic/newspack-popups/issues/105)
* allow inline campaigns to appear before first block ([23b86ce](https://github.com/Automattic/newspack-popups/commit/23b86ce702108ebd8434b6f0e8734655971ad778)), closes [#209](https://github.com/Automattic/newspack-popups/issues/209)
* setting sitewide default ([8e3037f](https://github.com/Automattic/newspack-popups/commit/8e3037f7283154d0c98dee71428e8b577b576e11)), closes [#72](https://github.com/Automattic/newspack-popups/issues/72)
* testing of overlay popups ([79f9f0d](https://github.com/Automattic/newspack-popups/commit/79f9f0d29a77cd28b91ae2577069d7ae28c89c8d)), closes [#62](https://github.com/Automattic/newspack-popups/issues/62)


### Features

* **segmentation:** save WC donation data ([#269](https://github.com/Automattic/newspack-popups/issues/269)) ([3cc48d4](https://github.com/Automattic/newspack-popups/commit/3cc48d4f4ff5e23dbac6d36690a37b128e2ac5c9))
* disable native preview button ([2dd545b](https://github.com/Automattic/newspack-popups/commit/2dd545b28f29df9dcc1f6cf798f9a87cfdabdfcc)), closes [#130](https://github.com/Automattic/newspack-popups/issues/130)
* handle MC4WP; store email subscriptions as an array ([#258](https://github.com/Automattic/newspack-popups/issues/258)) ([baf4426](https://github.com/Automattic/newspack-popups/commit/baf44263f7f170ee7585c83c5572d0a3aaafa011))
* simplify previewing logic ([#266](https://github.com/Automattic/newspack-popups/issues/266)) ([97b17fe](https://github.com/Automattic/newspack-popups/commit/97b17fe62d49e1f4df4b245a871d26e0cba0d2ff)), closes [#110](https://github.com/Automattic/newspack-popups/issues/110)
* tag filtering ([#267](https://github.com/Automattic/newspack-popups/issues/267)) ([cf2b546](https://github.com/Automattic/newspack-popups/commit/cf2b54622b1a29a62876d5e289b502181bcc4aa0)), closes [#109](https://github.com/Automattic/newspack-popups/issues/109)
* use cacheing for client data ([b5c4cba](https://github.com/Automattic/newspack-popups/commit/b5c4cba7aeadc5899071955dfcc8cbefb46804bc))

# [1.14.0](https://github.com/Automattic/newspack-popups/compare/v1.13.0...v1.14.0) (2020-10-20)


### Bug Fixes

* inline markup ([#254](https://github.com/Automattic/newspack-popups/issues/254)) ([bc26e7b](https://github.com/Automattic/newspack-popups/commit/bc26e7b0d57d10bf8027c7cad740333e305b1af7)), closes [#246](https://github.com/Automattic/newspack-popups/issues/246)
* time-triggered popups ([#242](https://github.com/Automattic/newspack-popups/issues/242)) ([9bee014](https://github.com/Automattic/newspack-popups/commit/9bee0149c335b05ba2f1e544b357e1311e56ee25)), closes [#241](https://github.com/Automattic/newspack-popups/issues/241) [#247](https://github.com/Automattic/newspack-popups/issues/247)


### Features

* non-interactive mode ([#253](https://github.com/Automattic/newspack-popups/issues/253)) ([36774f8](https://github.com/Automattic/newspack-popups/commit/36774f830436300b1a62ab4ed76a114d84fc2c15)), closes [#248](https://github.com/Automattic/newspack-popups/issues/248)

# [1.13.0](https://github.com/Automattic/newspack-popups/compare/v1.12.2...v1.13.0) (2020-10-07)


### Features

* segmentation data collection ([ff5ffdc](https://github.com/Automattic/newspack-popups/commit/ff5ffdca2969418fa58a4dd59df8dabd1381b2cd)), closes [#233](https://github.com/Automattic/newspack-popups/issues/233)

## [1.12.2](https://github.com/Automattic/newspack-popups/compare/v1.12.1...v1.12.2) (2020-09-30)


### Bug Fixes

* scroll-triggered popups on non-AMP pages ([6b55e13](https://github.com/Automattic/newspack-popups/commit/6b55e1360aeb0c3d9896917cf10c39904ee29fac))

## [1.12.1](https://github.com/Automattic/newspack-popups/compare/v1.12.0...v1.12.1) (2020-09-29)


### Bug Fixes

* scroll-triggered campaigns ([#236](https://github.com/Automattic/newspack-popups/issues/236)) ([79b654a](https://github.com/Automattic/newspack-popups/commit/79b654a5b83afb59dca8bf80b81e135a6e1d5fc2))
* scroll-triggered popups ([#232](https://github.com/Automattic/newspack-popups/issues/232)) ([cff67c6](https://github.com/Automattic/newspack-popups/commit/cff67c6db04a7aafb9c092fe18f8ec499e93e0be)), closes [#217](https://github.com/Automattic/newspack-popups/issues/217) [#231](https://github.com/Automattic/newspack-popups/issues/231)

# [1.12.0](https://github.com/Automattic/newspack-popups/compare/v1.11.0...v1.12.0) (2020-09-22)


### Bug Fixes

* **api:** config creation ([#228](https://github.com/Automattic/newspack-popups/issues/228)) ([d06daa5](https://github.com/Automattic/newspack-popups/commit/d06daa5af2e2a076b5925f7de69f673253cb0ffe))
* prevent a test category popup from taking the place of a sitewide default ([c610ad0](https://github.com/Automattic/newspack-popups/commit/c610ad0ce9dbefe79fa8a6f52e6400b2d1fd71e6)), closes [#219](https://github.com/Automattic/newspack-popups/issues/219)
* reporting view on overlay campaigns ([#221](https://github.com/Automattic/newspack-popups/issues/221)) ([6f63f5b](https://github.com/Automattic/newspack-popups/commit/6f63f5ba8ff645e573540526f6f075198b6af287)), closes [#220](https://github.com/Automattic/newspack-popups/issues/220)
* **api:** enable drafts handling for setting options and categories ([4828355](https://github.com/Automattic/newspack-popups/commit/48283550208901658c8e6dd2c53e972294a75bc4))


### Features

* **api:** allow requests from AMP Cache ([#226](https://github.com/Automattic/newspack-popups/issues/226)) ([e0f7aaf](https://github.com/Automattic/newspack-popups/commit/e0f7aaff79aac68cf2db65e64c441211fb1a7154))
* create config if not exists ([#212](https://github.com/Automattic/newspack-popups/issues/212)) ([0bdc4a3](https://github.com/Automattic/newspack-popups/commit/0bdc4a3d3ef1291e0309ca98c5d9aaa4b1d98ea2))
* use single request for amp-access; lightweight POST endpoint ([#213](https://github.com/Automattic/newspack-popups/issues/213)) ([e7c3356](https://github.com/Automattic/newspack-popups/commit/e7c3356a75d19f3c163319e14d2380ebfa17028f))

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
