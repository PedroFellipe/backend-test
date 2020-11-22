Coderockr recruitment test
=======================

Technologies Used:
-----------------------

 * PHP v7.3 or higher
 * Laravel v8.12
 * MySql v8.0.22
 * tymon/jwt-auth

Project Installation and Execution
------------
Clone the project:

    git clone https://github.com/PedroFellipe/backend-test.git

After cloning the project, access the folder and run the following command to install the dependencies:

    composer install
    
After installation copy the .env.example file (If you are in a linux environment, just run the following command):

    cp .env.example .env

Now enter your local bank information in the following variables:

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=database
    DB_USERNAME=user
    DB_PASSWORD=password

Then, run the command below to run the project migrations:

    php artisan migrate
    
Run the command below to generate the application key:

    php artisan key:generate

Generating a JWT Secret Key

    php artisan jwt:secret 

To run the application run the laravel server:

    php artisan serve
    
Then, access the URL:

    http://localhost:8000/

      
Execution of unit tests:

    php artisan test

When performing unit tests, you should receive this as a result
    
  ![Alt text](unit_tests.png?raw=true "Title")

    
# Api Doc

## Open Endpoints

Open endpoints require no Authentication.

* [Login](api_docs/login.md) : `POST /api/auth/login/`
* [Register](api_docs/register.md) : `POST /api/auth/register/`
* [Event List](api_docs/get_events.md) : `GET /api/event/`
* [Event Info](api_docs/get_event.md) : `GET /api/event/{id}`

## Endpoints that require Authentication

Closed endpoints require a valid Token to be included in the header of the
request. A Token can be acquired from the Login view above.

### Friendship Invitation

* [Friendship Invitation List](api_docs/get_event_invitation.md) : `GET /api/friendship_invitation/`
* [Invite a friend](api_docs/post_event_invitation.md) : `POST /api/friendship_invitation/`
* [Accept Friend Invitation](api_docs/put_event_invitation.md) : `PUT /api/friendship_invitation/{id_friendship_invitation}`
* [Declines Friend Invitation](api_docs/delete_friendship_invitation.md) : `DELETE /api/friendship_invitation/{id_friendship_invitation}`

### Friendship
* [Friendship List](api_docs/get_friendship.md) : `GET /api/friendship/`
* [Remove a friend](api_docs/delete_friendship_invitation.md) : `DELETE /api/friendship/{id_friendship}`

### Events
* [My Events](api_docs/get_user_events.md) : `GET /api/user/event`
* [Create Event](api_docs/post_event.md) : `POST /api/event`
* [Update Event](api_docs/put_event.md) : `PUT /api/event/{id}`
* [Cancel Event](api_docs/delete_event.md) : `DELETE /api/event/{id}`

### Invitations to Event
* [My invitations to events](api_docs/get_event_invitation.md) : `GET /api/user/invitation`
* [Invite to a event](api_docs/post_event_invitation.md) : `POST /api/event/{id_event}/invitation`
* [Update Event Invitation Status](api_docs/put_event.md) : `PUT /api/event/{id_event}/invitation/{id_event_invitation}`

**Obs.:when a user invites someone with an email that is not registered in the system, an invitation email will be sent to that person, in the current settings, that email is available in `` storage/logs/laravel.log`` if you wish send the actual email, you must change the settings in the .env file
