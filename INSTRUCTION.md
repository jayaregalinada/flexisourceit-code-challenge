# EonX Code Challenge
This challenge requires you to create an API that will import data from a third party API and be
able to display it. This challenge should demonstrate how you structure your code and apply
any appropriate design patterns. Please read through everything before starting.

### Features
- Import customers from a 3rd party data provider and save to database.
- Display a list of customers from the database.
- Select and display details of a single customer from the database.

### Framework
- Use Lumen or Symfony to write the following backend services in a single project.

### Data Importer
- Fetch and store a minimum of 100 users from this data provider: https://randomuser.me/api. See official documentation for fetching multiple users API Documentation
- The user data retrieved from the data provider must be stored in a SQL Type database and must be called customers table.
- Only import customers that have the Australian nationality, Refer to API Documentation.
- The importer service should be constructed in a way that it can be used in any part of the Application -Services or Controllers such as API controllers, Command, Job, etc.
- The importer should be designed so the data provider could be replaced later with minimal impact on the codebase.
- Create a console command to be able to execute the importer.
- If the data provider returns customer that already exist by email - Update the customer.

### RESTful API
Create 2 RESTful API endpoints with the following route definition:
- `GET /customers` - retrieve the list of all customers from the database. The response should contain:
  - full name (first name + last name)
  - email
  - country

- `GET /customers/{customerId}` - retrieve more details of a single customer. The response should contain:
  - full name (first name + last name) 
  - email
  - username
  - gender
  - country
  - city
  - phone
  
> __DEVELOPERS NOTES__
> - The customer password should be hashed using the md5 algorithm.
> - The database should only store the information that is needed for this task.
> - In your tests make sure to not to request the real third party API.
> - The database layer should be [Doctrine](https://www.doctrine-project.org/projects/orm.html), [Laravel Doctrine](http://www.laraveldoctrine.org/docs/1.3/orm)
> - Please submit your code in a GitHub repository.

