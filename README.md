**A Real Estate Agency Challenge:** <br/><br/>
I have almost completed this app. As always I have used the TDD method for creating this app.<br/> You can see the tests and understand the app. Although, I am going to describe it, in this app:<br/>
- you can register as a landlord, who has a house to rent or sell,
- as a landlord after registering you can log in and get a JWT token for using the app,
- a landlord can store a home,
- a landlord can update his/her own home's attributes,
- a landlord can see all his/her own homes, as well as a single one,
- you can register as a customer, who wants to rent or buy, (of course you can log in and get a JWT token!)
- as a customer, you can see houses which stored before,
- admin can create an appointment between customer and employee, (send an employee to present the home),
- each employee can see his/her appointments,
- each employee can start the time of his/her appointment,
- while employee start the appointment he/she can define departure location (office or previous appointment location)
- locations in this system are based on zip codes
- when the employee starts the appointment this app converts the zip code to the coordinate using [postcode api](https://postcodes.io/) service. <br/>
    Then by using [here api](https://www.here.com/) service the distance-time between two locations is estimated and stored in the appointment record.
- each employee can finish the appointment, (we can know about employee's open appointment by this field)
- a normal appointment duration is configured on 60 minutes, so by estimating distance and duration time the Admin can create the next appointment time for that employee,
- admin can see all employee and their appointments so that he/she knows the free time and location of each employee.

I have used Adapter Design Pattern for using external services so that you can set your config services without changing app codes for converting zip codes and estimating distance time. I have created two interfaces inside the external services you should implement.

To use **POSTCODE** service you do not need any authentication, but for **HERE** service you need to register and get an API key and set it in the .env file. I added the key name in the .env.example.

If you have any questions and suggestions, please let me know. I would be so happy! 

