# Contractor App

The Contractor App is a platform designed to connect contractors with customers based on skills, locations, and job requirements. It provides functionality for managing contractors, customers, skills, locations, and job postings, as well as creating contracts between contractors and customers.

## Features

- Manage contractors, customers, skills, and locations.
- Match contractors to jobs based on required skills.
- Seed the database with sample data for testing and development.
- Truncate and reset the database for clean testing environments.

## Technologies Used

- **Backend**: Laravel (PHP Framework)
- **Database**: MySQL (or any database supported by Laravel)
- **API**: This API is served with GraphQL

## Installation

1. Clone the repository:
   ```bash
   git clone git@github.com:vhmocampo/contractor-app.git
   cd contractor-app

2. Run Sail
   ```
   sail up -d

3. Copy environment values and generate key
cp .env.example .env
sail artisan key:generate

4 . Run migrations
sail artisan migrate


## 📦 Database Seeding

The application includes a robust seeding mechanism to populate the database with sample data for development and testing purposes.

### 🔧 How Seeding Works

- **Skills**: Default skills are created using the `Skill` model's `$defaultValues` property.
- **Contractors**: 100 contractors are generated using a factory. Each contractor is assigned 3 random skills.
- **Customers**: 100 customers are generated using a factory.
- **Locations**: 100 locations are generated using a factory.

To run the seeder:

```bash
sail artisan db:seed

## 🗂️ Database Schema

The database schema includes the following tables:

- `ca_customers`: Stores customer information  
- `ca_contractors`: Stores contractor information  
- `ca_skills`: Stores available skills  
- `ca_locations`: Stores location data  
- `ca_contractors_skills`: Pivot table linking contractors to their skills  
- `ca_jobs`: Stores job postings  
- `ca_jobs_skills`: Pivot table linking jobs to required skills  
- `ca_contracts`: Stores contracts between contractors and customers  

---

## ♻️ Resetting the Database

To reset the database, as well as migrations, run:

```bash
sail artisan ca:boot

