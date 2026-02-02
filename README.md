Organization Relationships API
    
    A RESTful API built with Laravel 12 for storing, managing, and querying hierarchical relationships between organizations.
    
    The API is designed to handle complex organizational structures, including multi-level hierarchies and many-to-many relationships, while providing a simple and efficient way to retrieve all related entities (parents, daughters, and sisters).
    
    ---
    
     🚀 Features
    
    - Recursive Organization Creation  
      Add deeply nested organizational structures in a single POST request.
    
    - Many-to-Many Relationships  
      An organization can have:
      - multiple parents  
      - multiple daughters  
    
    - Automatic Relationship Discovery
      - Parents – organizations above the current one  
      - Daughters – organizations below the current one  
      - Sisters – organizations that share at least one common parent  
    
    - Optimized Results
      - Returns a flattened list of all related organizations  
      - Alphabetically sorted  
      - Duplicate-free  
      - Supports pagination
    
    - Transactional Safety
      - All recursive inserts are wrapped in database transactions  
      - Guarantees atomicity and data integrity
    
    ---
    
     🛠 Technical Stack
    
    - Framework: Laravel 12  
    - Language: PHP 8.3+  
    - Database: MySQL / MariaDB  
    - Environment: DDEV  
    - ORM: Eloquent  
    
    ---
    
     📦 Installation & Setup
    
     1. Clone the repository
    bash
    git clone <repository-url>
    cd organization-relationships-api
    

 2\. Start DDEV

    ddev start
    

 3\. Install PHP dependencies

    ddev composer install
    

 4\. Configure environment

Copy .env.example to .env if needed and ensure database settings are correct (DDEV handles this by default).

 5\. Run migrations

    ddev artisan migrate
    

 6\. Install API scaffolding (if not already installed)

    ddev artisan install:api
    

  

📡 API Usage
------------

 1\. Add Organizations (Recursive POST)

Endpoint

    POST /api/organizations
    

Description  
Creates an organization and its full hierarchy recursively.  
If an organization already exists, it will be reused and linked instead of duplicated.

Example Request

    curl -X POST http://org-relations-ddev.ddev.site/api/organizations \
      -H "Content-Type: application/json" \
      -d '{
        "orgname": "Paradise Island",
        "daughters": [
          {
            "orgname": "Banana tree",
            "daughters": [
              { "orgname": "Black Banana" }
            ]
          }
        ]
      }'
    

Behavior

   Creates all missing organizations
   Links parents and daughters
   Prevents duplicate relationships
   Runs inside a database transaction

  

 2\. Get Organization Relations (GET)

Endpoint

    GET /api/organizations/{orgname}/relations
    

Description  
Returns all related organizations for the given organization:

   parents
   daughters
   sisters

Results are:

   flattened into a single list
   alphabetically sorted
   paginated

Example Request

    curl -X GET "http://org-relations-ddev.ddev.site/api/organizations/Black%20Banana/relations"
    

Example Response (simplified)

    {
      "data": [
        "Banana tree",
        "Paradise Island"
      ],
      "meta": {
        "currentpage": 1,
        "perpage": 10,
        "total": 2
      }
    }
    

  

🧠 Implementation Details
-------------------------

   Recursive Processing
    
       Organizations and their daughters are processed recursively
       Each level is stored and linked automatically
   Database Design
    
       organizations table stores unique organizations
       Pivot table (e.g. organizationrelationships) stores many-to-many links
   Data Integrity
    
       Uses database transactions
       Prevents partial inserts on failure
   Efficient Queries
    
       Uses Eloquent relationships and collections
       Merges parents, daughters, and sisters
       Removes duplicates before sorting and pagination

  

📈 Future Improvements (Ideas)
------------------------------

   GraphQL endpoint
   Caching for relation queries
   Cycle detection and prevention
   Soft deletes for organizations
   Role-based access control (RBAC)
   OpenAPI / Swagger documentation

  

📄 License
----------

This project is open-source and available under the MIT License.

  

👤 Author
---------

Built with ❤️ using Laravel 12  
Feel free to fork, extend, and contribute.