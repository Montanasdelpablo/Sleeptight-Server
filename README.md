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
