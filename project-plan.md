# Ì≤∞ Cash Record App ‚Äî Project Plan

A personal-use web app to track cash and online transactions with individuals. Designed for simplicity, clarity, and offline use ‚Äî no authentication required.

---

## Ì∑± Technologies Used

- **Laravel** (latest version)
  - Backend framework
  - Eloquent ORM for database
  - Routing and service layer

- **Livewire**
  - Reactive UI components
  - SPA-like behavior without JavaScript

- **Tailwind CSS**
  - Utility-first styling
  - Responsive and clean UI

- **SQLite**
  - Lightweight, file-based database
  - Ideal for personal/local use
  - No server setup required

---

## Ì∑≠ App Overview

- No login or user accounts
- Dashboard loads on app open
- Tracks people and their transactions
- Supports cash and online modes
- Shows balances, dues, and history

---

## Ì∑ÇÔ∏è Data Models

### 1. People

| Field      | Type     | Description               |
|------------|----------|---------------------------|
| id         | bigint   | Primary key               |
| name       | string   | Person's name             |
| phone      | string   | Optional contact          |
| email      | string   | Optional contact          |
| created_at | datetime | Record creation timestamp |

### 2. Transactions

| Field       | Type     | Description                          |
|-------------|----------|--------------------------------------|
| id          | bigint   | Primary key                          |
| person_id   | foreign  | Linked to `people`                   |
| amount      | decimal  | Transaction amount                   |
| direction   | enum     | `give`, `get`, or `both`             |
| method      | enum     | `cash` or `online`                   |
| description | text     | Optional reason or context           |
| due_date    | date     | Optional repayment deadline          |
| saved_at    | datetime | When transaction was recorded        |
| created_at  | datetime | Record creation timestamp            |

---

## ‚öôÔ∏è Livewire Components

- `Dashboard`
  - Total cash in/out
  - Upcoming dues
  - People summary

- `PeopleList`
  - Add/edit/delete people
  - View individual ledger

- `PersonLedger`
  - All transactions for one person
  - Running balance
  - Filters by date, method, direction

- `TransactionForm`
  - Add/edit transaction
  - Fields: amount, direction, method, description, due date

---

## Ì≥ä Business Logic

- **Balance per person** = total `get` ‚àí total `give`
- **Overdue** = `due_date < today` and direction is `get`
- **Description** helps clarify purpose (e.g., "Rent", "Loan", "Gift")
- **Both** direction can be split internally if needed

---

## Ìª£Ô∏è Roadmap

### Phase 1: Core Features
- People + Transactions CRUD
- Dashboard with summary
- Livewire UI

### Phase 2: Enhancements
- Filters and search
- Overdue highlighting
- Export to Excel/PDF

### Phase 3: Optional Add-ons
- SMS reminders
- Tags/categories
- PIN lock screen (no full auth)

---

## Ì∑™ Validation Rules

- Amount must be positive
- Direction must be valid enum
- Due date must be in future (if set)
- Description is optional but encouraged

---

## Ì∑† Usage Flow

1. Open app ‚Üí Dashboard loads
2. Add people ‚Üí Create transactions
3. View balances and dues
4. Filter by person, method, or date
5. Export or backup data manually

---

## Ì∑ÉÔ∏è SQLite Setup Notes

- `.env` configuration:
