## Packt Assignment - 2

Laravel is accessible, powerful, and provides tools required for large, robust applications.

Things Used - Laravel Framework, Packt APIs available at https://api.packt.com/.

How to use : 

Clone the repo in your local machine from github.

copy the .env.sample to .env

Run the following command: 

```./vendor/bin/sail up -d```

Once the Docker is up run the following command:

```./vendor/bin/sail npm install && npm run dev```

Once everything is set up, head to http://localhost/ in the browser. It will display a catalogue of the books available. At a time we show 9 tiles which are paginated at the bottom. The / url can have a query string of limit which can be passed to limit the display of books.

![packt-5](https://user-images.githubusercontent.com/5665522/144005874-5efb0148-d25c-4268-926d-236170280c0d.png)

On top there is a search option, which searches the books with respect to the title of the book, and returns the results.

![packt-3](https://user-images.githubusercontent.com/5665522/143896072-6e0e59dd-e26a-4ea4-b54c-dc7af4857463.png)

On clicking any tile or the read more button at the bottom of the card, you will redirected to a detail page which displays the information for each book, with Pricing.

![packt-4](https://user-images.githubusercontent.com/5665522/143896331-4835a518-0ad3-4b46-bed7-38624e3bdb7b.png)

Below there are two buttons - Access from Packt Website which will redirect to the Packt Website for that Particular book, and Back to listings page which will redirect to the Main Listings page.

Caching Mechanism with the Redis, which comes out of the box with Sail has been implemented. Subsequent Requests will be much faster, then the pervious ones.
