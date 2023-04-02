

<!-- GETTING STARTED -->
## Getting Started


### Prerequisites

Need to have docker installed on your machine.

### Installation

1. The following API keys are required to run the application:
    1. News API: https://newsapi.org/
    2. New York Times: https://developer.nytimes.com/
    3. The Guardian: https://open-platform.theguardian.com/access/
2. Copy paste the /server/.env.example to /server/.env
    ```sh
    cp /server/.env.example /server/.env
    ```
3. Update the /server/.env  with the API keys
    ```sh
    NEWS_API_KEY=ENTER YOUR API
    NY_TIMES_API_KEY=ENTER YOUR API
    THE_GUARDIAN_API_KEY=ENTER YOUR API
    ```
   
4. Copy paste the /client/.env.example to /client/.env
    ```sh
    cp /client/.env.example /client/.env
    ```
5. Run the following command to build the docker image:
    ```sh
    docker-compose up --build
    ```
6. Run the following command to setup the application:
   1. Migrate the db
       ```sh
        docker-compose run artisan migrate
       ```
   2. Seed the db
        ```sh
         docker-compose run artisan db:seed
        ```
   3. Index models in elasticsearch
        ```sh
         docker-compose run artisan app:index-models
        ```
   4. To manually run the data sources run
        ```sh
        docker-compose run artisan app:feed-news-data-source
        ```
7. The front end should be available at the following URL in your browser:
    ```sh
    http://localhost:3000/
    ```
   
