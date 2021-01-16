# header-grabber
A WordPress plugin used to pull and post a header from a url. This can be helpful when posting and updating a franchise brand header across multiple websites.

## Installation
1. Clone or download this repo
1. Drop the entire /header-grabber/ folder into your /wp-content/plugins/ folder of your website
1. Activate the plugin in the WordPress backend
1. Add a file URL to Tools —> Header Grabber

**(Note: Your theme must have the standard body open function `<?php wp_body_open(); ?>` for this plugin to display the code.)**

## Features
* Stores the code in the database
* Settings page for setup and testing
* Adds a cron job to automatically curl the URL twice daily
* Shortcode can also be used to display the code: `[ header_grabber ]`
