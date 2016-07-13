# Facebook Bots Created with Slim Framework and Cognitive Services

This application uses the latest Slim 3. It also uses Eloquent for debugging Facebook Requests. It connect to Microsoft Cognitive's Service's Computer Vision API. You will need SSL support for being able to connect with Facebook API. I strongly recommend to use this under Azure App Services which have free version and support SSL, you can find more information [here](https://azure.microsoft.com/en-us/services/app-service/). It supported PHP.

To run this application first you need to create an .env file in the root of application. You will also need [Facebook Access token](https://developers.facebook.com/docs/messenger-platform) and  [Microsoft Cognitive's Service's Computer Vision API](https://www.microsoft.com/cognitive-services) subscription's key. 

## Change the environtment file

DB_HOST is your Database Host name. DB_DATABASE is your database name. DB_USERNAME and DB_PASSWORD are your database username and password. FB_TOKEN is your Facebook Page Access Token. COGNITIVE_KEY is your Microsoft Cognitive's Service's Computer Vision API subscription's key.

and that's it, You now can run the application.
