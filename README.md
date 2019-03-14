# Blog API

This is an example of a Blog API containing:

* Posts
* Comments
* Users

## How to Run

*All commands were tested with a Debian 9 (Stretch) system.*

Use *composer* to install the dependencies:

> composer install

**Note**: You might need the mongodb driver for PHP installed in your machine to run composer install

Then run the *docker* container using the *docker-compose.dev.yml* file:

> docker-compose -f docker-compose.dev.yml -d up --build

This will setup both the *web* container adn the *mongo* container, the php code will be mapped to your project folder.

Note that the mongo database will be populated with data, this occurs in the build and initialization process. In the *Dockerfile* the json data is fetched from the remote api, and in the *docker-php-entrypoint* the data is imported into the mongo database. If the data was already imported before, it is not imported again.


## Contributing

The project follows a MVC-like structure, the typical request would go like this:

1. User request endpoint */posts*
2. .htaccess file rewrites request to the *index.php* file
3. *index.php* calls *setup.php* to setup basic application configurations
4. Request url is matched against a **route** defined with the *Flight::route()* call
5. Controller method corresponding to the route is called with the route parameters
6. Controller calls the respective **model**
7. Model make the necessary validations and call the **MongoDB** api classes
8. Model returns the data to the Controller
9. Controller responds encoding the data as *JSON* to the user.


## Deployment

To deploy use the *docker-compose.yml* and *Dockerfile*, which will copy the files to the container image itself:

> docker-compose up -d --build

Ideally, you would run the deployment in a CI/CD pipeline, with something like Jenkins or Gitlab, and provision the environments and services with a provisioning tool like **Ansible**, **Puppet** or **Terraform**.

The current deployment include only 2 services:

 * web (*php with apache*)
 * mongo (*mongodb*)

 This should be fine for testing purposes, but to run in a production environment it should be considered a more scalable architecture. One such approach could contain:

 * A load balancer/reverse proxy front-end (Nginx, HAProxy or Traefik);
 * A variable number of the **web** container, since it is stateless, there shouldn't be any issues;
 * A mongodb cluster with many container instances, using replication or sharding, depending on the type of load;
 * A Redis cluster to store cache and stateful data might be useful;

 This could be accomplished with a **docker swarm** cluster for example, with only minor modifications to the project.

 Depending on the cloud provider used (if any), some of these services could be changed for vendor-specific services, for example, AWS ECS (Elastic Container Service) already provides load-balancing/high availability functionalities, along with key-value stores and NoSQL databases.