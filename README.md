# Sleeptight-Server
Serverside for Sleeptight. Using Slim Framework and PHP for fetching data from the database into JSON and inserting Senz2 responses from JSON into the SQL Database.


Return JSON

```
Pattern: localhost/sleeptight-server/

Sample URL: http://localhost/sleeptight-server/

Sample Output: {"name":"Pablo","age":22}

```


Return JSON with Parameter

```
Pattern: localhost/sleeptight-server/hello/{name}

Sample URL: http://localhost/sleeptight-server/hello/kees

Sample output: {"intent":"Hello","to":"kees"}

```


Return JSON with double Parameter

```
Pattern: localhost/sleeptight-server/{intent}/{uid}

Sample URL: http://localhost/sleeptight-server/follow/423

Sample output: {"intent":"follow","who":"423"}

```

Protected JSON Route with Masterkey

```
Pattern: localhost/sleeptight-server/{key}

When given wrong Masterkey:

Sample URL: http://localhost/sleeptight-server/asbasdds

Sample output:
{"success":false,"connected":false,"errors":{"key":"Wrong master key"}}

When given correct Masterkey:

Sample URL: http://localhost/sleeptight-server/YOUR_SECRET_CODE

Sample output:
{"success":true,"connected":true,"errors":false}


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
