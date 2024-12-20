
---

### **CHANGELOG.md**

# Changelog

## [1.0.0] - 2024-12-19
### Added
- Initial release of **Ivan HRK API Based Addon**.
- Fetch and cache data from the `https://miusage.com/v1/challenge/1/` API endpoint.
- Custom AJAX endpoint to retrieve data for both logged-in and logged-out users.
- Gutenberg block to display API data in a table, with toggleable column visibility settings.
- Admin page to display cached data and clear the cache manually.
- WP CLI command (`wp ivan-api-based refresh-cache`) to force cache clearing.
- Data caching mechanism with a default duration of 1 hour.
- Comprehensive sanitization, validation, and escaping for all inputs and outputs.
- Fully localized and translatable plugin text.
- Modern development practices:
  - OOP design and PSR-4 autoloading.
  - WordPress Coding Standards compliance.
  - Composer for dependency management.

## [1.1.0] - 2024-12-19
### Changed
- Improved table styling for better frontend and editor display.
- Enhanced Gutenberg block functionality with added customization options for column visibility.
- Refactored caching logic for better reliability and extensibility.

### Fixed
- Resolved minor inconsistencies in data rendering within the Gutenberg block.
- Fixed AJAX error handling for better user feedback.

## [1.2.0] - 2024-12-20
## Changed
- **Major refactor**: Removed dependency injection container (DIC) for simplified class structure.
- Introduced a singleton-based design for the `Plugin` class.

### Added
- New caching enhancements: Added a WordPress filter for dynamic cache key modification.
- Localized strings for all admin JavaScript functionality using `wp_localize_script`.

### Fixed
- Improved admin page styling and conditional script loading for better performance.

## [1.2.1] - 2024-12-20
- Corrected `README.md` headers for WordPress.org compatibility:
