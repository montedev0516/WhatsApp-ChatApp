# 🚀 WhatsApp Business ChatBot Platform

[![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-3.x-green.svg)](https://vuejs.org)
[![Twilio](https://img.shields.io/badge/Twilio-WhatsApp_API-blue.svg)](https://www.twilio.com/whatsapp)
[![Real-time](https://img.shields.io/badge/Real--time-WebSockets-orange.svg)](https://laravel.com/docs/broadcasting)

A powerful, enterprise-grade WhatsApp Business API chatbot platform built with modern web technologies. This application provides a complete solution for businesses to manage WhatsApp conversations, handle multimedia messages, and engage with customers in real-time through an intuitive web interface.

## ✨ Key Features

### 🎯 **Core Messaging Capabilities**
- **📱 WhatsApp Business API Integration** - Seamless connection with Twilio WhatsApp Business API
- **💬 Bidirectional Messaging** - Send and receive messages in real-time
- **📁 Rich Media Support** - Handle images, videos, audio files, and documents
- **⚡ Real-time Updates** - Instant message delivery and status updates using WebSockets
- **📋 Message History** - Complete conversation tracking and storage

### ⚙️ **Advanced Management Features**
- **🗑️ Message Management** - Delete individual messages or entire conversations
- **📊 Conversation Analytics** - Track message history and engagement
- **⏰ Timestamp Tracking** - Precise message delivery times
- **📤 File Upload/Download** - Seamless media file handling
- **🔄 Auto-sync** - Automatic synchronization with WhatsApp Business API


## 🏗️ Technology Stack

| **Frontend** | **Backend** | **Real-time** | **Integration** |
|--------------|-------------|---------------|-----------------|
| Vue.js 3.x | Laravel 10.x | Laravel WebSockets | Twilio WhatsApp API |
| Inertia.js | PHP 8.1+ | Pusher.js | WhatsApp Business |
| Tailwind CSS | MySQL/PostgreSQL | Broadcasting | Media Webhooks |
| FontAwesome | Laravel Echo | Socket.io | File Storage |

## 🚀 Quick Start

### Prerequisites
- PHP 8.1 or higher
- Node.js 16+ and npm/yarn
- MySQL/PostgreSQL database
- Twilio account with WhatsApp Business API access

### Installation

1. **Clone the repository**
```bash
git clone https://github.com/your-username/WhatsApp-ChatApp.git
cd WhatsApp-ChatApp
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Install Node.js dependencies**
```bash
npm install
```

4. **Environment setup**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Configure your environment variables**
```env
# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=whatsapp_chatbot
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Twilio WhatsApp Configuration
TWILIO_ACCOUNT_SID=your_twilio_account_sid
TWILIO_AUTH_TOKEN=your_twilio_auth_token
TWILIO_WHATSAPP_FROM=whatsapp:+14155238886

# Broadcasting Configuration
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your_pusher_app_id
PUSHER_APP_KEY=your_pusher_key
PUSHER_APP_SECRET=your_pusher_secret
PUSHER_APP_CLUSTER=your_pusher_cluster

# WebSockets Configuration
LARAVEL_WEBSOCKETS_PORT=6001
```

6. **Database setup**
```bash
php artisan migrate
```

7. **Build frontend assets**
```bash
npm run build
# or for development
npm run dev
```

8. **Start the application**
```bash
# Start Laravel server
php artisan serve

# Start WebSocket server (in another terminal)
php artisan websockets:serve

# Start Vite dev server (for development)
npm run dev
```

## 📱 Screenshots

### Main Chat Interface
![WhatsApp Chat Interface](/assets/sender1.jpg)

### File Sharing Capabilities
![File Sender Interface](/assets/file_sender.jpg)

## 🔧 Configuration

### WhatsApp Business API Setup

1. **Twilio Configuration**
   - Sign up for a Twilio account
   - Enable WhatsApp Business API
   - Configure webhook endpoints:
     - Message webhook: `https://your-domain.com/receive-message`
     - Media webhook: `https://your-domain.com/twilio/webhook`

2. **Webhook Configuration**
   ```php
   // In routes/web.php - Webhook endpoints are already configured
   Route::post('/receive-message', [SmsController::class, 'receiveMessage']);
   ```

### Real-time Configuration

The application uses Laravel WebSockets for real-time functionality:

```bash
# Start WebSocket server
php artisan websockets:serve --port=6001
```

Access WebSocket dashboard at: `http://localhost:8000/laravel-websockets`

## 🎯 Usage

### Sending Messages
```javascript
// Send text message
await axios.post('/vue-message', {
    message: 'Hello from ChatBot!',
    PhoneNumber: '+1234567890'
});

// Send media file
const formData = new FormData();
formData.append('file', selectedFile);
formData.append('PhoneNumber', '+1234567890');

await axios.post('/media-message', formData);
```

### Managing Conversations
- **View Conversations**: Click on any contact in the sidebar
- **Delete Messages**: Hover over a message and click the trash icon
- **Delete Conversations**: Hover over a contact and click the trash icon
- **Search**: Use the search bar to find specific conversations or messages

## 🔌 API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/` | Main chat interface |
| POST | `/vue-message` | Send text message |
| POST | `/media-message` | Send media files |
| POST | `/receive-message` | Webhook for incoming messages |
| POST | `/get-message` | Retrieve conversation history |
| GET | `/get-message` | Get all phone numbers |
| POST | `/delete-phone` | Delete conversation |
| POST | `/delete-message` | Delete specific message |


## 🛠️ Development

### Project Structure
```
WhatsApp-ChatApp/
├── app/
│   ├── Http/Controllers/     # API controllers
│   ├── Models/              # Database models
│   ├── Events/              # Broadcasting events
│   └── ...
├── resources/
│   ├── js/Pages/User/       # Vue.js components
│   ├── css/                 # Styling
│   └── views/               # Blade templates
├── routes/                  # Route definitions
├── database/                # Migrations and seeders
└── public/                  # Static assets
```

### Key Components
- **SmsController**: Handles message sending/receiving
- **MessageSent Event**: Broadcasting for real-time updates
- **Show.vue**: Main chat interface component
- **UpdateModel**: Message storage model


## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - The PHP framework for web artisans
- [Vue.js](https://vuejs.org) - The progressive JavaScript framework
- [Twilio](https://www.twilio.com) - Communications APIs for SMS, Voice, Video and more
- [Tailwind CSS](https://tailwindcss.com) - A utility-first CSS framework

---

<div align="center">

**⭐ Star this repository if this project helped you! ⭐**

</div>
