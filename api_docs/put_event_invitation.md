# Event Invitation

Update Event Invitation

**URL** : `api/event/{id_event}/invitation/{id_event_invitation}`

**Method** : `PUT`

**Auth required** : YES

**Data constraints**

```json
{
    "status": "[you can update the status of a event invitation|in:confirmed,rejected,awaiting_confirmation]"
}
```

**Data example**
```json
{
    "status": "confirmed"
}
```
## Success Response

**Code** : `200 OK`

**Content example**

```json
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
}
```

## Error Response


**Condition** : Data not found

**Code** : `404 Not Found`

**Example Content** :

```json
{
   "message": "Event invitation not found!"
}
```

**Condition** : if this event have already happened

**Code** : `400 Bad Request`

**Example Content** :

```json
{
   "message": "you cannot change the status of the invitation, because this event has already happened!"
}
```
