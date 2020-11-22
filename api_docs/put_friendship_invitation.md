# Friendship Invitation

Accepts a friendship invitation.

**URL** : `api/friendship_invitation/{friendship_invitation_id}`

**Method** : `PUT`

**Auth required** : YES

## Success Response

**Code** : `200 Ok`

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
        "confirmed": 1

    }
]
```

## Error Response
**Condition** : If you have already accepted this request

**Code** : `404 Not Found`

**Example Content** :

```json
{
   "message": "Friendship request not found!!"
}
```



**Condition** : If you have already accepted this request

**Code** : `400 Bad Request`

**Example Content** :

```json
{
   "message": "This friend request has already been accepted !"
}
```

