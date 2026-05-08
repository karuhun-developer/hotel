# Hotel Management System

A comprehensive hotel/hospital management system built with Laravel 12, Livewire 4, and Flux UI. Supports multi-tenant architecture with role-based access control.

## Features

### CMS (Admin Panel)
- **Dashboard** — Overview stats (hotels/hospitals count for superadmin, user/room count + front desk for hotel admin)
- **Tenant Management** — Manage hotels/hospitals, profiles with media (logos, photos, intro video)
- **Room Management** — Room types and rooms per tenant
- **Content Management** — Contents and content items with media
- **Food Management** — Food categories and food items with media
- **Front Desk** — Guest check-in/check-out management
- **Application Management** — Manage applications per tenant
- **M3U Management** — M3U sources and channels (IPTV)
- **Tenant Channels** — Assign M3U channels to tenants with aliases
- **API Key Management** — Generate and manage API keys
- **User Management** — Users, roles, permissions, menus

### API (v1)
- **Authentication** — Login, register, password reset (Bearer token via Sanctum)
- **API Key Auth** — Authenticate via `X-API-KEY` header
- **Tenant Info** — Get tenant details with profile and media
- **Contents** — List contents and content items with change list support
- **Food** — List food categories and items with change list support
- **Rooms** — List room types and rooms
- **Applications** — List applications with change list support
- **M3U Channels** — List assigned M3U channels for tenant

## Tech Stack

- PHP 8.4
- Laravel 12
- Livewire 4
- Flux UI 2
- Laravel Folio (file-based routing)
- Laravel Fortify (authentication + 2FA)
- Laravel Sanctum (API tokens)
- Spatie Laravel Permission (roles & permissions)
- Spatie Laravel Media Library (file uploads)
- Spatie Laravel Activity Log (audit trail)

## Installation

```bash
# Clone the repository
git clone <repository-url> hotel-rewrite
cd hotel-rewrite

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Configure database in .env
# DB_DATABASE=hotel_rewrite
# DB_USERNAME=root
# DB_PASSWORD=

# Run migrations and seeders
php artisan migrate:fresh --seed

# Build assets
npm run build

# Start the server
php artisan serve
```

## Default Accounts

| Email | Password | Role | Description |
|-------|----------|------|-------------|
| `superadmin@superadmin.com` | password | superadmin | Full access to all features |
| `user@user.com` | password | user | Basic user role |
| `adminhotel@haris-hotel-bandung.com` | password | hotel_admin | Admin for Haris Hotel Bandung |
| `receptionist@haris-hotel-bandung.com` | password | hotel_receptionist | Receptionist for Haris Hotel |
| `adminhotel@santosa-hospital-bandung-central.com` | password | hotel_admin | Admin for Santosa Hospital |
| `receptionist@santosa-hospital-bandung-central.com` | password | hotel_receptionist | Receptionist for Santosa Hospital |

## Roles & Permissions

| Role | Access |
|------|--------|
| **superadmin** | Full access to all modules, all tenants |
| **hotel_admin** | Manage own tenant: users, rooms, content, food, applications, API keys, front desk |
| **hotel_receptionist** | View rooms, manage front desk check-in/check-out |
| **user** | Basic user, view/update own profile |

## Routes

### Web (CMS)

| Route | Description |
|-------|-------------|
| `/cms/dashboard` | Dashboard |
| `/cms/front-desk` | Front Desk Management |
| `/cms/tenant` | Tenant Management |
| `/cms/tenant/channel` | Tenant Channel Management |
| `/cms/application` | Application Management |
| `/cms/room/room-type` | Room Type Management |
| `/cms/room/room` | Room Management |
| `/cms/content/content` | Content Management |
| `/cms/content/content-item` | Content Item Management |
| `/cms/food/food-categories` | Food Category Management |
| `/cms/food/food` | Food Management |
| `/cms/m3u` | M3U Source Management |
| `/cms/m3u/m3u-channel` | M3U Channel Management |
| `/cms/api-key` | API Key Management |
| `/cms/management/user` | User Management |
| `/cms/management/role` | Role Management |
| `/cms/management/permission` | Permission Management |
| `/cms/management/menu` | Menu Management |
| `/settings/profile` | Profile Settings |
| `/settings/password` | Password Settings |
| `/settings/appearance` | Appearance Settings |
| `/settings/two-factor` | Two-Factor Authentication |

### API (v1)

All API endpoints are prefixed with `/api/v1`.

#### Authentication
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/v1/auth/login` | Login (returns Bearer token) |
| POST | `/api/v1/auth/register` | Register new user |
| POST | `/api/v1/auth/forgot-password` | Request password reset |
| POST | `/api/v1/auth/reset-password` | Reset password |
| GET | `/api/v1/auth/me` | Get authenticated user |
| PUT | `/api/v1/auth/me` | Update authenticated user |
| POST | `/api/v1/auth/resend` | Resend email verification |
| DELETE | `/api/v1/auth/logout` | Logout |

#### Protected (requires `X-API-KEY` header or Bearer token)
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v1/tenant` | Get tenant info with profile |
| GET | `/api/v1/contents` | List contents |
| GET | `/api/v1/content-items` | List content items |
| GET | `/api/v1/changelist/contents` | Content change list |
| GET | `/api/v1/changelist/content-items` | Content item change list |
| GET | `/api/v1/applications` | List applications |
| GET | `/api/v1/changelist/applications` | Application change list |
| GET | `/api/v1/foods/categories` | List food categories |
| GET | `/api/v1/foods/categories/{id}` | Show food category |
| GET | `/api/v1/changelist/foods/categories` | Food category change list |
| GET | `/api/v1/foods/items` | List food items |
| GET | `/api/v1/foods/items/{id}` | Show food item |
| GET | `/api/v1/changelist/foods/items` | Food item change list |
| GET | `/api/v1/rooms/types` | List room types |
| GET | `/api/v1/rooms/types/{id}` | Show room type |
| GET | `/api/v1/rooms/items` | List rooms |
| GET | `/api/v1/rooms/items/{no}` | Show room by number |
| GET | `/api/v1/m3u-channels` | List M3U channels |

#### API Query Parameters
Most list endpoints support:
- `search` — Search keyword
- `searchBySpecific` — Search by specific field
- `orderBy` — Order by field
- `order` — `asc` or `desc`
- `paginate` — Items per page (default: 10)
- `ids` — Filter by comma-separated IDs
- `after` — For change list, get records with version > value

## Architecture

### Coding Style
- **Action Classes** — All business logic is encapsulated in Action classes (`app/Actions/`)
- **Livewire Components** — Split into `table` (list/pagination) and `create-update` (form modal) components
- **Folio Pages** — File-based routing for all CMS pages
- **Traits** — Reusable logic (filtering, media, response formatting)

### Directory Structure
```
app/
├── Actions/
│   ├── Api/V1/          # API action classes
│   ├── Cms/             # CMS action classes (Store, Update, Delete per module)
│   └── Fortify/         # Auth action classes
├── Enums/               # CommonStatusEnum, TenantTypeEnum, ValidationEnum
├── Http/
│   ├── Controllers/Api/ # API controllers (thin, delegate to actions)
│   ├── Middleware/       # AuthApiKey middleware
│   └── Requests/        # Form request validation
├── Livewire/            # BaseComponent
├── Models/              # Eloquent models
├── Providers/           # Service providers
└── Traits/              # Reusable traits

resources/views/
├── components/cms/      # Livewire components (⚡table/, ⚡create-update/)
├── pages/cms/           # Folio pages
└── layouts/             # App layout, auth layout
```

## API Authentication

The API supports two authentication methods:

### 1. Bearer Token (Sanctum)
```bash
# Login to get token
curl -X POST /api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email": "admin@example.com", "password": "password"}'

# Use token
curl -H "Authorization: Bearer {token}" /api/v1/tenant
```

### 2. API Key
Generate an API key from the CMS (`/cms/api-key`), then use it:
```bash
curl -H "X-API-KEY: {encrypted-api-key}" /api/v1/tenant
```

## License

Proprietary.
