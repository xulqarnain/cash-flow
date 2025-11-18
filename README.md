# Cash Record App

A personal-use web application to track cash and online transactions with individuals. Built with Laravel, Livewire, and Tailwind CSS.

## Features

- **No Authentication Required** - Simple, single-user application
- **Dashboard** - Overview of cash/online transactions with upcoming and overdue payments
- **People Management** - Add, edit, and delete people with contact information
- **Transaction Tracking** - Record cash and online transactions with:
  - Direction: Give (pay), Get (receive), or Both
  - Payment method: Cash or Online
  - Optional due dates with overdue highlighting
  - Descriptions for context
- **Balance Calculation** - Automatic balance calculation per person
- **Filtering** - Filter transactions by direction, method, and date range
- **Responsive Design** - Clean, modern UI that works on all devices

## Technologies

- **Laravel 12** - Latest PHP framework
- **Livewire 3** - Reactive components without writing JavaScript
- **Tailwind CSS v4** - Utility-first styling
- **SQLite/MySQL** - Lightweight database (configurable)

## Installation

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js & npm
- SQLite PHP extension (php8.4-sqlite3) OR MySQL

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd cash-flow
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node dependencies**
   ```bash
   npm install
   ```

4. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database**

   For SQLite (recommended for personal use):
   ```bash
   # Install PHP SQLite extension if not already installed
   # Ubuntu/Debian: sudo apt-get install php8.4-sqlite3

   # Create database file
   touch database/database.sqlite

   # Update .env to use SQLite
   DB_CONNECTION=sqlite
   DB_DATABASE=/full/path/to/database/database.sqlite
   ```

   For MySQL:
   ```bash
   # Update .env with your MySQL credentials
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=cash_record
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

6. **Run migrations**
   ```bash
   php artisan migrate
   ```

7. **Build frontend assets**
   ```bash
   npm run build
   ```

8. **Start the development server**
   ```bash
   php artisan serve
   ```

9. **Access the application**

   Open your browser and visit: `http://localhost:8000`

## Usage

### Dashboard
- View summary statistics for cash and online transactions
- See upcoming dues and overdue payments
- Quick access to recent people

### Managing People
- Navigate to "People" from the main menu
- Click "Add Person" to create a new contact
- Edit or delete existing people
- View individual ledgers by clicking "View"

### Recording Transactions
- Open a person's ledger
- Click "Add Transaction"
- Fill in the details:
  - **Amount**: The transaction value
  - **Direction**:
    - "I will receive" (get) - Money owed to you
    - "I will pay" (give) - Money you owe
    - "Both" - Split or complex transaction
  - **Method**: Cash or Online
  - **Description**: Optional note about the transaction
  - **Due Date**: Optional deadline for payment

### Filtering Transactions
- Use filters on the person ledger page
- Filter by direction, method, or date range
- Clear all filters with one click

## Database Schema

### People Table
- `id` - Primary key
- `name` - Person's name (required)
- `phone` - Phone number (optional)
- `email` - Email address (optional)
- `created_at` - Timestamp

### Transactions Table
- `id` - Primary key
- `person_id` - Foreign key to people
- `amount` - Transaction amount (decimal)
- `direction` - Enum: give, get, both
- `method` - Enum: cash, online
- `description` - Optional text
- `due_date` - Optional deadline
- `saved_at` - When transaction was recorded
- `created_at` - Timestamp

## Development

To run in development mode with hot reloading:

```bash
# Terminal 1: Start Laravel server
php artisan serve

# Terminal 2: Start Vite dev server
npm run dev
```

## Validation Rules

### Person
- Name: Required, max 255 characters
- Phone: Optional, max 20 characters
- Email: Optional, valid email format

### Transaction
- Amount: Required, positive decimal number
- Direction: Required, must be 'give', 'get', or 'both'
- Method: Required, must be 'cash' or 'online'
- Description: Optional, max 500 characters
- Due Date: Optional, must be today or future date

## Business Logic

- **Balance Calculation**:
  - Balance = Total 'get' amounts - Total 'give' amounts
  - 'Both' direction counts in both totals

- **Overdue Detection**:
  - Transaction is overdue if:
    - Direction is 'get'
    - Due date exists
    - Due date is in the past

## Future Enhancements

See `project-plan.md` for planned features:
- Phase 2: Export to Excel/PDF, advanced filtering
- Phase 3: SMS reminders, categories, PIN lock

## License

MIT License

## Support

For issues or questions, please open an issue on the repository.
