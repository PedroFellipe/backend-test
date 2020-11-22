# Event Invitation

You can invite some or all of you friends, if you want to invite all, you have to pass the param type=all as a URL PARAM, if you want to invite some friends, you don't pass the URL PARAM, and you have to send a array of ids, with the users tha want to invite

**URL** : `api/event/{id_event}/invitation`

**Method** : `POST`

**Auth required** : YES

**Params** :

    -type=all

**Data constraints**

```json
{
    "users_id": "[a array of integers with the ids of the users, this is required if the PARAM 'type=all' is not present]"
}
```

**Data example**
```json
{
    "email": [1,2,3]
}
```
## Success Response

**Code** : `201 Created`

**Content example**

```json
{
    "message": "Invitations sent successfully"
}
```

## Error Response


**Condition** : Data not found

**Code** : `404 Not Found`

**Example Content** :

```json
{
   "message": "Event not found"
}
```

**Condition** : if you didn't created this event

**Code** : `400 Bad Request`

**Example Content** :

```json
{
   "message": "You can only send invitations to events that you have created!"
}
```

**Condition** : if this event it's canceled

**Code** : `400 Bad Request`

**Example Content** :

```json
{
   "message": "You can not invite friends for a canceled event!"
}
```

**Condition** : If you're trying to invite yourself

**Code** : `400 Bad Request`

**Example Content** :

```json
{
   "message": "If you're trying to invite a person that is not your friend"
}
```
