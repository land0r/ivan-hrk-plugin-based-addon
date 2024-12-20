# Ivan Hrk Api Based Addon

![Version](https://img.shields.io/github/v/tag/land0r/ivan-hrk-plugin-based-addon?label=version)
![Build Status](https://github.com/land0r/ivan-hrk-plugin-based-addon/actions/workflows/push-commit.yml/badge.svg)
![Build Status](https://github.com/land0r/ivan-hrk-plugin-based-addon/actions/workflows/pull-request.yml/badge.svg)

- Tags: api, cache, admin, Gutenberg
- Requires at least: 6.0
- Tested up to: 6.7
- Requires PHP: 8.0
- Stable tag: 1.2.1
- License: GPLv2 or later
- License URI: https://www.gnu.org/licenses/gpl-2.0.html

A WordPress plugin that retrieves and displays API data using a Gutenberg block and admin interface.

## References

The following resources were used in the development of this plugin:

1. **Gutenberg Block Development:**
	- [Getting Started with the Block Editor](https://developer.wordpress.org/block-editor/getting-started/tutorial/)
2. **Styling Reference:**
	- [WP Mail SMTP Admin Styles](https://github.com/awesomemotive/WP-Mail-SMTP/blob/master/assets/css/smtp-admin.scss)
3. **WPForms WordPress Coding Standards (WPCS):**
	- [wpforms-phpcs](https://github.com/awesomemotive/wpforms-phpcs)
4. **Dependency Injection Approach:**
	- [DIC Guide](https://github.com/rdlowrey/auryn)
5. **Hook-Based Plugin Structure:**
   - [WordPress Plugin Constructors Shouldn’t Define Hooks](https://tommcfarlin.com/wordpress-plugin-constructors-hooks/)

## Possible optimization

- create Interface for `Data_Store` in case if we need different type of storages, depends on website setup (Transient, Redis, Memcache, ...). Data store can be conditional.
- `Admin_Page.php` restructure and splitting into smaller classes
- Validator and data format for REST API responses, check that we always have the same number of headers and row cells, etc.
- Add options for customization, user could change: cache lifetime, datetime format for date column
- For block: styling options, [header, cells colors and background](https://wordpress.github.io/gutenberg/?path=/docs/components-colorpalette--docs), [font](https://wordpress.github.io/gutenberg/?path=/docs/components-fontsizepicker--docs), [border](https://wordpress.github.io/gutenberg/?path=/docs/components-borderboxcontrol--docs), padding and margin), [block styles](https://developer.wordpress.org/block-editor/reference-guides/block-api/block-styles/), etc.
- For block: add sorting options with possibility to enable/disable that functionality for some columns

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

## Development Setup

### **Prerequisites**

To set up the development environment for the plugin, ensure the following tools are installed on your system:
- **[Node.js](https://nodejs.org/)** (LTS version recommended, 14+)
- **[npm](https://www.npmjs.com/)** (comes with Node.js)
- **[Composer](https://getcomposer.org/)** (PHP dependency manager)
- **[Git](https://git-scm.com/)** (for cloning and version control)

### **Steps**

1. **Clone the Repository**
Clone the repository to your local development environment:
   ```bash
   git clone https://github.com/land0r/ivan-hrk-plugin-based-addon.git
   cd ivan-hrk-plugin-based-addon
   ```
2. **Install PHP Dependencies**
	```bash
	composer install
 	```
3. **Install JavaScript Dependencies**
   ```bash
   npm install
	```
4. **Build JavaScript Files**
   ```bash
   npm run build
	```
5. For live development with file watching, use:
    ```bash
     npm run start
    ```

## Installation

1. Download latest release from the plugin repository.
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
