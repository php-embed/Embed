# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [4.4.18] - 2026-06-19
### Added
- Add PHPStan Static Analysis (Level Max + Strict Rules) [#562]
- Add support for Facebook reel URLs [#572]
- Update oEmbed endpoints [#574]

### Changed
- Make snapshot updates possible without modifying test code [#564]

### Removed
- Remove support for discontinued services (chirb.it and sassmeister.com) [#565]

### Fixed
- Add PHPStan generic type annotations and simplify detector implementations [#563]
- Update fixtures to pass PHPUnit [#559]
- wrong mb_encoding_aliases() handling for PHP 8.0+ [#560]
- Remove calls to curl_close and curl_multi_close on PHP 8.0 and later [#569]
- Add phpstan.dist.neon to export-ignore in .gitattributes [#571]

## [4.4.17] - 2025-05-13
### Fixed
- Adapters hostname detection [#556].

## [4.4.16] - 2025-05-09
### Fixed
- Adapters hostname detection [#555].

## [4.4.15] - 2025-01-02
### Fixed
- Type bug [#553].

## [4.4.14] - 2024-12-04
### Fixed
- Php 8.4 support [#551].

## [4.4.13] - 2024-11-21
### Fixed
- Php 8.4 support [#548].

## [4.4.12] - 2024-07-24
### Fixed
- X.com (Twitter) [#540]
- Updated oembed resources.

## [4.4.11] - 2024-06-10
### Fixed
- Updated oEmbed entry points [#537]

## [4.4.10] - 2023-12-10
### Fixed
- PHP 7.4 support
- Use correct method for string length [#529]

## [4.4.9] - 2023-12-01
### Fixed
- Performance and memory leak issues [#525], [#527].

## [4.4.8] - 2023-05-22
### Fixed
- Support for `psr/http-message@2` [#514], [#515]

## [4.4.7] - 2022-12-12
### Fixed
- Href attributes with `undefined` values [#501], [#502]
- Deprecated warning for var interpolation in PHP 8.2 [#506]
- Prevent unsupported operand types exception [#507]

## [4.4.6] - 2022-10-02
### Fixed
- Some code issues detected by phpstan: [#495], [#496], [#497], [#498].
- Fix for quotation marks in redirect URL [#499]

## [4.4.5] - 2022-09-06
### Fixed
- Updated oembed endpoints [#494]

## [4.4.4] - 2022-04-13
### Fixed
- Error getting data from Linked data [#481].

## [4.4.3] - 2022-03-13
### Fixed
- PHP 8.1 deprecation notice [#480].

## [4.4.2] - 2022-02-13
### Added
- Options to customize the CurlClient to perform http queries [#474].

## [4.4.1] - 2022-02-06
### Fixed
- PHP 8.1 deprecation notice [#473].

## [4.4.0] - 2022-01-08
### Added
- New settings option `twitter:token` to use Twitter API to get the data [#364] [#468].

### Fixed
- Headers not sent properly by curl [#466], [#467].

## [4.3.5] - 2021-10-10
### Fixed
- Updated oEmbed endpoints
- Fixed embed code for Instagram [#456], [#459]

### Security
- Fixed a possible XML Quadratic Blowup vulnerability.

## [4.3.4] - 2021-06-22
### Fixed
- Urls of images should include the same url for the `$info->image` value. [#452]

## [4.3.3] - 2021-06-22
### Fixed
- Facebook embed redirects to `/login`. [#450], [#451]

## [4.3.2] - 2021-04-04
### Fixed
- Add configured oEmbed query parameters to all oEmbed endpoints [#437]
- Updated oEmbed endpoints.
- Replaced Travis with Github workflows for testing

## [4.3.1] - 2021-03-21
### Added
- Support for binary files (video, audio, images, etc) [#412] [#413]

### Fixed
- Oembed for facebook photos [#405] [#406]
- Oembed for facebook videos [#432] [#433]
- Added more ways to detect data using meta tags [#427]
- Bandcamp provider name [#429] [#430]

## [4.3.0] - 2020-11-04
### Added
- New function `$embed->setSettings()` to pass the settings before get the site info

### Fixed
- PHP 8 compatibility [#394]
- Facebook and Instagram adapted to the new API changes [#392] [#399]

## [4.2.7] - 2020-09-23
### Added
- New option `twitch:parent` to fix Twitch embed with iframes [#384]

### Fixed
- Added `datePublished` check to `PublishedTime` extractor [#385] [#386]
- Added `@property-read` for IDE suppport [#387] [#388]

## [4.2.6] - 2020-08-28
### Fixed
- Code width and height when the provided value is not numeric (ex: 100%) [#380]

## [4.2.5] - 2020-08-01
### Fixed
- Github TypeError exception with some urls [#375]

## [4.2.4] - 2020-07-06
### Fixed
- Ignore invalid urls instead throw an exception
- Updated oembed list of endpoints

## [4.2.3] - 2020-06-12
### Fixed
- Suppport for other non-latin alphabets such Persian or Arabic [#366]

## [4.2.2] - 2020-05-31
### Fixed
- Provided a fallback for oEmbed compatible sites like Instagram that redirects to login page [#357]

## [4.2.1] - 2020-05-25
### Fixed
- Redirect urls like `t.co`.

## [4.2.0] - 2020-05-23
### Added
- Added the `ignored_errors` settings to ignore some curls errors instead throw an exception [#355]
- Support for Twitch embeds [#332]

### Fixed
- Ignored linkedData errors [#356]

## [4.1.1] - 2020-04-24
### Added
- Updated oembed endpoints from `oembed.com`
- Add support for tiktok.com

## [4.1.0] - 2020-04-19
### Added
- Ability to send settings to `CurlClient`. Added the `cookies_path` setting to customize the file used for cookies. [#345]
- `Document::selectCss()` function to select elements using css selectors instead xpath (it requires `symfony/css-selector`)
- `Document::removeCss()` function to remove elements using css selectors instead xpath (it requires `symfony/css-selector`)
- Ability to configure OEmbed parameters from the outside using the `oembed:query_parameters` setting [#346]

## [4.0.0] - 2020-03-13
Full library refactoring.

### Added
- Support for multiple parallel request with `curl_multi`
- Support for PSR-7 Http Messages, PSR-17 Http Factories and PSR-18 Http Client
- `cms` value
- `language` to detect the page language
- `languages` to detect urls to versions in different languages
- `favicon` to detect small favicons (16 or 32px)
- `icon` to detect big icons (from 48px)

### Changed
- Changed providers (oEmbed, Html, OpenGraph etc) by independent detectors (title, url, language etc).
- The `tags` value is renamed to `keywords`
- Use Psr standards instead custom interfaces.
- Improved tests using cached responses.

### Removed
- Support for PHP<7.4
- `type` value (is was very confusing)
- `images` value
- `providerImage` (use `favicon` or `icon` instead)
- Support for files (pdf, jpg, video, etc).

[#332]: https://github.com/oscarotero/Embed/issues/332
[#345]: https://github.com/oscarotero/Embed/issues/345
[#346]: https://github.com/oscarotero/Embed/issues/346
[#355]: https://github.com/oscarotero/Embed/issues/355
[#356]: https://github.com/oscarotero/Embed/issues/356
[#357]: https://github.com/oscarotero/Embed/issues/357
[#364]: https://github.com/oscarotero/Embed/issues/364
[#366]: https://github.com/oscarotero/Embed/issues/366
[#375]: https://github.com/oscarotero/Embed/issues/375
[#380]: https://github.com/oscarotero/Embed/issues/380
[#384]: https://github.com/oscarotero/Embed/issues/384
[#385]: https://github.com/oscarotero/Embed/issues/385
[#386]: https://github.com/oscarotero/Embed/issues/386
[#387]: https://github.com/oscarotero/Embed/issues/387
[#388]: https://github.com/oscarotero/Embed/issues/388
[#392]: https://github.com/oscarotero/Embed/issues/392
[#394]: https://github.com/oscarotero/Embed/issues/394
[#399]: https://github.com/oscarotero/Embed/issues/399
[#405]: https://github.com/oscarotero/Embed/issues/405
[#406]: https://github.com/oscarotero/Embed/issues/406
[#412]: https://github.com/oscarotero/Embed/issues/412
[#413]: https://github.com/oscarotero/Embed/issues/413
[#427]: https://github.com/oscarotero/Embed/issues/427
[#429]: https://github.com/oscarotero/Embed/issues/429
[#430]: https://github.com/oscarotero/Embed/issues/430
[#432]: https://github.com/oscarotero/Embed/issues/432
[#433]: https://github.com/oscarotero/Embed/issues/433
[#437]: https://github.com/oscarotero/Embed/issues/437
[#450]: https://github.com/oscarotero/Embed/issues/450
[#451]: https://github.com/oscarotero/Embed/issues/451
[#452]: https://github.com/oscarotero/Embed/issues/452
[#456]: https://github.com/oscarotero/Embed/issues/456
[#459]: https://github.com/oscarotero/Embed/issues/459
[#466]: https://github.com/oscarotero/Embed/issues/466
[#467]: https://github.com/oscarotero/Embed/issues/467
[#468]: https://github.com/oscarotero/Embed/issues/468
[#473]: https://github.com/oscarotero/Embed/issues/473
[#474]: https://github.com/oscarotero/Embed/issues/474
[#480]: https://github.com/oscarotero/Embed/issues/480
[#481]: https://github.com/oscarotero/Embed/issues/481
[#494]: https://github.com/oscarotero/Embed/issues/494
[#495]: https://github.com/oscarotero/Embed/issues/495
[#496]: https://github.com/oscarotero/Embed/issues/496
[#497]: https://github.com/oscarotero/Embed/issues/497
[#498]: https://github.com/oscarotero/Embed/issues/498
[#499]: https://github.com/oscarotero/Embed/issues/499
[#501]: https://github.com/oscarotero/Embed/issues/501
[#502]: https://github.com/oscarotero/Embed/issues/502
[#506]: https://github.com/oscarotero/Embed/issues/506
[#507]: https://github.com/oscarotero/Embed/issues/507
[#514]: https://github.com/oscarotero/Embed/issues/514
[#515]: https://github.com/oscarotero/Embed/issues/515
[#525]: https://github.com/oscarotero/Embed/issues/525
[#527]: https://github.com/oscarotero/Embed/issues/527
[#529]: https://github.com/oscarotero/Embed/issues/529
[#537]: https://github.com/oscarotero/Embed/issues/537
[#540]: https://github.com/oscarotero/Embed/issues/540
[#548]: https://github.com/oscarotero/Embed/issues/548
[#551]: https://github.com/oscarotero/Embed/issues/551
[#553]: https://github.com/oscarotero/Embed/issues/553
[#555]: https://github.com/oscarotero/Embed/issues/555
[#556]: https://github.com/oscarotero/Embed/issues/556
[#559]: https://github.com/oscarotero/Embed/issues/559
[#560]: https://github.com/oscarotero/Embed/issues/560
[#562]: https://github.com/oscarotero/Embed/issues/562
[#563]: https://github.com/oscarotero/Embed/issues/563
[#564]: https://github.com/oscarotero/Embed/issues/564
[#565]: https://github.com/oscarotero/Embed/issues/565
[#569]: https://github.com/oscarotero/Embed/issues/569
[#571]: https://github.com/oscarotero/Embed/issues/571
[#572]: https://github.com/oscarotero/Embed/issues/572
[#574]: https://github.com/oscarotero/Embed/issues/574

[4.4.18]: https://github.com/oscarotero/Embed/compare/v4.4.17...v4.4.18
[4.4.17]: https://github.com/oscarotero/Embed/compare/v4.4.16...v4.4.17
[4.4.16]: https://github.com/oscarotero/Embed/compare/v4.4.15...v4.4.16
[4.4.15]: https://github.com/oscarotero/Embed/compare/v4.4.14...v4.4.15
[4.4.14]: https://github.com/oscarotero/Embed/compare/v4.4.13...v4.4.14
[4.4.13]: https://github.com/oscarotero/Embed/compare/v4.4.12...v4.4.13
[4.4.12]: https://github.com/oscarotero/Embed/compare/v4.4.11...v4.4.12
[4.4.11]: https://github.com/oscarotero/Embed/compare/v4.4.10...v4.4.11
[4.4.10]: https://github.com/oscarotero/Embed/compare/v4.4.9...v4.4.10
[4.4.9]: https://github.com/oscarotero/Embed/compare/v4.4.8...v4.4.9
[4.4.8]: https://github.com/oscarotero/Embed/compare/v4.4.7...v4.4.8
[4.4.7]: https://github.com/oscarotero/Embed/compare/v4.4.6...v4.4.7
[4.4.6]: https://github.com/oscarotero/Embed/compare/v4.4.5...v4.4.6
[4.4.5]: https://github.com/oscarotero/Embed/compare/v4.4.4...v4.4.5
[4.4.4]: https://github.com/oscarotero/Embed/compare/v4.4.3...v4.4.4
[4.4.3]: https://github.com/oscarotero/Embed/compare/v4.4.2...v4.4.3
[4.4.2]: https://github.com/oscarotero/Embed/compare/v4.4.1...v4.4.2
[4.4.1]: https://github.com/oscarotero/Embed/compare/v4.4.0...v4.4.1
[4.4.0]: https://github.com/oscarotero/Embed/compare/v4.3.5...v4.4.0
[4.3.5]: https://github.com/oscarotero/Embed/compare/v4.3.4...v4.3.5
[4.3.4]: https://github.com/oscarotero/Embed/compare/v4.3.3...v4.3.4
[4.3.3]: https://github.com/oscarotero/Embed/compare/v4.3.2...v4.3.3
[4.3.2]: https://github.com/oscarotero/Embed/compare/v4.3.1...v4.3.2
[4.3.1]: https://github.com/oscarotero/Embed/compare/v4.3.0...v4.3.1
[4.3.0]: https://github.com/oscarotero/Embed/compare/v4.2.7...v4.3.0
[4.2.7]: https://github.com/oscarotero/Embed/compare/v4.2.6...v4.2.7
[4.2.6]: https://github.com/oscarotero/Embed/compare/v4.2.5...v4.2.6
[4.2.5]: https://github.com/oscarotero/Embed/compare/v4.2.4...v4.2.5
[4.2.4]: https://github.com/oscarotero/Embed/compare/v4.2.3...v4.2.4
[4.2.3]: https://github.com/oscarotero/Embed/compare/v4.2.2...v4.2.3
[4.2.2]: https://github.com/oscarotero/Embed/compare/v4.2.1...v4.2.2
[4.2.1]: https://github.com/oscarotero/Embed/compare/v4.2.0...v4.2.1
[4.2.0]: https://github.com/oscarotero/Embed/compare/v4.1.1...v4.2.0
[4.1.1]: https://github.com/oscarotero/Embed/compare/v4.1.0...v4.1.1
[4.1.0]: https://github.com/oscarotero/Embed/compare/v4.0.0...v4.1.0
[4.0.0]: https://github.com/oscarotero/Embed/releases/tag/v4.0.0
