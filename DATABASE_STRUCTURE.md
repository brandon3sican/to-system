# Travel Order System Database Structure

This document provides a detailed overview of the database structure for the Travel Order System.

## Core Tables

### 1. `roles`
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

### 2. `div_sec_units`
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

### 3. `positions`
- **Purpose**: Stores job positions within units
- **Columns**:
  - `id`: Primary key
  - `name`: Position name (unique)
  - `div_sec_unit_id`: Foreign key to `div_sec_units`
  - `created_at`, `updated_at`: Timestamps

### 4. `employees`
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

### 5. `travel_orders`
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

## Supporting Tables

### 1. `employment_statuses`
- **Purpose**: Stores employment status types
- **Columns**:
  - `id`: Primary key
  - `name`: Status name
  - `description`: Status description
  - `created_at`, `updated_at`: Timestamps

### 2. `official_stations`
- **Purpose**: Stores official station locations
- **Columns**:
  - `id`: Primary key
  - `name`: Station name
  - `address`: Station address
  - `created_at`, `updated_at`: Timestamps

### 3. `travel_order_logs`
- **Purpose**: Stores audit logs for travel orders
- **Columns**:
  - `id`: Primary key
  - `travel_order_id`: Foreign key to `travel_orders`
  - `action`: Type of action performed
  - `user_id`: ID of the user performing the action
  - `created_at`: Timestamp of the action

## Foreign Key Relationships

1. `employees.position_id` → `positions.id`
2. `employees.div_sec_unit_id` → `div_sec_units.id`
3. `employees.employment_status_id` → `employment_statuses.id`
4. `travel_orders.employee_id` → `employees.id`
5. `travel_orders.official_station_id` → `official_stations.id`
6. `travel_orders.created_by` → `users.id`
7. `travel_orders.recommended_by` → `users.id`
8. `travel_orders.approved_by` → `users.id`
9. `travel_order_logs.travel_order_id` → `travel_orders.id`
10. `travel_order_logs.user_id` → `users.id`

## Additional Notes

1. The database uses Laravel's built-in `users` table for authentication and authorization.
2. All timestamps are stored in UTC format.
3. All foreign key relationships are enforced at the database level.
4. The system uses enum fields for fixed value sets (e.g., gender, status).
5. Personal information (first_name, last_name) is protected with a unique constraint to prevent duplicates.
