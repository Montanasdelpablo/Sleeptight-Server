# Sleeptight-Server
Serverside for Sleeptight. Using Slim Framework and PHP for fetching data from the database into JSON and inserting Senz2 responses from JSON into the SQL Database.

localhost/sleeptight-server/

Returns JSON

```
Sample URL: http://localhost/sleeptight-server/

{"name":"Pablo","age":22}

```

localhost/sleeptight-server/hello/{name}

Returns JSON with Parameter

```

Sample URL: http://localhost/sleeptight-server/hello/kees

Sample output: {"intent":"Hello","to":"kees"}

```
