# Sleeptight-Server
Serverside for Sleeptight. Using Slim Framework and PHP for fetching data from the database into JSON and inserting Senz2 responses from JSON into the SQL Database.


API Register Route

```
Pattern: localhost/sleeptight-server/api/register

Make a post request to sample URL with the following data:
activation, username, password, name (optional), surname (optional), email (optional)

Sample URL: http://localhost/sleeptight-server/api/register

Sample output if required fields are entered:
{"status": "Success"}

Sample output if required fields are not entred:
{"error": "404", "status": "Inserting went wrong/Not all required fields entered"}


```

API Auth Route

```
Pattern: localhost/sleeptight-server/api/auth

Make a post request to sample URL with the following data:
username, password

Sample URL: http://localhost/sleeptight-server/api/auth



Sample output if required fields are entered:
{
  "status": "Success",
  "user": {
    "id": "11",
    "token": "zyxwvutsrqponmlkjihgfedcba",
    "username": "jenny",
    "name": "Koekiemonster",
    "surname": "Aapjes"
  }
}

Sample output if required fields are not entred:
{
  "error": "404",
  "status": "No users found for that credentials"
}


```


Gebruiker JSON Route

```
Pattern: localhost/sleeptight-server/api/gebruikers

Sample output:
[{"gebruiker_id":"1","voornaam":"Johan","achternaam":"Derkens","emailadres":"johanderkens@gmail.com"},{"gebruiker_id":"2","voornaam":"Gerda","achternaam":"Burka","emailadres":"gburkaas@gmail.com"},{"gebruiker_id":"3","voornaam":"Frederik","achternaam":"Oppel","emailadres":"foppel@gmail.com"}]

Pattern: localhost/sleeptight-server/api/gebruiker/{uid}

Sample URL: http://localhost/sleeptight-server/api/gebruiker/1

Sample output:
{"gebruiker_id":"1","voornaam":"Johan","achternaam":"Derkens","emailadres":"johanderkens@gmail.com"}


```
