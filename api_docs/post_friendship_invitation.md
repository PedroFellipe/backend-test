# Friendship Invitation

Creates a new Friendship Request.

**URL** : `api/friendship_invitation`

**Method** : `POST`

**Auth required** : YES

**Data constraints**

```json
{
    "email": "[a valid email]"
}
```

**Data example**
```json
{
    "email": "teste3@gmail.com"
}
```
## Success Response

**Code** : `201 Created`

**Content example**

```json
[
    {
        "id": 2,
        "user_that_make_invite": {
            "id": 3,
            "name": "testA",
            "email": "testA@gmail.com",
            "bio": "testA@gmail.com",
            "city": "testA@gmail.com",
            "state": "testA@gmail.com"
        },
        "user_invited": {
            "id": 1,
            "name": "teste3",
            "email": "teste3@gmail.com",
            "bio": "teste3@gmail.com",
            "city": "teste3@gmail.com",
            "state": "teste3@gmail.com"
        },
        "confirmed": 0

    }
]
```

## Error Response

**Condition** : If you data request don't pass of the validation 

**Code** : `422 Unprocessable Entity
`

**Example Content** :

```json
{
    "errors": {
        "email": [
            "The email field is required."
        ]
    }
}
```

**Condition** : If you have already made a friend invitation to that person

**Code** : `400 Bad Request`

**Example Content** :

```json
{
   "message": "you've already made a friend invitation to that person!"
}
```


**Condition** : If that person has already invited you to be your friend

**Code** : `400 Bad Request`

**Example Content** :

```json
{
   "message": "that person has already invited you to be your friend!"
}
```

**Condition** : If the person that you're trying to invite is already your friend

**Code** : `400 Bad Request`

**Example Content** :

```json
{
   "message": "you're already friends!"
}
```


**Condition** : If you're trying to invite yourself

**Code** : `400 Bad Request`

**Example Content** :

```json
{
   "message": "you can't invite yourself!"
}
```
