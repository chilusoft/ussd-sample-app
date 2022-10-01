# USSD Mock Application
------
The script written in PHP simulates the action flow of a typical USSD application

---
***The repo does not cover on how to integrate with various USSD API gateways, a good place to start from is here --> [https://africastalking.com/ussd] the code in this repo only simulates how a USSD application would work in production***

### Instructions

- Make sure that you have the XAMPP stack (Apache2, MySQL, PHP, Pearl[not needed]) and Postman client
- clone this git repo into your htdocs folder 
```
> cd <path-to-document-root>
> git clone https://github.com/chilusoft/ussd-sample-app.git

```
- Run the Apache and MySQL modules.
- Open Postman client and key in the address of the localhost instance
- Use URL params in Postman to simulate user input, so we are using the GET http method for requests

