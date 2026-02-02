Organization Relationships API
A RESTful API built with Laravel 12 for storing, managing, and querying hierarchical relationships between organizations.

The service handles complex structures, including multi-level hierarchies and many-to-many relationships (multiple parents and multiple daughters), while providing an efficient way to retrieve all related entities (parents, daughters, and sisters).

🚀 Features
Recursive Organization Creation: Add deeply nested structures in a single POST request.

Many-to-Many Relationships: Support for multiple parents and multiple daughters.

Automatic Relationship Discovery:

Parents: Organizations above the current one.

Daughters: Organizations below the current one.

Sisters: Organizations sharing at least one common parent.

Optimized Results: Flattened, duplicate-free list, alphabetically sorted with pagination support (up to 100 rows).

Transactional Safety: All recursive operations are wrapped in database transactions to ensure data integrity.

🛠 Technical Stack
Framework: Laravel 12

Language: PHP 8.3+

Database: MySQL / MariaDB

Environment: DDEV

Testing: PHPUnit

📦 Installation & Setup
1. Prerequisites
Ensure you have Docker and DDEV installed on your system.

Docker Installation Guide

DDEV Installation Guide

2. Clone the repository

git clone https://github.com/ErikEgl/org-relations-ddev.git
cd org-relations-ddev
3. Initialize Environment
If you are running the project for the first time or from a fresh clone, run these commands:


# Configure DDEV for the project
ddev config --project-type=laravel --docroot=public

# Start the environment
ddev start

# Install PHP dependencies
ddev composer install

# Configure environment variables
cp .env.example .env
ddev artisan key:generate

# Install API scaffolding (Sanctum/Routes)
ddev artisan install:api

# Run database migrations
ddev artisan migrate
📡 API Usage
1. Add Organizations (Recursive POST)
Endpoint: POST /api/organizations

Example Request:

curl -X POST http://org-relations-ddev.ddev.site/api/organizations \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "org_name": "Paradise Island",
    "daughters": [
      {
        "org_name": "Banana tree",
        "daughters": [
          { "org_name": "Black Banana" }
        ]
      }
    ]
  }'
2. Get Organization Relations (GET)
Endpoint: GET /api/organizations/{org_name}/relations

Example Request:


curl -X GET "http://org-relations-ddev.ddev.site/api/organizations/Black%20Banana/relations" \
     -H "Accept: application/json"
✅ Testing
To ensure everything is working correctly, run the automated feature tests:


ddev artisan test
🧠 Implementation Details
Recursive Processing: Organizations are processed level by level. Existing organizations are identified by their unique org_name and linked accordingly using firstOrCreate.

Relationship Mapping: Uses a self-referencing many-to-many relationship via a pivot table (organization_relations).

Sister Logic: Implemented by fetching all daughters of the organization's parents, excluding the organization itself.

👤 Author
Built by Erik for the coding challenge.
