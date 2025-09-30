# Dynamic Content Display

[![License](https://img.shields.io/badge/license-GPL--2.0%2B-blue.svg)](https://github.com/mwaleed-580/dynamic-content-display/blob/main/LICENSE)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D7.4-blue.svg)](https://secure.php.net/)
[![WordPress Version](https://img.shields.io/badge/wordpress-%3E%3D5.0-blue.svg)](https://wordpress.org/)

A professional WordPress plugin that serves different content to different users based on URL parameters, featuring dynamic tab ordering and persistent user preferences.

## 🚀 Features

- **Dynamic Content Display**: Serve different content based on URL parameters
- **Tab Ordering**: Dynamically reorder ACF repeater content sections
- **Persistent Preferences**: Cookie-based user preference storage
- **Project-Specific Settings**: Individual settings per project post
- **Professional Architecture**: Industry-standard OOP patterns and PSR-4 autoloading

## 📋 Requirements

- PHP 7.4 or higher
- WordPress 5.0 or higher
- Advanced Custom Fields (ACF) plugin
- Dynamic Content for Elementor (DCE) plugin

## 🔧 Installation

### Via Composer (Recommended)

```bash
# Navigate to plugin directory
cd wp-content/plugins/dynamic-content-display

# Install dependencies
composer install --no-dev --optimize-autoloader
```

### Manual Installation

1. Upload the plugin files to `/wp-content/plugins/dynamic-content-display/`
2. Activate the plugin through the WordPress admin
3. Ensure ACF and DCE plugins are installed and activated

## 📖 Usage

### URL Parameters

#### Display Mode Parameter
```
?sr=up
```
Sets the display mode to "up" and stores preference in cookie for 24 hours.

#### Tab Ordering Parameter
```
?fe=info chat google-map
```
Reorders project tabs based on specified sequence (space-separated) and stores preference for 30 days.

### Example URLs

```bash
# Basic tab ordering
/project/my-project/?fe=info chat google-map

# Alternative ordering
/project/my-project/?fe=api info additional-data

# Display mode
/project/my-project/?sr=up

# Combined parameters
/project/my-project/?fe=chat info&sr=up
```

### ACF Field Structure

Create a repeater field named `data_tabs` with:
- Field Name: `data_tabs`
- Sub-field: `title` (Text field)

## 🏗️ Architecture

### Directory Structure

```
dynamic-content-display/
├── dynamic-content-display.php    # Main plugin file
├── composer.json                  # Composer configuration
├── includes/
│   ├── Core/
│   │   └── Plugin.php             # Main orchestrator class
│   ├── Services/
│   │   ├── CookieService.php      # Cookie management
│   │   └── TabOrderingService.php # ACF and CSS logic
│   └── Traits/
│       └── Singleton.php          # Singleton pattern implementation
└── vendor/                        # Composer dependencies
```

### Design Patterns

- **Singleton Pattern**: Ensures single instance of services
- **Dependency Injection**: Loose coupling between components
- **Service Layer**: Separation of concerns
- **PSR-4 Autoloading**: Industry-standard class loading

### Classes Overview

#### `Plugin` (Core\Plugin)
Main orchestrator that initializes services and handles URL parameter processing.

#### `CookieService` (Services\CookieService)
Manages all cookie operations:
- `set_display_mode_cookie(string $mode)`: Sets display mode cookie
- `save_tab_order_cookie(int $post_id, string $tab_order)`: Saves tab order
- `get_tab_order_from_cookie(int $post_id)`: Retrieves tab order
- `has_tab_order_cookie(int $post_id)`: Checks cookie existence

#### `TabOrderingService` (Services\TabOrderingService)
Handles ACF data processing and CSS generation:
- `add_tab_ordering_css()`: Main method for CSS output
- `process_tabs_for_ordering(array $data_tabs, array $desired_order)`: Processes tab data
- `convert_title_to_slug(string $title)`: Converts titles to slugs
- `output_tab_ordering_css(array $tab_order, int $total_rows)`: Outputs CSS

#### `Singleton` (Traits\Singleton)
Provides singleton pattern implementation for all services.

## 🔄 How It Works

1. **URL Parameter Detection**: Plugin detects `fe` or `sr` parameters
2. **Cookie Storage**: Parameters are stored in project-specific cookies
3. **ACF Data Processing**: Retrieves `data_tabs` repeater field data
4. **Title Conversion**: Converts ACF titles to URL-friendly slugs
5. **Order Mapping**: Maps URL parameter order to CSS element IDs
6. **CSS Generation**: Outputs CSS with `order` properties for reordering
7. **Pattern Repetition**: Applies same ordering to additional element sets

## 🎯 CSS Output Example

For URL `?fe=info chat google-map` with 3 tabs:

```css
.dce-elementor-rendering-id-2 { order: 1; } /* Info */
.dce-elementor-rendering-id-1 { order: 2; } /* Chat */
.dce-elementor-rendering-id-3 { order: 3; } /* Google Map */

/* Repeating pattern for additional sets */
.dce-elementor-rendering-id-4 { order: 1; }
.dce-elementor-rendering-id-5 { order: 2; }
.dce-elementor-rendering-id-6 { order: 3; }
```

## 🧪 Development

### Setup Development Environment

```bash
# Install all dependencies (including dev)
composer install

# Run code style checks
composer run cs

# Fix code style issues
composer run cbf

# Run tests
composer run test
```

### Adding New Features

1. Follow PSR-4 autoloading standards
2. Use dependency injection for service communication
3. Implement singleton pattern for services
4. Add only required methods (no bloat)
5. Follow WordPress coding standards

## 🔒 Security

- All user inputs are sanitized using `sanitize_text_field()`
- Direct file access is prevented
- Proper nonce validation (where applicable)
- Secure cookie handling

## 🐛 Troubleshooting

### Common Issues

**CSS not applying**: Ensure ACF field name is exactly `data_tabs`
**Cookies not saving**: Check PHP session and cookie settings
**Autoloader errors**: Run `composer dump-autoload -o`

### Debug Mode

Add to `wp-config.php` for debugging:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

## 📝 Contributing

1. Fork the repository
2. Create a feature branch
3. Follow coding standards
4. Add tests for new features
5. Submit a pull request

## 📄 License

This plugin is licensed under the GPL v2 or later.

## 👨‍💻 Author

**Muhammad Waleed**
- LinkedIn: [mwaleed580](https://www.linkedin.com/in/mwaleed580/)
- GitHub: [mwaleed-580](https://github.com/mwaleed-580)

## 🙏 Acknowledgments

- WordPress community for excellent documentation
- ACF team for the powerful custom fields plugin
- DCE team for dynamic content capabilities