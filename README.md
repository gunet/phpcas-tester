# Description

A simple Docker image for testing CAS login (and attributes) using the phpCAS library
Docker Hub image: [gunet/phpcas-tester](https://hub.docker.com/repository/docker/gunet/phpcas-tester/general)

# Main files
* `docker-compose.yaml`
  - Builds the project (altough you can also download the image from Docker Hub). Run `docker compose build`
  - Includes the `variables.env` environment variables file
  - Exposes port 443
* `docker-compose.volume.yaml`
  - Volume mount the `code` folder
* `docker-compose.test.yaml`
  - Includes the `gunet/simple-cas` and `gunet/simple-ldap` Docker images in order to allow the compose stack
    to be self-contained
  - Just point your browser to `https://localhost` and things should work!
  - Volume mounts the `code` folder into the tester container so that any changes to the php files are
    automatically reflected in the tester container.
  - Some test users:
    * test/test
    * auser/auser
* `variables.env`: Environment variables file By default, it is designed to assist the `docker-compose.test.yaml` stack
  - `DEBUG` variable sets debugging (mainly phpCAS debug)

# Run
* Edit `variables.env` if needed
* Run `docker compose up -d` if we want to check an existing production CAS server
* Run `docker compose -f docker-compose.yaml -f docker-compose.test.yaml up -d` if we want to run the test stack (Which includes a CAS and LDAP server)
* Point browser to `https://localhost`

# Environment Variables
* `CAS_SERVER`: The hostname of the CAS server (default `localhost`)
* `CAS_CONTEXT`: The CAS context. In the case of `gunet/simple-cas` it will be `/cas`, while in the case of a regulat CAS it will be `./` (default `/cas`)
* `CAS_PORT`: The port where the CAS server is listening to (default `8443`)
* `CAS_VERSION`: The CAS protocol version (default `3.0`)
* `CAS_SERVICE_NAME`: The service name to use for our service (default `https://localhost`)
* `CAS_CONTAINER`: If using PHP CAS tester in a Docker compose stack environment, `localhost` will mean something different for the user using it and for php-castester itself. In such a case we can set the `CAS_CONTAINER` environment variable (it is empty by default) to point to a "correct" CAS server name. This is used to access `/p3/serviceValidate` and the actual string will be: `'https://' . $_ENV['CAS_CONTAINER'] . ':' . $_ENV['CAS_PORT'] . $_ENV['CAS_CONTEXT'] . '/p3/serviceValidate'`