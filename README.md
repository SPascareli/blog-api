# Blog API

This is an example of a Blog API containing:

* Posts
* Comments
* Users

## How to Run

Use *composer* to install the dependencies:

> composer install

Then run the *docker* container using the *docker-compose.dev.yml* file:

> docker-compose up -d -f docker-compose.dev.yml

This will setup both the *web* container adn the *mongo* container.