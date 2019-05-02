# zipdev-api
CRUD (API) to register, phone numbers and emails in a phonebook
## Instructions
 * Install DB dump file on your database server `zipdev_contacts.sql` located under the db folder 
 * Start php server `php -S localhost:8000` on the root of your project
## Api Endpoints
Method | Endpoint | Description |
------|------------|------|
GET | http://localhost:8000/contacts | Get all contacts |
GET | http://localhost:8000/contacts/:id | Get one contact |
POST | http://localhost:8000/contacts | Save a new contact |
POST | http://localhost:8000/contacts/:id | Update a contact |
DELETE | http://localhost:8000/contacts/:id | Delete a contact |
GET | http://localhost:8000/contacts?search=someone | Search for a contact |

[Endpoints documentation](https://web.postman.co/collections/4575440-213cb6cc-6b47-47b3-85e8-b2afa3a39486?workspace=a5c65fff-be7f-4d0e-8f55-bf500f237d92)

### Get all / one contacts
![List contacts](https://raw.githubusercontent.com/carlos-cruz/zipdev-api/master/documentation/ListContacts.png)

### Save contact
![Save contact](https://raw.githubusercontent.com/carlos-cruz/zipdev-api/master/documentation/SaveContact.png)

### Update contact
![Update contact](https://raw.githubusercontent.com/carlos-cruz/zipdev-api/master/documentation/UpdateContact.png)

### Delete contact
![Delete contact](https://raw.githubusercontent.com/carlos-cruz/zipdev-api/master/documentation/DeleteContact.png)

### Search contacts
![Search contact](https://raw.githubusercontent.com/carlos-cruz/zipdev-api/master/documentation/SearchContact.png)
