**A Real Estate Agency Challenge:** <br/><br/>
I have almost completed this app. As always I have used the TDD method for creating this app.<br/> You can see the tests and undestand the app. Although, I am going to descrbe it, in this app:<br/>
- you can register as a landlord, who has a house to rent or sell,
- as a landlord after registering you can log in and get jwt token for using the app,
- a landlord can store a home,
- a landlord can update his/her own home's attributes,
- a lanlord can see all his/her own homes and as well as single one,
- you can register as a customer, who wants to rent or buy, (of course you can log in and get jwt token!)
- as a customer you can see houses which stored before,
- admin can create an appointment between customer and employee, (send employee to present the home),
- each employee can see his/her appointments,
- each employee can start time of his/her appointment,
- while employee start the appointment he/she can define departure location (office or previouse appointment location)
- locations in this system are based on zip codes
- when the employee start the appointment this app convert zip code to the coordinate using [postcode api](https://postcodes.io/) service. <br/>
    Then by using [here api](https://www.here.com/) service the distance time between two location estimated and store in the appointment record.
- each employee can finish the appointment, (we can know aboute employee's open appoitment by this field)
- a normal appointment duration configed on 60 minutes, so by estimating distance and duration time the Admin can create next appointment time for that employee,
- admin can see all employee and their appointments so that he/she knows that free time and location of each employee.
