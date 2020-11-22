# Event

Creates a new Event in the system.

**URL** : `api/event/{id_event}`

**Method** : `DELETE`

**Auth required** : YES

## Success Response

**Code** : `200 Ok`

**Content example**

```json
{
    "message": "Event successfully canceled!"
}
```

## Error Response

**Condition** : If the event does not exist

**Code** : `404 Not Found`

**Example Content** :

```json
{
    "message": "event not found!"
}
```


**Condition** : If you are trying to edit an event that you are not the creator

**Code** : `400 Not Found`

**Example Content** :

```json
{
   "message": "You cannot cancel this event because you are not the creator"
}
```

**Condition** : If you are trying to delete an event that has already been canceled

**Code** : `400 Not Found`

**Example Content** :

```json
{
   "message": "This event has already been canceled"
}
```
