# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel 11 (PHP 8.2+) application for inventory and sales management ("Suministros Osicala"). It's an ERP system that integrates with El Salvador's DTE (Documento Tributario Electrónico) fiscal system for electronic invoicing. The application uses Filament v3 for the admin panel and includes point-of-sale, inventory management, purchasing, and accounting features.

## Tech Stack

- **Backend**: Laravel 11.45.1 (PHP 8.2+)
- **Admin Panel**: Filament v3.2
- **Frontend**: Livewire 3.4, Volt 1.0, Tailwind CSS
- **Database**: MariaDB
- **PDF Generation**: DomPDF (barryvdh/laravel-dompdf)
- **Excel Export**: Maatwebsite Excel, Filament Excel
- **QR Codes**: SimpleSoftwareIO Simple QR Code
- **Key Packages**:
  - filament/filament - Admin panel
  - bezhansalleh/filament-shield - Role-based permissions
  - rmsramos/activitylog - Activity logging
  - luecano/numero-a-letras - Number to words (Spanish)

## Development Commands

### Initial Setup
```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file and configure
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Seed database (if seeders exist)
php artisan db:seed
```

### Development Server
```bash
# Start Laravel development server
php artisan serve

# Compile assets (development)
npm run dev

# Compile assets (production)
npm run build
```

### Code Quality & Testing
```bash
# Run tests
php artisan test
# OR
vendor/bin/phpunit

# Format code with Laravel Pint
vendor/bin/pint

# Clear caches (useful when debugging)
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Filament-specific cache clear
php artisan filament:cache-components
```

### Database
```bash
# Run migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Fresh migration (WARNING: destroys data)
php artisan migrate:fresh

# Refresh migration with seeding
php artisan migrate:fresh --seed
```

## Architecture Overview

### Core Domain Models

The application is centered around these primary domains:

**Inventory & Products:**
- `Product` - Product catalog with categories and brands (marcas)
- `Inventory` - Stock management per branch with min/max levels and costs
- `Kardex` - Inventory movement tracking (created automatically via `KardexHelper`)
- `InventoryCostoHistory` - Cost history tracking for weighted average cost calculation
- `InventoryGrouped` - Product grouping/bundles

**Sales & Cash Management:**
- `Sale` - Sales/invoices with DTE integration
- `SaleItem` - Line items for sales
- `Order` - Sales orders (can be converted to invoices)
- `Quote` - Customer quotations
- `CashBox` - Cash register/point-of-sale definitions
- `CashBoxOpen` - Cash box sessions with opening/closing balances
- `CashBoxCorrelative` - Document numbering per cash box

**Purchasing:**
- `Purchase` - Purchase orders and receipts
- `PurchaseItem` - Purchase line items
- `Provider` - Supplier management

**Customers & Configuration:**
- `Customer` - Customer records with document type validation
- `Company` - Company configuration for DTE
- `Branch` - Branches/warehouses
- `Employee` - Staff records (sellers, cashiers, mechanics)

### Key Services & Helpers

**`KardexHelper` (app/Helpers/KardexHelper.php):**
- Autoloaded via composer.json (in "files" array)
- `createKardexFromInventory()` - Creates kardex entries for inventory movements
- Calculates weighted average cost (promedio ponderado) automatically
- Called from inventory transactions (sales, purchases, adjustments, transfers)

**`GetCashBoxOpenedService` (app/Service/):**
- Validates that a cash box is open before allowing sales operations
- Returns current opened cash box session

**`CashBoxResumenService` (app/Services/):**
- Generates cash box closing summaries

### DTE Integration (Electronic Invoicing)

The system integrates with El Salvador's Ministerio de Hacienda DTE system:

**`DTEController` (app/Http/Controllers/DTEController.php):**
- `generarDTE($idVenta)` - Generates and sends electronic invoices to Hacienda
- `anularDTE($idVenta)` - Cancels/invalidates DTEs
- `printDTETicket($idVenta)` - Prints ticket format
- `printDTEPdf($idVenta)` - Prints PDF format
- Supports document types: 01 (Factura), 03 (CCF), 04 (Nota de Remisión), 05 (Nota de Crédito), 06 (Nota de Débito), 11 (Exportación), 14 (Sujeto Excluido)

**DTE-Related Models:**
- `HistoryDte` - DTE transaction history
- `DteTransmisionWherehouse` - DTE transmission warehouse
- `Contingency` / `ContingencyType` - Handles contingency mode when Hacienda is offline
- `BillingModel` - Billing model configuration
- `TransmisionType` - Transmission type (normal/contingency)

### Filament Resources

Filament Resources follow standard Laravel patterns in `app/Filament/Resources/`:
- Each resource has a main Resource file (e.g., `SaleResource.php`)
- Pages subdirectory contains List/Create/Edit pages
- `SaleResource` includes custom logic for calculating totals, taxes, and retention
- Form components use reactive fields to update calculations in real-time

### Route Structure

**Web Routes (routes/web.php):**
- Root redirects to `/admin` (Filament admin panel)
- DTE operations: `/generarDTE/{idVenta}`, `/sendAnularDTE/{idVenta}`, etc.
- Print operations: `/printDTETicket/{idVenta}`, `/ordenPrint/{idVenta}`, etc.
- Reports: `/sale/iva/libro/ccf/{startDate}/{endDate}`, `/sale/json/...`, etc.
- Employee reports: `/employee/sales/{id_employee}/{star_date}/{end_date}`
- Contingency operations: `/contingency/{description}`, `/contingency_close/{uuid_contingence}`

Filament handles all CRUD operations internally via `/admin` routes.

### Important Behavioral Notes

**Inventory Management:**
- Inventory is tracked per branch (`branch_id` on Inventory model)
- Every inventory movement creates a Kardex entry automatically
- Cost calculation uses weighted average (costo promedio ponderado)
- When Inventory is created, a Kardex entry is auto-generated via model boot event (see Inventory.php:32-59)

**Sales Flow:**
1. Check if cash box is open (via `GetCashBoxOpenedService`)
2. Create Sale with customer, document type, payment method
3. Add SaleItems (reduces inventory automatically)
4. Calculate totals including IVA and retention (see `updateTotalSale()` in SaleResource.php)
5. Optionally generate DTE if `is_dte` flag is set
6. Print ticket/invoice

**Tax Calculations:**
- IVA (sales tax) is configurable via `Tribute` model (typically 13%)
- ISR (retention) is also configurable
- Tax applied conditionally based on `is_taxed` flag
- Retention applied based on `have_retention` flag
- Net calculation: `neto = is_taxed ? montoTotal / (1 + ivaRate) : montoTotal`

**Document Numbering:**
- Each CashBox has correlatives for different document types (via `CashBoxCorrelative`)
- Documents auto-increment based on cash box and document type

## Database Configuration

The application uses MariaDB by default (see .env.example):
```
DB_CONNECTION=mariadb
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=erp_dte
DB_USERNAME=root
DB_PASSWORD=
```

## Asset Compilation

Vite is configured to watch:
- `resources/css/app.css`
- `resources/js/app.js`
- `resources/css/filament/admin/theme.css`
- Auto-refresh on changes to: `app/Filament/**`, `app/Forms/Components/**`, `app/Livewire/**`, `app/Tables/Columns/**`

## Key Conventions

1. **Models**: Follow Eloquent conventions, relationships defined explicitly
2. **Filament Resources**: Use form schemas with reactive fields for dynamic calculations
3. **Controllers**: Focused on DTE operations and PDF generation
4. **Services**: Located in both `app/Service/` and `app/Services/` (note inconsistent naming)
5. **Helpers**: Use `KardexHelper` for all inventory movement tracking - never create Kardex entries directly
6. **Soft Deletes**: Many models use SoftDeletes trait (Sale, Inventory, etc.)
7. **Exports**: Located in `app/Filament/Exports/` and `app/Exports/`

## Common Patterns

**Creating a Sale with DTE:**
1. Validate cash box is open
2. Create Sale record with `is_dte = false` initially
3. Add SaleItems (inventory decrements automatically)
4. Call `updateTotalSale()` to recalculate taxes/totals
5. Call DTEController::generarDTE() to submit to Hacienda
6. Update Sale with `is_dte = true`, `generationCode`, `receiptStamp`, etc.

**Working with Inventory:**
- Always update via Purchase, Sale, Transfer, or AdjustmentInventory
- Never modify Inventory stock directly without creating Kardex entry via KardexHelper
- Cost changes are tracked in InventoryCostoHistory

**Generating Reports:**
- Excel exports use Maatwebsite Excel or Filament Excel
- PDF reports use DomPDF
- JSON exports for DTE compliance (Hacienda reporting)

## Deployment Notes

This is a XAMPP-based application (note Windows paths in git output). In production:
- Ensure proper file permissions for storage/ and bootstrap/cache/
- Configure queue worker if using database queues (QUEUE_CONNECTION=database)
- Set APP_DEBUG=false and APP_ENV=production
- Configure proper MAIL settings for DTE email sending
- Ensure AWS credentials if using S3 for DTE storage
