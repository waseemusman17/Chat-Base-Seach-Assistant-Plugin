# Chat Search Assistant

A floating, chat-style search assistant plugin for WordPress powered by OpenAI's GPT API.

## ✨ Features

- Floating chat icon in bottom-right corner
- Expands to a full chat box on click
- Accepts user queries and returns intelligent responses from OpenAI
- Easy to install and configure
- Secure REST API integration

## 📦 Installation

1. Upload the plugin files to the `/wp-content/plugins/chat-search-assistant` directory, or install via the WordPress plugins screen.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Add your OpenAI API key in the `includes/class-csa-api.php` file (replace `'your-api-key'`).
4. The chat icon will appear on the bottom-right of the frontend.

## 🛠️ Folder Structure

chat-search-assistant/
├── assets/
│ ├── css/
│ │ └── csa-style.css
│ └── js/
│ └── csa-script.js
├── includes/
│ └── class-csa-api.php
├── chat-search-assistant.php
└── README.md

## 🔐 Security Note

- Make sure your OpenAI key is not exposed publicly.
- Only allow access to authorized users if extending this for logged-in environments.

## 🚀 Future Ideas

- Add settings page to store API key securely
- Support for contextual WooCommerce search
- GPT-4 Vision and plugin tools integration

## 📄 License

This project is licensed under the MIT License.
