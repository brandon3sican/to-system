# Travel Order System

A Laravel-based system for managing travel orders and employee information.

## Features

- Employee Management
- Position Management
- Division/Section/Unit Management
- Travel Order Processing
- Role-Based Access Control
- Audit Logging

## Database Structure

This section provides a detailed overview of the database structure.

### Core Tables

#### 1. `roles`
- **Purpose**: Stores user roles and permissions
- **Columns**:
  - `id`: Primary key
  - `name`: Role name (unique)
  - `description`: Role description
  - `created_at`, `updated_at`: Timestamps
- **Default Roles**:
  - Employee
  - Recommender
  - Approver
  - Administrator

#### 2. `div_sec_units`
- **Purpose**: Stores Division/Section/Unit information
- **Columns**:
  - `id`: Primary key
  - `name`: Unit name (unique)
  - `description`: Unit description
  - `created_at`, `updated_at`: Timestamps
- **Default Units**:
  - Development Department
  - Security Department
  - Unit 1
  - Unit 2

#### 3. `positions`
- **Purpose**: Stores job positions within units
- **Columns**:
  - `id`: Primary key
  - `name`: Position name (unique)
  - `div_sec_unit_id`: Foreign key to `div_sec_units`
  - `created_at`, `updated_at`: Timestamps

#### 4. `employees`
- **Purpose**: Stores employee information
- **Columns**:
  - `id`: Primary key
  - `first_name`: Employee first name
  - `last_name`: Employee last name
  - `phone`: Contact number
  - `address`: Employee address
  - `birthdate`: Date of birth
  - `gender`: Gender (Male/Female/Other)
  - `date_hired`: Date of employment
  - `position_id`: Foreign key to `positions`
  - `div_sec_unit_id`: Foreign key to `div_sec_units`
  - `employment_status_id`: Foreign key to `employment_statuses`
  - `salary`: Employee salary
  - `created_at`, `updated_at`: Timestamps
- **Constraints**:
  - Unique constraint on `first_name` and `last_name` combination

#### 5. `travel_orders`
- **Purpose**: Stores travel order information
- **Columns**:
  - `id`: Primary key
  - `employee_id`: Foreign key to `employees`
  - `official_station_id`: Foreign key to `official_stations`
  - `destination`: Travel destination
  - `purpose`: Purpose of travel
  - `departure_date`: Date of departure
  - `arrival_date`: Date of arrival
  - `return_date`: Date of return
  - `per_deim`: Per diem allowance (yes/no)
  - `assistant`: Number of assistants
  - `appropriation`: Appropriation details
  - `status`: Current status (Pending/Recommended/Approved/Rejected/Cancelled)
  - `remarks`: Additional remarks
  - `created_by`: ID of the creator
  - `recommended_by`: ID of recommender (nullable)
  - `approved_by`: ID of approver (nullable)
  - `created_at`, `updated_at`: Timestamps

## Setup Instructions

1. Clone the repository:
```bash
git clone https://github.com/brandon3sican/to-system.git
```

2. Install dependencies:
```bash
cd to-system
composer install
npm install
```

3. Copy the environment file and configure it:
```bash
cp .env.example .env
```
Edit `.env` with your database credentials:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

4. Generate application key:
```bash
php artisan key:generate
```

5. Run database migrations:
```bash
php artisan migrate
```

6. Run database seeders:
```bash
php artisan db:seed
```

7. Compile assets:
```bash
npm run dev
```

8. Start the development server:
```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## Additional Notes

1. The database uses Laravel's built-in `users` table for authentication and authorization.
2. All timestamps are stored in UTC format.
3. All foreign key relationships are enforced at the database level.
4. The system uses enum fields for fixed value sets (e.g., gender, status).
5. Personal information (first_name, last_name) is protected with a unique constraint to prevent duplicates.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
