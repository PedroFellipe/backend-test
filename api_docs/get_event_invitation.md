# Events

Returns all friendship invitations

**URL** : `api/user/invitation`

**Method** : `GET`

**Auth required** : NO

## Success Response

**Code** : `200 OK`

**Content example**

```json
[
    {
        "id": 1,
        "event": {
            "id": 2,
            "user": {
                "id": 3,
                "name": "testA",
                "email": "testA@gmail.com",
                "bio": "testA@gmail.com",
                "city": "testA@gmail.com",
                "state": "testA@gmail.com"
            },
            "name": "PHP na brasa",
            "description": "evento anual do PHP na brasa",
            "date": "2020-12-10",
            "time": "12:00",
            "place": "AABB",
            "canceled": null
        },
        "status": "confirmed"
    },
    {
        "id": 2,
        "event": {
            "id": 2,
            "user": {
                "id": 3,
                "name": "testA",
                "email": "testA@gmail.com",
                "bio": "testA@gmail.com",
                "city": "testA@gmail.com",
                "state": "testA@gmail.com"
            },
            "name": "PHP na brasa",
            "description": "evento anual do PHP na brasa",
            "date": "2020-12-10",
            "time": "12:00",
            "place": "AABB",
            "canceled": null
        },
        "status": "awaiting_confirmation"
    }
]
```
