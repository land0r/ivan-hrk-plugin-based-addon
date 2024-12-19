# Ivan HRK API Based Addon

![Version](https://img.shields.io/github/v/tag/land0r/ivan-hrk-plugin-based-addon?label=version)
![Build Status](https://github.com/land0r/ivan-hrk-plugin-based-addon/actions/workflows/push-commit.yml/badge.svg)
![Build Status](https://github.com/land0r/ivan-hrk-plugin-based-addon/actions/workflows/pull-request.yml/badge.svg)

A WordPress plugin that retrieves data from a remote API and displays it via a Gutenberg block and an admin page. The plugin includes a custom AJAX endpoint and a WP CLI command for enhanced functionality.

## Features

- Fetches data from a remote API endpoint: `https://miusage.com/v1/challenge/1/`.
- Stores and caches data to reduce redundant API calls (cache duration: 1 hour).
- Custom WordPress AJAX endpoint for retrieving data (works for both logged-in and logged-out users).
- Gutenberg block to display data in a customizable table format.
	- Allows toggling visibility of individual table columns via block settings.
- Admin page styled like WP Mail SMTP, displaying API data with the ability to refresh the cache.
- WP CLI command to force cache clearing, bypassing the 1-hour cache limit.
- Fully localized and translatable strings for all user-facing text.
- Built using modern PHP standards (OOP, PSR-4, Composer) and WordPress Coding Standards.

## Installation

1. Clone or download the plugin repository.
2. Place the plugin folder in your WordPress `wp-content/plugins` directory.
3. Activate the plugin via the WordPress admin dashboard.

## Usage

### **Gutenberg Block**
1. Add the **Ivan HRK API Table** block in the Block Editor.
2. Customize the visibility of columns using the block settings.
3. Save and view the table on the frontend.

### **Admin Page**
1. Navigate to **Dashboard > Ivan API Data** in the admin panel.
2. View fetched API data in a table format.
3. Use the **Clear Cache** button to refresh the cache manually.

### **WP CLI Command**
Run the following command to clear the cache manually:
```bash
wp ivan-api-based refresh-cache
