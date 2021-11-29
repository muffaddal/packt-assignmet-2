## Packt Assignment - 2

Laravel is accessible, powerful, and provides tools required for large, robust applications.

The Technical Test has been completed.

Things Used - Laravel Framework, Packt APIs available at https://api.packt.com/.

How to use : 

Clone the repo in your local machine from github.

Run the following command: 

```./vendor/bin/sail up```

This will start a docker env. Once everything is set up, head to http://localhost/ in the browser. It will display a catalogue of the books available. At a time we show 9 tiles which are paginated at the bottom. The / url can have a query string of limit which can be passed to limit the display of books.


![Screenshot from 2021-11-21 13-23-36](https://user-images.githubusercontent.com/5665522/142754381-8e501eda-894c-4706-a98d-4ba10577529c.png)

On top there is a search option, which searches the books with respect to the title of the book, and returns the results.

![Screenshot from 2021-11-21 13-23-36](https://user-images.githubusercontent.com/5665522/142754381-8e501eda-894c-4706-a98d-4ba10577529c.png)

On clicking any tile or the read more button at the bottom of the card, you will redirected to a detail page which displays the information for each book, with Pricing.

![Screenshot from 2021-11-21 13-23-36](https://user-images.githubusercontent.com/5665522/142754381-8e501eda-894c-4706-a98d-4ba10577529c.png)

Below there are two buttons - Access from Packt Website which will redirect to the Packt Website for that Particular book, and Back to listings page which will redirect to the Main Listings page.
