# Events

Returns all friendship invitations

**URL** : `api/friendship_invitation`

**Method** : `GET`

**Auth required** : NO

## Success Response

**Code** : `200 OK`

**Content example**

```json
[
    {
        "id": 3,
        "user_that_make_invite": {
            "id": 2,
            "name": "test",
            "email": "test@gmail.com",
            "bio": "test@gmail.com",
            "city": "test@gmail.com",
            "state": "test@gmail.com"
        },
        "user_invited": {
            "id": 3,
            "name": "testA",
            "email": "testA@gmail.com",
            "bio": "testA@gmail.com",
            "city": "testA@gmail.com",
            "state": "testA@gmail.com"
        },
        "confirmed": 0
    }
]
```
