=== WPGetAPI API to Posts ===
Contributors: wpgetapi
Tags: api, external api, import
Requires at least: 5.0
Tested up to: 6.2
Version: 1.3.14

A plugin extension for WPGetAPI that allows you to import items from an API and create posts from the items.

== Changelog ==

= 1.3.14 (2023-12-05) =
- New - add new filter 'wpgetapi_api_to_posts_get_item_for_mapping' to allow setting any item to use as the mapping 'base' item.

= 1.3.13 (2023-12-05) =
- New - add ability for plugin to 'find' an images that is buried in sub-arrays when using Featured Image.

= 1.3.12 (2023-11-30) =
- Update - modify the way taxonomies are recognised in mapping dropdown. Using get_object_taxonomies() now.

= 1.3.11 (2023-11-01) =
- New - add pagination for Growthhub

= 1.3.10 (2023-10-27) =
- Fix - delete cron job when turning of automatic sync.
- Fix - add check for string within post name creator value.

= 1.3.9 (2023-10-24) =
- Fix - allow arrays to pass through as JSON if in single field.

= 1.3.8 (2023-10-14) =
- Fix - error in pagination for Spark API.

= 1.3.7 (2023-10-13) =
- New - add pagination for Spark API.

= 1.3.6 (2023-09-21) =
- Update - trime whitespace from values before processing.
- Update - allow multiple categories and tags to be added.

= 1.3.5 (2023-09-20) =
- New - add date fields to data mapping.
- Update - modify some checks on the regular_price and sale_price for WooCommerce importing.

= 1.3.4 (2023-09-19) =
- New - add pagination for trendz API.

= 1.3.3 (2023-09-15) =
- New - filter 'wpgetapi_api_to_posts_mapped_value' for modifying a single mapped value.
- New - filter 'wpgetapi_api_to_posts_pagination_delay' for modifying the delay between pagination. Default 0.2 seconds.
- New - filter 'wpgetapi_api_to_posts_importer_items_before_save' for modifying entire items array before starting the saving/pagination process.

= 1.3.2 (2023-09-12) =
- New - new pagination type included for TMDB API.

= 1.3.1 (2023-09-07) =
- Updates - hiding/showing correct fields when choosing the WordPress field type.

= 1.3.0 (2023-09-06) =
- Breaking Update - add repeatable mapping fields.
- Update - make compatible with XML APIs when using PRO.

= 1.2.4 (2023-09-05) =
- Update - test pagination and include minor updates to get this working.

= 1.2.3 (2023-08-30) =
- Fix - add user-agent to image upload. Was getting 403 error with some URLs
- Fix - add check for $this->setup_opts['apis'] in class-admin-options.php

= 1.2.2 (2023-08-28) =
- New - add step down field if value is array in mapping tab.
- New - allow to get array value within post title field in mapping.
- Fix - issue with adding linked endpoint when it is not a linked endpoint.

= 1.2.1 (2023-08-28) =
- Fix - error using pp() function from testing. Change to wpgetapi_pp().

= 1.2.0 (2023-08-24) =
- New - post creator done via AJAX.
- New - dashboard added.
- Fix - recognise post type correctly on detail data type.

= 1.1.0 (2023-08-17) =
- New - add 'Detail' API Type which allows linking multiple endpoints.
- New - Sidebar info.
- Update - Major UI overhaul. Ensure tabs are working correctly.
- Update - Hide Save buttons on Importer and Creator tabs.

= 1.0.0 (2023-08-12) =
- Initial Release

== Upgrade Notice ==

= 1.2.5 =
Upgrade notices describe the reason a user should upgrade.  No more than 300 characters.
